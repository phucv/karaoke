<?php

/**
 * A base model with a series of CRUD functions (powered by CI's query builder),
 * validation-in-model support, event callbacks and more.
 *
 * @link      http://github.com/jamierumbelow/codeigniter-base-model
 * @copyright Copyright (c) 2012, Jamie Rumbelow <http://jamierumbelow.net>
 * @property CI_DB_query_builder $db
 * @property CI_DB_forge         $dbforge
 * @property CI_Config           $config
 * @property CI_Session          $session
 * @property CI_Image_lib        $image_lib
 * @property CI_Loader           $load
 * @property CI_Upload           $upload
 * @property Ion_auth_model      $ion_auth
 * @property MY_Form_validation  $form_validation
 */
class Crud_model extends CI_Model {

    /* --------------------------------------------------------------
     * VARIABLES
     * ------------------------------------------------------------ */

    /**
     * This model's default database table. Automatically
     * guessed by pluralising the model name.
     */
    protected $_table;
    protected $_table_alias = "m";

    /**
     * The database connection object. Will be set to the default
     * connection. This allows individual models to use different DBs
     * without overwriting CI's global $this->db connection.
     */
    public $_database;

    /**
     * This model's default primary key or unique identifier.
     * Used by the get(), update() and delete() functions.
     */
    protected $primary_key = 'id';

    /**
     * Support for soft deletes and this model's 'deleted' key
     */
    protected $soft_delete = TRUE;
    protected $soft_delete_key = 'deleted';
    protected $_temporary_with_deleted = FALSE;
    protected $_temporary_only_deleted = FALSE;

    /**
     * The various callbacks available to the model. Each are
     * simple lists of method names (methods will be run on $this).
     */
    protected $before_create = array();
    protected $after_create = array();
    protected $before_update = array();
    protected $after_update = array();
    protected $before_get = array();
    protected $after_get = array();
    protected $before_delete = array();
    protected $after_delete = array();

    protected $callback_parameters = array();

    /**
     * Protected, non-modifiable attributes
     */
    protected $protected_attributes = array();

    /**
     * Relationship arrays. Use flat strings for defaults or string
     * => array to customise the class name and primary key
     */
    protected $belongs_to = array();
    protected $has_many = array();

    protected $_with = array();

    /**
     * An array of validation rules. This needs to be the same format
     * as validation rules passed to the Form_validation library.
     */
    protected $validate = array();

    /**
     * Optionally skip the validation. Used in conjunction with
     * skip_validation() to skip data validation for any future calls.
     */
    protected $skip_validation = FALSE;

    /**
     * By default we return our results as objects. If we need to override
     * this, we can, or, we could use the `as_array()` and `as_object()` scopes.
     */
    protected $return_type = 'object';
    protected $_temporary_return_type = NULL;

    /**
     *
     */
    protected $model_subfix = "_model";
    protected $model_prefix = "M_";
    /* --------------------------------------------------------------
     * GENERIC METHODS
     * ------------------------------------------------------------ */

    /**
     * Initialise the model, tie into the CodeIgniter superobject and
     * try our best to guess the table name.
     */
    public function __construct() {
        parent::__construct();

        $this->load->helper('inflector');

        $this->_fetch_table();

        $this->_database = $this->db;

        array_unshift($this->before_create, 'protect_attributes');
        array_unshift($this->before_update, 'protect_attributes');

        $this->_temporary_return_type = $this->return_type;
    }

    /* --------------------------------------------------------------
     * CRUD INTERFACE
     * ------------------------------------------------------------ */

    /**
     * Fetch a single record based on the primary key. Returns an object.
     */
    public function get($primary_value) {
        return $this->get_by($this->primary_key, $primary_value);
    }

    /**
     * Fetch a single record based on an arbitrary WHERE call. Can be
     * any valid value to $this->_database->where().
     */
    public function get_by() {
        $where = func_get_args();

        if ($this->soft_delete && $this->_temporary_with_deleted !== TRUE) {
            $this->_database->where($this->_table_alias . "." . $this->soft_delete_key, (bool)$this->_temporary_only_deleted);
        }

        $this->_set_where($where);

        $this->trigger('before_get');

        $row = $this->_database->get($this->_table . " AS " . $this->_table_alias)
            ->{$this->_return_type()}();
        $this->_temporary_return_type = $this->return_type;

        $row = $this->trigger('after_get', $row);

        $this->_with = array();
        return $row;
    }

