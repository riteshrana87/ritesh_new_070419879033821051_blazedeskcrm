<?php

class Estimate_model extends CI_Model {

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
	/*function getprdListByExistingGroupId($id, $tempEstPrdIdArry) {
		$fields = array("tax.tax_id,tax.tax_percentage");
        $data['taxes'] = $this->common_model->get_records(PRODUCT_TAX_MASTER . ' as tax', $fields, '', '', "");
        $table = PRODUCT_GROUP_MASTER . ' as PG';
        $match = "PG.product_group_id=" . $id . " AND PM.is_delete = 1 AND PM.product_id IN (".implode(',',$tempEstPrdIdArry).") ";
        $join_tables = array(PRODUCT_GROUP_RELATION . ' as PGR' => 'PGR.product_group_id=PG.product_group_id', PRODUCT_MASTER . ' as PM' => 'PM.product_id=PGR.product_id', PRODUCT_TAX_MASTER . ' as ptm' => 'PGR.product_tax_id = ptm.tax_id');
        $join_type = 'left';
        $fields = array("ptm.is_delete,ptm.tax_id,ptm.tax_name,ptm.tax_percentage,PG.product_group_id,PM.product_id,PM.product_name,PM.product_description,PM.sales_price_unit,PGR.product_group_status");
        return $data['group_info_products'] = $this->common_model->get_records($table, $fields, $join_tables, $join_type, $match);
	}*/
	function getprdListByExistingGroupId($estgid, $grpid, $tempEstPrdIdArry, $estId) {
        $fields = array("tax.tax_id,tax.tax_percentage");
        $data['taxes'] = $this->common_model->get_records(PRODUCT_TAX_MASTER . ' as tax', $fields, '', '', "");

        $table = ESTIMATE_PRODUCT . ' as ep';
        $match = "PM.is_delete = 1 AND PM.product_id IN (" . implode(',', $tempEstPrdIdArry) . ") and ep.estimate_id=" . $estId . " and epgm.est_prd_grp_id=".$estgid." ";
        $join_tables = array(ESTIMATE_PRODUCT_GROUP . ' as epgm' => 'epgm.est_prd_grp_id = ep.est_prd_grp_id',
            PRODUCT_GROUP_MASTER . ' as PG' => 'PG.product_group_id=epgm.product_group_id',
            PRODUCT_GROUP_RELATION . ' as PGR' => 'PGR.product_group_id=PG.product_group_id',
            PRODUCT_MASTER . ' as PM' => 'PM.product_id=ep.product_id',
            PRODUCT_TAX_MASTER . ' as ptm' => 'PGR.product_tax_id = ptm.tax_id',
        );
        //$fields = array("ptm.is_delete,ptm.tax_id,ptm.tax_name,ptm.tax_percentage,PG.product_group_id,PM.product_id,PM.product_name, PM.product_description, PM.sales_price_unit, PGR.product_group_status, PGR.product_qty as prdMainQty, ep.product_qty, ep.product_discount, ep.product_disoption,ep.est_prd_id");
        $fields = array("ptm.is_delete,ptm.tax_id,ptm.tax_name,ptm.tax_percentage,PG.product_group_id,PM.product_id,PM.product_name, PM.product_description, PGR.product_group_status, PGR.product_qty as prdMainQty, ep.product_qty, ep.product_discount, ep.product_disoption,ep.est_prd_id,ep.product_order, ep.product_sales_price as sales_price_unit");
        $join_type = 'left';
        $data['group_info_products'] = $this->common_model->get_records($table, $fields, $join_tables, $join_type, $match, '', '', '', '', '', 'ep.product_id,est_prd_id');
		return $data['group_info_products'];
    }

}
