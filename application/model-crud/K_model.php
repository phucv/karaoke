<?php

class K_model extends Crud_model {

    public $schema = Array();

    public function __construct() {
        parent::__construct();
        $this->_standard_schema();
        $this->before_create['before_create'] = 'init_before_create';
        $this->before_update['before_update'] = 'init_before_update';
        $this->before_delete['before_delete'] = 'init_before_delete';
        $this->load->library("File_cacher");
    }

    protected function cache_set($key, $value) {
        File_cacher::cache_set($this->_table, $key, $value);
    }

    protected function cache_get($key) {
        return File_cacher::cache_get($this->_table, $key);
    }

    protected function cache_remove($key) {
        return File_cacher::cache_remove($this->_table, $key);
    }

    protected function _standard_schema() {
        foreach ($this->schema as &$item) {
            if (!isset($item['db_field'])) {
                $item['db_field'] = $this->_table_alias . "." . $item['field'];
            }
            if (isset($item['table']) && !is_array($item['table'])) {
                $item['table'] = Array();
            }
            if (isset($item['form']) && !is_array($item['form'])) {
                $item['form'] = Array();
            }
            if (isset($item['filter']) && !is_array($item['filter'])) {
                $item['filter'] = Array();
            }
            if (!isset($item['rules'])) {
                $item['rules'] = "";
            }
        }
    }

    public function get_list_filter($whereCondition, $whereInCondition, $likeCondition, $limit = 0, $post = 0, $order = NULL) {
        $this->_filter_prepare($whereCondition, $whereInCondition, $likeCondition, $limit, $post, $order);
        return $this->get_all();
    }

