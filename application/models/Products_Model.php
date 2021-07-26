<?php

class Products_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function get_products( $id ) {
		$this->db->select('*, productcategories.name, productcategories.id as categoryid, products.id as id, products.code, products.productname, products.description, products.purchase_price, products.sale_price, products.stock, products.vat, products.status_id, products.productimage');
		$this->db->join( 'productcategories', 'products.categoryid = productcategories.id', 'inner' );
		return $this->db->get_where( 'products', array( 'products.id' => $id ) )->row_array();
	}

	function get_product_by_id( $id ) {
		$this->db->select('*');
		return $this->db->get_where( 'products', array( 'id' => $id ) )->row_array();
	}

	function get_all_products() { 
		$this->db->select('productcategories.name, productcategories.id as categoryid, products.id as id, products.code, products.productname, products.description, products.purchase_price, products.sale_price, products.stock, products.vat, products.status_id, products.productimage');
		$this->db->join( 'productcategories', 'products.categoryid = productcategories.id', 'left' );
		$this->db->order_by( 'products.id', 'desc' );
		return $this->db->get_where( 'products', array( '' ) )->result_array();
	}

	function getallproductsjson() {
		$this->db->select( 'id id,code code,productname label,sale_price sale_price,vat vat' );
		$this->db->from( 'products' );
		return $this->db->get()->result();
	}

	function add_products( $params ) {
		$this->db->insert( 'products', $params );
		$product = $this->db->insert_id();
		$appconfig = get_appconfig();
		$number = $appconfig['product_series'] ? $appconfig['product_series'] : $product;
		$product_number = $appconfig['product_prefix'] . $number;
		$this->db->where('id', $product)->update('products', array('product_number' => $product_number));
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $this->session->usr_id . '"> ' . $this->session->staffname . '</a> ' . lang( 'addedanewproduct' ) . ' <a href="products/product/' . $product . '"> ' . lang( 'product' ) . ' ' .get_number('products',$product,'product','product') . '</a>' ),
			'staff_id' => $this->session->usr_id
		) );
		return $product;
	}

	function insert_products_csv($data) {
		$this->db->insert('products', $data);
		$product = $this->db->insert_id();
		$appconfig = get_appconfig();
		$number = $appconfig['product_series'] ? $appconfig['product_series'] : $product;
		$product_number = $appconfig['product_prefix'].$number;
		$this->db->where('id', $product)->update( 'products', array('product_number' => $product_number ) );
    }

	function update_products( $id, $params ) {
		$appconfig = get_appconfig();
		$product_data = $this->get_products($id);
		if ($product_data['product_number'] == '') {
			$number = $appconfig['product_series'] ? $appconfig['product_series'] : $id;
			$product_number = $appconfig['product_prefix'] . $number;
			$this->db->where('id', $id)->update('products', array('product_number' => $product_number));
			if (($appconfig['product_series'] != '')) {
				$product_number = $appconfig['product_series'];
				$product_number = $product_number + 1;
				$this->Settings_Model->increment_series('product_series', $product_number);
			}
		}
		$this->db->where( 'id', $id );
		$response = $this->db->update( 'products', $params );
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $this->session->usr_id . '"> ' . $this->session->staffname . '</a> ' . lang( 'updated' ) . ' <a href="products/product/' . $id . '"> ' . lang( 'product' ) . ' ' . get_number('products',$id,'product','product') . '</a>' ),
			'staff_id' => $this->session->usr_id
		) );
	}

	function get_products_for_import() {     
        $query = $this->db->get('products');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

	function get_product_categories() {
		$this->db->order_by( 'id', 'desc' );
		return $this->db->get_where( 'productcategories', array( '' ) )->result_array();
	}

	function get_categories($staff_id='') {
		$this->db->select('productcategories.name as name, COUNT(productcategories.name) as y');
		$this->db->join( 'productcategories', 'products.categoryid = productcategories.id', 'left' );
		if($staff_id){
			$this->db->where('product_created_by', $staff_id);
		}
		$this->db->group_by('productcategories.name'); 
		//$this->db->order_by('total', 'desc'); 
		return $this->db->get_where( 'products', array( '' ) )->result_array();
	}

	function get_category( $id ) {
		return $this->db->get_where( 'productcategories', array( 'id' => $id ) )->row_array();
	}

	function update_category( $id, $params ) {
		$this->db->where( 'id', $id );
		return $this->db->update( 'productcategories', $params );
	}

	function check_category($id) {
		$data = $this->db->get_where( 'products', array( 'categoryid' => $id ) )->num_rows();
		return $data;
	}

	function remove_category( $id ) {
		$response = $this->db->delete( 'productcategories', array( 'id' => $id ) );
	}

	function delete_products( $id, $number ) {
		$response = $this->db->delete( 'products', array( 'id' => $id ) );
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $this->session->usr_id . '"> ' . $this->session->staffname . '</a> ' . lang( 'deleted' ) . ' ' . lang( 'product' ) .' '. $number . '' ),
			'staff_id' => $this->session->usr_id
		) );
		$response = $this->db->delete( 'custom_fields_data', array( 'relation_type' => 'product', 'relation' => $id ) );
	}

	function get_all_products_by_privileges($staff_id='') { 
		$this->db->select('productcategories.name, productcategories.id as categoryid, products.id as id, products.code, products.productname, products.description, products.purchase_price, products.sale_price, products.stock, products.vat, products.status_id, products.productimage');
		$this->db->join( 'productcategories', 'products.categoryid = productcategories.id', 'left' );
		$this->db->order_by( 'products.id', 'desc' );
		if($staff_id) {
			return $this->db->get_where( 'products', array( 'product_created_by' => $staff_id ) )->result_array();
		} else {
			return $this->db->get_where( 'products', array( '' ) )->result_array();
		}
	}

	function get_product_by_privileges( $id, $staff_id='' ) {
		$this->db->select('*');
		if($staff_id) {
			return $this->db->get_where( 'products', array( 'id' => $id, 'product_created_by' => $staff_id ) )->row_array();
		} else {
			return $this->db->get_where( 'products', array( 'id' => $id ) )->row_array();	
		}
		
	}
}