<?php defined('BASEPATH') OR exit('No direct script access allowed');
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

class Backmodel extends CI_Model {
	// LOG_Access
	function r_LOG_Access($email) {
		$this->db->select('*');
		$this->db->from('LOG_Access');
		$this->db->where('accessEmail', $email);
		
		$q = $this->db->get()->result();
		return $q[0];
	}
	function ra_LOG_Access($id) {
		$this->db->select('idAccess,accessName,lastUpdate');
		$this->db->from('LOG_Access');
		$this->db->where('idAccess<>', $id);
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}
	}
	public function u_LOG_Access($id,$data) {
		$this->db->where('idAccess', $id);
		$this->db->update('LOG_Access', $data); 
	}
	// PRD Products
	public function c_PRD_Product($data) {
		$this->db->insert('PRD_Products', $data);

		$insertedID = $this->db->insert_id();
		return $insertedID;
	}
	public function ra_PRD_Products($limit, $offset, $byCategory, $byManufacturer, $byTax, $withCategory, $withPhotoCover, $lang) {
		$this->db->select('*');
		$this->db->from('PRD_Products');
		$this->db->join('PRD_Manufacturers','PRD_Manufacturers.idManufacturer = PRD_Products.idManufacturer','inner');
		
		if ($limit != '') {
			$this->db->limit($limit, $offset);
		}
		
		if ($withCategory != '') {
			$this->db->join('LANG_Categories','PRD_Products.idCategory = LANG_Categories.idCategory','inner');
			$this->db->where('LANG_Categories.language',$lang);
		}
		
		if ($byCategory != '') {	
			$this->db->where('idCategory', $byCategory);
		}
		
		if ($byManufacturer != '') {	
			$this->db->where('PRD_Products.idManufacturer', $byManufacturer);
		}
		
		if ($byTax != '') {	
			$this->db->where('PRD_Products.idTax', $byTax);
		}
		
		if ($withPhotoCover != '') {	
			$this->db->join('PRD_Photos','PRD_Photos.idProduct = PRD_Products.idProduct','inner');
			$this->db->where('isCover',1);
		}
		
		if ($lang != '') {
			$this->db->join('LANG_Products','PRD_Products.idProduct = LANG_Products.idProduct','inner');
			$this->db->where('LANG_Products.language',$lang);
		}
		
		$this->db->order_by('PRD_Products.createdOn','desc');
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}	
	}
	public function r_PRD_Product($id,$withPhotoCover,$lang) {
		$this->db->select('*');
		$this->db->from('PRD_Products');
		$this->db->where('PRD_Products.idProduct',$id);
		
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
	public function u_PRD_Product($id, $data) {
		$this->db->where('idProduct', $id);
		$this->db->update('PRD_Products', $data); 
	}
	public function d_PRD_Product($id) {
	   $this->db->where('idProduct', $id);
	   $this->db->delete('PRD_Products'); 
	}
	public function s_PRD_Product($column, $value,$lang){
		$this->db->select('*');
		$this->db->from('PRD_Products');
		$this->db->join('PRD_Manufacturers','PRD_Manufacturers.idManufacturer = PRD_Products.idManufacturer','inner');
		
		$this->db->join('PRD_Photos','PRD_Photos.idProduct = PRD_Products.idProduct','inner');
		$this->db->where('isCover',1);
		
		$this->db->join('PRD_Categories','PRD_Categories.idCategory = PRD_Products.idCategory','inner');
		$this->db->join('LANG_Categories','LANG_Categories.idCategory = PRD_Products.idCategory','inner');
		$this->db->where('LANG_Categories.language',$lang);
		
		$this->db->join('LANG_Products','PRD_Products.idProduct = LANG_Products.idProduct','inner');
		$this->db->where('LANG_Products.language',$lang);
		
		$this->db->where('(MATCH ('.$column.') AGAINST ("'.$value.'"))', NULL, FALSE);
			
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}
	}
	// PRD Manufacturer	
	public function c_PRD_Manufacturer($data) {
		$this->db->insert('PRD_Manufacturers', $data);
	}
	public function ra_PRD_Manufacturers($limit,$offset,$id,$name) {
		$this->db->select('*');
		$this->db->from('PRD_Manufacturers');
		if ($limit != '') {
			$this->db->limit($limit,$offset);
		}
		
		if ($id != '') {
			$this->db->where('idManufacturer',$id);
		} else {
			if ($name != '') {
				$this->db->where('manufacturerName',$name);
			}
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
	public function u_PRD_Manufacturer($id, $data) {
		$this->db->where('idManufacturer', $id);
		$this->db->update('PRD_Manufacturers', $data); 
	}
	public function d_PRD_Manufacturer($id) {
	   $this->db->where('idManufacturer', $id);
	   $this->db->delete('PRD_Manufacturers'); 
	}
	// PRD_Categories
	public function c_PRD_Category($data) {
		$this->db->insert('PRD_Categories', $data);
		
		$insertedID = $this->db->insert_id();
		return $insertedID;
	}
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
			$this->db->where('PRD_Categories.idCategory',$id);
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
	public function u_PRD_Category($id, $data) {
		$this->db->where('idCategory', $id);
		$this->db->update('PRD_Categories', $data); 
	}
	public function d_PRD_Category($id) {
	   $this->db->where('idCategory', $id);
	   $this->db->delete('PRD_Categories'); 
	}
	// PRD_Values
	public function c_PRD_Values($data) {
		$this->db->insert('PRD_Values', $data);
		
		$insertedID = $this->db->insert_id();
		return $insertedID;
	}
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
	public function d_PRD_Values($id, $idFeature) {
		if ($id != '') {
		    $this->db->where('idValue', $id);
		    $this->db->delete('PRD_Values'); 
	    }
	    if ($idFeature != '') {
		    $this->db->where('idFeature', $id);
		    $this->db->delete('PRD_Values'); 
	    }
	}
	// PRD_Features
	public function c_PRD_Features($data) {
		$this->db->insert('PRD_Features', $data);
		
		$insertedID = $this->db->insert_id();
		return $insertedID;
	}
	public function ra_PRD_Features($limit,$isFeature,$lang) {
		$this->db->select('*');
		$this->db->from('PRD_Features');
		if ($limit != '') {
			$this->db->limit($limit);
		}
		if ($isFeature != '') {
			$this->db->where('isFeature',$isFeature);
		}
		if ($lang != '') {
			$this->db->join('LANG_Features','PRD_Features.idFeature = LANG_Features.idFeature','right');
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
	public function r_PRD_Features($id, $lang) {
		$this->db->select('*');
		$this->db->from('PRD_Features');
		$this->db->where('PRD_Features.idFeature',$id);
		
		if ($lang != '') {
			$this->db->join('LANG_Features','PRD_Features.idFeature = LANG_Features.idFeature','right');
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
	public function d_PRD_Features($id) {
	   $this->db->where('idFeature', $id);
	   $this->db->delete('PRD_Features'); 
	}	
	// PRD_Photos
	public function c_PRD_Photo($data) {
		$this->db->insert('PRD_Photos', $data);
	}
	public function ra_PRD_Photos($limit, $idProduct, $isDefault) {
		$this->db->select('*');
		$this->db->from('PRD_Photos');
		if ($limit != '') {
			$this->db->limit($limit);
		}
		if ($idProduct != '') {
			$this->db->where('idProduct',$idProduct);
		}
		if ($isDefault != '') {
			$this->db->where('photoName','default.jpg');
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
	public function u_PRD_Photo($id, $data) {
		$this->db->where('idPhoto', $id);
		$this->db->update('PRD_Photos', $data); 
	}
	public function d_PRD_Photo($id) {
	   $this->db->where('idPhoto', $id);
	   $this->db->delete('PRD_Photos'); 
	}
	// PRD_Combinations
	public function c_PRD_Combinations($data) {
		$this->db->insert('PRD_Combinations', $data);
		
		$insertedID = $this->db->insert_id();
		return $insertedID;
	}	
	public function ra_PRD_Combinations($limit) {
		$this->db->select('*');
		$this->db->from('PRD_Combinations');
		
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
	public function r_PRD_Combinations($id, $idProduct) {
		$this->db->select('*');
		$this->db->from('PRD_Combinations');
		
		if ($id != '') {
			$this->db->where('idCombination', $id);
		}
		
		if ($idProduct != '') {
			$this->db->where('idProduct', $idProduct);
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
	public function d_PRD_Combinations($id, $idProduct) {
		if ($id) {
			$this->db->where('idCombination', $id);
			$this->db->delete('PRD_Combinations'); 
		} 
	   	if ($idProduct) {
	   		$this->db->where('idProduct', $idProduct);
	   		$this->db->delete('PRD_Combinations'); 
	   	} 
	}
	// PRD_Groups
	public function c_PRD_Groups($data) {
		$this->db->insert('PRD_Groups', $data);
	}	
	public function ra_PRD_Groups($limit, $byCombinations, $byValue, $byFeature,$withValue, $withFeature, $lang) {
		$this->db->select('*');
		$this->db->from('PRD_Groups');
		
		if ($limit != '') {
			$this->db->limit($limit);
		}
		
		if ($byCombinations != '') {
			$this->db->where('idCombination', $byCombinations);
		}
		
		if ($byValue != '') {
			$this->db->where('PRD_Groups.idValue', $byValue);
		}
		
		if ($withValue != '') {
			$this->db->join('PRD_Values', 'PRD_Values.idValue = PRD_Groups.idValue', 'inner');
			$this->db->join('LANG_Values', 'PRD_Values.idValue = LANG_Values.idValue', 'inner');
			$this->db->where('LANG_Values.language', $lang);
		}
		
		if ($withFeature != '') {
			$this->db->join('PRD_Features', 'PRD_Features.idFeature = PRD_Values.idFeature', 'inner');
			$this->db->join('LANG_Features', 'PRD_Features.idFeature = LANG_Features.idFeature', 'inner');
			$this->db->where('LANG_Features.language', $lang);
		}
		
		if ($byFeature != '') {
			$this->db->where('PRD_Features.idFeature', $byFeature);
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
	public function r_PRD_Groups($id,$limit,$withValuesFeatures,$lang) {
		$this->db->select('*');
		$this->db->from('PRD_Groups');
		
		if ($limit != '') {
			$this->db->limit($limit);
		}
		
		if ($id != '') {
			$this->db->where('idGroup', $id);
		}
		
		if ($withValuesFeatures == 1) {
			$this->db->join('PRD_Values', 'PRD_Values.idValue = PRD_Groups.idValue', 'inner');
			$this->db->join('LANG_Values', 'LANG_Values.idValue = PRD_Values.idValue', 'inner');
			$this->db->where('LANG_Values.language', $lang);
			$this->db->join('LANG_Features', 'LANG_Features.idFeature = PRD_Values.idFeature', 'inner');
			$this->db->where('LANG_Features.language', $lang);
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
	public function r_PRD_Groups_AllFields($id,$limit) {
		$this->db->select('*');
		$this->db->from('PRD_Groups');
		$this->db->join('PRD_Values', 'PRD_Values.idValue = PRD_Groups.idValue', 'inner');
		$this->db->join('PRD_Features', 'PRD_Features.idFeature = PRD_Values.idFeature', 'inner');
		
		if ($id != '') {
			$this->db->where('idGroup', $id);
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
	public function d_PRD_Groups_byCombination($id) {
	   $this->db->where('idCombination', $id);
	   $this->db->delete('PRD_Groups'); 
	}
	// PRD_Sales
	public function c_PRD_Sale($data) {
		$this->db->insert('PRD_Sales', $data);
	}
	public function ra_PRD_Sales($limit,$offset,$isNotIdSale, $saleAmount, $salePercentage, $saleStart, $saleEnd, $idProduct, $beetweenDates, $groupBy) {
		$this->db->select('*');
		$this->db->from('PRD_Sales');
		
		if ($limit != '' && $offset != '') {
			$this->db->limit($limit,$offset);
		}
		
		if ($isNotIdSale != '') {
			$this->db->where('idSale <>',$isNotIdSale);
		}	
		
		if ($saleAmount != '') {
			$this->db->where('saleAmount',$saleAmount);
		}	
		
		if ($salePercentage != '') {
			$this->db->where('salePercentage',$salePercentage);
		}	
		
		if ($idProduct != '') {
			$this->db->where('idProduct',$idProduct); 
		}	
		
		if ($saleStart != '' && $saleEnd != '' && $beetweenDates == 0) {
			$this->db->where('saleStart',$saleStart);
			$this->db->where('saleEnd',$saleEnd);
		}
		
		if ($saleStart != '' && $saleEnd != '' && $beetweenDates == 1) {
			$this->db->where("(('$saleStart' >= saleStart AND '$saleStart' <= saleEnd)");              
			$this->db->or_where("('$saleEnd' >= saleStart AND '$saleEnd' <= saleEnd)");    		
			$this->db->or_where("('$saleStart' < saleStart AND '$saleEnd' > saleEnd))"); 
		}
		
		if ($groupBy == 1) {
			$this->db->group_by(array("salePercentage", "saleAmount"));
			// $this->db->select('*');
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
	public function r_PRD_Sale($id, $idProduct, $saleStart, $saleEnd) {
		$this->db->select('*');
		$this->db->from('PRD_Sales');
		$this->db->where('idSale',$id);
		
		if ($idProduct != '') {
			$this->db->where('idProduct',$idProduct);
		}
		
		if ($saleStart != '' && $saleEnd != '') {
			$this->db->where('saleStart',$saleStart);
			$this->db->where('saleEnd',$saleEnd);
		}
		
		$q = $this->db->get()->result();
		return $q[0];
	}
	public function u_PRD_Sale($id, $data) {
		$this->db->where('idSale', $id);
		$this->db->update('PRD_Sales', $data); 
	}
	public function d_PRD_Sale($id) {
	   $this->db->where('idSale', $id);
	   $this->db->delete('PRD_Sales'); 
	}
	
	// ORD_Clients
	public function c_ORD_Client($data) {
		$this->db->insert('ORD_Clients', $data);
	}
	public function ra_ORD_Clients($limit,$offset,$withCountries) {
		$this->db->select('*');
		$this->db->from('ORD_Clients');
		if ($limit != '') {
			$this->db->limit($limit,$offset);
		}
		
		if ($withCountries == 1) {
			$this->db->join('STN_Countries','STN_Countries.idCountry = ORD_Clients.idCountry','inner');
			$this->db->order_by('clientName', 'asc');
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
	public function r_ORD_Client_AllFeature($id) {
		$this->db->select('*');
		$this->db->from('ORD_Clients');
		$this->db->where('idClient',$id);
		$this->db->join('CTR_Countries','CTR_Countries.idCountry = ORD_Clients.idCountry','inner');
		
		$q = $this->db->get()->result();
		return $q[0];
	}	
	public function r_ORD_Client($id, $withCountries) {
		$this->db->select('*');
		$this->db->from('ORD_Clients');
		$this->db->where('idClient',$id);
		
		if ($withCountries == 1) {
			$this->db->join('STN_Countries','STN_Countries.idCountry = ORD_Clients.idCountry','inner');
			$this->db->order_by('clientName', 'asc');
		}	
		
		$q = $this->db->get()->result();
		return $q[0];
	}	
	public function u_ORD_Client($id, $data) {
		$this->db->where('idClient', $id);
		$this->db->update('ORD_Clients', $data); 
	}
	// ORD_Couriers
	public function ra_ORD_Couriers($limit, $available) {
		$this->db->select('*');
		$this->db->from('ORD_Couriers');
		if ($limit != '') {
			$this->db->limit($limit);
		}
		if ($available != '') {
			$this->db->where('courierStatus', $available);
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
	public function r_ORD_Order($id) {
		$this->db->select('idOrder, idClient,billingName, billingCountry.countryCode as billingCountryCode, billingCountry.callPrefix as billingCallPrefix, billingCountry.zipCodeFormat as billingZipCodeFormat, billingCountry.countryName as billingCountryName, billingState, billingCity, billingAddress, billingZip, shippingName, shippingCountry.countryCode as shippingCountryCode, shippingCountry.callPrefix as shippingCallPrefix, shippingCountry.zipCodeFormat as shippingZipCodeFormat, shippingCountry.countryName as shippingCountryName, verificationPaypal, orderStatus, orderAmount, createdOn, idPaypal,  shippingCity, shippingZip, shippingState, shippingAddress, shippingAmount, billingEmail');
		$this->db->from('ORD_Orders');
		
		if ($id != '') {
			$this->db->where('idOrder',$id);
		}
		
		$this->db->join('STN_Countries AS shippingCountry','shippingCountry.idCountry = ORD_Orders.shippingIdCountry','left');
		$this->db->join('STN_Countries AS billingCountry','billingCountry.idCountry = ORD_Orders.billingIdCountry','left');
		
		$q = $this->db->get()->result();
		return $q[0];
	}
	public function ra_ORD_Orders($limit, $offset, $withClient, $byClient) {
		if ($withClient != '') {
			$this->db->select('idOrder, ORD_Orders.idClient,billingName, billingCountry.countryCode as billingCountryCode, billingCountry.callPrefix as billingCallPrefix, billingCountry.zipCodeFormat as billingZipCodeFormat, billingCountry.countryName as billingCountryName, billingState, billingCity, billingAddress, billingZip, shippingName, shippingCountry.countryCode as shippingCountryCode, shippingCountry.callPrefix as shippingCallPrefix, shippingCountry.zipCodeFormat as shippingZipCodeFormat, shippingCountry.countryName as shippingCountryName, verificationPaypal, orderStatus, orderAmount, ORD_Orders.createdOn, idPaypal, shippingCity, shippingZip, shippingState, shippingAddress, clientName, clientSurname, clientFiscalCode, clientEmail, clientPassword, clientPhone, clientAddress, clientHouseNumber, clientPostalCode, clientCity, clientState, idPaypal, shippingAmount');
			$this->db->from('ORD_Orders');
		
			$this->db->join('ORD_Clients','ORD_Clients.idClient = ORD_Orders.idClient','left');
		} else {
			$this->db->select('idOrder, ORD_Orders.idClient,billingName, billingCountry.countryCode as billingCountryCode, billingCountry.callPrefix as billingCallPrefix, billingCountry.zipCodeFormat as billingZipCodeFormat, billingCountry.countryName as billingCountryName, billingState, billingCity, billingAddress, billingZip, shippingName, shippingCountry.countryCode as shippingCountryCode, shippingCountry.callPrefix as shippingCallPrefix, shippingCountry.zipCodeFormat as shippingZipCodeFormat, shippingCountry.countryName as shippingCountryName, verificationPaypal, orderStatus, orderAmount, ORD_Orders.createdOn, idPaypal, shippingCity, shippingZip, shippingState, shippingAddress, shippingAmount');
			$this->db->from('ORD_Orders');
		}
		
		if ($byClient != '') {
			$this->db->where('ORD_Orders.idClient', $byClient);
		}
		
		if ($limit != '') {
			$this->db->limit($limit, $offset);
		}
		
		$this->db->join('STN_Countries AS shippingCountry','shippingCountry.idCountry = ORD_Orders.shippingIdCountry','left');
		$this->db->join('STN_Countries AS billingCountry','billingCountry.idCountry = ORD_Orders.billingIdCountry','left');
		
		$this->db->order_by('ORD_Orders.createdOn','desc');
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}	
	}
	public function ra_ORD_Orders_Products($limit, $withProducts, $withPhotoCover, $byCombination, $byProduct, $byOrder, $bySale, $lang) {
		$this->db->select('*');
		$this->db->from('ORD_Orders_Products');
		
		if ($limit != '') {
			$this->db->limit($limit);
		}
		
		if ($withProducts != '') {
			$this->db->join('PRD_Products','PRD_Products.idProduct = ORD_Orders_Products.idProduct','inner');
			$this->db->join('PRD_Manufacturers','PRD_Manufacturers.idManufacturer = PRD_Products.idManufacturer','inner');
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
		
		if ($bySale != '') {
			$this->db->where('idSale', $bySale);
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
	public function ra_ORD_Carts_Products($limit, $withProducts, $byCombination, $byProduct, $bySale) {
		$this->db->select('*');
		$this->db->from('ORD_Carts_Products');
		
		if ($limit != '') {
			$this->db->limit($limit);
		}
		
		if ($withProducts != '') {
			$this->db->join('PRD_Products','PRD_Products.idProduct = ORD_Carts_Products.idProduct','inner');
		}
		
		if ($byCombination != '') {
			$this->db->where('idCombination', $byCombination);
		}
		
		if ($byProduct != '') {
			$this->db->where('idProduct', $byProduct);
		}
		
		if ($bySale != '') {
			$this->db->where('idSale', $bySale);
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
	// STN_Slides
	public function c_STN_Slide($data) {
		$this->db->insert('STN_Slides',$data);
		
		$insertedID = $this->db->insert_id();
		return $insertedID;
	}
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
	public function r_STN_Slide($id) {
		$this->db->select('*');
		$this->db->from('STN_Slides');
		
		if ($id != '') {
			$this->db->where('idSlide',$id);
		}
		
		$q = $this->db->get()->result();
		return $q[0];
	}
	public function u_STN_Slide($id, $data) {
		$this->db->where('idSlide', $id);
		$this->db->update('STN_Slides', $data); 
	}
	public function d_STN_Slide($id) {
	   $this->db->where('idSlide', $id);
	   $this->db->delete('STN_Slides'); 
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
	public function u_STN_Banner($id, $data) {
		$this->db->where('idBanner', $id);
		$this->db->update('STN_Banners', $data); 
	}
	// STN_Articles
	public function c_STN_Article($data) {
		$this->db->insert('STN_Articles',$data);
		
		$insertedID = $this->db->insert_id();
		return $insertedID;
	}
	public function u_STN_Article($id, $data) {
		$this->db->where('idArticle', $id);
		$this->db->update('STN_Articles', $data); 
	}
	public function r_STN_Article($id) {
		$this->db->select('*');
		$this->db->from('STN_Articles');
		
		if ($id != '') {
			$this->db->where('idArticle',$id);
		}
		
		$q = $this->db->get()->result();
		return $q[0];
	}
	public function ra_STN_Articles($limit, $offset, $withPhotoCover, $withCategory, $byCategory, $lang) {
		$this->db->select('*');
		$this->db->from('STN_Articles');
		
		if ($limit != '') {
			$this->db->limit($limit, $offset);
		}
		
		if ($withPhotoCover != '') {	
			$this->db->join('STN_Photos','STN_Photos.idArticle = STN_Articles.idArticle','inner');
			$this->db->where('isCover',1);
		}
		
		if ($withCategory != '') {	
			$this->db->join('LANG_Articles_Categories','LANG_Articles_Categories.idArticlesCategory = STN_Articles.idArticlesCategory','inner');
			$this->db->where('LANG_Articles_Categories.language',$lang);
		}
		
		if ($byCategory != '') {	
			$this->db->where('STN_Articles.idArticlesCategory',$byCategory);
		}
		
		if ($lang != '') {
			$this->db->join('LANG_Articles','STN_Articles.idArticle = LANG_Articles.idArticle','inner');
			$this->db->where('LANG_Articles.language',$lang);
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
	public function d_STN_Article($id) {
	   $this->db->where('idArticle', $id);
	   $this->db->delete('STN_Articles'); 
	}
	public function s_STN_Article($column, $value,$lang){
		$this->db->select('*');
		$this->db->from('STN_Articles');
		
		$this->db->join('STN_Photos','STN_Photos.idArticle = STN_Articles.idArticle','inner');
		$this->db->where('isCover',1);
		
		$this->db->join('STN_Articles_Categories','STN_Articles_Categories.idArticlesCategory = STN_Articles.idArticlesCategory','inner');
		$this->db->join('LANG_Articles_Categories','LANG_Articles_Categories.idArticlesCategory = STN_Articles.idArticlesCategory','inner');
		$this->db->where('LANG_Articles_Categories.language',$lang);
		
		$this->db->join('LANG_Articles','STN_Articles.idArticle = LANG_Articles.idArticle','inner');
		$this->db->where('LANG_Articles.language',$lang);
		
		$this->db->where('(MATCH ('.$column.') AGAINST ("'.$value.'"))', NULL, FALSE);
			
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}
	} // BUG: Gli articoli senza immagini non vengono trovati.
	// STN_Articles_Categories
	public function c_STN_Articles_Categories($data) {
		$this->db->insert('STN_Articles_Categories',$data);
		
		$insertedID = $this->db->insert_id();
		return $insertedID;
	}
	public function ra_STN_Articles_Categories($limit, $offset, $lang) {
		$this->db->select('*');
		$this->db->from('STN_Articles_Categories');
		
		if ($limit != '') {
			$this->db->limit($limit, $offset);
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
	public function d_STN_Articles_Categories($id) {
	   $this->db->where('idArticlesCategory', $id);
	   $this->db->delete('STN_Articles_Categories'); 
	}
	// STN_Pages
	public function c_STN_Page($data) {
		$this->db->insert('STN_Pages',$data);
		
		$insertedID = $this->db->insert_id();
		return $insertedID;
	}
	public function ra_STN_Pages($limit, $offset, $lang) {
		$this->db->select('*');
		$this->db->from('STN_Pages');
		
		if ($limit != '') {
			$this->db->limit($limit, $offset);
		}
		
		if ($lang != '') {
			$this->db->join('LANG_Pages','STN_Pages.idPage = LANG_Pages.idPage','inner');
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
	public function d_STN_Page($id) {
	   $this->db->where('idPage', $id);
	   $this->db->delete('STN_Pages'); 
	}
	public function s_STN_Page($column, $value,$lang){
		$this->db->select('*');
		$this->db->from('STN_Pages');
		
		$this->db->join('LANG_Pages','STN_Pages.idPage = LANG_Pages.idPage','inner');
		$this->db->where('LANG_Pages.language',$lang);
		
		$this->db->where('(MATCH ('.$column.') AGAINST ("'.$value.'"))', NULL, FALSE);
			
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}
	} // BUG: Gli articoli senza immagini non vengono trovati.
	// STN_Photos
	public function c_STN_Photo($data) {
		$this->db->insert('STN_Photos',$data);
	}
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
	public function u_STN_Photo($id, $data) {
		$this->db->where('idPhoto', $id);
		$this->db->update('STN_Photos', $data); 
	}
	public function d_STN_Photo($id) {
	   $this->db->where('idPhoto', $id);
	   $this->db->delete('STN_Photos'); 
	}
	// STN_Countries
	public function ra_STN_Countries($limit) {
		$this->db->select('*');
		$this->db->from('STN_Countries');
		if ($limit != '') {
			$this->db->limit($limit);
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
	public function u_STN_Country($id, $data) {
		$this->db->where('idCountry', $id);
		$this->db->update('STN_Countries', $data); 
	}
	// STN_Currencies
	public function ra_STN_Currencies($limit) {
		$this->db->select('*');
		$this->db->from('STN_Currencies');
		if ($limit != '') {
			$this->db->limit($limit);
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
	public function r_STN_Currency($id, $currencyCode) {
		$this->db->select('*');
		$this->db->from('STN_Currencies');
		if($id) {
			$this->db->where('idCurrency',$id);
		}
		if($currencyCode) {
			$this->db->where('currencyCode',$currencyCode);
		}
		
		$q = $this->db->get()->result();
		return $q[0];
	}
	public function u_STN_Currency($id, $data) {
		$this->db->where('idCurrency', $id);
		$this->db->update('STN_Currencies', $data); 
	}
	// STN_Settings
	public function r_STN_Settings() {
		$this->db->select('*');
		$this->db->from('STN_Settings');
		$this->db->where('idSetting',1);
		
		$q = $this->db->get()->result();
		return $q[0];
	}
	public function u_STN_Settings($id, $data) {
		$this->db->where('idSetting', 1);
		$this->db->update('STN_Settings', $data); 
	}
	// STN_Messages //
	public function c_STN_Message($data) {
		$this->db->insert('STN_Messages', $data);
	}	
	public function r_STN_Message($limit,$offset) {	
		$this->db->select('*');
		$this->db->from('STN_Messages');
		if ($limit) {
			$this->db->limit($limit,$offset);
		}
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row) {
	    		$data[] = $row;
			}
			return $data;
		}
	}
	public function r_STN_Message_After($time,$offset) {	
		$this->db->select('LOG_Access.accessName, LOG_Access.accessEmail ,STN_Messages.createdOn, STN_Messages.idSender, STN_Messages.messageText, STN_Messages.messageTime');
		$this->db->from('STN_Messages');
		$this->db->join('LOG_Access','idAccess = idSender', 'Left');
		$this->db->where('messageTime >', $time);
		$this->db->order_by('messageTime', 'ASC');
		$this->db->limit(10,$offset);
	
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row) {
	    		$data[] = $row;
			}
			return $data;
		}
	}
	// STN_Commitments
	public function c_STN_Commitment($data) {
		$this->db->insert('STN_Commitments', $data);
	}	
	public function ra_STN_Commitments($limit,$offset) {
		$this->db->select('*');
		$this->db->from('STN_Commitments');
		
		if ($limit != '') {
			$this->db->limit($limit,$offset);
		}
		$this->db->order_by('createdOn', 'ASC');
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}	
	}
	public function u_STN_Commitment($id, $data) {
		$this->db->where('idCommitment', $id);
		$this->db->update('STN_Commitments', $data); 
	}
	public function d_STN_Commitment($id) {
	   $this->db->where('idCommitment', $id);
	   $this->db->delete('STN_Commitments'); 
	}
	// STN_Tax
	public function c_STN_Tax($data) {
		$this->db->insert('STN_Tax', $data);
	}
	public function ra_STN_Tax($limit) {
		$this->db->select('*');
		$this->db->from('STN_Tax');
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
	public function r_STN_Tax($id) {
		$this->db->select('*');
		$this->db->from('STN_Tax');
		if ($id != '') {
			$this->db->where('idTax',$id);
		}
		
		$q = $this->db->get()->result();
		return $q[0];
	}
	public function u_STN_Tax($id, $data) {
		$this->db->where('idTax', $id);
		$this->db->update('STN_Tax', $data); 
	}
	public function d_STN_Tax($id) {
	   $this->db->where('idTax', $id);
	   $this->db->delete('STN_Tax'); 
	}
	// STN_Ebay
	public function r_STN_Ebay() {
		$this->db->select('*');
		$this->db->from('STN_Ebay');
		$this->db->where('idEbay',1);
		
		$q = $this->db->get()->result();
		return $q[0];
	}
	public function u_STN_Ebay($data) {
		$this->db->where('idEbay', 1);
		$this->db->update('STN_Ebay', $data); 
	}
	public function ra_PRD_Products_Ebay($lang) {
		$this->db->select('*');
		$this->db->from('PRD_Products');
		$this->db->where('PRD_Products.isMarketplace',1);
		$this->db->join('PRD_Manufacturers','PRD_Manufacturers.idManufacturer = PRD_Products.idManufacturer','inner');

		$this->db->join('LANG_Categories','PRD_Products.idCategory = LANG_Categories.idCategory','inner');
		$this->db->where('LANG_Categories.language',$lang);
		$this->db->join('STN_Ebay_Categories','PRD_Products.idCategory = STN_Ebay_Categories.idCategory','inner');
		
		$this->db->join('LANG_Products','PRD_Products.idProduct = LANG_Products.idProduct','inner');
		$this->db->where('LANG_Products.language',$lang);
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}	
	}
	// STN_Ebay_Categories
	public function c_STN_Ebay_Category($data) {
		$this->db->insert('STN_Ebay_Categories', $data);
	}
	public function ra_STN_Ebay_Category($id) {
		$this->db->select('*');
		$this->db->from('STN_Ebay_Categories');
		$this->db->where('idCategory',$id);
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}
	}
	public function u_STN_Ebay_Category($id,$data) {
		$this->db->where('idCategory', $id);
		$this->db->update('STN_Ebay_Categories', $data); 
	}
	// STN_Ebay
	public function ra_STN_Ebay_SiteId() {
		$this->db->select('*');
		$this->db->from('STN_Ebay_SiteId');
		
		$q = $this->db->get();		
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row)
			{
			    $data[] = $row;
			}
			return $data;
		}
	}
	public function r_STN_Ebay_SiteId($siteId) {
		$this->db->select('*');
		$this->db->from('STN_Ebay_SiteId');
		$this->db->where('siteId',$siteId);
	
		$q = $this->db->get()->result();
		return $q[0];
	}
	// LANG_Articles
	public function c_LANG_Article($data) {
		$this->db->insert('LANG_Articles',$data);
	}
	public function ra_LANG_Articles($limit,$idArticle,$lang) {
		$this->db->select('*');
		$this->db->from('LANG_Articles');
		if ($limit != '') {
			$this->db->limit($limit);
		}
		if ($idArticle != '') {
			$this->db->where('idArticle',$idArticle);
		}
		if ($lang != '') {
			$this->db->where('language',$lang);
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
	public function u_LANG_Article($id, $idArticle, $data, $lang) {
		$this->db->where('idLangArticle', $id);
		
		if ($idArticle != '') {
			$this->db->where('idArticle', $idArticle);
		}
		
		if ($lang != '') {
			$this->db->where('language',$lang);
		}
		
		$this->db->update('LANG_Articles', $data); 
	}
	public function d_LANG_Article($id,$idArticle,$lang) {
		if ($id != '') {
			$this->db->where('idLangArticle', $id);
			$this->db->delete('LANG_Articles'); 
		}
		if ($idArticle != '') {
			$this->db->where('idArticle', $idArticle);
			$this->db->delete('LANG_Articles'); 
		}
		if ($lang != '') {
			$this->db->where('language', $lang);
			$this->db->delete('LANG_Articles'); 
		}
	}
	// LANG_Slides
	public function c_LANG_Slide($data) {
		$this->db->insert('LANG_Slides',$data);
	}
	public function ra_LANG_Slides($limit,$idSlide,$lang) {
		$this->db->select('*');
		$this->db->from('LANG_Slides');
		if ($limit != '') {
			$this->db->limit($limit);
		}
		if ($idSlide != '') {
			$this->db->where('idSlide',$idSlide);
		}
		if ($lang != '') {
			$this->db->where('language',$lang);
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
	public function u_LANG_Slide($id, $idSlide, $data, $lang) {
		$this->db->where('idLangSlide', $id);
		
		if ($idSlide != '') {
			$this->db->where('idSlide', $idSlide);
		}
		
		if ($lang != '') {
			$this->db->where('language',$lang);
		}
		
		$this->db->update('LANG_Slides', $data); 
	}
	public function d_LANG_Slide($id,$idSlide,$lang) {
		if ($id != '') {
			$this->db->where('idLangSlide', $id);
			$this->db->delete('LANG_Slides'); 
		}
		if ($idSlide != '') {
			$this->db->where('idSlide', $idSlide);
			$this->db->delete('LANG_Slides'); 
		}
		if ($lang != '') {
			$this->db->where('language', $lang);
			$this->db->delete('LANG_Slides'); 
		}
	}
	// LANG_Products
	public function c_LANG_Product($data) {
		$this->db->insert('LANG_Products',$data);
	}
	public function ra_LANG_Products($idProduct,$lang) {
		$this->db->select('*');
		$this->db->from('LANG_Products');
		if ($idProduct != '') {
			$this->db->where('idProduct',$idProduct);
		}
		if ($lang != '') {
			$this->db->where('language',$lang);
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
	public function u_LANG_Product($id, $data, $lang) {
		$this->db->where('idProduct', $id);
		
		if ($lang != '') {
			$this->db->where('language',$lang);
		}
		
		$this->db->update('LANG_Products', $data); 
	}
	public function d_LANG_Product($id,$idProduct,$lang) {
		if ($id != '') {
			$this->db->where('idLangProduct', $id);
			$this->db->delete('LANG_Products'); 
		}
		if ($idProduct != '') {
			$this->db->where('idProduct', $idProduct);
			$this->db->delete('LANG_Products'); 
		}
		if ($lang != '') {
			$this->db->where('language', $lang);
			$this->db->delete('LANG_Products'); 
		}
	}
	// LANG_Category
	public function c_LANG_Category($data) {
		$this->db->insert('LANG_Categories',$data);
	}
	public function ra_LANG_Categories($limit,$idCategory,$lang) {
		$this->db->select('*');
		$this->db->from('LANG_Categories');
		if ($limit != '') {
			$this->db->limit($limit);
		}
		if ($lang != '') {
//			$this->db->join('LANG_Categories','PRD_Categories.idCategory = LANG_Categories.idCategory','right');
			$this->db->where('LANG_Categories.language',$lang);
		}
		if ($idCategory != '') {
			$this->db->where('idCategory', $idCategory);
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
	public function u_LANG_Category($id, $data) {
		$this->db->where('idLangCategory', $id);
		$this->db->update('LANG_Categories', $data); 
	}
	public function d_LANG_Category($id,$idCategory,$lang) {
		if ($id != '') {
			$this->db->where('idLangCategory', $id);
			$this->db->delete('LANG_Categories');
		}
		if ($idCategory != '') {
			$this->db->where('idCategory', $idCategory);
			$this->db->delete('LANG_Categories');
		}
		if ($lang != '') {
			$this->db->where('language', $lang);
			$this->db->delete('LANG_Categories');
		} 
	}
	// LANG_Values
	public function c_LANG_Value($data) {
		$this->db->insert('LANG_Values',$data);
	}
	public function ra_LANG_Values($limit,$idValue,$idFeature,$lang) {
		$this->db->select('*');
		$this->db->from('LANG_Values');
		if ($limit != '') {
			$this->db->limit($limit);
		}
		if ($lang != '') {
//			$this->db->join('LANG_Values','PRD_Values.idValue = LANG_Values.idValue','right');
			$this->db->where('LANG_Values.language',$lang);
		}
		if ($idValue != '') {
			$this->db->where('idValue', $idValue);
		}
		if ($idFeature != '') {
			$this->db->join('PRD_Values','PRD_Values.idValue = LANG_Values.idValue','right');
			$this->db->where('idFeature', $idFeature);
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
	public function u_LANG_Value($id, $data) {
		$this->db->where('idLangValue', $id);
		$this->db->update('LANG_Values', $data); 
	}
	public function d_LANG_Value($id, $idValue, $lang) {
		if ($id != '') {
			$this->db->where('idLangValue', $id);
			$this->db->delete('LANG_Values'); 
		}
		if ($idValue != '') {
			$this->db->where('idValue', $idValue);
			$this->db->delete('LANG_Values'); 
		}
		if ($lang != '') {
			$this->db->where('language', $lang);
			$this->db->delete('LANG_Values'); 
		}
	}
	// LANG_Features
	public function c_LANG_Feature($data) {
		$this->db->insert('LANG_Features',$data);
	}
	public function ra_LANG_Features($limit,$idFeature,$lang) {
		$this->db->select('*');
		$this->db->from('LANG_Features');
		if ($limit != '') {
			$this->db->limit($limit);
		}
		if ($lang != '') {
//			$this->db->join('LANG_Features','PRD_Features.idFeature = LANG_Features.idFeature','right');
			$this->db->where('LANG_Features.language',$lang);
		}
		if ($idFeature != '') {
			$this->db->where('idFeature', $idFeature);
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
	public function u_LANG_Feature($id, $data) {
		$this->db->where('idLangFeature', $id);
		$this->db->update('LANG_Features', $data); 
	}
	public function d_LANG_Feature($id, $idFeature, $lang) {
		if ($id != '') {
			$this->db->where('idLangFeature', $id);
			$this->db->delete('LANG_Features'); 
		}
		if ($idFeature != '') {
			$this->db->where('idFeature', $idFeature);
			$this->db->delete('LANG_Features'); 
		}
		if ($lang != '') {
			$this->db->where('language', $lang);
			$this->db->delete('LANG_Features'); 
		}
	}
	// LANG_Banners
	public function c_LANG_Banner($data) {
		$this->db->insert('LANG_Banners',$data);
	}
	public function ra_LANG_Banners($limit,$idBanner,$lang) {
		$this->db->select('*');
		$this->db->from('LANG_Banners');
		if ($limit != '') {
			$this->db->limit($limit);
		}
		if ($lang != '') {
//			$this->db->join('LANG_Features','PRD_Features.idFeature = LANG_Features.idFeature','right');
			$this->db->where('LANG_Banners.language',$lang);
		}
		if ($idBanner != '') {
			$this->db->where('idBanner', $idBanner);
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
	public function u_LANG_Banner($id, $idBanner, $data, $lang) {
		$this->db->where('idLangBanner', $id);
		
		if ($idBanner != '') {
			$this->db->where('idBanner', $idBanner);
		}
		
		if ($lang != '') {
			$this->db->where('language',$lang);
		}
		
		$this->db->update('LANG_Banners', $data); 
	}
	public function d_LANG_Banner($id,$lang) {
		if ($id != '') {
			$this->db->where('idLangBanner', $id);
			$this->db->delete('LANG_Banners'); 
		}
		if ($lang != '') {
			$this->db->where('language', $lang);
			$this->db->delete('LANG_Banners'); 
		}
	}
	// LANG_Articles_Categories
	public function c_LANG_Articles_Categories($data) {
		$this->db->insert('LANG_Articles_Categories',$data);
	}
	public function ra_LANG_Articles_Categories($limit,$idArticlesCategory,$lang) {
		$this->db->select('*');
		$this->db->from('LANG_Articles_Categories');
		if ($limit != '') {
			$this->db->limit($limit);
		}
		if ($lang != '') {
//			$this->db->join('LANG_Features','PRD_Features.idFeature = LANG_Features.idFeature','right');
			$this->db->where('LANG_Articles_Categories.language',$lang);
		}
		if ($idArticlesCategory != '') {
			$this->db->where('idArticlesCategory', $idArticlesCategory);
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
	public function u_LANG_Articles_Category($id, $idArticlesCategory, $data, $lang) {
		$this->db->where('idLangArticlesCategory', $id);
		
		if ($idArticlesCategory != '') {
			$this->db->where('idArticlesCategory', $idArticlesCategory);
		}
		
		if ($lang != '') {
			$this->db->where('language',$lang);
		}
		
		$this->db->update('LANG_Articles_Categories', $data); 
	}
	public function d_LANG_Articles_Categories($id,$idArticlesCategory,$lang) {
		if ($id != '') {
			$this->db->where('idLangArticlesCategory', $id);
			$this->db->delete('LANG_Articles_Categories'); 
		}
		if ($idArticlesCategory != '') {
			$this->db->where('idArticlesCategory', $idArticlesCategory);
			$this->db->delete('LANG_Articles_Categories'); 
		}
		if ($lang != '') {
			$this->db->where('language', $lang);
			$this->db->delete('LANG_Articles_Categories'); 
		}
	}
	// LANG_Pages
	public function c_LANG_Page($data) {
		$this->db->insert('LANG_Pages',$data);
	}
	public function ra_LANG_Pages($limit,$idPage,$lang) {
		$this->db->select('*');
		$this->db->from('LANG_Pages');
		if ($limit != '') {
			$this->db->limit($limit);
		}
		if ($lang != '') {
			$this->db->where('LANG_Pages.language',$lang);
		}
		if ($idPage != '') {
			$this->db->where('idPage', $idPage);
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
	public function u_LANG_Page($id, $data, $lang) {
		$this->db->where('idLangPage', $id);
		
		if ($lang != '') {
			$this->db->where('language',$lang);
		}
		
		$this->db->update('LANG_Pages', $data); 
	}
	public function d_LANG_Page($id,$lang) {
		if ($id != '') {
			$this->db->where('idLangPage', $id);
			$this->db->delete('LANG_Pages'); 
		}
		if ($lang != '') {
			$this->db->where('language', $lang);
			$this->db->delete('LANG_Pages'); 
		}
	}
	// EXPORT FILE
	public function export($tableName){
		$this->db->select('*');
		$this->db->from($tableName);
		
		$q = $this->db->get();
		return $q;
	}  
}