    /**
     * Fetch an array of records based on an array of primary values.
     */
    public function get_many($values) {
        $this->_database->where_in($this->primary_key, $values);

        return $this->get_all();
    }

    /**
     * Fetch an array of records based on an arbitrary WHERE call.
     */
    public function get_many_by() {
        $where = func_get_args();

        $this->_set_where($where);

        return $this->get_all();
    }

    /**
     * Fetch all the records in the table. Can be used as a generic call
     * to $this->_database->get() with scoped methods.
     */
    public function get_all() {
        $this->trigger('before_get');

        if ($this->soft_delete && $this->_temporary_with_deleted !== TRUE) {
            $this->_database->where($this->_table_alias . "." . $this->soft_delete_key, (bool)$this->_temporary_only_deleted);
        }

        $result = $this->_database->get($this->_table . " AS " . $this->_table_alias)
            ->{$this->_return_type(1)}();
        $this->_temporary_return_type = $this->return_type;

        if(!empty($this->after_get)) {
            foreach ($result as $key => &$row) {
                $row = $this->trigger('after_get', $row, ($key == count($result) - 1));
            }
        }

        $this->_with = array();
        return $result;
    }

    /**
     * Insert a new row into the table. $data should be an associative array
     * of data to be inserted. Returns newly created ID.
     */
    public function insert($data, $skip_validation = FALSE) {
        if ($skip_validation === FALSE) {
            $data = $this->validate($data);
        }

        if ($data !== FALSE) {
            $data = $this->trigger('before_create', $data);

            $this->_database->insert($this->_table, $data);
            $insert_id = $this->_database->insert_id();

            $this->trigger('after_create', $insert_id);
            if (!$insert_id) $insert_id = empty($data[$this->primary_key]) ? 0 : $data[$this->primary_key];

            return $insert_id;
        } else {
            return FALSE;
        }
    }

    /**
     * Insert multiple rows into the table. Returns an array of multiple IDs.
     */
    public function insert_many($data, $skip_validation = FALSE) {
        $ids = array();

        foreach ($data as $key => $row) {
            $ids[] = $this->insert($row, $skip_validation, ($key == count($data) - 1));
        }

        return $ids;
    }

    /**
     * Insert multiple rows into the table. Returns number row insert success.
     *
     * @param $data
     *
     * @return int
     */
    public function insert_batch($data) {
        foreach ($data as $k => $v) {
            $data[$k] = $this->trigger('before_create', $v);
        }
        return $this->_database->insert_batch($this->_table, $data);
    }

    /**
     * Updated a record based on the primary value.
     */
    public function update($primary_value, $data, $skip_validation = FALSE) {
        $data = $this->trigger('before_update', $data);

        if ($skip_validation === FALSE) {
            $data = $this->validate($data);
        }

        if ($data !== FALSE) {
            $result = $this->_database->where($this->_table_alias . "." . $this->primary_key, $primary_value)
                ->set($data)
                ->update($this->_table . " AS " . $this->_table_alias);
            $affected_rows = $this->_database->affected_rows();
            $this->trigger('after_update', array($data, $affected_rows));
            return $affected_rows;
        } else {
            return FALSE;
        }
    }

    /**
     * Update many records, based on an array of primary values.
     */
    public function update_many($primary_values, $data, $skip_validation = FALSE) {
        $data = $this->trigger('before_update', $data);

        if ($skip_validation === FALSE) {
            $data = $this->validate($data);
        }

        if ($data !== FALSE) {
            $result = $this->_database->where_in($this->_table_alias . "." . $this->primary_key, $primary_values)
                ->set($data)
                ->update($this->_table . " AS " . $this->_table_alias);
            $affected_rows = $this->_database->affected_rows();
            $this->trigger('after_update', array($data, $affected_rows));

            return $affected_rows;
        } else {
            return FALSE;
        }
    }

