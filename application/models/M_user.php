<?php

class M_user extends K_model {
    protected $_table = "users";

    public function __construct() {
        parent::__construct();
        $this->before_get["join_role_table"] = "join_role_table";
    }

    public function join_role_table() {
        $this->db->select($this->_table_alias . ".*, role.name as role_name, role.alias as role_alias, role.role_data, role.group as role_group");
        $this->db->join("users_role as role", $this->_table_alias . ".role_id=role.id AND role.deleted!=1");
    }
}