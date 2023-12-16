<?php

class M_product extends K_model {
    protected $_table = "products";
    function update_quantity($product_id, $quantity) {
        $query = "UPDATE $this->_table SET `quantity`=`quantity`+$quantity WHERE id = $product_id";
        return $this->db->query($query);
    }
}