    /**
     * Updated a record based on an arbitrary WHERE clause.
     */
    public function update_by() {
        $args = func_get_args();
        $data = array_pop($args);

        $data = $this->trigger('before_update', $data);

        if ($this->validate($data) !== FALSE) {
            if ($this->soft_delete && $this->_temporary_with_deleted !== TRUE) {
                if ($this->_database->field_exists($this->soft_delete_key, $this->_table)) {
                    $this->_database->where($this->_table_alias . "." . $this->soft_delete_key, (bool)$this->_temporary_only_deleted);
                }
            }
            $this->_set_where($args);
            $result = $this->_database->set($data)
                ->update($this->_table . " AS " . $this->_table_alias);
            $affected_rows = $this->_database->affected_rows();
            $this->trigger('after_update', array($data, $affected_rows));

            return $affected_rows;
        } else {
            return FALSE;
        }
    }

    /**
     * Update all records
     */
    public function update_all($data) {
        $data = $this->trigger('before_update', $data);
        $result = $this->_database->set($data)
            ->update($this->_table . " AS " . $this->_table_alias);
        $affected_rows = $this->_database->affected_rows();
        $this->trigger('after_update', array($data, $affected_rows));

        return $affected_rows;
    }

    /**
     * Delete a row from the table by the primary value
     */
    public function delete($id) {
        $data[$this->primary_key] = $id;
        $data = $this->trigger('before_delete', $data);

        if ($this->soft_delete) {
            $this->_database->where($this->_table_alias . "." . $this->primary_key, $id);
            $result = $this->_database->update($this->_table . " AS " . $this->_table_alias,
                array_merge($data, array($this->_table_alias . "." . $this->soft_delete_key => TRUE)));
        } else {
            $this->_database->where($this->primary_key, $id);
            $result = $this->_database->delete($this->_table);
        }
        $affected_rows = $this->_database->affected_rows();
        $row = [
            "list_id"       => [$id],
            "affected_rows" => $affected_rows,
        ];
        $this->trigger('after_delete', $row);

        return $affected_rows;
    }

    /**
     * Delete a row from the database table by an arbitrary WHERE clause
     */
    public function delete_by() {
        $where = func_get_args();
        $this->_set_where($where);

        $data = $this->trigger('before_delete', []);


        if ($this->soft_delete) {
            $result = $this->_database->update($this->_table . " AS " . $this->_table_alias,
                array_merge($data, array($this->_table_alias . "." . $this->soft_delete_key => TRUE)));
        } else {
            $result = $this->_database->delete($this->_table);
        }

        $affected_rows = $this->_database->affected_rows();
        $row = [
            "by_where"      => $where,
            "affected_rows" => $affected_rows,
        ];
        $this->trigger('after_delete', $row);

        return $affected_rows;
    }

    /**
     * Delete many rows from the database table by multiple primary values
     */
    public function delete_many($primary_values) {
        $data = $this->trigger('before_delete', []);

        if ($this->soft_delete) {
            $this->_database->where_in($this->_table_alias . "." . $this->primary_key, $primary_values);
            $result = $this->_database->update($this->_table . " AS " . $this->_table_alias,
                array_merge($data, array($this->_table_alias . "." . $this->soft_delete_key => TRUE)));
        } else {
            $this->_database->where_in($this->primary_key, $primary_values);
            $result = $this->_database->delete($this->_table);
        }

        $affected_rows = $this->_database->affected_rows();
        $row = [
            "list_id"       => $primary_values,
            "affected_rows" => $affected_rows,
        ];
        $this->trigger('after_delete', $row);

        return $affected_rows;
    }


    /**
     * Truncates the table
     */
    public function truncate() {
        $result = $this->_database->truncate($this->_table);

        return $result;
    }

    /* --------------------------------------------------------------
     * RELATIONSHIPS
     * ------------------------------------------------------------ */

    public function with($relationship) {
        $this->_with[] = $relationship;

        if (!in_array('relate', $this->after_get)) {
            $this->after_get[] = 'relate';
        }

        return $this;
    }

