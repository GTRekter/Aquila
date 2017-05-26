<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package     CodeIgniter
 * @author  	Ivan Porta
 * @copyright 	Copyright (c) 2015.
 * @license  	GLP
 * @since  		Version 1.0
 * @version  	1.0
 */

// ------------------------------------------------------------------------

class Frontmodel extends CI_Model {

	// Validate login
	function validate($email, $password) {
		$this->db->where('accessEmail', $email);
		$this->db->where('accessPassword', sha1($password));
		
		$q = $this->db->get('LOG_Access');
		
		if ($q->num_rows() == 1) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			
			return $data;
		}
	}
	// Validate login
	function validate_Client($email, $password) {
		$this->db->where('ClientEmail', $email);
		$this->db->where('clientPassword', sha1($password));
		
		$q = $this->db->get('ORD_Clients');
		
		if ($q->num_rows() == 1) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}
	}
	// LOG_Blocked_IP
	public function c_LOG_Blocked_IP($data) {
		$this->db->insert('LOG_Blocked_IP', $data);
	}
	public function r_LOG_Blocked_IP($ip) {
		$this->db->select('*');
		$this->db->from('LOG_Blocked_IP');
		
		$this->db->where('IP', $ip);
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}
	}
	// PRD_Products
	public function ra_PRD_Products($limit,$offset,$byManufacturer,$withCategory,$byCategory,$withPhotoCover,$productOrder,$saleOrder,$likeOrder,$specialSale,$priceFrom,$priceTo,$lang) {
		$this->db->select('*');
		$this->db->from('PRD_Products');
		$this->db->join('PRD_Manufacturers','PRD_Manufacturers.idManufacturer = PRD_Products.idManufacturer','inner');
		
		if ($limit != '') {
			$this->db->limit($limit,$offset);
		}
		
		if ($byManufacturer != '') {
			$this->db->where('PRD_Products.idManufacturer', $byManufacturer);
		}
		
		if ($byCategory != '') {
			$this->db->where('PRD_Products.idCategory', $byCategory);
		}
		
		if ($withCategory != '') {
			$this->db->join('PRD_Categories','PRD_Categories.idCategory = PRD_Products.idCategory','inner');
			$this->db->join('LANG_Categories','LANG_Categories.idCategory = PRD_Products.idCategory','inner');
			$this->db->where('LANG_Categories.language',$lang);
		}
		
		if ($withPhotoCover != '') {	
			$this->db->join('PRD_Photos','PRD_Photos.idProduct = PRD_Products.idProduct','inner');
			$this->db->where('isCover',1);
		}
		
		if ($productOrder != '') {
			$this->db->order_by('PRD_Products.createdOn',$productOrder);
		}
		
		if ($saleOrder != '') {
		}
		
		if ($specialSale != '') {
		}
		
		if ($likeOrder != '') {
		}
		
		if ($priceFrom != '' && $priceTo != '') {
			$this->db->where('productPrice >=', $priceFrom);
			$this->db->where('productPrice <=', $priceTo);
		}
		
		if ($lang != '') {
			$this->db->join('LANG_Products','LANG_Products.idProduct = PRD_Products.idProduct','inner');
			$this->db->where('LANG_Products.language',$lang);
		}
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}	
	}
	public function r_PRD_Product($id,$withPhoto,$withPhotoCover,$lang) {
		$this->db->select('*');
		$this->db->from('PRD_Products');
		$this->db->where('PRD_Products.idProduct',$id);
		$this->db->join('PRD_Manufacturers','PRD_Manufacturers.idManufacturer = PRD_Products.idManufacturer','inner');
		
		if ($withPhoto == 1) {
			$this->db->join('PRD_Photos','PRD_Photos.idProduct = PRD_Products.idPhoto','inner');
		}
		
		if ($withPhotoCover != '') {	
			$this->db->join('PRD_Photos','PRD_Photos.idProduct = PRD_Products.idProduct','inner');
			$this->db->where('isCover',1);
		}
		
		if ($lang != '') {
			$this->db->join('LANG_Products','PRD_Products.idProduct = LANG_Products.idProduct','inner');
			$this->db->where('LANG_Products.language',$lang);
		}
		
		$q = $this->db->get()->result();
		return $q[0];
	}
	public function i_PRD_Likes($id) {
		$this->db->where('idProduct', $id);
		$this->db->set('productLikes', 'productLikes+1', FALSE);
		$this->db->update('PRD_Likes');
	}
	public function u_PRD_Product($id, $data) {
		$this->db->where('idProduct', $id);
		$this->db->update('PRD_Products', $data); 
	}
	// PRD_Categories
	public function ra_PRD_Categories($limit,$lang) {
		$this->db->select('*');
		$this->db->from('PRD_Categories');
		if ($limit != '') {
			$this->db->limit($limit);
		}
		
		if ($lang != '') {
			$this->db->join('LANG_Categories','PRD_Categories.idCategory = LANG_Categories.idCategory','right');
			$this->db->where('LANG_Categories.language',$lang);
		}
		
		$this->db->order_by('idParentCategory','asc');
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}
	}
	public function r_PRD_Category($id,$lang) {
		$this->db->select('*');
		$this->db->from('PRD_Categories');
		$this->db->where('PRD_Categories.idCategory',$id);
		
		if ($lang != '') {
			$this->db->join('LANG_Categories','PRD_Categories.idCategory = LANG_Categories.idCategory','right');
			$this->db->where('LANG_Categories.language',$lang);
		}
		
		if ($id != '') {
			$this->db->where('idCategory',$id);
		}
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}
	}
	//PRD_Manufacturer
	public function ra_PRD_Manufacturers($limit) {
		$this->db->select('*');
		$this->db->from('PRD_Manufacturers');
		if ($limit != '') {
			$this->db->limit($limit);
		}
		$this->db->order_by('manufacturerName','asc');
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}	
	}
	// PRD_Photos
	public function ra_PRD_Photos($limit, $idProduct) {
		$this->db->select('*');
		$this->db->from('PRD_Photos');
		if ($limit != '') {
			$this->db->limit($limit);
		}
		if ($idProduct != '') {
			$this->db->where('idProduct',$idProduct);
		}
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}
	}
	public function r_PRD_Photo($id, $idProduct) {
		$this->db->select('*');
		$this->db->from('PRD_Photos');
		if ($id != '') {
			$this->db->where('idPhoto',$id);
		}
		if ($idProduct != '') {
			$this->db->where('idProduct',$idProduct);
		}
		
		$q = $this->db->get()->result();
		return $q[0];
	}
	// PRD_Combinations
	public function r_PRD_Combinations($id,$idProduct,$limit) {
		$this->db->select('*');
		$this->db->from('PRD_Combinations');
		if ($id != '') {
			$this->db->where('idCombination', $id);
		}
		if ($idProduct != '') {
			$this->db->where('idProduct', $idProduct);
		}
		if ($limit != '') {
			$this->db->limit($limit);
		}
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}
	}
	public function u_PRD_Combination($id, $data) {
		$this->db->where('idCombination', $id);
		$this->db->update('PRD_Combinations', $data); 
	}
	// PRD_Groups
	public function ra_PRD_Groups($limit,$withValues,$withFeatures,$lang) {
		$this->db->select('*');
		$this->db->from('PRD_Groups');
		
		if ($limit != '') {
			$this->db->limit($limit);
		}
		if ($withValues != '') {
			$this->db->join('PRD_Values', 'PRD_Values.idValue = PRD_Groups.idValue', 'inner');
			$this->db->join('LANG_Values','PRD_Values.idValue = LANG_Values.idValue','inner');
			$this->db->where('LANG_Values.language',$lang);
		}
		if ($withFeatures != '') {
			$this->db->join('PRD_Features', 'PRD_Features.idFeature = PRD_Values.idFeature', 'inner');
			$this->db->join('LANG_Features','PRD_Features.idFeature = LANG_Features.idFeature','inner');
			$this->db->where('LANG_Features.language',$lang);
		}
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}
	}
	// PRD_Values
	public function ra_PRD_Values($limit,$lang) {
		$this->db->select('*');
		$this->db->from('PRD_Values');
		if ($limit != '') {
			$this->db->limit($limit);
		}	
		if ($lang != '') {
			$this->db->join('LANG_Values','PRD_Values.idValue = LANG_Values.idValue','right');
			$this->db->where('LANG_Values.language',$lang);
		}
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}
	}	
	public function r_PRD_Values($id,$idFeature,$lang) {
		$this->db->select('*');
		$this->db->from('PRD_Values');
		
		if ($id != '') {
			$this->db->where('idValue',$id);
		}
		
		if ($idFeature != '') {
			$this->db->where('PRD_Values.idFeature',$idFeature);
			$this->db->join('PRD_Features', 'PRD_Features.idFeature = PRD_Values.idFeature', 'left');
		}
		
		if ($lang != '') {
			$this->db->join('LANG_Values','PRD_Values.idValue = LANG_Values.idValue','right');
			$this->db->where('LANG_Values.language',$lang);
		}
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}	
	}
	// STN_Photos
	public function ra_STN_Photos($limit, $idArticle) {
		$this->db->select('*');
		$this->db->from('STN_Photos');
		if ($limit != '') {
			$this->db->limit($limit);
		}
		if ($idArticle != '') {
			$this->db->where('idArticle',$idArticle);
		}
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}
	}
	public function r_STN_Photo($id, $idArticle) {
		$this->db->select('*');
		$this->db->from('STN_Photos');
		if ($id != '') {
			$this->db->where('idPhoto',$id);
		}
		if ($idArticle != '') {
			$this->db->where('idArticle',$idArticle);
		}
		
		$q = $this->db->get()->result();
		return $q[0];
	}
	// STN_Articles
	public function ra_STN_Articles($limit,$offset,$withPhotoCover,$articleOrder,$idArticlesCategory,$articleDate,$lang) {
		$this->db->select('*');
		$this->db->from('STN_Articles');
		
		if ($limit != '') {
			$this->db->limit($limit,$offset);
		}
		
		if ($lang != '') {
			$this->db->join('LANG_Articles','STN_Articles.idArticle = LANG_Articles.idArticle','inner');
			$this->db->where('LANG_Articles.language',$lang);
		}
		
		if ($withPhotoCover != '') {	
			$this->db->join('STN_Photos','STN_Photos.idArticle = STN_Articles.idArticle','inner');
			$this->db->where('isCover',1);
		}
		if ($idArticlesCategory != '') {
			$this->db->where('STN_Articles.idArticlesCategory',$idArticlesCategory);
		}
		
		if ($articleOrder != '') {
			$this->db->order_by('STN_Articles.createdOn',$articleOrder);
		}
		if ($articleDate != '') {
			$m = date('n',strtotime($articleDate));
			$y = date('Y',strtotime($articleDate));
			if ($m == 12 ) {
				$y ++;
				$m = 1;
			} else {
				$m ++;
			}
			$this->db->where('STN_Articles.createdOn>=',date('Y-m-d',strtotime($articleDate)));
			$this->db->where('STN_Articles.createdOn<',date('Y-m-d',strtotime(''.$y.'-'.$m.'-01')));
		}
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}	
	}
	public function r_STN_Article($id,$withPhotoCover,$lang) {
		$this->db->select('*');
		$this->db->from('STN_Articles');
		
		if($id != '') {
			$this->db->where('STN_Articles.idArticle',$id);
		} else {
			$this->db->order_by('createdOn', 'asc');	
		}
		
		if ($lang != '') {
			$this->db->join('LANG_Articles','STN_Articles.idArticle = LANG_Articles.idArticle','inner');
			$this->db->where('LANG_Articles.language',$lang);
		}
		
		if ($withPhotoCover != '') {	
			$this->db->join('STN_Photos','STN_Photos.idArticle = STN_Articles.idArticle','inner');
			$this->db->where('isCover',1);
		}
		
		$q = $this->db->get()->result();
		return $q[0];
	}
	// STN_Articles_Categories
	public function ra_STN_Articles_Categories($limit,$lang) {
		$this->db->select('*');
		$this->db->from('STN_Articles_Categories');
		
		if ($limit != '') {
			$this->db->limit($limit);
		}
		
		if ($lang != '') {
			$this->db->join('LANG_Articles_Categories','STN_Articles_Categories.idArticlesCategory = LANG_Articles_Categories.idArticlesCategory','inner');
			$this->db->where('LANG_Articles_Categories.language',$lang);
		}
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}
	}
	public function ra_STN_Articles_Category($id,$lang) {
		$this->db->select('*');
		$this->db->from('STN_Articles_Categories');
		
		if($id != '') {
			$this->db->where('STN_Articles_Categories.idArticlesCategory',$id);
		}
		
		if ($lang != '') {
			$this->db->join('LANG_Articles_Categories','STN_Articles_Categories.idArticlesCategory = LANG_Articles_Categories.idArticlesCategory','inner');
			$this->db->where('LANG_Articles_Categories.language',$lang);
		}
		
		$q = $this->db->get()->result();
		return $q[0];
	}
	// STN_Banners
	public function ra_STN_Banners($limit,$lang) {
		$this->db->select('*');
		$this->db->from('STN_Banners');
		if ($limit != '') {
			$this->db->limit($limit);
		}
		if ($lang != '') {
			$this->db->join('LANG_Banners','STN_Banners.idBanner = LANG_Banners.idBanner','right');
			$this->db->where('LANG_Banners.language',$lang);
		}
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}	
	}
	public function r_STN_Banner($id) {
		$this->db->select('*');
		$this->db->from('STN_Banners');
		
		if ($id != '') {
			$this->db->where('idBanner',$id);
		}
		
		$q = $this->db->get()->result();
		return $q[0];
	}
	// STN_Slides
	public function ra_STN_Slides($limit,$lang) {
		$this->db->select('*');
		$this->db->from('STN_Slides');
		if ($limit != '') {
			$this->db->limit($limit);
		}
		if ($lang != '') {
			$this->db->join('LANG_Slides','STN_Slides.idSlide = LANG_Slides.idSlide','right');
			$this->db->where('LANG_Slides.language',$lang);
		}
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}	
	}
	// STN_Settings
	public function r_STN_Settings() {
		$this->db->select('*');
		$this->db->from('STN_Settings');
		$this->db->where('idSetting',1);
		
		$q = $this->db->get()->result();
		return $q[0];
	}
	// CUR_Currencies
	public function r_STN_Currency($id, $byCode) {
		$this->db->select('*');
		$this->db->from('STN_Currencies');
		if ($id != '') {
			$this->db->where('idCurrency', $id);
		}
		if ($byCode != '') {
			$this->db->where('currencyCode', $byCode);
		}
		
		$q = $this->db->get()->result();
		return $q[0];
	}
	public function ra_STN_Currencies($active, $currencyCode) {
		$this->db->select('*');
		$this->db->from('STN_Currencies');
		if ($active != '') {
			$this->db->where('currencyStatus', $active);
		}
		if ($currencyCode != '') {
			$this->db->where('currencyCode', $currencyCode);
		}
		
		$this->db->order_by('currencyCode', 'asc');	
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}	
	}
	// ORD_Clients
	public function c_ORD_Client($data) {
		$this->db->insert('ORD_Clients', $data);
		
		$insertedID = $this->db->insert_id();
		return $insertedID;
	}
	public function ra_ORD_Clients($id, $email) {
		$this->db->from('ORD_Clients');
		if ($id != '') {
			$this->db->where('idValue',$id);
		}
		if ($email != '') {
			$this->db->where('clientEmail',$email);
		}
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}	
	}
	public function r_ORD_Client($id, $withCountries) {
		$this->db->select('*');
		$this->db->from('ORD_Clients');
		$this->db->where('idClient',$id);
		
		if ($withCountries != '') {
			$this->db->join('STN_Countries','STN_Countries.idCountry = ORD_Clients.idCountry','inner');
		}
		
		$q = $this->db->get()->result();
		return $q[0];
	}
	public function u_ORD_Client($id, $data) {
		$this->db->where('idClient', $id);
		$this->db->update('ORD_Clients', $data); 
	}
	// ORD_Carts
	public function c_ORD_Cart($data) {
		$this->db->insert('ORD_Carts', $data);
		
		$insertedID = $this->db->insert_id();
		return $insertedID;
	}
	public function ra_ORD_Cart($id, $idClient, $byInvoice) {
		$this->db->select('*');
		$this->db->from('ORD_Carts');
		if ($id != '') {
			$this->db->where('idCart',$id);
		}
		
		if ($idClient != '') {
			$this->db->where('idClient',$idClient);
		}
		
		if ($byInvoice != '') {
			$this->db->where('invoicePaypal',$byInvoice);
		}
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}
	}
	// ORD_Couriers
	public function ra_ORD_Couriers($id, $byCountry) {
		$this->db->select('*');
		$this->db->from('ORD_Couriers');
		if ($id != '') {
			$this->db->where('idCourier',$id);
		}
		
		if ($byCountry != '') {
			$this->db->join('STN_Countries','STN_Countries.idCourier = ORD_Carts.idCourier','inner');
			$this->db->where('idCountry',$byCountry);
		}
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}
	}
	// ORD_Ranges_Couriers
	public function ra_ORD_Couriers_Ranges($id, $byCourier, $byWeight) {
		$this->db->select('*');
		$this->db->from('ORD_Couriers_Ranges');
		if ($id != '') {
			$this->db->where('idRangesCourier',$id);
		}
		
		if ($byCourier != '') {
			$this->db->where('idCourier',$byCourier);
		}
		
		if ($byWeight != '') {
			$this->db->where('rangeStart<=',$byWeight);
			$this->db->where('rangeEnd>=',$byWeight);
		}
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}
	}
	// ORD_Orders
	public function c_ORD_Order($data) {
		$this->db->insert('ORD_Orders', $data);
		
		$insertedID = $this->db->insert_id();
		return $insertedID;
	}
	public function r_ORD_Order($id) {
		$this->db->select('*');
		$this->db->from('ORD_Orders');
		$this->db->where('idOrder',$id);
		
		$q = $this->db->get()->result();
		return $q[0];
	}
	public function ra_ORD_Orders($id, $idClient) {
		$this->db->select('*');
		$this->db->from('ORD_Orders');
		if ($id != '') {
			$this->db->where('idOrder',$id);
		}
		
		if ($idClient != '') {
			$this->db->where('idClient',$idClient);
		}
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}
	}
	// ORD_Orders_Products
	public function c_ORD_Orders_Product($data,$withReturnedID) {
		$this->db->insert('ORD_Orders_Products', $data);
		
		if ($withReturnedID == 1) {
			$insertedID = $this->db->insert_id();
			return $insertedID;
		}
	}
	public function ra_ORD_Orders_Products($limit, $withProducts, $withPhotoCover, $byCombination, $byProduct, $byOrder, $lang) {
		$this->db->select('*');
		$this->db->from('ORD_Orders_Products');
		
		if ($limit != '') {
			$this->db->limit($limit);
		}
		
		if ($withProducts != '') {
			$this->db->join('PRD_Products','PRD_Products.idProduct = ORD_Orders_Products.idProduct','inner');
		}
		if ($withPhotoCover != '') {	
			$this->db->join('PRD_Photos','PRD_Photos.idProduct = ORD_Orders_Products.idProduct','inner');
			$this->db->where('isCover',1);
		}
		
		if ($byCombination != '') {
			$this->db->where('idCombination', $byCombination);
		}
		
		if ($byProduct != '') {
			$this->db->where('idProduct', $byProduct);
		}
		
		if ($byOrder != '') {
			$this->db->where('idOrder', $byOrder);
		}
		
		if ($lang != '') {
			$this->db->join('LANG_Products','ORD_Orders_Products.idProduct = LANG_Products.idProduct','inner');
			$this->db->where('LANG_Products.language',$lang);
		}
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}
	}
	// ORD_Carts_Products
	public function c_ORD_Carts_Product($data,$withReturnedID) {
		$this->db->insert('ORD_Carts_Products', $data);
		
		if ($withReturnedID == 1) {
			$insertedID = $this->db->insert_id();
			return $insertedID;
		}
	}
	public function r_ORD_Cart_Products($id, $idCart, $byIdClient, $withCart, $withProduct, $withPhotoCover, $lang){
		$this->db->select('*');
		$this->db->from('ORD_Carts_Products');
		if ($id != '') {
			$this->db->where('idCartsProduct',$id);
		}
		
		if ($idCart != '') {
			$this->db->where('idCart',$idCart);
		}
		
		if ($withCart != '') {
			$this->db->join('ORD_Carts','ORD_Carts.idCart = ORD_Carts_Products.idCart','inner');
		}
		
		if ($byIdClient != '') {
			$this->db->where('idClient',$byIdClient);
		}
		
		if ($withProduct != '') {
			$this->db->join('PRD_Products','PRD_Products.idProduct = ORD_Carts_Products.idProduct','inner');
		}
		
		if ($withPhotoCover != '') {	
			$this->db->join('PRD_Photos','PRD_Photos.idProduct = PRD_Products.idProduct','inner');
			$this->db->where('isCover',1);
		}
		
		if ($lang != '') {
			$this->db->join('LANG_Products','LANG_Products.idProduct = PRD_Products.idProduct','inner');
			$this->db->where('LANG_Products.language',$lang);
		}
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}
	}
	public function u_ORD_Cart_Products($id, $data) {
		$this->db->where('idCartsProduct', $id);
		$this->db->update('ORD_Carts_Products', $data); 
	}
	public function d_ORD_Carts_Product($id) {
	   $this->db->where('idCartsProduct', $id);
	   $this->db->delete('ORD_Carts_Products'); 
	}
	// ORD_Newsletters
	public function c_ORD_Newsletter($data) {
		$this->db->insert('ORD_Newsletters', $data);
	}
	public function r_ORD_Newsletter($id, $clientEmail) {
		$this->db->select('*');
		$this->db->from('ORD_Newsletters');
		if ($id != '') {
			$this->db->where('idNewsletter',$id);
		}
		if ($clientEmail != '') {
			$this->db->where('clientEmail',$clientEmail);
		}
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}
	}
	// CTR_Countries
	public function ra_STN_Pages($limit,$lang) {
		$this->db->select('*');
		$this->db->from('STN_Pages');
		if ($limit != '') {
			$this->db->limit($limit);
		}
		if ($lang != '') {
			$this->db->join('LANG_Pages','STN_Pages.idPage = LANG_Pages.idPage','right');
			$this->db->where('LANG_Pages.language',$lang);
		}
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}	
	}
	public function r_STN_Page($id,$lang) {
		$this->db->select('*');
		$this->db->from('STN_Pages');
		if ($id != '') {
			$this->db->where('STN_Pages.idPage',$id);
		}
		if ($lang != '') {
			$this->db->join('LANG_Pages','STN_Pages.idPage = LANG_Pages.idPage','right');
			$this->db->where('LANG_Pages.language',$lang);
		}
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}	
	}
	// CTR_Countries
	public function ra_STN_Countries($limit,$byCountryCode) {
		$this->db->select('*');
		$this->db->from('STN_Countries');
		if ($limit != '') {
			$this->db->limit($limit);
		}
		if ($byCountryCode != '') {
			$this->db->where('countryCode', $byCountryCode);
		}
		$this->db->order_by('countryName', 'asc');	
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}	
	}
	public function r_STN_Country($id) {
		$this->db->select('*');
		$this->db->from('STN_Countries');
		$this->db->where('idCountry',$id);
		$q = $this->db->get()->result();
		return $q[0];
	}	
	public function search($limit, $offset, $data, $withCategory, $withPhotoCover, $lang) {
		$this->db->select('*');
		$this->db->from('PRD_Products');
		
		$this->db->join('PRD_Manufacturers','PRD_Manufacturers.idManufacturer = PRD_Products.idManufacturer','inner');
		
		if ($lang != '') {
			$this->db->join('LANG_Products','LANG_Products.idProduct = PRD_Products.idProduct','inner');
			$this->db->where('LANG_Products.language',$lang);
		}
		
		if ($withCategory != '') {
			$this->db->join('PRD_Categories','PRD_Categories.idCategory = PRD_Products.idCategory','inner');
			$this->db->join('LANG_Categories','LANG_Categories.idCategory = PRD_Products.idCategory','inner');
			$this->db->where('LANG_Categories.language',$lang);
		}
		
		if ($withPhotoCover != '') {	
			$this->db->join('PRD_Photos','PRD_Photos.idProduct = PRD_Products.idProduct','inner');
			$this->db->where('isCover',1);
		}

		foreach ($data as $key => $value) {
			switch ($key) {
				case 'productPriceFrom':
					$this->db->where('productPrice >=', $value);
					break;
				case 'productPriceTo':	
					$this->db->where('productPrice <=', $value);
					break;
				case 'search':
					$this->db->where('(MATCH (manufacturerName) AGAINST ("'. $value .'")', NULL, FALSE);
					$this->db->or_where('MATCH (productName) AGAINST ("'. $value .'")', NULL, FALSE);
					$this->db->or_where('MATCH (productDescription) AGAINST ("'. $value .'"))', NULL, FALSE);
					break;
				case 'idManufacturer':	
					$this->db->where('PRD_Products.idManufacturer', $value);
					break;
				case 'idCategory':	
					$this->db->where('PRD_Products.idCategory', $value);
					break;
			}	
		}
		$this->db->limit($limit, $offset);
		
		$q = $this->db->get();
		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row) {
			    $result[] = $row;
			}
			
			return $result;
		}
	}
	public function search_count($data, $withCategory, $lang) {
		$this->db->select('*');
		$this->db->from('PRD_Products');
		
		$this->db->join('PRD_Manufacturers','PRD_Manufacturers.idManufacturer = PRD_Products.idManufacturer','inner');
		
		if ($lang != '') {
			$this->db->join('LANG_Products','LANG_Products.idProduct = PRD_Products.idProduct','inner');
			$this->db->where('LANG_Products.language',$lang);
		}
		
		if ($withCategory != '') {
			$this->db->join('PRD_Categories','PRD_Categories.idCategory = PRD_Products.idCategory','inner');
			$this->db->join('LANG_Categories','LANG_Categories.idCategory = PRD_Products.idCategory','inner');
			$this->db->where('LANG_Categories.language',$lang);
		}
		
		foreach ($data as $key => $value) {
			switch ($key) {
				case 'productPriceFrom':
					$this->db->where('productPrice >=', $value);
					break;
				case 'productPriceTo':	
					$this->db->where('productPrice <=', $value);
					break;
				case 'search':
					$this->db->where('(MATCH (manufacturerName) AGAINST ("'. $value .'")', NULL, FALSE);
					$this->db->or_where('MATCH (productName) AGAINST ("'. $value .'")', NULL, FALSE);
					$this->db->or_where('MATCH (productDescription) AGAINST ("'. $value .'"))', NULL, FALSE);
					break;
				case 'idManufacturer':	
					$this->db->where('PRD_Products.idManufacturer', $value);
					break;
				case 'idCategory':	
					$this->db->where('PRD_Products.idCategory', $value);
					break;
			}	
		}
		
		$q = $this->db->get();
		
		return $q->num_rows();
	}
}