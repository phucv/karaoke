<?php

/**
 * Created by PhpStorm.
 * User: Pham Quynh
 * Date: 3/5/2018
 * Time: 11:09 AM
 */
class M_absent_campaign extends Ows_model {
    protected $_table = "ows_absent_campaign";

    public function __construct() {
        parent::__construct();
    }

    public function get_list_filter($whereCondition, $whereInCondition, $likeCondition, $limit = 0, $post = 0, $order = NULL)
    {
        $this->before_get['join_absent'] = 'join_absent';
        return parent::get_list_filter($whereCondition, $whereInCondition, $likeCondition, $limit, $post, $order);
    }

    public function get_list_filter_count($whereCondition, $whereInCondition, $likeCondition)
    {
        $this->before_get['join_absent'] = 'join_absent';
        return parent::get_list_filter_count($whereCondition, $whereInCondition, $likeCondition);
    }

    protected function join_absent() {
        $this->db->select($this->_table_alias . ".*, GROUP_CONCAT(DISTINCT(class.class_fullname)) class_fullname, sum(if(user_absent.status_rule = 'valid' && m.user_id = user_absent.user_id, 1, 0)) num_valid,
         sum(if(user_absent.status_rule = 'invalid', 1, 0)) num_invalid, sum(if(user_absent.status_rule = 'valid' AND user_absent.new_live_id IS NOT NULL, 1, 0)) num_offset_valid,
         sum(if(user_absent.status_rule = 'invalid' AND user_absent.new_live_id IS NOT NULL, 1, 0)) num_offset_invalid, sum(if(user_absent.new_live_id IS NOT NULL, 1, 0)) num_offset");
        $this->db->join("ows_user_absent as user_absent", $this->_table_alias . ".id = user_absent.campaign_id AND user_absent.deleted = 0");
        $this->db->join("ows_classes as class", "class.id = user_absent.class_id AND class.deleted = 0");
        $this->db->group_by($this->_table_alias . ".id");
    }
}