    public function relate($row) {
        if (empty($row)) {
            return $row;
        }

        foreach ($this->belongs_to as $key => $value) {
            if (is_string($value)) {
                $relationship = $value;
                $options = array('primary_key' => $value . '_id', 'model' => $this->model_prefix . $value . $this->model_subfix);
            } else {
                $relationship = $key;
                $options = $value;
            }

            if (in_array($relationship, $this->_with)) {
                $this->load->model($options['model'], $this->model_prefix . $relationship . $this->model_subfix);

                if (is_object($row)) {
                    $row->{$relationship} = $this->{$this->model_prefix . $relationship . $this->model_subfix}->get($row->{$options['primary_key']});
                } else {
                    $row[$relationship] = $this->{$this->model_prefix . $relationship . $this->model_subfix}->get($row[$options['primary_key']]);
                }
            }
        }

        foreach ($this->has_many as $key => $value) {
            if (is_string($value)) {
                $relationship = $value;
                $options = array('primary_key' => singular($this->_table) . '_id', 'model' => $this->model_prefix . singular($value) . $this->model_subfix);
            } else {
                $relationship = $key;
                $options = $value;
            }

            if (in_array($relationship, $this->_with)) {
                $this->load->model($options['model'], $this->model_prefix . $relationship . $this->model_subfix);

                if (is_object($row)) {
                    $row->{$relationship} = $this->{$this->model_prefix . $relationship . $this->model_subfix}
                        ->get_many_by($options['primary_key'], $row->{$this->primary_key});
                } else {
                    $row[$relationship] = $this->{$this->model_prefix . $relationship . $this->model_subfix}
                        ->get_many_by($options['primary_key'], $row[$this->primary_key]);
                }
            }
        }

        return $row;
    }

    /* --------------------------------------------------------------
     * UTILITY METHODS
     * ------------------------------------------------------------ */

    /**
     * Retrieve and generate a form_dropdown friendly array
     *
     * @param        $value_field       field using for value in option, if only pass one argument,
     *                                  it will be display_field and primary_key will be value_field
     * @param string $display_field     field using for display in option
     *
     * @return array|bool|mixed
     */
    function dropdown($value_field, $display_field = NULL) {
        $args = func_get_args();
        if (count($args) == 2) {
            list($value_field, $display_field) = $args;
        } else {
            $value_field = $this->primary_key;
            $display_field = $args[0];
        }
        $this->trigger('before_dropdown', array($value_field, $display_field));

        if ($this->soft_delete && $this->_temporary_with_deleted !== TRUE) {
            $this->_database->where($this->_table_alias . "." . $this->soft_delete_key, FALSE);
        }

        $result = $this->_database->select(array($value_field, $display_field))
            ->get($this->_table . " AS " . $this->_table_alias)
            ->result();

        $options = array();

        foreach ($result as $row) {
            $options[$row->{$value_field}] = $row->{$display_field};
        }

        $options = $this->trigger('after_dropdown', $options);

        return $options;
    }

    /**
     * Fetch a count of rows based on an arbitrary WHERE call.
     */
    public function count_by() {
        $this->trigger('before_get');
        if ($this->soft_delete && $this->_temporary_with_deleted !== TRUE) {
            $this->_database->where($this->_table_alias . "." . $this->soft_delete_key, (bool)$this->_temporary_only_deleted);
        }

        $where = func_get_args();
        $this->_set_where($where);

        return $this->_database->count_all_results($this->_table . " AS " . $this->_table_alias);
    }

    /**
     * Fetch a total count of rows, using previous conditions
     */
    public function count_all_results() {
        $this->trigger('before_get');
        if ($this->soft_delete && $this->_temporary_with_deleted !== TRUE) {
            $this->_database->where($this->_table_alias . "." . $this->soft_delete_key, (bool)$this->_temporary_only_deleted);
        }

        return $this->_database->count_all_results($this->_table . " AS " . $this->_table_alias);
    }

    /**
     * Fetch a total count of rows, disregarding any previous conditions
     */
    public function count_all() {
        if ($this->soft_delete && $this->_temporary_with_deleted !== TRUE) {
            $this->_database->where($this->_table_alias . "." . $this->soft_delete_key, (bool)$this->_temporary_only_deleted);
        }

        return $this->_database->count_all($this->_table . " AS " . $this->_table_alias);
    }

    /**
     * Tell the class to skip the insert validation
     */
    public function skip_validation() {
        $this->skip_validation = TRUE;
        return $this;
    }