    protected function _filter_prepare($whereCondition, $whereInCondition, $likeCondition, $limit = 0, $post = 0, $order = NULL) {
        if (isset($whereCondition["where_text"])) {
            if ($whereCondition["where_text"]) {
                $this->db->group_start();
                $this->_database->where($whereCondition["where_text"]);
                $this->db->group_end();
            }
            unset($whereCondition["where_text"]);
        }
        if (is_array($whereCondition) && count($whereCondition) > 0) {
            $this->_database->where($whereCondition);
        } else if (intval($whereCondition) > 0) {
            $this->_database->where("m." . $this->primary_key, $whereCondition);
        }
        if (is_array($whereInCondition) && count($whereInCondition) > 0) {
            $this->db->group_start();
            foreach ($whereInCondition as $key => $value) {
                if ($key == "or") {
                    foreach ($value as $keyOr => $valueOr) {
                        if ((is_array($valueOr) && count($valueOr)) ||
                            (is_string($valueOr) && strlen($valueOr))
                        ) {
                            $this->_database->or_where_in($keyOr, $valueOr);
                        }
                    }
                } else if ((is_array($value) && count($value)) ||
                    (is_string($value) && strlen($value))
                ) {
                    $this->_database->where_in($key, $value);
                }
            }
            $this->db->group_end();
        }
        if (isset($likeCondition['and']) && is_array($likeCondition['and']) && count($likeCondition['and']) > 0) {
            $this->db->group_start();
            foreach ($likeCondition['and'] as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $value_item) {
                        $this->_database->like($key, $value_item);
                    }
                } else {
                    $this->_database->like($key, $value);
                }
            }
            $this->db->group_end();
        }

        if (isset($likeCondition['or']) && is_array($likeCondition['or']) && count($likeCondition['or']) > 0) {
            $this->db->group_start();
            foreach ($likeCondition['or'] as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $value_item) {
                        $this->_database->or_like($key, $value_item);
                    }
                } else {
                    $this->_database->or_like($key, $value);
                }
            }
            $this->db->group_end();
        }
        if ($limit) {
            $this->limit($limit, $post);
        }
        if ($order) {
            $this->order_by($order);
        } else {
            $this->order_by($this->_table_alias . "." . $this->primary_key, "DESC");
        }
    }

    /**
     * Get total item with special condition(use with get_list_filter for paging)
     *
     * @param   array $whereCondition   Array with key is field need to find, and value is String, which need to find
     *                                  with WHERE
     * @param   array $whereInCondition Array with key is field need to find, and value is Array of value need to find
     *                                  with WHERE_IN
     * @param   array $likeCondition    Array with key is field need to find, and value is String of value need to find
     *                                  with LIKE
     *
     * @return  Int     Count of all item match param condition
     */
    public function get_list_filter_count($whereCondition, $whereInCondition, $likeCondition) {
        $this->_filter_prepare($whereCondition, $whereInCondition, $likeCondition, 0, 0, NULL);
        return $this->count_all_results();
    }

    /**
     * Init default create
     *
     * @param $row array
     *
     * @return array
     */
    public function init_before_create($row) {
        $table = $this->_table;
        if ($this->db->field_exists("created_on", $table) && empty($row["created_on"])) {
            $row["created_on"] = date("Y-m-d H:i:s");
        }
        if ($this->db->field_exists("created_by", $table) && empty($row["created_by"])) {
            $row["created_by"] = $this->_get_user_id();
        }
        return $row;
    }

    public function init_after_create($id) {
    }

    public function init_after_update($data = array()) {
    }

    /**
     * Init default update
     *
     * @param $row array
     *
     * @return array
     */
    public function init_before_update($row) {
        $table = $this->_table;
        if ($this->db->field_exists("lastest_update_on", $table) && empty($row["lastest_update_on"])) {
            $row["lastest_update_on"] = date("Y-m-d H:i:s");
        }
        if ($this->db->field_exists("lastest_update_by", $table) && empty($row["lastest_update_by"])) {
            $row["lastest_update_by"] = $this->_get_user_id();
        }
        return $row;
    }

    /**
     * Init default delete
     *
     * @param $row array
     *
     * @return array
     */
    public function init_before_delete($row) {
        $table = $this->_table;
        if ($this->db->field_exists("lastest_update_on", $table) && empty($row["lastest_update_on"])) {
            $row["lastest_update_on"] = date("Y-m-d H:i:s");
        }
        if ($this->db->field_exists("lastest_update_by", $table) && empty($row["lastest_update_by"])) {
            $row["lastest_update_by"] = $this->_get_user_id();
        }
        return $row;
    }

    /**
     * Delete a row from the table by the primary value
     */
    public function delete($id) {
        $data[$this->primary_key] = $id;
        $data = $this->trigger('before_delete', $data);
        if ($this->soft_delete) {
            $this->_database->where($this->_table_alias . "." . $this->primary_key, $id);
            $this->_database->update($this->_table . " AS " . $this->_table_alias,
                array_merge($data, array($this->_table_alias . "." . $this->soft_delete_key => TRUE)));
        } else {
            $this->_database->where($this->primary_key, $id);
            $this->_database->delete($this->_table);
        }
        $affected_rows = $this->_database->affected_rows();
        $row = [
            "list_id"       => [$id],
            "affected_rows" => $affected_rows,
        ];
        $this->trigger('after_delete', $row);

        return $affected_rows;
    }

    public function init_after_delete($row) {}

    // check field exist
    public function check_field_exist($field = "") {
        if ($this->db->field_exists($field, $this->_table)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Lay thong tin cua row tu dieu kien
     * $where: Mảng điều kiện
     */
    function get_info_rule($where = array()) {
        $this->db->where($where);
        $query = $this->db->get($this->table);
        if ($query->num_rows()) {
            return $query->row();
        }
        return FALSE;
    }

    /**
     * Get user_id by session
     **/
    protected function _get_user_id() {
        $user_id = empty($this->session->userdata()) ? 0 : $this->session->userdata("id");
        if (!$user_id) $user_id = 0;
        return $user_id;
    }

    /**
     * Transaction manual: begin
     */
    public function trans_begin() {
        $this->db->trans_begin();
    }

    /**
     * Transaction manual: status
     */
    public function trans_status() {
        return $this->db->trans_status();
    }

    /**
     * Transaction manual: rollback
     */
    public function trans_rollback() {
        $this->db->trans_rollback();
    }

    /**
     * Transaction manual: commit
     */
    public function trans_commit() {
        $this->db->trans_commit();
    }

    public function _with_deleted() {
        $this->_temporary_with_deleted = TRUE;
    }
}