<?php
defined('BASEPATH') OR exit('No direct script access allowed');
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

class PrivateArea extends CI_Controller {

// Login
	function __construct() {
		parent::__construct();
		$query = $this->is_logged_in();
		if ($query == false) {
			$this->session->set_flashdata('message','Per accedere all area privata devi prima eseguire il login!');
			redirect('front/signin');
		}
	}
	function is_logged_in() {
		$is_logged_in = $this->session->userdata('client_is_logged_in');
		
		if (!isset($is_logged_in) || $is_logged_in != true) {
			return false;
		} else {
			return true;
		}
	}
	function logout() {
		$data = array('idClient', 'clientName', 'client_is_logged_in', 'cartProducts');
		$this->session->unset_userdata($data);
		
		$this->session->set_flashdata('message','Ciao, a presto!');
		redirect('front');
	}
// End Login

// ACTIONS	
	public function r_ORD_Order() {
		$idOrder = $this->input->post('idOrder');
		$data = $this->frontmodel->r_ORD_Order($idOrder);
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	public function r_ORD_Orders_Products() {
		$idOrder = $this->input->post('idOrder');
		$data = $this->frontmodel->r_ORD_Orders_Product('',$idOrder);
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	
	public function u_ORD_Client() {
		//Form Validation
		$this->load->library('form_validation');
		$this->form_validation->set_rules('clientName', 'Nome', 'trim|required');
		$this->form_validation->set_rules('clientSurname', 'Cognome', 'trim|required');
		$this->form_validation->set_rules('clientPhone', 'Telefono', 'trim|required');
		$this->form_validation->set_rules('clientAddress', 'Indirizzo', 'trim|required');
		$this->form_validation->set_rules('clientHouseNumber', 'Codice postale', 'trim|required');
		$this->form_validation->set_rules('clientPostalCode', 'Codice postale', 'trim|required');
		$this->form_validation->set_rules('clientCity', 'Città', 'trim|required');
		$this->form_validation->set_rules('clientState', 'Provincia', 'trim|required');
		$this->form_validation->set_message('required', 'Riempi il campo %s');
		//End Form Validation
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('message', validation_errors());
		} else {
			$data = array(
				'clientName' => strtolower($this->input->post('clientName')),
				'clientSurname' => strtolower($this->input->post('clientSurname')),
				'clientPhone' => $this->input->post('clientPhone'),
				'clientAddress' => strtolower($this->input->post('clientAddress')),
				'clientHouseNumber' => $this->input->post('clientHouseNumber'),
				'clientPostalCode' => $this->input->post('clientPostalCode'),
				'clientCity' => $this->input->post('clientCity'),
				'clientState' => $this->input->post('clientState'),
				'idCountry' => $this->input->post('idCountry'),
			);
			$this->frontmodel->u_ORD_Client($this->input->post('idClient'),$data);
			$this->session->set_flashdata('message','Utente modificato con successo');
			redirect(site_url('PrivateArea'));
		}		
	}
	public function d_ORD_Cart_Products() {
		$this->frontmodel->d_ORD_Cart_Products( $this->input->post('idCartProduct') ); 

		$this->session->set_flashdata('message', 'Prodotto cancellato con successo');
		redirect('PrivateArea/cart');
	}
	
	// Navigation
	public function nav_lang() {
		$settings = $this->frontmodel->r_STN_Settings();
		$languages = array_filter(array_filter(explode( ',', $settings->shopLanguages )));
		array_unshift($languages, "it");
		return $languages;
	} 
	public function nav_categories($categories) {
		// CATEGORIES TREE (NAV)
		$navCategories = array();
		
		if ($categories) {
			for ($i = 0; $i < count($categories); $i++) {
				
				$tempSubcategory = (object) [
					'idSubcategory' => $categories[$i]->idCategory,
				    'subcategoryName' => $categories[$i]->categoryName,
				    'idParentsubcategory' => $categories[$i]->idParentCategory,
				    'subCategories' => array()
				];
			
				$tempCategory = (object) [
					'idCategory' => $categories[$i]->idCategory,
				    'categoryName' => $categories[$i]->categoryName,
				    'idParentcategory' => $categories[$i]->idParentCategory,
				    'subCategories' => array()
				];
		
				// Trovo il primo nodo della categoria
				do {
					for ($k = 0; $k < count($categories); $k++) {
						if ($categories[$k]->idCategory == $tempCategory->idParentcategory) {
							$tempCategory->idCategory = $categories[$k]->idCategory;
							$tempCategory->categoryName = $categories[$k]->categoryName;
							$tempCategory->idParentcategory = $categories[$k]->idParentCategory;
						}
					}
				} while ($tempCategory->idParentcategory != null || $tempCategory->idParentcategory != '' );
				
				// Controllo se la categoria in esame è già presente nell'array finale
				$isPresent = false;
				for ($l = 0; $l < count($navCategories); $l++) {
					if ($navCategories[$l]->idCategory == $tempCategory->idCategory) {
						$isPresent = true;
						// Controllo se è il secondo nodo
						if ($navCategories[$l]->idCategory == $tempSubcategory->idParentsubcategory) {
							array_push($navCategories[$l]->subCategories, $tempSubcategory);
						}
					}
				}
				// la categoria in esame non è presente nell'array finale
				if ($isPresent == false) {
					array_push($navCategories, $tempCategory);
				}
			}
			
			for ($j = 0; $j < count($navCategories); $j++) {
				for ($i = 0; $i < count($navCategories[$j]->subCategories); $i++) {
					$childCategories = $this->getChild_categories($categories, $navCategories[$j]->subCategories[$i]->idSubcategory);
					$navCategories[$j]->subCategories[$i]->subCategories = $childCategories;
				}
			}
		}
		
		return $navCategories;
		// END CATEGORIES TREE
	}
	public function getChild_categories($categories, $idC) {
		$subCategories = array();
		
		if ($categories) {
			for ($i = 0; $i < count($categories); $i++) {
					
				$currentCategory = (object) [
					'idCategory' => $categories[$i]->idCategory,
				    'categoryName' => $categories[$i]->categoryName,
				    'idParentcategory' => $categories[$i]->idParentCategory
				];
			
				$tempCategory = (object) [
					'idCategory' => $categories[$i]->idCategory,
				    'categoryName' => $categories[$i]->categoryName,
				    'idParentcategory' => $categories[$i]->idParentCategory
				];
		
				do {
					for ($k = 0; $k < count($categories); $k++) {
						if ($categories[$k]->idCategory == $tempCategory->idParentcategory) {
							$tempCategory->idCategory = $categories[$k]->idCategory;
							$tempCategory->categoryName = $categories[$k]->categoryName;
							$tempCategory->idParentcategory = $categories[$k]->idParentCategory;
						}
						if ($categories[$k]->idParentCategory == $idC) {
							$isPresent = false;
							for ($j = 0; $j < count($subCategories); $j++) {
								if ($subCategories[$j]->idCategory == $categories[$k]->idCategory) {
									$isPresent = true;
									break;
								}
							}
							if ($isPresent == false) {
								array_push($subCategories, $categories[$k]);
							}
						}
					}
				} while ($tempCategory->idParentcategory != null || $tempCategory->idParentcategory != '' );
			
			}
		}
			
		return $subCategories;
	}
	public function nav_cart() {
		if($this->session->userdata('idClient')) {
			$products = $this->frontmodel->r_ORD_Cart_Products('', '', $this->session->userdata('idClient'), 1, 1, 1, $this->session->userdata('site_lang_code'));
		} else {
			$products = [];
			$cartProducts = $this->session->userdata('cartProducts');
			if ($cartProducts) {
				foreach ($cartProducts as $cartProduct) {
					$product = $this->frontmodel->r_PRD_Product($cartProduct->idProduct,'',1,$this->session->userdata('site_lang_code'));
					$completeProduct = (object) array_merge((array) $cartProduct, (array) $product);
					array_push($products, $completeProduct);
				}
			}
		}
		return $products;
	}
	public function products_orders($orders) {	
		$products = array();
		foreach ($orders as $order) {
			$tempProducts = $this->frontmodel->ra_ORD_Orders_Products('', 1, 1, 1, '', $order->idOrder, $this->session->userdata('site_lang_code'));
			if ( $tempProducts ) {
				$products = array_merge($products, $tempProducts);
			}
		}
		
		return $products;
	}