    /**
     * Get the skip validation status
     */
    public function get_skip_validation() {
        return $this->skip_validation;
    }

    /**
     * Return the next auto increment of the table. Only tested on MySQL.
     */
    public function get_next_id() {
        return (int)$this->_database->select('AUTO_INCREMENT')
            ->from('information_schema.TABLES')
            ->where('TABLE_NAME', $this->_table)
            ->where('TABLE_SCHEMA', $this->_database->database)->get()->row()->AUTO_INCREMENT;
    }

    /**
     * Getter for the table name
     */
    public function table() {
        return $this->_table;
    }

    /* --------------------------------------------------------------
     * GLOBAL SCOPES
     * ------------------------------------------------------------ */

    /**
     * Return the next call as an array rather than an object
     */
    public function as_array() {
        $this->_temporary_return_type = 'array';
        return $this;
    }

    /**
     * Return the next call as an object rather than an array
     */
    public function as_object() {
        $this->_temporary_return_type = 'object';
        return $this;
    }

    /**
     * Don't care about soft deleted rows on the next call
     */
    public function with_deleted() {
        $this->_temporary_with_deleted = TRUE;
        return $this;
    }

    /**
     * Only get deleted rows on the next call
     */
    public function only_deleted() {
        $this->_temporary_only_deleted = TRUE;
        return $this;
    }

    /* --------------------------------------------------------------
     * OBSERVERS
     * ------------------------------------------------------------ */

    /**
     * MySQL DATETIME created_at and updated_at
     */
    public function created_at($row) {
        if (is_object($row)) {
            $row->created_at = date('Y-m-d H:i:s');
        } else {
            $row['created_at'] = date('Y-m-d H:i:s');
        }

        return $row;
    }

    public function updated_at($row) {
        if (is_object($row)) {
            $row->updated_at = date('Y-m-d H:i:s');
        } else {
            $row['updated_at'] = date('Y-m-d H:i:s');
        }

        return $row;
    }

    /**
     * Serialises data for you automatically, allowing you to pass
     * through objects and let it handle the serialisation in the background
     */
    public function serialize($row) {
        foreach ($this->callback_parameters as $column) {
            $row[$column] = serialize($row[$column]);
        }

        return $row;
    }

    public function unserialize($row) {
        foreach ($this->callback_parameters as $column) {
            if (is_array($row)) {
                $row[$column] = unserialize($row[$column]);
            } else {
                $row->$column = unserialize($row->$column);
            }
        }

        return $row;
    }

    /**
     * Protect attributes by removing them from $row array
     */
    public function protect_attributes($row) {
        foreach ($this->protected_attributes as $attr) {
            if (is_object($row)) {
                unset($row->$attr);
            } else {
                unset($row[$attr]);
            }
        }

        return $row;
    }

    /* --------------------------------------------------------------
     * QUERY BUILDER DIRECT ACCESS METHODS
     * ------------------------------------------------------------ */

    /**
     * A wrapper to $this->_database->order_by()
     */
    public function order_by($criteria, $order = 'ASC') {
        if (is_array($criteria)) {
            foreach ($criteria as $key => $value) {
                $this->_database->order_by($key, $value);
            }
        } else {
            $this->_database->order_by($criteria, $order);
        }
        return $this;
    }

    /**
     * A wrapper to $this->_database->limit()
     */
    public function limit($limit, $offset = 0) {
        $this->_database->limit($limit, $offset);
        return $this;
    }

    /**
     * get primary key of model
     *
     * @return string
     */
    public function get_primary_key() {
        return $this->primary_key;
    }

    /* --------------------------------------------------------------
     * INTERNAL METHODS
     * ------------------------------------------------------------ */

    /**
     * Trigger an event and call its observers. Pass through the event name
     * (which looks for an instance variable $this->event_name), an array of
     * parameters to pass through and an optional 'last in interation' boolean
     */
    public function trigger($event, $data = FALSE, $last = TRUE) {
        if (isset($this->$event) && is_array($this->$event)) {
            foreach ($this->$event as $method) {
                if (strpos($method, '(')) {
                    preg_match('/([a-zA-Z0-9\_\-]+)(\(([a-zA-Z0-9\_\-\., ]+)\))?/', $method, $matches);

                    $method = $matches[1];
                    $this->callback_parameters = explode(',', $matches[3]);
                }

                $data = call_user_func_array(array($this, $method), array($data, $last));
            }
        }

        return $data;
    }

