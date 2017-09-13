<?php

class EstimateModel extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function getProductsListByExistingGroupId($id) {
        $fields = array("tax.tax_id,tax.tax_percentage");
        $data['taxes'] = $this->common_model->get_records(PRODUCT_TAX_MASTER . ' as tax', $fields, '', '', "");
        $table = PRODUCT_GROUP_MASTER . ' as PG';
        $match = "PG.product_group_id=" . $id;
        $join_tables = array(PRODUCT_GROUP_RELATION . ' as PGR' => 'PGR.product_group_id=PG.product_group_id', PRODUCT_MASTER . ' as PM' => 'PM.product_id=PGR.product_id');
        $join_type = 'inner';
        $fields = array("PG.product_group_id,PM.product_id,PM.product_name,PM.product_description,PM.sales_price_unit,PM.product_group_status,PM.product_group_status");
        return $data['group_info_products'] = $this->common_model->get_records($table, $fields, $join_tables, $join_type, $match);
    }

}