    public function set_trigger($event, $function) {
        if (isset($this->$event)) {
            $trigger = $this->$event;
            $trigger[$function] = $function;
            $this->$event = $trigger;
        }
    }

    /**
     * Run validation on the passed data
     */
    public function validate($data, $validate = NULL) {
        if (!$validate) {
            $validate = $this->validate;
        }
        if ($this->skip_validation) {
            return $data;
        }

        if (!empty($validate)) {
            $this->load->library('form_validation');
            $this->form_validation->reset_validation();
            $this->form_validation->set_data($data);

            if (is_array($validate)) {
                $this->form_validation->set_rules($validate);
                $is_empty_rules = TRUE;
                foreach ($validate as $item) {
                    if (!empty($item['rules'])) {
                        $is_empty_rules = FALSE;
                        break;
                    }
                }
                if ($is_empty_rules || $this->form_validation->run() === TRUE) {
                    return $data;
                } else {
                    return FALSE;
                }
            } else {
                if ($this->form_validation->run($validate) === TRUE) {
                    return $data;
                } else {
                    return FALSE;
                }
            }
        } else {
            return $data;
        }
    }

    public function get_validate_error() {
        return $this->form_validation->error_array();
    }

    public function set_before_get($keys = []) {
        foreach ($keys as $key) {
            $this->before_get[$key];
        }
    }

    public function unset_before_get($keys = []) {
        if (empty($keys)) $this->before_get = [];
        $keys = is_array($keys) ? $keys : [$keys];
        foreach ($keys as $key) {
            if (isset($this->before_get[$key])) unset($this->before_get[$key]);
        }
    }

    /**
     * Guess the table name by pluralising the model name
     */
    private function _fetch_table() {
        if ($this->_table == NULL) {
            $this->_table = plural(preg_replace('/(_m|_model)?$/', '', strtolower(get_class($this))));
            $this->_table = plural(preg_replace('/^(M_)?/', '', strtolower(get_class($this))));
        }
    }

    /**
     * Guess the primary key for current table
     */
    private function _fetch_primary_key() {
        if ($this->primary_key == NULL) {
            $this->primary_key = $this->_database->query("SHOW KEYS FROM `" . $this->_table . "` WHERE Key_name = 'PRIMARY'")->row()->Column_name;
        }
    }

    /**
     * Set WHERE parameters, cleverly
     */
    protected function _set_where($params) {
        if (count($params) == 1 && is_array($params[0])) {
            foreach ($params[0] as $field => $filter) {
                $field_where = strpos($field, ".") ? $field : $this->_table_alias . "." . $field;
                if (is_array($filter)) {
                    $this->_database->where_in($field_where, $filter);
                } else {
                    if (is_int($field)) {
                        $this->_database->where($filter);
                    } else {
                        $this->_database->where($field_where, $filter);
                    }
                }
            }
        } else if (count($params) == 1) {
            $this->_database->where($params[0]);
        } else if (count($params) == 2) {
            $field_where = strpos($params[0], ".") ? $params[0] : $this->_table_alias . "." . $params[0];
            if (is_array($params[1])) {
                $this->_database->where_in($field_where, $params[1]);
            } else {
                $this->_database->where($field_where, $params[1]);
            }
        } else if (count($params) == 3) {
            $field_where = strpos($params[0], ".") ? $params[0] : $this->_table_alias . "." . $params[0];
            $this->_database->where($field_where, $params[1], $params[2]);
        } else {
            $field_where = strpos($params[0], ".") ? $params[0] : $this->_table_alias . "." . $params[0];
            if (is_array($params[1])) {
                $this->_database->where_in($field_where, $params[1]);
            } else {
                $this->_database->where($field_where, $params[1]);
            }
        }
    }

    /**
     * Return the method name for the current return type
     */
    protected function _return_type($multi = FALSE) {
        $method = ($multi) ? 'result' : 'row';
        return $this->_temporary_return_type == 'array' ? $method . '_array' : $method;
    }
}