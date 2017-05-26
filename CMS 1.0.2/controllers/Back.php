<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package     CodeIgniter
 * @author  	Ivan Porta
 * @copyright 	Copyright (c) 2014.
 * @license  	GLP
 * @since  		Version 1.0
 * @version  	1.0
 */

// ------------------------------------------------------------------------

class Back extends CI_Controller {
	// LOGIN CHECK
	function __construct() {
		parent::__construct();
		$query = $this->is_logged_in();
		if ($query == false) {
			$this->session->set_flashdata('message','Per accedere al Pannello Admin devi prima eseguire il login!');
			redirect('front/console');
		} else {
			$this->load->model('backmodel');
			$data = array(
				'lastUpdate' => date("Y-m-d H:i:s")
			);
			$this->backmodel->u_LOG_Access($this->session->userdata('idAccess'),$data);
		}
	}
	function is_logged_in() {
		$is_logged_in = $this->session->userdata('is_logged_in');
		
		if (!isset($is_logged_in) || $is_logged_in != true) {
			return false;
		} else {
			return true;
		}
	}
	function logout() {
		$data = array('accessEmail', 'is_logged_in');
		$this->session->unset_userdata($data);
		
		$this->session->set_flashdata('message','Ciao, a presto!');
		redirect('front/console');
	}
	// PRESENTATION
	public function index() {
		$data['page'] = 'index';
		
		$this->load->view('header',$data);
		$this->load->view('nav');
		
		$data['last_products'] = $this->backmodel->ra_PRD_Products(5,'','','','','','','it');
		$data['products'] = $this->backmodel->ra_PRD_Products('','','','','',1,1,'it');
		$clients = $this->backmodel->ra_ORD_Clients('','','');
		$data['clients'] = $clients;
		$orders =  $this->backmodel->ra_ORD_Orders('', '', '','');
		$data['orders'] = $orders;
		$data['articles'] = $this->backmodel->ra_STN_Articles('', '', '', '', '', '');
		
		// START: Popolo l'array con le combinazioni
		$_tempValues = array();
		$_tempFeatures = array();
		// END Popolo l'array con le combinazioni
		
		// START: Popolo gli array con i dati dei clienti e degli ordini per i Charts
		$year = date("Y");
		$clientChart = array();
		$ordersChart = array();
		$labelChart = array();
		for ($i = 1; $i <= 12; $i++) {
			$nClient = 0;
			$nOrder = 0;
			for ($k = 0; $k < count($clients); $k++) {
				if ( (date("Y",strtotime($clients[$k]->createdOn)) == $year) && (date("m",strtotime($clients[$k]->createdOn)) == $i) ) {
					$nClient ++;
				}
			}
			for ($k = 0; $k < count($orders); $k++) {
				if ( (date("Y",strtotime($orders[$k]->createdOn)) == $year) && (date("m",strtotime($orders[$k]->createdOn)) == $i) ){
					$nOrder ++;
				}
			}
			array_push($labelChart, date('F', mktime(0, 0, 0, $i, 10)));
			array_push($clientChart, $nClient);
			array_push($ordersChart, $nOrder);
		}
		$data['clients_chart'] = $clientChart;
		$data['orders_chart'] = $ordersChart;
		$data['label_chart'] = $labelChart;
		// END: Popolo gli array con i dati dei clienti e degli ordini per i Charts
		
		$this->load->view('home',$data);
		$this->load->view('footer',$data);
	}
	public function product() {
		$data['page'] = 'product';
		$this->load->view('header',$data);
		$this->load->view('nav');
		// Leggo le lingue disponibili e ci aggiungo l'italiano	
		$data['languages'] = $this->get_languages();
		
		$data['settings'] = $this->backmodel->r_STN_Settings();
		$data['categories'] = $this->backmodel->ra_PRD_Categories('','it');
		$data['manufacturers'] = $this->backmodel->ra_PRD_Manufacturers('','','','');
		$data['tax'] = $this->backmodel->ra_STN_Tax('');
		
		$data['attributes'] = $this->backmodel->ra_PRD_Features('','0','it');
		$data['features'] = $this->backmodel->ra_PRD_Features('','1','it');
		$data['values'] = $this->backmodel->ra_PRD_Values('','it');
		
		$data['photos'] = $this->backmodel->ra_PRD_Photos('', $this->uri->segment(3), '');
		$data['product'] = $this->backmodel->r_PRD_Product($this->uri->segment(3),'','');
		$data['product_translations'] = $this->backmodel->ra_LANG_Products($this->uri->segment(3),'');
		
		$PRD_Combinations = $this->backmodel->r_PRD_Combinations('', $data['product']->idProduct);
		
		$feature_combinations = array();
		$combinations = array();
		for ($i = 0; $i < count($PRD_Combinations); $i++) {
			$combination = array();
			$groups = $this->backmodel->ra_PRD_Groups('',$PRD_Combinations[$i]->idCombination,'','',1,1,'it');
			for ($k = 0; $k < count($groups); $k++) {
				$object = (object) [
				    'idValue' => $groups[$k]->idValue,
				    'valueName' => $groups[$k]->valueName,
				    'idFeature' => $groups[$k]->idFeature,
				    'featureName' => $groups[$k]->featureName
				];
				array_push($combination,$object);
			}
			if($PRD_Combinations[$i]->combinationQuantity != NULL) {
				$object = (object) [
					'idCombination' => $PRD_Combinations[$i]->idCombination,
				    'featureName' => "Quantità",
				    'valueName' => $PRD_Combinations[$i]->combinationQuantity
				];
				array_push($combination,$object);
				array_push($combinations,$combination);
			} else {
				$object = (object) [
					'idCombination' => $PRD_Combinations[$i]->idCombination,
				    'featureName' => null,
				    'valueName' => null
				];
				array_push($combination,$object);
				array_push($feature_combinations,$combination);
			}
		}
		// ORDINAMENTO DEI DATI DELLE COMBINAZIONI IN UN FORMATO UGUALE A QUELLO USATO NELLE TEMPCOMBINATIONS
		$_tempCombinations = array();
		for ($i = 0; $i < count($PRD_Combinations); $i++) {
			if($PRD_Combinations[$i]->combinationQuantity != NULL) {
				$groups = $this->backmodel->ra_PRD_Groups('',$PRD_Combinations[$i]->idCombination,'','',1,1,'it');
				$isPresent = false;
				for ($k = 0; $k < count($groups); $k++) {
					for ($l = 0; $l < count($_tempCombinations); $l++) {
						if($_tempCombinations[$l]->idFeature == $groups[$k]->idFeature){
							$isPresent = true;
							break;
						}
					}
					if($isPresent == false){
						$object = (object) [
						    'featureName' => $groups[$k]->featureName,
						    'idFeature' => $groups[$k]->idFeature,
						    'values' => array()
						];
						array_push($_tempCombinations, $object);
					}
				}
			}
		}
		for ($i = 0; $i < count($combinations); $i++) {
			for ($j = 0; $j < count($combinations[$i])-1; $j++) {
				for ($k = 0; $k < count($_tempCombinations); $k++) {
					if($_tempCombinations[$k]->idFeature == $combinations[$i][$j]->idFeature){
						$isPresent = false;
						for ($l = 0; $l < count($_tempCombinations[$k]->values); $l++) {
							if($_tempCombinations[$k]->values[$l]->idValue == $combinations[$i][$j]->idValue){
								$isPresent = true;
							}
						}
						if($isPresent == false){
							$object = (object) [
							    'featureName' => $combinations[$i][$j]->featureName,
							    'idValue' => $combinations[$i][$j]->idValue,
							    'valueName' => $combinations[$i][$j]->valueName
							];
							array_push($_tempCombinations[$k]->values, $object);
						}
					}
				}
			}
		}
		$data['_tempCombinations'] = $_tempCombinations;
		$data['feature_combinations'] = $feature_combinations;
		$data['combinations'] = $combinations;
		
		$this->load->view('pages/product',$data);
		$this->load->view('footer',$data);
	}
	public function products() {
		$data['page'] = 'products';		
		$this->load->view('header',$data);
		$this->load->view('nav');
		// Leggo le lingue disponibili e ci aggiungo l'italiano	
		$data['languages'] = $this->get_languages();
		// Leggo le categorie ed i produttori
		$data['categories'] = $this->backmodel->ra_PRD_Categories('','it');
		$data['manufacturers'] = $this->backmodel->ra_PRD_Manufacturers('','','','');
		// Leggo tutti i prodotti
		$products = $this->backmodel->ra_PRD_Products('','','','','','','','');	
		// Configuro la paginazione
		$this->load->library('pagination');
		$config['base_url'] = site_url('back/products');
		$config['total_rows'] = count($products);
		$config['per_page'] = 20;
		$config['num_links'] = 3;
		$config['use_page_numbers'] = True;
		$config['num_tag_open'] = '<li>';		
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li><a href="#" class="active">';
		$config['cur_tag_close'] = '</a></li>';
		$config['next_tag_open'] = '<li>';
		$config['next_link'] = '<i class="fa fa-caret-right"></i>';
		$config['next_tag_close'] = '</i></li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_link'] = '<i class="fa fa-caret-left"></i>';
		$config['prev_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['first_link'] = 'Primi';
		$config['last_link'] = 'Ultimi';
		$this->pagination->initialize($config);
		$data["pagination"] = $this->pagination->create_links();
		// Imposto la paginazione
		$page = $this->uri->segment(3);
		if ($page >= 1) {		
			$data['products'] = $this->backmodel->ra_PRD_Products($config['per_page'],($page-1)*$config['per_page'],'','','',1,1,'it');
		} else {
			$data['products'] = $this->backmodel->ra_PRD_Products($config['per_page'],0,'','','',1,1,'it');
		}
		$averagePrice = 0;
		if($products) {
			foreach ($products as $product) {
				$averagePrice += $product->productPrice;
			} 
			$averagePrice = round($averagePrice/ count($products),2);
		}
		$data['average_product_price'] = number_format((float)$averagePrice,2,'.','');
		$data['major_payment_method'] = 'Paypal';
		$data['total_products'] = count($products);
		$this->load->view('pages/products',$data);
		$this->load->view('footer',$data);
	}	
	public function n_product() {
		$data['page'] = 'n_product';
		
		$this->load->view('header',$data);
		$this->load->view('nav');
		
		$settings = $this->backmodel->r_STN_Settings();
		$data['languages'] = array_filter(explode( ',', $settings->shopLanguages ));
		array_unshift($data['languages'], "it");
		
		$data['settings'] = $this->backmodel->r_STN_Settings();
		$data['tax'] = $this->backmodel->ra_STN_Tax('');
		$data['categories'] = $this->backmodel->ra_PRD_Categories('','it');
		$data['manufacturers'] = $this->backmodel->ra_PRD_Manufacturers('','','','');		
		$data['features'] = $this->backmodel->ra_PRD_Features('',1,'it');
		$data['attributes'] = $this->backmodel->ra_PRD_Features('','0','it');
		
		$this->load->view('pages/n_product',$data);
		$this->load->view('footer',$data);
	}	
	public function features() {
		$data['page'] = 'features';
		
		$this->load->view('header',$data);
		$this->load->view('nav');
		
		$settings = $this->backmodel->r_STN_Settings();
		$data['languages'] = array_filter(explode( ',', $settings->shopLanguages ));
		array_unshift($data['languages'], "it");
		
		$data['features_translations'] = $this->backmodel->ra_LANG_Features('','','');
		$data['values_translations'] = $this->backmodel->ra_LANG_Values('','','','');
		
		$data['features'] = $this->backmodel->ra_PRD_Features('','1','it');
		$data['values'] = $this->backmodel->ra_PRD_Values('','it');
	
		$this->load->view('pages/features',$data);
		$this->load->view('footer',$data);
	}
	public function attributes() {
		$data['page'] = 'attributes';
		
		$this->load->view('header',$data);
		$this->load->view('nav');
		
		$settings = $this->backmodel->r_STN_Settings();
		$data['languages'] = array_filter(explode( ',', $settings->shopLanguages ));
		array_unshift($data['languages'], "it");
		
		$data['attributes_translations'] = $this->backmodel->ra_LANG_Features('','','');
		$data['values_translations'] = $this->backmodel->ra_LANG_Values('','','','');
		
		$data['attributes'] = $this->backmodel->ra_PRD_Features('','0','it');
		$data['values'] = $this->backmodel->ra_PRD_Values('','it');
		
		$this->load->view('pages/attributes',$data);
		$this->load->view('footer',$data);
	}
	public function sales() {
		$data['page'] = 'sales';
		$this->load->view('header',$data);
		$this->load->view('nav');
		
		// Leggo tutti gli sconti
		$sales = $this->backmodel->ra_PRD_Sales('','','','','','','', '', 0, 1);
	
		// Configuro la paginazione
		$this->load->library('pagination');
		$config['base_url'] = site_url('back/sales');
		$config['total_rows'] = count($sales);
		$config['per_page'] = 10;
		$config['num_links'] = 3;
		$config['use_page_numbers'] = True;
		$config['num_tag_open'] = '<li>';		
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li><a href="#" class="active">';
		$config['cur_tag_close'] = '</a></li>';
		$config['next_tag_open'] = '<li>';
		$config['next_link'] = '<i class="fa fa-caret-right"></i>';
		$config['next_tag_close'] = '</i></li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_link'] = '<i class="fa fa-caret-left"></i>';
		$config['prev_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['first_link'] = 'Primi';
		$config['last_link'] = 'Ultimi';
		$this->pagination->initialize($config);
		$data["pagination"] = $this->pagination->create_links();
		// Imposto la paginazione
		$page = $this->uri->segment(3);
		if ($page >= 1) {		
			$data['sales'] = $this->backmodel->ra_PRD_Sales($config['per_page'], ($page-1)*$config['per_page'],'','','','','','',0,1);
		} else {
			$data['sales'] = $this->backmodel->ra_PRD_Sales($config['per_page'],0,'','','','','','',0,1);
		}
		
		$averageSales = 0;
		if ($data['sales']) {
			$averageArticles = count($data['sales'])/count($data['sales']);
		}
		$data['average_sales'] = $averageSales;
		$this->load->view('pages/sales',$data);
		$this->load->view('footer',$data);
	}
	public function n_sale() {
		$data['page'] = 'n_sale';
		
		$this->load->view('header',$data);
		$this->load->view('nav');
		
		$this->load->view('pages/n_sale',$data);
		$this->load->view('footer',$data);
	}	
	public function categories() {
		$data['page'] = 'categories';
		$this->load->view('header',$data);
		$this->load->view('nav');
		// Leggo le lingue disponibili e ci aggiungo l'italiano	
		$data['languages'] = $this->get_languages();
		// Leggo anche i prodotti per effettuare una media dei prodotti per categoria
		$products = $this->backmodel->ra_PRD_Products('','','','','','','','it');
		$categories = $this->backmodel->ra_PRD_Categories('','it');
		$data['categories'] = $categories;
		
		// START: Calcolo delle statistiche riguardo le categorie 
		$subCategories = 0;
		$averageProducts = 0;
		for ($i = 0; $i < count($categories); $i++) {
			if ($categories[$i]->idParentCategory != null) {
				$subCategories++;
			}
		}
		$data['average_products'] = $averageProducts;
		$data['total_subcategories'] = $subCategories;
		// END: Calcolo delle statistiche riguardo le categorie 
		
		$this->load->view('pages/categories',$data);
		$this->load->view('footer',$data);
	}
	public function manufacturers() {
		$data['page'] = 'manufacturers';
		$this->load->view('header',$data);
		$this->load->view('nav');
		
		// Leggo tutti gli sconti
		$manufacturers = $this->backmodel->ra_PRD_Manufacturers('','','','');
	
		// Configuro la paginazione
		$this->load->library('pagination');
		$config['base_url'] = site_url('back/manufacturers');
		$config['total_rows'] = count($manufacturers);
		$config['per_page'] = 10;
		$config['num_links'] = 3;
		$config['use_page_numbers'] = True;
		$config['num_tag_open'] = '<li>';		
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li><a href="#" class="active">';
		$config['cur_tag_close'] = '</a></li>';
		$config['next_tag_open'] = '<li>';
		$config['next_link'] = '<i class="fa fa-caret-right"></i>';
		$config['next_tag_close'] = '</i></li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_link'] = '<i class="fa fa-caret-left"></i>';
		$config['prev_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['first_link'] = 'Primi';
		$config['last_link'] = 'Ultimi';
		$this->pagination->initialize($config);
		$data["pagination"] = $this->pagination->create_links();
		// Imposto la paginazione
		$page = $this->uri->segment(3);
		if ($page >= 1) {		
			$data['manufacturers'] = $this->backmodel->ra_PRD_Manufacturers($config['per_page'], ($page-1)*$config['per_page'],'','');
		} else {
			$data['manufacturers'] = $this->backmodel->ra_PRD_Manufacturers($config['per_page'],0,'','');
		}

		$this->load->view('pages/manufacturers',$data);
		$this->load->view('footer',$data);
	}
	public function tax() {
		$data['page'] = 'tax';
		$this->load->view('header',$data);
		$this->load->view('nav');
		// Leggo le lingue disponibili e ci aggiungo l'italiano	
		$data['languages'] = $this->get_languages();
		$data['tax'] = $this->backmodel->ra_STN_Tax('');
		
		$this->load->view('pages/tax',$data);
		$this->load->view('footer',$data);
	}
	public function orders() {
		$data['page'] = 'orders';
		$this->load->view('header',$data);
		$this->load->view('nav');
		// Leggo tutti gli ordini
		$orders = $this->backmodel->ra_ORD_Orders('','',1,'');
		// Configurazione paginatione
		$this->load->library('pagination');
		$config['base_url'] = site_url('back/orders');
		$config['total_rows'] = count($orders);
		$config['per_page'] = 20;
		$config['num_links'] = 3;
		$config['use_page_numbers'] = True;
		$config['num_tag_open'] = '<li>';		
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li><a href="#" class="active">';
		$config['cur_tag_close'] = '</a></li>';
		$config['next_tag_open'] = '<li>';
		$config['next_link'] = '<i class="fa fa-caret-right"></i>';
		$config['next_tag_close'] = '</i></li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_link'] = '<i class="fa fa-caret-left"></i>';
		$config['prev_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['first_link'] = 'Primi';
		$config['last_link'] = 'Ultimi';
		$this->pagination->initialize($config);
		$data["pagination"] = $this->pagination->create_links();
		// Impostazione paginazione
		$page = $this->uri->segment(3);
		if ($page >= 1) {
			$data['orders'] = $this->backmodel->ra_ORD_Orders($config['per_page'],($page-1)*$config['per_page'],1,'');
		} else {
			$data['orders'] = $this->backmodel->ra_ORD_Orders($config['per_page'],0,1,'');
		}
		
		$averageAmount = 0;
		$majorOrder = 0;	
		$majorPaymentMethod = 'Paypal';
		if ($orders) {
			foreach ($orders as $order) {
				$averageAmount += $order->orderAmount;
				if ($order->orderAmount > $majorOrder) {
					$majorOrder = $order->orderAmount;
				}
			}
			$averageAmount = $averageAmount/count($orders);
		}
		$data['average_order_amount'] = number_format((float)$averageAmount,2,'.','');
		$data['major_order'] = number_format((float)$majorOrder,2,'.','');
		$data['major_payment_method'] = 'Paypal';
		$data['total_orders'] = count($orders);
		$this->load->view('pages/orders',$data);
		$this->load->view('footer',$data);
	}
	public function clients() {
		$data['page'] = 'clients';
		$this->load->view('header',$data);
		$this->load->view('nav');
		// Leggo tutti i prodotti
		$clients = $this->backmodel->ra_ORD_Clients('','',1);
		// Configuro la paginazione
		$this->load->library('pagination');
		$config['base_url'] = site_url('back/clients');
		$config['total_rows'] = count($clients);
		$config['per_page'] = 20;
		$config['num_links'] = 3;
		$config['use_page_numbers'] = True;
		$config['num_tag_open'] = '<li>';		
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li><a href="#" class="active">';
		$config['cur_tag_close'] = '</a></li>';
		$config['next_tag_open'] = '<li>';
		$config['next_link'] = '<i class="fa fa-caret-right"></i>';
		$config['next_tag_close'] = '</i></li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_link'] = '<i class="fa fa-caret-left"></i>';
		$config['prev_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['first_link'] = 'Primi';
		$config['last_link'] = 'Ultimi';
		$this->pagination->initialize($config);
		$data["pagination"] = $this->pagination->create_links();
		// Imposto la paginazione
		$page = $this->uri->segment(3);
		if ($page >= 1) 
		{		
			$data['clients'] = $this->backmodel->ra_ORD_Clients($config['per_page'],($page-1)*$config['per_page'],1);
		} else {
			$data['clients'] = $this->backmodel->ra_ORD_Clients($config['per_page'],0,1);
		}
		$data['total_clients'] = count($clients);
		$this->load->view('pages/clients',$data);
		$this->load->view('footer',$data);
	}
	public function slides() {
		$data['page'] = 'slides';
		$this->load->view('header',$data);
		$this->load->view('nav');
		// Leggo le lingue disponibili e ci aggiungo l'italiano	
		$data['languages'] = $this->get_languages();
		$data['slides'] = $this->backmodel->ra_STN_Slides('','it');
		$this->load->view('pages/slides',$data);
		$this->load->view('footer',$data);
	}
	public function n_slide() {
		$data['page'] = 'n_slide';
		$this->load->view('header',$data);
		$this->load->view('nav');
		
		$this->load->view('pages/n_slide',$data);
		$this->load->view('footer',$data);
	}
	public function banners() {
		$data['page'] = 'banners';
		$this->load->view('header',$data);
		$this->load->view('nav');
		
		$settings = $this->backmodel->r_STN_Settings();
		$data['languages'] = array_filter(explode( ',', $settings->shopLanguages ));
		array_unshift($data['languages'], "it");
		
		$data['banners'] = $this->backmodel->ra_STN_Banners('','it');
		
		$this->load->view('pages/banners',$data);
		$this->load->view('footer',$data);
	}
	public function articles_categories() {
		$data['page'] = 'article_categories';
		$this->load->view('header',$data);
		$this->load->view('nav');
		// Leggo le lingue disponibili e ci aggiungo l'italiano	
		$data['languages'] = $this->get_languages();
		$data['articles'] = $this->backmodel->ra_STN_Articles('','','','','','it');
		$data['categories'] = $this->backmodel->ra_STN_Articles_Categories('','','it');
		
		$averageArticles = 0;
		if ($data['articles'] && $data['categories']) {
			$averageArticles = count($data['articles'])/count($data['categories']);
		}
		$data['average_articles'] = $averageArticles;
		$this->load->view('pages/articles_categories',$data);
		$this->load->view('footer',$data);
	}
	public function n_article() {
		$data['page'] = 'n_article';
		$this->load->view('header',$data);
		$this->load->view('nav');
		
		$data['categories'] = $this->backmodel->ra_LANG_Articles_Categories('','','it');
		
		$this->load->view('pages/n_article',$data);
		$this->load->view('footer',$data);
	}
	public function articles() {
		$data['page'] = 'articles';
		$this->load->view('header',$data);
		$this->load->view('nav');
		// Leggo le lingue disponibili e ci aggiungo l'italiano	
		$data['languages'] = $this->get_languages();
		// Leggo le immagini e le categorie
		$data['photos'] = $this->backmodel->ra_STN_Photos('',$this->uri->segment(3));
		$data['categories'] = $this->backmodel->ra_LANG_Articles_Categories('','','it');
		// Leggo tutti gli articoli
		$articles = $this->backmodel->ra_STN_Articles('','','','','','it');
		// Configuro la paginazione
		$this->load->library('pagination');
		$config['base_url'] = site_url('back/articles');
		$config['total_rows'] = count($articles);
		$config['per_page'] = 20;
		$config['num_links'] = 3;
		$config['use_page_numbers'] = True;
		$config['num_tag_open'] = '<li>';		
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li><a href="#" class="active">';
		$config['cur_tag_close'] = '</a></li>';
		$config['next_tag_open'] = '<li>';
		$config['next_link'] = '<i class="fa fa-caret-right"></i>';
		$config['next_tag_close'] = '</i></li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_link'] = '<i class="fa fa-caret-left"></i>';
		$config['prev_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['first_link'] = 'Primi';
		$config['last_link'] = 'Ultimi';
		$this->pagination->initialize($config);
		$data["pagination"] = $this->pagination->create_links();
		// Imposto la paginazione
		$page = $this->uri->segment(3);
		if ($page >= 1) {		
			$data['articles'] = $this->backmodel->ra_STN_Articles($config['per_page'],($page-1)*$config['per_page'],'',1,'','it');
		} else {
			$data['articles'] = $this->backmodel->ra_STN_Articles($config['per_page'],0,'',1,'','it');
		}
		$data['total_articles'] = count($articles);
		$this->load->view('pages/articles',$data);
		$this->load->view('footer',$data);
	}
	public function n_page() {
		$data['page'] = 'n_page';
		$this->load->view('header',$data);
		$this->load->view('nav');
		
		$this->load->view('pages/n_page',$data);
		$this->load->view('footer',$data);
	}
	public function pages() {
		$data['page'] = 'pages';
		$this->load->view('header',$data);
		$this->load->view('nav');
		
		$settings = $this->backmodel->r_STN_Settings();
		$data['languages'] = array_filter(explode( ',', $settings->shopLanguages ));
		array_unshift($data['languages'], "it");
		
		$data['pages'] = $this->backmodel->ra_STN_Pages('','','');
		
		//Pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url('back/pages');
		$config['total_rows'] = count($data['pages'] );
		$config['per_page'] = 20;
		$config['num_links'] = 3;
		$config['use_page_numbers'] = True;
		$config['num_tag_open'] = '<li>';		
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li><a href="#" class="active">';
		$config['cur_tag_close'] = '</a></li>';
		$config['next_tag_open'] = '<li>';
		$config['next_link'] = '<i class="fa fa-caret-right"></i>';
		$config['next_tag_close'] = '</i></li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_link'] = '<i class="fa fa-caret-left"></i>';
		$config['prev_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['first_link'] = 'Primi';
		$config['last_link'] = 'Ultimi';
		$this->pagination->initialize($config);
		$data["pagination"] = $this->pagination->create_links();
		
		$page = $this->uri->segment(3);
		if ($page >= 1) 
		{		
			$data['pages'] = $this->backmodel->ra_STN_Pages($config['per_page'],($page-1)*$config['per_page'],'it');
		} else {
			$data['pages'] = $this->backmodel->ra_STN_Pages($config['per_page'],0,'it');
		}
		
		$this->load->view('pages/pages',$data);
		$this->load->view('footer',$data);
	}
	public function couriers() {
		$data['page'] = 'couriers';
		
		$this->load->view('header',$data);
		$this->load->view('nav');
		
		$data['couriers'] = $this->backmodel->ra_ORD_Couriers('',1);
		$data['countries'] = $this->backmodel->ra_STN_Countries('');
		
		$this->load->view('pages/couriers',$data);
		$this->load->view('footer',$data);
	}	
	public function n_client() {
		$data['page'] = 'n_client';
		
		$this->load->view('header',$data);
		$this->load->view('nav');
		
		$data['countries'] = $this->backmodel->ra_STN_Countries('');
		
		$this->load->view('pages/n_client',$data);
		$this->load->view('footer',$data);
	}
	public function countries() {
		$data['page'] = 'countries';
		
		$this->load->view('header',$data);
		$this->load->view('nav');
		
		$data['countries'] = $this->backmodel->ra_STN_Countries('');
		
		$this->load->view('pages/countries',$data);
		$this->load->view('footer',$data);
	}	
	public function currencies() {
		$data['page'] = 'currencies';
		
		$this->load->view('header',$data);
		$this->load->view('nav');
		
		$data['currencies'] = $this->backmodel->ra_STN_Currencies('');
		
		$this->load->view('pages/currencies',$data);
		$this->load->view('footer',$data);
	}
	public function export() {
		$data['page'] = 'export';
		
		$this->load->view('header',$data);
		$this->load->view('nav');
		
		$this->load->view('pages/export',$data);
		$this->load->view('footer',$data);
	}
	public function settings() {
		$data['page'] = 'settings';
		
		$this->load->view('header',$data);
		$this->load->view('nav');
		
		$data['settings'] = $this->backmodel->r_STN_Settings();
		
		$this->load->view('pages/settings',$data);
		$this->load->view('footer',$data);
	}
	public function ebay_settings() {
		$data['page'] = 'ebay_settings';
		$this->load->view('header',$data);
		$this->load->view('nav');
		
		$data['categories'] = $this->backmodel->ra_PRD_Categories('','it');
		$data['countries'] = $this->backmodel->ra_STN_Countries('');
		$data['settings'] = $this->backmodel->r_STN_Ebay();
		$data['sites'] = $this->backmodel->ra_STN_Ebay_SiteId();
		
		$data['ebaycategories'] = $this->getCategories();
		
		$this->load->view('pages/ebay_settings',$data);
		$this->load->view('footer',$data);
	}
	public function ebay_synchronization() {
		$data['page'] = 'ebay_synchronization';
		$this->load->view('header',$data);
		$this->load->view('nav');

		$data['categories'] = $this->backmodel->ra_PRD_Categories('','it');
		$data['countries'] = $this->backmodel->ra_STN_Countries('');
		$data['settings'] = $this->backmodel->r_STN_Ebay();
		
		$data['ebaycategories'] = $this->getCategories();
		
		$this->load->view('pages/ebay_synchronization',$data);
		$this->load->view('footer',$data);
	}	
	// ACTIONS	
	// LOG_Access
	public function ra_LOG_Access() {  
		// Per motivi di sicurezza ritorno solo l'id ed il nome dell'utente online (da completare)
		$access = $this->backmodel->ra_LOG_Access($this->session->userdata('idAccess'));
	    echo json_encode($access);
	}
	// PRD_Products
	public function c_PRD_Product() {
		//Form Validation
		$this->load->library('form_validation');
		$this->form_validation->set_rules('productName', 'Nome prodotto', 'trim|required');
		$this->form_validation->set_rules('productDescription', 'Descrizione prodotto', 'trim|required');
		$this->form_validation->set_rules('idManufacturer', 'Produttore', 'trim|required');
		$this->form_validation->set_rules('idTax', 'Tassazione', 'trim|required');
		$this->form_validation->set_rules('productPrice', 'Prezzo del prodotto', 'trim|required');
		$this->form_validation->set_rules('productLenght', 'Larghezza imballaggio', 'trim|required');
		$this->form_validation->set_rules('productWidth', 'Lunghezza imballaggio', 'trim|required');
		$this->form_validation->set_rules('productHeight', 'Altezza imballaggio', 'trim|required');
		$this->form_validation->set_rules('productWeight', 'Peso del prodotto', 'trim|required');
		$this->form_validation->set_message('required', 'Riempi il campo %s');
		//End Form Validation
	
		if ($this->form_validation->run() == FALSE) {
			$message = 'Errore 001INT - Salvataggio Incompleto';
		} else {	
			try{
				if(!$this->input->post('isEcommerce')) {
					$isEcommerce = 0;
				} else {
					$isEcommerce = $this->input->post('isEcommerce');
				}
				if(!$this->input->post('isMarketplace')) {
					$isMarketplace = 0;
				} else {
					$isMarketplace = $this->input->post('isMarketplace');
				}
				$data = array(
					'idManufacturer' => $this->input->post('idManufacturer'),
					'idCategory' => $this->input->post('idCategory'),
					'productEAN' => $this->input->post('productEAN'),
					'productSKU' => $this->input->post('productSKU'),
					'productPrice' => $this->input->post('productPrice'),
					'productLenght' => $this->input->post('productLenght'),
					'productWidth' => $this->input->post('productWidth'),
					'productHeight' => $this->input->post('productHeight'),
					'productWeight' => $this->input->post('productWeight'),
					'isEcommerce' => $isEcommerce,
					'isMarketplace' => $isMarketplace,
					'createdOn' => date("Y-m-d")
				);
				$idProduct = $this->backmodel->c_PRD_Product($data);
			}catch (exception $exception) {
				$response = (object)array(
					'error' => 'Errore 002INT - Salvataggio Incompleto',
					'message' => null,
					'idProduct' => null
				);
				header('Content-Type: application/x-json; charset=utf-8');
				echo json_encode($response);
				exit();
			}
			
			try{
				$data = array(
					'idProduct' => $idProduct,
					'photoName' => 'default.jpg',
					'isCover' => 1,
					'createdOn' => date("Y-m-d")
				);
				$this->backmodel->c_PRD_Photo($data);
			}catch (exception $exception) {
				$response = (object)array(
					'error' => 'Errore 003INT - Salvataggio Incompleto',
					'message' => null,
					'idProduct' => null
				);
				header('Content-Type: application/x-json; charset=utf-8');
				echo json_encode($response);
				exit();
			}
			
			$settings = $this->backmodel->r_STN_Settings();
			$languages = array_filter(explode( ',', $settings->shopLanguages ));
			
			$UT_translator = $this->load->library('utilities/UT_translator',NULL,'UT_translator');
			$config['client_id'] = '[CLIENT_ID]';
			$config['client_secret'] = '[CLIENT_SECRET]';
			$this->UT_translator->initialize($config);
			
			try{
				$this->UT_translator->setTokens();
				for ($i = 0; $i < count($languages); $i++) {				
					$data = array(
						'idProduct' => $idProduct,
						'language' => $languages[$i],
						'productName' => $this->UT_translator->curlRequest($languages[$i], $languages[$i], $this->input->post('productName')),
						'productDescription' => $this->UT_translator->curlRequest($languages[$i], $languages[$i], $this->input->post('productDescription'))
					);
					$this->backmodel->c_LANG_Product($data);
				}
				$data = array(
					'idProduct' => $idProduct,
					'language' => 'it',
					'productName' => $this->input->post('productName'),
					'productDescription' => $this->input->post('productDescription')
				);
				$this->backmodel->c_LANG_Product($data);
			}catch (exception $exception) {				
				$response = (object)array(
					'error' => 'Errore 004INT - Salvataggio Incompleto',
					'message' => null,
					'idProduct' => null
				);
				header('Content-Type: application/x-json; charset=utf-8');
				echo json_encode($response);
				exit();
			}
			
			$response = (object)array(
				'error' => null,
				'message' => 'Salvataggio Completato',
				'idProduct' => $idProduct
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
		}	
	} // CORRETTA
	public function r_PRD_Products() {
		try{
			$idProduct = $this->input->post('idProduct');
			$data = $this->backmodel->r_PRD_Product($idProduct,'','');
			$response = (object)array(
				'error' => null,
				'data' => $data
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
		}catch (exception $exception) {
			$response = (object)array(
				'error' => 'Errore 001INT - Caricamento Incompleto',
				'data' => null
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
		}
	} // CORRETTA
	public function ra_PRD_Products() {
		$page = $this->input->post('page');
		$products = $this->backmodel->ra_PRD_Products('','','','','','','','');	
		
		$this->load->library('pagination');
		
		$config['base_url'] = '';
		$config['total_rows'] = count($products);
		$config['per_page'] = 10;
		$config['num_links'] = 3;
		$config['use_page_numbers'] = True;
		$config['num_tag_open'] = '<li>';		
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li><a href="#" data-ci-pagination-page="1">';
		$config['cur_tag_close'] = '</a></li>';
		$config['next_tag_open'] = '<li>';
		$config['next_link'] = '<i class="fa fa-caret-right"></i>';
		$config['next_tag_close'] = '</i></li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_link'] = '<i class="fa fa-caret-left"></i>';
		$config['prev_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['first_link'] = 'Primi';
		$config['last_link'] = 'Ultimi';
		
		$this->pagination->initialize($config);
		$data["pagination"] = $this->pagination->create_links();
		
		// Imposto la paginazione
		if ($page >= 1) {		
			$data['products'] = $this->backmodel->ra_PRD_Products($config['per_page'],($page-1)*$config['per_page'],'','','',1,1,'it');
		} else {
			$data['products'] = $this->backmodel->ra_PRD_Products($config['per_page'],0,'','','',1,1,'it');
		}
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	public function u_PRD_Product() {
		//Form Validation
		$this->load->library('form_validation');
		$this->form_validation->set_rules('productName[]', 'Nome prodotto', 'trim|required');
		$this->form_validation->set_rules('productDescription[]', 'Descrizione prodotto', 'trim|required');
		$this->form_validation->set_rules('idManufacturer', 'Produttore', 'trim|required');
		$this->form_validation->set_rules('idTax', 'Tassazione', 'trim|required');
		$this->form_validation->set_rules('productPrice', 'Prezzo del prodotto', 'trim|required');
		$this->form_validation->set_rules('productLenght', 'Larghezza dell imballaggio', 'trim|required');
		$this->form_validation->set_rules('productWidth', 'Lunghezza dell imballaggio', 'trim|required');
		$this->form_validation->set_rules('productHeight', 'Altezza dell imballaggio', 'trim|required');
		$this->form_validation->set_rules('productWeight', 'Peso del prodotto', 'trim|required');
		$this->form_validation->set_message('required', 'Riempi il campo %s');
		//End Form Validation

		if ($this->form_validation->run() == FALSE) {
			$message = 'Errore 001INT - Salvataggio Incompleto';
		} else {	
			try{
				if (!$this->input->post('isEcommerce')) {
					$isEcommerce = 0;
				} else {
					$isEcommerce = $this->input->post('isEcommerce');
				}
				if (!$this->input->post('isMarketplace')) {
					$isMarketplace = 0;
				} else {
					$isMarketplace = $this->input->post('isMarketplace');
				}
				$data = array(
					'idManufacturer' => $this->input->post('idManufacturer'),
					'idCategory' => $this->input->post('idCategory'),
					'productEAN' => $this->input->post('productEAN'),
					'productSKU' => $this->input->post('productSKU'),
					'productPrice' => $this->input->post('productPrice'),
					'productLenght' => $this->input->post('productLenght'),
					'productWidth' => $this->input->post('productWidth'),
					'productHeight' => $this->input->post('productHeight'),
					'productWeight' => $this->input->post('productWeight'),
					'isEcommerce' => $isEcommerce,
					'isMarketplace' => $isMarketplace,
					'createdOn' => date("Y-m-d")
				);
				$this->backmodel->u_PRD_Product($this->input->post('idProduct'),$data);
			}catch (exception $exception) {
				$response = (object)array(
					'error' => 'Errore 002INT - Salvataggio Incompleto',
					'message' => null
				);
				header('Content-Type: application/x-json; charset=utf-8');
				echo json_encode($response);
				exit();
			}
			
			$languages = $this->input->post('languages');
			$productNames = $this->input->post('productName');
			$productDescriptions = $this->input->post('productDescription');
			
			try{
				for ($i = 0; $i < count($languages); $i++) {
					$data = array(
						'language' => $languages[$i],
						'productName' => $productNames[$i],
						'productDescription' => $productDescriptions[$i],
					);
					// Saving manufacturer to Database
					$this->backmodel->u_LANG_Product($this->input->post('idProduct'), $data, $languages[$i]);
				}
			}catch (exception $exception) {
				$response = (object)array(
					'error' => 'Errore 003INT - Salvataggio Incompleto',
					'message' => null
				);
				header('Content-Type: application/x-json; charset=utf-8');
				echo json_encode($response);
				exit();
			}
		}	
		
		$response = (object)array(
			'error' => null,
			'message' => 'Salvataggio Completato'
		);
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($response);
	} // CORRETTA
	public function d_PRD_Products() {
		$isAllDeleted = true;
		$idProducts = $this->input->post('idProduct');
		
		try{
			for ($i = 0; $i < count($idProducts); $i++) {
				$combinations = $this->backmodel->r_PRD_Combinations('', $idProducts[$i]);
				for ($k = 0; $k < count($combinations); $k++) {
					$carts = $this->backmodel->ra_ORD_Carts_Products('', '', $combinations[$k]->idCombination, '','');
					$orders = $this->backmodel->ra_ORD_Orders_Products('', '', '', $combinations[$k]->idCombination, '','','','');
					if ($orders || $carts) {
						unset($idProducts[$i]);
						$isAllDeleted = false;
					}
				}
			}
		} catch (exception $exception) {				
			$response = (object)array(
				'error' => 'Errore 001INT - Cancellazione Incompleta',
				'message' => null,
				'idProduct' => null
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
			exit();
		}
		
		try{
			for ($i = 0; $i < count($idProducts); $i++) {
				$photos = $this->backmodel->ra_PRD_Photos('',$idProducts[$i],'');
				for ($k = 0; $k < count($photos); $k++) {
					$img = $photos[$k];
					if($photos[$k]->photoName != 'default.jpg') {
						unlink('./resources/img/products/large/'.$photos[$k]->photoName);
						unlink('./resources/img/products/medium/'.$photos[$k]->photoName);
						unlink('./resources/img/products/small/'.$photos[$k]->photoName);
						unlink('./resources/img/products/extra_small/'.$photos[$k]->photoName);
						unlink('./resources/img/products/special_sale/medium/'.$photos[$k]->photoName);
						unlink('./resources/img/products/special_sale/small/'.$photos[$k]->photoName);
					}
					$this->backmodel->d_PRD_Photo($photos[$k]->idPhoto);
				}
				$this->backmodel->d_PRD_Product($idProducts[$i]); 
				$this->backmodel->d_LANG_Product('',$idProducts[$i],'');
				$combinations = $this->backmodel->r_PRD_Combinations('',$idProducts[$i]);
				for ($k = 0; $k < count($combinations); $k++) {
					$this->backmodel->d_PRD_Groups_byCombination($combinations[$k]->idCombination);
				}
				$this->backmodel->d_PRD_Combinations('', $idProducts[$i]);
			}
		} catch (exception $exception) {				
			$response = (object)array(
				'error' => 'Errore 002INT - Cancellazione Incompleta',
				'message' => null,
				'idProduct' => null
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
			exit();
		}
		
		if (!$isAllDeleted) {
			$response = (object)array(
				'error' => null,
				'message' => 'Canellazione Incompleta <br> Alcuni prodotti non sono stati cancellati in quanto presenti in uno o più carrelli/ordini <br>'
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
		} else {
			$response = (object)array(
				'error' => null,
				'message' => 'Canellazione Completata'
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
		}
	} // CORRETTA
	public function s_PRD_Products() {
		try{
			$column = $this->input->post('column-search');
			$value = $this->input->post('value-search');
			$data = $this->backmodel->s_PRD_Product($column, $value,'it');
			$response = (object)array(
				'error' => null,
				'data' => $data
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
		}catch (exception $exception) {
			$response = (object)array(
				'error' => 'Errore 001INT - Caricamento Incompleto',
				'data' => null
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
		}
	} // CORRETTA
	public function dp_PRD_Products() {
		$idProducts = $this->input->post('idProduct');
		$productNotCopied = false;
		
		foreach ($idProducts as $idProduct) {
			$originalProduct = $this->backmodel->r_PRD_Product($idProduct,'','');
			unset($originalProduct->idProduct);
			$originalProduct->createdOn = date('Y-m-d');
			$returnedID = $this->backmodel->c_PRD_Product($originalProduct);
		
			$combinations = $this->backmodel->r_PRD_Combinations('', $idProduct);
			if ($combinations) {
				foreach ($combinations as $combination) {
					unset($combination->idCombination);
					$this->backmodel->c_PRD_Combinations($combination);
				}
			}
			
			$photos = $this->backmodel->ra_PRD_Photos('', $idProduct, '');
			if ($photos) {
				foreach ($photos as $photo) {
					$oldPhoto = explode(".", $photo->photoName);
					
				    if (!copy('./resources/img/products/extra_small/'.$photo->photoName,'./resources/img/products/extra_small/'.$oldPhoto[0].'_c_'.$returnedID.'.'.$oldPhoto[1])) {
				    	$productNotCopied = true;
				    }
				    if (!copy('./resources/img/products/small/'.$photo->photoName,'./resources/img/products/small/'.$oldPhoto[0].'_c_'.$returnedID.'.'.$oldPhoto[1])) {
				    	$productNotCopied = true;
				    }
				    if (!copy('./resources/img/products/medium/'.$photo->photoName,'./resources/img/products/medium/'.$oldPhoto[0].'_c_'.$returnedID.'.'.$oldPhoto[1])) {
				    	$productNotCopied = true;
				    }
				    if (!copy('./resources/img/products/large/'.$photo->photoName,'./resources/img/products/large/'.$oldPhoto[0].'_c_'.$returnedID.'.'.$oldPhoto[1])) {
				    	$productNotCopied = true;
				    }
				    if (!copy('./resources/img/products/special_sale/small/'.$photo->photoName,'./resources/img/products/special_sale/small/'.$oldPhoto[0].'_c_'.$returnedID.'.'.$oldPhoto[1])) {
				    	$productNotCopied = true;
				    }
				    if (!copy('./resources/img/products/special_sale/medium/'.$photo->photoName,'./resources/img/products/special_sale/medium/'.$oldPhoto[0].'_c_'.$returnedID.'.'.$oldPhoto[1])) {
				    	$productNotCopied = true;
				    }
				
					unset($photo->idPhoto);
					$photo->photoName = $oldPhoto[0].'_c_'.$returnedID.'.'.$oldPhoto[1];
					$photo->idProduct = $returnedID;
					$this->backmodel->c_PRD_Photo($photo);
				}
			}
			
			$translations = $this->backmodel->ra_LANG_Products($idProduct,'');
			if ($translations) {
				foreach ($translations as $translation) {
					unset($translation->idLangProduct);
					$translation->productName = $translation->productName.'_Copia';
					$translation->idProduct = $returnedID;
					$this->backmodel->c_LANG_Product($translation);
				}	
			}
			
		}
	}
	// PRD_Manufacturer
	public function c_PRD_Manufacturer() {
		//Form Validation
		$this->load->library('form_validation');
		$this->form_validation->set_rules('manufacturerName', 'Nome produttore', 'trim|required');
		$this->form_validation->set_message('required', 'Riempi il campo %s');
		//End Form Validation
		
		if ($this->form_validation->run() == FALSE) {
			$response = (object)array(
				'error' => 'Errore 001INT - Salvataggio Incompleto',
				'message' => null
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
			exit();
		} else {
		
			try{
				if(!($_FILES['cover']['size'] == 0)){
				 	$config = array(
				 	    'upload_path' => $this->config->item('upload_path').'/img/manufacturers/',
				 	    'allowed_types' => 'jpg|png',
				 	);
				 	$this->load->library('upload', $config);
			 		if (!$this->upload->do_upload('cover')) {
			 			$response = (object)array(
			 				'error' => 'Errore 001INT - Salvataggio Incompleto',
			 				'message' => null
			 			);
			 			header('Content-Type: application/x-json; charset=utf-8');
			 			echo json_encode($response);
			 			exit();
			 		} else {	
			 			$file = $this->upload->data();
			 			$this->resizeImg(170,95,$file,$this->config->item('upload_path').'/img/manufacturers/'.$file['file_name'],'banner');
						$data = array(
							'manufacturerName' => $this->input->post('manufacturerName'),
							'manufacturerDescription' => $this->input->post('manufacturerDescription'),
							'photoName' => $file['file_name'],
						);
						$this->backmodel->c_PRD_Manufacturer($data);
					}
				} else {
					$data = array(
						'manufacturerName' => $this->input->post('manufacturerName'),
						'manufacturerDescription' => $this->input->post('manufacturerDescription'),
						'photoName' => NULL,
					);
					$this->backmodel->c_PRD_Manufacturer($data);
				}
			}catch (exception $exception) {
				$response = (object)array(
					'error' => 'Errore 003INT - Salvataggio Incompleto',
					'message' => null
				);
				header('Content-Type: application/x-json; charset=utf-8');
				echo json_encode($response);
				exit();
			}
			
			$response = (object)array(
				'error' => null,
				'message' => 'Salvataggio Completato'
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
		}
	} // CORRETTA
	public function r_PRD_Manufacturer() {
		$data = $this->backmodel->ra_PRD_Manufacturers('','',$this->input->post('idManufacturer'),'');
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	public function u_PRD_Manufacturer() {
		$message = 'Salvataggio completato';

		// Checking if Empty Cover 
		if (!($_FILES['cover']['size'] == 0)) {
			try{
				$config = array(
				    'upload_path' => $this->config->item('upload_path').'/img/_tmp/',
				    'allowed_types' => 'jpg|png'
				);		
				$this->load->library('upload', $config);		
				if (!$this->upload->do_upload('cover')) {
					$response = (object)array(
						'error' => 'Errore 001INT - Salvataggio Incompleto',
						'message' => null
					);
					header('Content-Type: application/x-json; charset=utf-8');
					echo json_encode($response);
					exit();
				} else {
					$file = $this->upload->data();
					$this->resizeImg(170,95,$file,$this->config->item('upload_path').'/img/manufacturers/'.$file['file_name'],'banner');
					$data = array(
						'manufacturerName' => $this->input->post('manufacturerName'),
						'manufacturerDescription' => $this->input->post('manufacturerDescription'),
						'photoName' => $file['file_name']
					);
					$this->backmodel->u_PRD_Manufacturer($this->input->post('idManufacturer'),$data);
				}
			} catch (exception $exception) {
				$response = (object)array(
					'error' => 'Errore 001INT - Salvataggio Incompleto',
					'message' => null
				);
				header('Content-Type: application/x-json; charset=utf-8');
				echo json_encode($response);
				exit();
			}
		} else {
			try {
				$data = array(
					'manufacturerName' => $this->input->post('manufacturerName'),
					'manufacturerDescription' => $this->input->post('manufacturerDescription')
				);
				$this->backmodel->u_PRD_Manufacturer($this->input->post('idManufacturer'),$data);
			} catch (exception $exception) {
				$response = (object)array(
					'error' => 'Errore 001INT - Salvataggio Incompleto',
					'message' => null
				);
				header('Content-Type: application/x-json; charset=utf-8');
				echo json_encode($response);
				exit();
			}
		}
		$response = (object)array(
			'error' => null,
			'message' => 'Salvataggio Completato'
		);
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($response);
	} // CORRETTA
	public function d_PRD_Manufacturers() {
		$isAllDeleted = true;
		$idManufacturers = $this->input->post('idManufacturer');
		
		try{
			for ($i = 0; $i < count($idManufacturers); $i++) {
				$products = $this->backmodel->ra_PRD_Products('','','',$idManufacturers[$i],'','','','');
				for ($k = 0; $k < count($products); $k++) {
					$carts = $this->backmodel->ra_ORD_Carts_Products('','','',$product->idProduct,'');
					if ($carts) {
						$isAllDeleted = false;
						unset($idManufacturers[$i]);
					}
				}
			}	
			for ($i = 0; $i < count($idManufacturers); $i++) {
				$manufacturer = $this->backmodel->ra_PRD_Manufacturers('','',$idManufacturers[$i],'');
				if(file_exists($this->config->item('upload_path').'/img/manufacturers/'.$manufacturer[0]->photoName) && $manufacturer[0]->photoName != "") {
					unlink($this->config->item('upload_path').'/img/manufacturers/'.$manufacturer[0]->photoName);
				}
				$this->backmodel->d_PRD_Manufacturer($idManufacturers[$i]); 
			}
		} catch (exception $exception) {				
			$response = (object)array(
				'error' => 'Errore 001INT - Cancellazione Incompleta',
				'message' => null
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
			exit();
		}
		if (!$isAllDeleted) {
			$response = (object)array(
				'error' => null,
				'message' => 'Canellazione Incompleta <br> Alcuni prodotti non sono stati cancellati in quanto presenti in uno o più carrelli <br>'
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
		} else {
			$response = (object)array(
				'error' => null,
				'message' => 'Canellazione Completata'
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
		}
	} // CORRETTA
	// PRD_Category
	public function c_PRD_Category() {
		//Form Validation
		$this->load->library('form_validation');
		$this->form_validation->set_rules('categoryName', 'Nome categoria', 'trim|required');
		$this->form_validation->set_message('required', 'Riempi il campo %s');
		//End Form Validation

		if ($this->form_validation->run() == FALSE) {
			$response = (object)array(
				'error' => 'Errore 001INT - Salvataggio Incompleto',
				'message' => null
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
			exit();
		} else {	
			$isPresent = $this->backmodel->r_PRD_Category('',$this->input->post('categoryName'));
			if(count($isPresent) < 1) {
				try{
					if ($this->input->post('idParentCategory') != 'NULL') {
						$data = array(
							'idParentCategory' => $this->input->post('idParentCategory'),
						);
					} else {
						$data = array(
							'idParentCategory' => NULL,
						);
					}
					$returnedID = $this->backmodel->c_PRD_Category($data);
					$data = array(
						'idCategory' => $returnedID,
						'language' => 'it',
						'categoryName' => $this->input->post('categoryName'),
						'categoryDescription' => $this->input->post('categoryDescription'),
					);
					$this->backmodel->c_LANG_Category($data);
					
					$settings = $this->backmodel->r_STN_Settings();
					$languages = array_filter(explode( ',', $settings->shopLanguages ));
					
					foreach ($languages as $language) {
						$data = array(
							'idCategory' => $returnedID,
							'language' => $language,
							'categoryName' => $this->translate($this->input->post('categoryName'),$language),
							'categoryDescription' => $this->translate($this->input->post('categoryDescription'),$language),
						);
						$this->backmodel->c_LANG_Category($data);
					}
				}catch (exception $exception) {
					$response = (object)array(
						'error' => 'Errore 002INT - Salvataggio Incompleto',
						'message' => null
					);
					header('Content-Type: application/x-json; charset=utf-8');
					echo json_encode($response);
					exit();
				}
			} else {
				$response = (object)array(
					'error' => 'Errore 003INT - Categoria già presente',
					'message' => null
				);
				header('Content-Type: application/x-json; charset=utf-8');
				echo json_encode($response);
				exit();
			}
			
			$response = (object)array(
				'error' => null,
				'message' => 'Salvataggio Completato'
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);	
		}
	} // CORRETTA
	public function u_PRD_Category() {
		//Form Validation
		$this->load->library('form_validation');
		$this->form_validation->set_rules('categoryName[]', 'Nome categoria', 'trim|required');
		$this->form_validation->set_rules('categoryDescription[]', 'Descrizione categoria', 'trim|required');
		$this->form_validation->set_message('required', 'Riempi il campo %s');
		//End Form Validation

		if ($this->form_validation->run() == FALSE) {
			$response = (object)array(
				'error' => 'Errore 001INT - Salvataggio Incompleto',
				'message' => null
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
			exit();
		} else {	
			try{	
				$categoryNames = $this->input->post('categoryName');
				$categoryDescriptions = $this->input->post('categoryDescription');
				$idLangCategories = $this->input->post('idLangCategory');
				
				$settings = $this->backmodel->r_STN_Settings();
				$languages = array_filter(explode( ',', $settings->shopLanguages ));
				array_unshift($languages, "it");
				
				$idParentCategory = NULL;
				if($this->input->post('idParentCategory') != "NULL"){
					$idParentCategory = $this->input->post('idParentCategory');
				}
				$data = array(
					'idParentCategory' => $idParentCategory
				);
				$this->backmodel->u_PRD_Category($this->input->post('idCategory'), $data);
				for ($i = 0; $i < count($languages); $i++) {
					$data = array(
						'categoryName' => $categoryNames[$i],
						'categoryDescription' => $categoryDescriptions[$i]
					);
					$this->backmodel->u_LANG_Category($idLangCategories[$i], $data);
				}						
			} catch (exception $exception) {
				$response = (object)array(
					'error' => 'Errore 002INT - Salvataggio Incompleto',
					'message' => null
				);
				header('Content-Type: application/x-json; charset=utf-8');
				echo json_encode($response);
				exit();
			}
			
			$response = (object)array(
				'error' => null,
				'message' => 'Salvataggio Completato'
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
		}	
	} // CORRETTA
	public function d_PRD_Categories() {
		$categories = $this->backmodel->ra_PRD_Categories('','it');
		$idCategories = $this->input->post('idCategory');
		$idCategoryToDelete = array();
		$isAllDeleted = true;
		
		// START: Verifico che nelle sotto-categorie non ci siano dei prodotti presenti nei carrelli
		try{
			for ($i = 0; $i < count($idCategories); $i++) {
			$idSubcategories = $this->getChild_categories($categories,$idCategories[$i]);
			for ($k = 0; $k < count($idSubcategories); $k++) {
				$products = $this->backmodel->ra_PRD_Products('','',$idSubcategories[$k],'','','',0,'');
				if ($products){
					for ($l = 0; $l < count($products); $l++) {
						$carts = $this->backmodel->ra_ORD_Carts_Products('', '', '', $products[$l]->idProduct,'');
						if ($carts) {
							unset($idCategories[$i]);
							$isAllDeleted = false;
						} else {
							array_push($idCategoryToDelete, $idCategoryToDelete[$k]);
						}
					}
				} else {
					array_push($idCategoryToDelete, $idSubcategories[$k]);
				}
			}
		}
		}catch (exception $exception) {				
			$response = (object)array(
				'error' => 'Errore 001INT - Cancellazione Incompleta',
				'message' => null
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
			exit();
		}
		// END: Verifico che nelle sotto-categorie non ci siano dei prodotti presenti nei carrelli

		// START: Cancello le categorie ed i prodotti ad essa associati
		try{
			for ($i = 0; $i < count($idCategoryToDelete); $i++) {
			$products = $this->backmodel->ra_PRD_Products('','',$idCategoryToDelete[$i],'','','',1,'');
			for ($k = 0; $k < count($products); $k++) {
				$photos = $this->backmodel->ra_PRD_Photos('',$products[$k]->idProduct,'');
				for ($j = 0; $j < count($photos); $j++) {
					$img = $this->backmodel->r_PRD_Photo($photos[$j]->idPhoto,'','');
					unlink('./resources/img/products/'.$img->photoName);
					unlink('./resources/img/products/large/'.$img->photoName);
					unlink('./resources/img/products/medium/'.$img->photoName);
					unlink('./resources/img/products/small/'.$img->photoName);
					unlink('./resources/img/products/extra_small/'.$img->photoName);
					unlink('./resources/img/products/special_sale/medium/'.$img->photoName);
					unlink('./resources/img/products/special_sale/small/'.$img->photoName);
					$this->backmodel->d_PRD_Photo($photos[$j]->idPhoto);
				}
				$this->backmodel->d_PRD_Product($products[$k]->idProduct); 
				$this->backmodel->d_LANG_Product('',$products[$k]->idProduct,'');
			}
			$this->backmodel->d_PRD_Category($idCategoryToDelete[$i]);
			$this->backmodel->d_LANG_Category('',$idCategoryToDelete[$i],'');
			
		} 
		}catch (exception $exception) {				
			$response = (object)array(
				'error' => 'Errore 002INT - Cancellazione Incompleta',
				'message' => null
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
			exit();
		}
		// END: Cancello le categorie ed i prodotti ad essa associati

		if (!$isAllDeleted) {
			$response = (object)array(
				'error' => null,
				'message' => 'Canellazione Incompleta <br> Alcuni categorie non sono stati cancellate in quanto correlate ad uno o più carrelli <br>'
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
		} else {
			$response = (object)array(
				'error' => null,
				'message' => 'Canellazione Completata'
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
		}
	} // CORRETTA	 
	
	// PRD_Values
	public function c_PRD_Values() {
		//Form Validation
		$this->load->library('form_validation');	
		$this->form_validation->set_rules('valueName', 'Nome', 'trim|required');
		$this->form_validation->set_rules('idFeature', 'Nome', 'trim|required');
		$this->form_validation->set_message('required', 'Riempi il campo %s');
		// End Form Validation
	
		if ($this->form_validation->run() == FALSE) {
			$response = (object)array(
				'error' => 'Errore 001INT - Salvataggio Incompleto',
				'message' => null
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
			exit();
		} else {
			try {
				$data = array(
					'idFeature' => $this->input->post('idFeature'),
				);
				$returnedID = $this->backmodel->c_PRD_Values($data);
				$data = array(
					'idValue' => $returnedID,
					'language' => 'it',
					'valueName' => $this->input->post('valueName'),
				);
				$this->backmodel->c_LANG_Value($data);
				
				$settings = $this->backmodel->r_STN_Settings();
				$languages = array_filter(explode( ',', $settings->shopLanguages ));
				
				foreach ($languages as $language) {
					$data = array(
						'idValue' => $returnedID,
						'language' => $language,
						'valueName' => $this->translate($this->input->post('valueName'),$language),
					);
					$this->backmodel->c_LANG_Value($data);
				}
			}catch (exception $exception) {
				$response = (object)array(
					'error' => 'Errore 002INT - Salvataggio Incompleto',
					'message' => null
				);
				header('Content-Type: application/x-json; charset=utf-8');
				echo json_encode($response);
				exit();
			}
			
			$response = (object)array(
				'error' => null,
				'message' => 'Salvataggio Completato'
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
		}	
	} // CORRETTA
	public function u_PRD_Values() {
		$idLangValues = $this->input->post('idLangValue');
		$newValues = $this->input->post('valueName');
		try{
			for ($i = 0; $i < count($idLangValues); $i++) {
				$data = array(
					'valueName' => $newValues[$i]
				);
				$this->backmodel->u_LANG_Value($idLangValues[$i],$data);
			}
		}catch (exception $exception) {
			$response = (object)array(
				'error' => 'Errore 002INT - Salvataggio Incompleto',
				'message' => null
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
			exit();
		}
		$response = (object)array(
			'error' => null,
			'message' => 'Salvataggio Completato'
		);
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($response);
	} // CORRETTA
	public function r_PRD_Values_byFeature() {
		$idFeature = $this->input->post('idFeature');
		$data = $this->backmodel->r_PRD_Values('',$idFeature,'it');
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	public function d_PRD_Value() {
		$isAllDeleted = true;
		$idValues = $this->input->post('idValue');
		
		try{
			for ($i = 0; $i < count($idValues); $i++) {
				$groups = $this->backmodel->ra_PRD_Groups('', '', $idValues[$i], '', 1, '', 'it');
				if ($groups) {
					unset($idValues[$i]);
					$isAllDeleted = false;
				} else {
					$this->backmodel->d_PRD_Values($idValues[$i], '');
					$this->backmodel->d_LANG_Value('', $idValues[$i], '');
				}
			}
		}catch (exception $exception) {				
			$response = (object)array(
				'error' => 'Errore 001INT - Cancellazione Incompleta',
				'message' => null
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
			exit();
		}
		
		if (!$isAllDeleted) {
			$response = (object)array(
				'error' => null,
				'message' => 'Canellazione Incompleta <br> Alcuni valori non sono stati cancellati in quanto associati ad uno o più prodotti <br>'
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
		} else {
			$response = (object)array(
				'error' => null,
				'message' => 'Canellazione Completata'
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
		}	
	} // CORRETTA
	// PRD_Features
	public function c_PRD_Features() {
		//Form Validation
		$this->load->library('form_validation');
		$this->form_validation->set_rules('featureName', 'Nome', 'trim|required');
		$this->form_validation->set_message('required', 'Riempi il campo %s');
		// End Form Validation
	
		if ($this->form_validation->run() == FALSE) {
			$response = (object)array(
				'error' => 'Errore 001INT - Salvataggio Incompleto',
				'message' => null
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
			exit();
		} else {
			try {
				$data = array(
					'isFeature' => $this->input->post('isFeature')
				);
				$returnedID = $this->backmodel->c_PRD_Features($data);
				$data = array(
					'idFeature' => $returnedID,
					'language' => 'it',
					'featureName' => $this->input->post('featureName'),
				);
				$this->backmodel->c_LANG_Feature($data);
				
				$settings = $this->backmodel->r_STN_Settings();
				$languages = array_filter(explode( ',', $settings->shopLanguages ));
				
				foreach ($languages as $language) {
					$data = array(
						'idFeature' => $returnedID,
						'language' => $language,
						'featureName' => $this->translate($this->input->post('featureName'),$language),
					);
					$this->backmodel->c_LANG_Feature($data);
				}				
			}catch (exception $exception) {				
				$response = (object)array(
					'error' => 'Errore 002INT - Salvataggio Incompleto',
					'message' => null
				);
				header('Content-Type: application/x-json; charset=utf-8');
				echo json_encode($response);
				exit();
			}
			
			$response = (object)array(
				'error' => null,
				'message' => 'Salvataggio Completato'
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
		}
	} // CORRETTA
	public function r_PRD_Features() {
		$idFeature = $this->input->post('idFeature');
		$data = $this->backmodel->r_PRD_Features($idFeature,'');
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	public function u_PRD_Features() {
		$idLangFeatures = $this->input->post('idLangFeature');
		$newFeatures = $this->input->post('featureName');
		try{
			for ($i = 0; $i < count($idLangFeatures); $i++) {
				$data = array(
					'featureName' => $newFeatures[$i]
				);
				$this->backmodel->u_LANG_Feature($idLangFeatures[$i],$data);
			}
		}catch (exception $exception) {
			$response = (object)array(
				'error' => 'Errore 001INT - Salvataggio Incompleto',
				'message' => null
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
			exit();
		}
		
		$response = (object)array(
			'error' => null,
			'message' => 'Salvataggio Completato'
		);
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($response);
	} // CORRETTA
	public function d_PRD_Features() {
		$idFeaturesNotDeleted = array();
		$idFeatures = $this->input->post('idFeature');
		$isAllDeleted = true;
		
		try{
			for ($i = 0; $i < count($idFeatures); $i++) {
				$groups = $this->backmodel->ra_PRD_Groups('', '', '', $idFeatures[$i], 1, 1, 'it');
				if ($groups) {
					unset($idFeatures[$i]);
					$isAllDeleted = false;
				} else {
					$this->backmodel->d_LANG_Feature('', $idFeatures[$i], '');
					$this->backmodel->d_PRD_Features($idFeatures[$i]);
					$values = $this->backmodel->r_PRD_Values('' ,$idFeatures[$i] ,'');
					for ($k = 0; $k < count($values); $k++) {
						$this->backmodel->d_PRD_Values($values[$k]->idValue,'');
						$this->backmodel->d_LANG_Value('',$values[$k]->idValue,'');
					}
				}
			}
		}catch (exception $exception) {
			$response = (object)array(
				'error' => 'Errore 001INT - Cancellazione Incompleta',
				'message' => null
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
			exit();
		}
		
		if (!$isAllDeleted) {
			$response = (object)array(
				'error' => null,
				'message' => 'Canellazione Incompleta <br> Alcune caratteristiche non sono stati cancellate in quanto associati ad uno o più prodotti <br>'
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
		} else {
			$response = (object)array(
				'error' => null,
				'message' => 'Canellazione Completata'
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
		}	
	} // CORRETTA
	public function dp_PRD_Features() {
		$idFeatures = $this->input->post('idFeature');
		$productNotCopied = false;
		
		/*
		foreach ($idProducts as $idProduct) {
			$originalProduct = $this->backmodel->r_PRD_Product($idProduct,'','');
			unset($originalProduct->idProduct);
			$originalProduct->createdOn = date('Y-m-d');
			$returnedID = $this->backmodel->c_PRD_Product($originalProduct);
		
			$combinations = $this->backmodel->r_PRD_Combinations('', $idProduct);
			if ($combinations) {
				foreach ($combinations as $combination) {
					unset($combination->idCombination);
					$this->backmodel->c_PRD_Combinations($combination);
				}
			}

			$translations = $this->backmodel->ra_LANG_Products($idProduct,'');
			if ($translations) {
				foreach ($translations as $translation) {
					unset($translation->idLangProduct);
					$translation->productName = $translation->productName.'_Copia';
					$translation->idProduct = $returnedID;
					$this->backmodel->c_LANG_Product($translation);
				}	
			}	
		}
		*/
	}
	// PRD_Sale
	public function c_PRD_Sale() {
		$sales = $this->input->post('sales');
		
		for ($i = 0; $i < count($sales); $i++) {
			// START: Inizializzazione del valore dell'Amount e della percentage
			$salePercentage = NULL;
			$saleAmount = NULL;
			if ($sales[$i]['newSaleAmount'] != '') {
				$saleAmount = $sales[$i]['newSaleAmount'];
			} 
			if ($sales[$i]['newSalePercentage'] != '') {
				$salePercentage = $sales[$i]['newSalePercentage'];
			} 
			// END

			// START: Sto creando una nuova associazione
			// Leggo i vecchi saldi per capire se ci sono già gli sconti per quei prodotti nelle nuove date
			$existingSales = $this->backmodel->ra_PRD_Sales('','','','','', date("Y-m-d", strtotime( $sales[$i]['newSaleStart'] )), date("Y-m-d", strtotime( $sales[$i]['newSaleEnd'] )), '', 1, 0);
			
			// START: Sconto non presente in DB ma presente in BE (create - edit)
			for ($k = 0; $k < count($sales[$i]['idProducts']); $k++) {
				// START: I prodotti non presenti nel DB ma presenti nel front sono creati
				$isPresentNewDate = false;
				$idOldSale = '';
				for ($j = 0; $j < count($existingSales); $j++) {
					if ($existingSales[$j]->idProduct == $sales[$i]['idProducts'][$k]) {
						$isPresentNewDate = true;
						break;
					}
				}
				if ($isPresentNewDate == false) {
					$data = array(
						'salePercentage' => $salePercentage,
						'saleAmount' => $saleAmount,
						'saleStart' => date("Y-m-d", strtotime( $sales[$i]['newSaleStart'] )),
						'saleEnd' => date("Y-m-d", strtotime( $sales[$i]['newSaleEnd'] )),
						'idProduct' => $sales[$i]['idProducts'][$k]
					);
					$this->backmodel->c_PRD_Sale($data);
				}
				// END
			}
			
		}
		$data['message'] = 'Salvataggio Completato';
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	public function r_PRD_Sale() {
		$orderedSales = array();
		$idSale = $this->input->post('idSale');
		$sale = $this->backmodel->r_PRD_Sale($idSale,'','','');
		
		$existingSales = $this->backmodel->ra_PRD_sales('','','',$sale->saleAmount,$sale->salePercentage,'','','',0,0);
		
		// ORDINO I DATI
		for ($i = 0; $i < count($existingSales); $i++) {
			$isPresent = False;
			for ($k = 0; $k < count($orderedSales); $k++) {
				if ($orderedSales[$k]->saleStart == date("d-m-Y", strtotime( $existingSales[$i]->saleStart)) && $orderedSales[$k]->saleEnd == date("d-m-Y", strtotime( $existingSales[$i]->saleEnd)) ) {
					array_push($orderedSales[$k]->idProducts, $existingSales[$i]->idProduct);
					$isPresent = True;
				}
			}
			if ($isPresent == False) {		
				$object = (object) [
				    'salePercentage' => $existingSales[$i]->salePercentage,
				    'saleAmount' => $existingSales[$i]->saleAmount,
				    'saleStart' => date("d-m-Y", strtotime( $existingSales[$i]->saleStart)),
				    'saleEnd' => date("d-m-Y", strtotime( $existingSales[$i]->saleEnd)),
				    'idProducts' => array($existingSales[$i]->idProduct)
				];
				array_push($orderedSales,$object);
			}
		}
		
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($orderedSales);
	}
	public function u_PRD_Sale() {
		$sales = $this->input->post('sales');
		$salesError = false;
		
		for ($i = 0; $i < count($sales); $i++) {
			// ESAMINO TUTTI GLI OGGETTI DELL'ARRAY
			if ( $sales[$i]['saleStart'] == $sales[$i]['newSaleStart'] && $sales[$i]['saleEnd'] == $sales[$i]['newSaleEnd'] ) {
				// Le date non sono cambiate
				// Leggo i vecchi prodotti presenti in DB (Array di Objects)
				
				echo "CASO 1";
				echo "\n";
				print_r('saleStart = newSaleStart - saleEnd = newSaleEnd');
				echo "\n\n"; // DEBUG
				
				$startingSales = $this->backmodel->ra_PRD_Sales('','','', $sales[$i]['saleAmount'], $sales[$i]['salePercentage'], date("Y-m-d", strtotime( $sales[$i]['saleStart'] )), date("Y-m-d", strtotime( $sales[$i]['saleEnd'] )), '', 0, 0);
				
				// START: I prodotti presenti nel DB ma non presenti nel front sono cancellati
				for ($k = 0; $k < count($startingSales); $k++) {
					if ( !in_array($startingSales[$k]->idProduct,$sales[$i]['idProducts']) ) {
						$this->backmodel->d_PRD_Sale($startingSales[$k]->idSale);	
						
						echo "\n"; // DEBUG
						echo "ELIMINO I PRODOTTI NON CHECKATI NEL FRONT E PRESENTI IN DB";
						echo "\n\n"; 
						
					}
				}
				// END
				
				for ($k = 0; $k < count($sales[$i]['idProducts']); $k++) {
					// START: I prodotti non presenti nel DB ma presenti nel front sono creati
					$isPresent = false;
					$idOldSale = '';
					for ($j = 0; $j < count($startingSales); $j++) {
						if ($startingSales[$j]->idProduct == $sales[$i]['idProducts'][$k]) {
							$isPresent = true;
							$idOldSale = $startingSales[$j]->idSale;
							break;
						}
					}
					if ($isPresent == false) {
						$data = array(
							'salePercentage' => $sales[$i]['newSalePercentage'],
							'saleAmount' => $sales[$i]['newSaleAmount'],
							'saleStart' => date("Y-m-d", strtotime( $sales[$i]['saleStart'] )),
							'saleEnd' => date("Y-m-d", strtotime( $sales[$i]['saleEnd'] )),
							'idProduct' => $sales[$i]['idProducts'][$k]
						);
						$this->backmodel->c_PRD_Sale($data);
						
						echo "\n"; // DEBUG
						echo "CREO I PRODOTTI  CHECKATI NEL FRONT E NON PRESENTI IN DB";
						echo "\n\n"; 
						
					}
					// END
					// START: I prodotti presenti sia nel DB sia presenti nel front sono aggiornati
					if ($isPresent == true) {
						$data = array(
							'salePercentage' => $sales[$i]['newSalePercentage'],
							'saleAmount' => $sales[$i]['newSaleAmount'],
							'saleStart' => date("Y-m-d", strtotime( $sales[$i]['saleStart'] )),
							'saleEnd' => date("Y-m-d", strtotime( $sales[$i]['saleEnd'] )),
						);
						$this->backmodel->u_PRD_Sale($idOldSale,$data);
						
						echo "\n"; // DEBUG
						echo "AGGIORNO I PRODOTTI CHECKATI SIA NEL FRONT SIA PRESENTI IN DB";
						echo "\n\n"; 
					}
					// END
				}
			} else {
				
				echo "CASO 2";
				echo "\n";
				print_r('saleStart != newSaleStart - saleEnd != newSaleEnd');
				echo "\n\n"; // DEBUG
			
				if ($sales[$i]['saleStart'] == null && $sales[$i]['saleEnd'] == null) {
				
					echo "\n"; // DEBUG
					echo "saleStart == null - saleEnd == null";
					echo "\n\n"; 
				
					// START: Sto creando una nuova associazione
					// Leggo i vecchi saldi per capire se ci sono già gli sconti per quei prodotti nelle nuove date
					$existingSales = $this->backmodel->ra_PRD_Sales('','','','','', date("Y-m-d", strtotime( $sales[$i]['newSaleStart'] )), date("Y-m-d", strtotime( $sales[$i]['newSaleEnd'] )), '', 1, 0);
					
					echo "\n"; // DEBUG
					print_r($existingSales);
					echo "\n\n"; 
					
					if ($existingSales) {
						// START: Sconto non presente in DB ma presente in BE (create - edit)
						for ($k = 0; $k < count($sales[$i]['idProducts']); $k++) {
							// START: I prodotti non presenti nel DB ma presenti nel front sono creati
							$isPresentNewDate = false;
							$idOldSale = '';
							for ($j = 0; $j < count($existingSales); $j++) {
								if ($existingSales[$j]->idProduct == $sales[$i]['idProducts'][$k]) {
									$isPresentNewDate = true;
									break;
								}
							}
							if ($isPresentNewDate == false) {
								$data = array(
									'salePercentage' => $sales[$i]['newSalePercentage'],
									'saleAmount' => $sales[$i]['newSaleAmount'],
									'saleStart' => date("Y-m-d", strtotime( $sales[$i]['newSaleStart'] )),
									'saleEnd' => date("Y-m-d", strtotime( $sales[$i]['newSaleEnd'] )),
									'idProduct' => $sales[$i]['idProducts'][$k]
								);
								$this->backmodel->c_PRD_Sale($data);
								
								echo "\n"; // DEBUG
								echo "CREO I PRODOTTI  CHECKATI NEL FRONT E NON PRESENTI IN DB";
								echo "\n\n"; 
							}
							// END
						}
						// END
						
						// START: Sconto presente in DB ma non in BE (delete) 
						// PROCESSO INUTILE (da verificare)
						/*
						for ($k = 0; $k < count($startingSales); $k++) {
							if (!in_array($startingSales[$k]->idProduct, $sales[$i]['idProducts'])) {
								$this->backmodel->d_PRD_Sale($startingSales[$k]->idSale);
								
								echo "\n"; // DEBUG
								echo "ELIMINO I PRODOTTI NON CHECKATI NEL FRONT E PRESENTI IN DB";
								echo "\n\n";  
							}
						}
						*/
						// END
					} else {
						// Non esistono sconti nelle date inserite, per cui posso creare gli sconti senza problemi
						for ($k = 0; $k < count($sales[$i]['idProducts']); $k++) {
							$data = array(
								'salePercentage' => $sales[$i]['newSalePercentage'],
								'saleAmount' => $sales[$i]['newSaleAmount'],
								'saleStart' => date("Y-m-d", strtotime( $sales[$i]['newSaleStart'] )),
								'saleEnd' => date("Y-m-d", strtotime( $sales[$i]['newSaleEnd'] )),
								'idProduct' => $sales[$i]['idProducts'][$k]
							);
							$this->backmodel->c_PRD_Sale($data);
							
							echo "\n"; // DEBUG
							echo "CREO I PRODOTTI  CHECKATI NEL FRONT E NON PRESENTI IN DB";
							echo "\n\n"; 
						}
					}
					// END
				} else {
				
					echo "\n"; // DEBUG
					echo "saleStart != null - saleEnd != null";
					echo "\n\n"; 
				
					// START: Non essendo saleStart e saleEnd a NULL vuol dire che sono state cambiate le date dei prodotti
					// Leggo i vecchi prodotti
					$startingSales = $this->backmodel->ra_PRD_Sales('','','', $sales[$i]['saleAmount'], $sales[$i]['salePercentage'], date("Y-m-d", strtotime( $sales[$i]['saleStart'] )), date("Y-m-d", strtotime( $sales[$i]['saleEnd'] )), '', 0, 0);
					// Leggo i prodotti nelle nuove date
					$existingSales = $this->backmodel->ra_PRD_Sales('','','','','', date("Y-m-d", strtotime( $sales[$i]['newSaleStart'] )), date("Y-m-d", strtotime( $sales[$i]['newSaleEnd'] )), '', 1, 0);
					
					// START: Sconto presente in DB ma non in BE (delete) 
					for ($k = 0; $k < count($startingSales); $k++) {
						if (!in_array($startingSales[$k]->idProduct, $sales[$i]['idProducts'])) {
							$this->backmodel->d_PRD_Sale($startingSales[$k]->idSale);
							
							echo "\n"; // DEBUG
							echo "ELIMINO I PRODOTTI NON CHECKATI NEL FRONT E PRESENTI IN DB";
							echo "\n\n"; 
						}
					}
					// END
					// START: Scontro non presente in DB ma presente in BE (create) - Sconto presente in DB e in BE (control + edit)
					for ($k = 0; $k < count($sales[$i]['idProducts']); $k++) {
						// START: I prodotti non presenti nel DB ma presenti nel front sono creati
						$isPresent = false;
						$idOldSale = '';
						for ($j = 0; $j < count($startingSales); $j++) {
							if ($startingSales[$j]->idProduct == $sales[$i]['idProducts'][$k]) {
								$isPresent = true;
								$idOldSale = $startingSales[$j]->idSale;
								break;
							}
						}
						if ($isPresent == false) {
							$isPresentNewDate = false;
							for ($j = 0; $j < count($existingSales); $j++) {
								if ($existingSales[$j]->idProduct == $sales[$i]['idProducts'][$k]) {
									$isPresentNewDate = true;
									break;
								}
							}
							if ($isPresentNewDate == false) {
								$data = array(
									'salePercentage' => $sales[$i]['newSalePercentage'],
									'saleAmount' => $sales[$i]['newSaleAmount'],
									'saleStart' => date("Y-m-d", strtotime( $sales[$i]['newSaleStart'] )),
									'saleEnd' => date("Y-m-d", strtotime( $sales[$i]['newSaleEnd'] )),
									'idProduct' => $sales[$i]['idProducts'][$k]
								);
								$this->backmodel->c_PRD_Sale($data);
								
								echo "\n"; // DEBUG
								echo "CREO I PRODOTTI  CHECKATI NEL FRONT E NON PRESENTI IN DB";
								echo "\n\n"; 
							}
						} else {
							// START: Prodotto non presente nelle nuove date (edit)
							$isPresentNewDate = false;
							for ($j = 0; $j < count($existingSales); $j++) {
								if ($existingSales[$j]->idProduct == $sales[$i]['idProducts'][$k]) {
									$isPresent = true;
									break;
								}
							}
							if ($isPresentNewDate == false) {
								$data = array(
									'salePercentage' => $sales[$i]['newSalePercentage'],
									'saleAmount' => $sales[$i]['newSaleAmount'],
									'saleStart' => date("Y-m-d", strtotime( $sales[$i]['newSaleStart'] )),
									'saleEnd' => date("Y-m-d", strtotime( $sales[$i]['newSaleEnd'] )),
									'idProduct' => $sales[$i]['idProducts'][$k]
								);
								$this->backmodel->u_PRD_Sale($idOldSale,$data);
								
								echo "\n"; // DEBUG
								echo "AGGIORNO I PRODOTTI CHECKATI SIA NEL FRONT SIA PRESENTI IN DB";
								echo "\n\n"; 
							}	
							// END
						}
					}
				}
				// END
				
			}	
		}		
		
		/*
		$message = '';
		if ($salesError) {
			$message['message'] = 'Salvataggio Completato';
		} else {
			$message['error'] = 'Salvataggio Incompleto <br/> Alcune associazioni non sono state modificate in quanto le date inserite generavano un conflitto con le date di un altro sconto.';
		}
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($message);
		*/
	}
	// STN_Slides
	public function c_STN_Slide() {
		//Form Validation
		$this->load->library('form_validation');
		$this->form_validation->set_rules('slideName', 'Nome slide', 'trim|required');
		$this->form_validation->set_message('required', 'Riempi il campo %s');
		//End Form Validation

		if ($this->form_validation->run() == FALSE) {
			$response = (object)array(
				'error' => 'Errore 001INT - Salvataggio Incompleto',
				'message' => null
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
			exit();
		} else {
			// Upload Path
		 	$config = array(
		 	    'upload_path' => $this->config->item('upload_path').'/img/slides/',
		 	    'allowed_types' => 'jpg|png',
		 	);
	 		$this->load->library('upload', $config);
	 	
	 		if ( ! $this->upload->do_upload('files')) {
	 			$response = (object)array(
	 				'error' => 'Errore 002INT - Salvataggio Incompleto',
	 				'message' => null
	 			);
	 			header('Content-Type: application/x-json; charset=utf-8');
	 			echo json_encode($response);
	 			exit();
	 		} else {	
	 			$data = array();
	 			$file = $this->upload->data();
	 			
	 			try{
		 			$this->resizeImg(1920,844,$file,$this->config->item('upload_path').'/img/slides/'.$file['file_name'],'banner');
	
			 		$record = array(
			 			'photoName' => $file['file_name'],
			 		);
			 		$returnedID = $this->backmodel->c_STN_Slide($record);
		 		}catch (exception $exception) {				
		 			$response = (object)array(
		 				'error' => 'Errore 003INT - Salvataggio Incompleto',
		 				'message' => null
		 			);
		 			header('Content-Type: application/x-json; charset=utf-8');
		 			echo json_encode($response);
		 			exit();
		 		}
				
				$settings = $this->backmodel->r_STN_Settings();
				$languages = array_filter(explode( ',', $settings->shopLanguages ));
				
				$UT_translator = $this->load->library('utilities/UT_translator',NULL,'UT_translator');
				$config['client_id'] = '[CLIENT_ID]';
				$config['client_secret'] = '[CLIENT_SECRET]';
				$this->UT_translator->initialize($config);
				
				try{
					$this->UT_translator->setTokens();
					for ($i = 0; $i < count($languages); $i++) {	
						$slideDescription = '';
						if ( $this->input->post('slideDescription') != '' ) {
							$slideDescription = $this->UT_translator->curlRequest($languages[$i], $languages[$i], $this->input->post('productDescription'));
						}		
						$data = array(
							'idSlide' => $returnedID,
							'language' => $languages[$i],
							'slideName' =>  $this->UT_translator->curlRequest($languages[$i], $languages[$i], $this->input->post('slideName')),
							'slideDescription' => $slideDescription,
						);
						$this->backmodel->c_LANG_Slide($data);
					}
					$data = array(
						'idSlide' => $returnedID,
						'language' => 'it',
						'slideName' => $this->input->post('slideName'),
						'slideDescription' => $this->input->post('slideDescription')
					);
					$this->backmodel->c_LANG_Slide($data);
				}catch (exception $exception) {				
					$response = (object)array(
						'error' => 'Errore 004INT - Salvataggio Incompleto',
						'message' => null
					);
					header('Content-Type: application/x-json; charset=utf-8');
					echo json_encode($response);
					exit();
				}
				
				try{
					foreach ($languages as $language) {
						if ( $this->input->post('slideDescription') != '' ) {
							$slideDescription = $this->translate($this->input->post('slideDescription'),$language);
						} else {
							$slideDescription = '';
						}
						$data = array(
							'idSlide' => $returnedID,
							'language' => $language,
							'slideName' => $this->translate($this->input->post('slideName'),$language),
							'slideDescription' => $slideDescription,
						);
						$this->backmodel->c_LANG_Slide($data);
					}
				}catch (exception $exception) {				
					$response = (object)array(
						'error' => 'Errore 004INT - Salvataggio Incompleto',
						'message' => null
					);
					header('Content-Type: application/x-json; charset=utf-8');
					echo json_encode($response);
					exit();
				}
				
				$response = (object)array(
					'error' => null,
					'message' => 'Salvataggio Completato'
				);	
				header('Content-Type: application/x-json; charset=utf-8');
				echo json_encode($response);
			}
		}	
	}	// CORRETTA
	public function r_STN_Slide() {
		$data = $this->backmodel->r_STN_Slide($this->input->post('idSlide'));
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	public function u_STN_Slide() {
		//Form Validation
		$this->load->library('form_validation');
		$this->form_validation->set_rules('slideName[]', 'Nome slide', 'trim|required');
		$this->form_validation->set_rules('slideDescription[]', 'Descrizione slide', 'trim|required');
		$this->form_validation->set_message('required', 'Riempi il campo %s');
		//End Form Validation

		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('message', 'Errore/i: '.validation_errors());
			redirect(site_url('back/slides'));
		} else {	
			$slideNames = $this->input->post('slideName');
			$slideDescriptions = $this->input->post('slideDescription');
			$idLangSlides = $this->input->post('idLangSlide');
			
			$settings = $this->backmodel->r_STN_Settings();
			$languages = array_filter(explode( ',', $settings->shopLanguages ));
			array_unshift($languages, "it");
				
			$langSlides = $this->backmodel->ra_LANG_Slides('',$this->input->post('idSlide'),'');
			$languagesPresent = array();
			foreach ($langSlides as $langSlide) {
				array_push($languagesPresent,$langSlide->language);
			}
				
			for ($i = 0; $i < count($languages); $i++) {
				if ( in_array($languages[$i], $languagesPresent) ) {
					$data = array(
						'slideName' => $slideNames[$i],
						'slideDescription' => $slideDescriptions[$i],
					);
					$this->backmodel->u_LANG_Slide($idLangSlides[$i],'', $data, '');
				} else {
					$data = array(
						'idSlide' => $this->input->post('idSlide'),
						'language' => $languages[$i],
						'slideName' => $slideNames[$i],
						'slideDescription' => $slideDescriptions[$i],
					);
					$this->backmodel->c_LANG_Slide($data);
				}
			}
			
			if (!($_FILES['files']['size'] == 0)) {
				// Upload Path
				$config = array(
					'upload_path' => $this->config->item('upload_path').'/img/slides/',
					'allowed_types' => 'jpg|png',
				);
				$this->load->library('upload', $config);
				 	
				if ( ! $this->upload->do_upload('files')) {
				 	$this->session->set_flashdata('message', 'Seleziona almeno un file da caricare. I file devono essere in formato jpg.');
				 	redirect(site_url('back/slides'));
				} else {	
				 	$data = array();
				 	$file = $this->upload->data();
				 	
				 	$slide = $this->backmodel->r_STN_Slide($this->input->post('idSlide'));
				 	unlink($this->config->item('upload_path').'/img/slides/'.$slide->photoName);
						
					$this->load->library('image_lib');
	
					$this->resizeImg(1920,844,$file,$this->config->item('upload_path').'/img/slides/'.$file['file_name'],'banner');
						
				 	$record = array(
				 		'photoName' => $file['file_name'],
				 	);
				 	// Saving Product to Database
				 	$this->backmodel->u_STN_Slide( $this->input->post('idSlide'),$record);
				}
			}
		}	
	} 
	public function d_STN_Slide() {
		$idSlides = $this->input->post('idSlide');
		
		try{
			for ($i = 0; $i < count($idSlides); $i++) {
				$slide = $this->backmodel->r_STN_Slide($idSlides[$i]);
				if($slide) {
					unlink($this->config->item('upload_path').'/img/slides/'.$slide->photoName);
					$this->backmodel->d_STN_Slide($idSlides[$i]);
				}
				$this->backmodel->d_LANG_Slide('', $idSlides[$i], ''); 
				$this->backmodel->d_STN_Slide($idSlides[$i]); 
			}
		}catch (exception $exception) {				
				$response = (object)array(
					'error' => 'Errore 001INT - Cancellazione Incompleta',
					'message' => null
				);
				header('Content-Type: application/x-json; charset=utf-8');
				echo json_encode($response);
				exit();
		}
		$response = (object)array(
			'error' => null,
			'message' => 'Cancellazione Completata'
		);
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($response);
	} // CORRETTA
	public function dp_STN_Slides() {
		$idSlides = $this->input->post('idSlide');
		$slideNotCopied = false;
		
		foreach ($idSlides as $idSlide) {
			$originalSlide = $this->backmodel->r_STN_Slide($idSlide);
			$oldPhoto = explode(".", $originalSlide->photoName);
			
			$data = array(
				'photoName' => $oldPhoto[0].'_c_'.$oldPhoto[1]
			);
			$returnedID = $this->backmodel->c_STN_Slide($data);
			$data = array(
				'photoName' => $oldPhoto[0].'_c_'.$returnedID.'.'.$oldPhoto[1]
			);
			$this->backmodel->u_STN_Slide($returnedID,$data);
					
		    if (!copy($this->config->item('upload_path').'/img/slides/'.$originalSlide->photoName,$this->config->item('upload_path').'/img/slides/'.$oldPhoto[0].'_c_'.$returnedID.'.'.$oldPhoto[1])) {
		    	$slideNotCopied = true;
		    }
			
			$translations = $this->backmodel->ra_LANG_Slides('',$idSlide,'');
			if ($translations) {
				foreach ($translations as $translation) {
					unset($translation->idLangSlide);
					$translation->slideName = $translation->slideName.'_Copia';
					$translation->idSlide = $returnedID;
					$this->backmodel->c_LANG_Slide($translation);
				}	
			}
			
		}
	}
	// STN_Banners
	public function r_STN_Banner() {
		$data = $this->backmodel->r_STN_Banner($this->input->post('idBanner'));
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	public function u_STN_Banner() {
		//Form Validation
		$this->load->library('form_validation');
		$this->form_validation->set_rules('bannerName[]', 'Nome banner', 'trim|required');
		$this->form_validation->set_rules('bannerDescription[]', 'Descrizione banner', 'trim|required');
		$this->form_validation->set_rules('bannerURL', 'URL di destinazione', 'trim|required');
		$this->form_validation->set_message('required', 'Riempi il campo %s');
		//End Form Validation

		if ($this->form_validation->run() == FALSE) {
			$response = (object)array(
				'error' => 'Errore 001INT - Salvataggio Incompleto',
				'message' => null
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
			exit();
		} else {
			if ($_FILES['files']['size'] > 0) {
				// Upload Path
				$config = array(
					'upload_path' => $this->config->item('upload_path').'/img/banners/',
					'allowed_types' => 'jpg|png',
				);
				$this->load->library('upload', $config);
				
				print_r($config);
				 	
				if (!$this->upload->do_upload('files')) {
				 	$response = (object)array(
			 			'error' => 'Errore 002INT - Salvataggio Incompleto',
			 			'message' => null
			 		);
			 		header('Content-Type: application/x-json; charset=utf-8');
			 		echo json_encode($response);
			 		exit();
				} else {	
				 	$data = array();
				 	$file = $this->upload->data();
				 	try{
				 		$banner = $this->backmodel->r_STN_Banner($this->input->post('idBanner'));
				 		unlink($this->config->item('upload_path').'/img/banners/'.$banner->photoName);
				 		
	 		 			$this->resizeImg($banner->photoWidth,$banner->photoHeight,$file,$this->config->item('upload_path').'/img/banners/'.$file['file_name'],'banner');
	 	
	 			 		$record = array(
 			 				'photoName' => $file['file_name'],
 			 			);
	 			 		$this->backmodel->u_STN_Banner( $this->input->post('idBanner'),$record);
	 		 		}catch (exception $exception) {				
	 		 			$response = (object)array(
	 		 				'error' => 'Errore 003INT - Salvataggio Incompleto',
	 		 				'message' => null
	 		 			);
	 		 			header('Content-Type: application/x-json; charset=utf-8');
	 		 			echo json_encode($response);
	 		 			exit();
	 		 		}
				}
			}
					
			$bannerNames = $this->input->post('bannerName');
			$bannerDescriptions = $this->input->post('bannerDescription');
			$idLangBanners = $this->input->post('idLangBanner');
			
			$settings = $this->backmodel->r_STN_Settings();
			$languages = array_filter(explode( ',', $settings->shopLanguages ));
			array_unshift($languages, "it");
				
			$langBanners = $this->backmodel->ra_LANG_Banners('',$this->input->post('idBanner'),'');
			$languagesPresent = array();
			foreach ($langBanners as $langBanner) {
				array_push($languagesPresent,$langBanner->language);
			}
				
			for ($i = 0; $i < count($languages); $i++) {
				if ( in_array($languages[$i], $languagesPresent) ) {
					$data = array(
						'bannerName' => $bannerNames[$i],
						'bannerDescription' => $bannerDescriptions[$i],
					);
					$this->backmodel->u_LANG_Banner($idLangBanners[$i],'', $data,'');
				} else {
					$data = array(
						'idBanner' => $this->input->post('idBanner'),
						'language' => $languages[$i],
						'bannerName' => $bannerNames[$i],
						'bannerDescription' => $bannerDescriptions[$i],
					);
					$this->backmodel->c_LANG_Banner($data);
				}
			}
			$record = array(
				'bannerURL' => $this->input->post('bannerURL'),
			);
			$this->backmodel->u_STN_Banner( $this->input->post('idBanner'),$record);	
		}
	}
	// STN_Articles
	public function c_STN_Article() {
		//Form Validation
		$this->load->library('form_validation');
		$this->form_validation->set_rules('articleName', 'Titolo news', 'trim|required');
		$this->form_validation->set_rules('articleDescription', 'Descrizione news', 'trim|required');
		$this->form_validation->set_message('required', 'Riempi il campo %s');
		//End Form Validation

		if ($this->form_validation->run() == FALSE) {
			$response = (object)array(
				'error' => 'Errore 001INT - Salvataggio Incompleto',
				'message' => null
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
			exit();
		} else {
			try{
				$data = array(
					'idArticlesCategory' => $this->input->post('idArticlesCategory'),
			 		'createdOn' => date("Y-m-d")
			 	);
			 	$returnedID = $this->backmodel->c_STN_Article($data);
			}catch (exception $exception){
				$response = (object)array(
					'error' => 'Errore 002INT - Salvataggio Incompleto',
					'message' => null
				);
				header('Content-Type: application/x-json; charset=utf-8');
				echo json_encode($response);
				exit();
			}
			
			$settings = $this->backmodel->r_STN_Settings();
			$languages = array_filter(explode( ',', $settings->shopLanguages ));
			
			$UT_translator = $this->load->library('utilities/UT_translator',NULL,'UT_translator');
			$config['client_id'] = '[CLIENT_ID]';
			$config['client_secret'] = '[CLIENT_SECRET]';
			$this->UT_translator->initialize($config);
			
			try{	
				$this->UT_translator->setTokens();
				for ($i = 0; $i < count($languages); $i++) {				
					$data = array(
						'idArticle' => $returnedID,
						'language' => $languages[$i],
						'articleName' => $this->UT_translator->curlRequest($languages[$i], $languages[$i], $this->input->post('articleName')),
						'articleDescription' => $this->UT_translator->curlRequest($languages[$i], $languages[$i], $this->input->post('articleDescription'))
					);
					$this->backmodel->c_LANG_Article($data);
				}
				$data = array(
					'idArticle' => $returnedID,
					'language' => 'it',
					'articleName' => $this->input->post('articleName'),
					'articleDescription' => $this->input->post('articleDescription')
				);
				$this->backmodel->c_LANG_Article($data);
			}catch (exception $exception){
				$response = (object)array(
					'error' => 'Errore 003INT - Salvataggio Incompleto',
					'message' => null
				);
				header('Content-Type: application/x-json; charset=utf-8');
				echo json_encode($response);
				exit();
			}
		
			if( $this->input->post('filesNumber') > 1 ) {
				 $config = array(
				     'upload_path' => $this->config->item('upload_path').'/img/_tmp/',
				     'allowed_types' => 'jpg|png',
				     'multi' => 'ignore'
				 );
				 $this->load->library('upload', $config);
			 	
			 	if ( ! $this->upload->do_upload('files')) {
			 		$response = (object)array(
			 			'error' => 'Errore 004INT - Salvataggio Incompleto',
			 			'message' => null
			 		);
			 		header('Content-Type: application/x-json; charset=utf-8');
			 		echo json_encode($response);
			 		exit();
			 	} else {	
			 		$data = array();
			 		$files = $this->upload->data();
			 		
			 		try{
				 		$this->load->library('image_lib');
				 		for ($i = 0; $i < count($files); $i++) {
				 			$this->resizeImg(851,408,$files[$i],$this->config->item('upload_path').'/img/news/medium/'.$files[$i]['file_name'],'product');
				 			$this->resizeImg(485,253,$files[$i],$this->config->item('upload_path').'/img/news/small/'.$files[$i]['file_name'],'product');
				 			$this->resizeImg(70,70,$files[$i],$this->config->item('upload_path').'/img/news/extra_small/'.$files[$i]['file_name'],'product');
				 			
				 			if ($i == 0) {
				 				$record = array(
			 						'idArticle' => $returnedID,
			 						'photoName' => $files[$i]['file_name'],
			 						'isCover' => 1,
			 					);
				 			} else {
				 				$record = array(
			 						'idArticle' => $returnedID,
			 						'photoName' => $files[$i]['file_name'],
			 						'isCover' => 0,
			 					);
				 			}
				 			array_push($data, $record);
				 			$this->backmodel->c_STN_Photo($record);
				 		}
			 		}catch (exception $exception) {
			 			$response = (object)array(
		 					'error' => 'Errore 005INT - Salvataggio Incompleto',
		 					'message' => null
		 				);
		 				header('Content-Type: application/x-json; charset=utf-8');
		 				echo json_encode($response);
		 				exit();
			 		}
			 	}
			 }
			 if( $this->input->post('filesNumber') == 1 ) { 
				// Upload Path
			 	$config = array(
			 	    'upload_path' => $this->config->item('upload_path').'/img/_tmp/',
			 	    'allowed_types' => 'jpg|png',
			 	);
			 	$this->load->library('upload', $config);
			 	
			 	if ( ! $this->upload->do_upload('files')) {
			 		$response = (object)array(
			 			'error' => 'Errore 006INT - Salvataggio Incompleto',
			 			'message' => null
			 		);
			 		header('Content-Type: application/x-json; charset=utf-8');
			 		echo json_encode($response);
			 		exit();
			 	} else {	
			 		$data = array();
			 		$file = $this->upload->data();
			 		
			 		try{
				 		$this->resizeImg(851,408,$file,$this->config->item('upload_path').'/img/news/medium/'.$file['file_name'],'product');
				 		$this->resizeImg(485,253,$file,$this->config->item('upload_path').'/img/news/small/'.$file['file_name'],'product');
				 		$this->resizeImg(70,70,$file,$this->config->item('upload_path').'/img/news/extra_small/'.$file['file_name'],'product');
			
				 		$record = array(
				 			'idArticle' => $returnedID,
				 			'photoName' => $file['file_name'],
				 			'isCover' => 1,
				 		);
				 		$this->backmodel->c_STN_Photo($record);
			 		}catch (exception $exception) {
			 			$response = (object)array(
		 					'error' => 'Errore 007INT - Salvataggio Incompleto',
		 					'message' => null
		 				);
		 				header('Content-Type: application/x-json; charset=utf-8');
		 				echo json_encode($response);
		 				exit();
			 		}
			 	}
			}
			
			$response = (object)array(
				'error' => null,
				'message' => 'Salvataggio Completato'
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
		}	
	} // CORRETTA
	public function r_STN_Article() {
		$idArticle = $this->input->post('isArticle');
		$data = $this->backmodel->r_STN_Article($idArticle);
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}	
	public function u_STN_Article() {
		/* AGGIORNAMENTO TESTI */	
		$articleNames = $this->input->post('articleName');
		$articleDescriptions = $this->input->post('articleDescription');
		$idLangArticles = $this->input->post('idLangArticle');
		
		$settings = $this->backmodel->r_STN_Settings();
		$languages = array_filter(explode( ',', $settings->shopLanguages ));
		array_unshift($languages, "it");
			
		$langArticles = $this->backmodel->ra_LANG_Articles('',$this->input->post('idArticle'),'');
		$languagesPresent = array();
		foreach ($langArticles as $langArticle) {
			array_push($languagesPresent,$langArticle->language);
		}
			
		for ($i = 0; $i < count($languages); $i++) {
			if ( in_array($languages[$i], $languagesPresent) ) {
				$data = array(
					'articleName' => $articleNames[$i],
					'articleDescription' => $articleDescriptions[$i],
				);
				$this->backmodel->u_LANG_Article($idLangArticles[$i],'', $data,'');
			} else {
				$data = array(
					'idArticle' => $this->input->post('idArticle'),
					'language' => $languages[$i],
					'articleName' => $articleNames[$i],
					'articleDescription' => $articleDescriptions[$i],
				);
				$this->backmodel->c_LANG_Article($data);
			}
		}
		$data = array(
			'idArticlesCategory' => $this->input->post('idArticlesCategory'),
		);
		$returnedID = $this->backmodel->u_STN_Article($this->input->post('idArticle'), $data);
		
		/* AGGIORNAMENTO IMMAGINI */	
		$photos = $this->input->post('idPhoto');	
		if ($photos) {
			foreach ($photos as $photo) {
				$img = $this->backmodel->r_STN_Photo($photo,'');
				unlink($this->config->item('upload_path').'/img/news/medium/'.$img->photoName);
				unlink($this->config->item('upload_path').'/img/news/small/'.$img->photoName);
				unlink($this->config->item('upload_path').'/img/news/extra_small/'.$img->photoName);
				$this->backmodel->d_STN_Photo($photo);  
			}
		}
		if ($this->input->post('idPhotoCover')) {
			$photos = $this->backmodel->ra_STN_Photos('',$this->input->post('idArticle'));
			foreach ($photos as $photo) {
				$data = array(
					'isCover' => 0,
				);
				$this->backmodel->u_STN_Photo($photo->idPhoto,$data);
			}
			$data = array(
				'isCover' => 1,
			);
			$this->backmodel->u_STN_Photo($this->input->post('idPhotoCover'),$data);
		}
		if (!($_FILES['files']['size'] == 0)) {
			if( $this->input->post('filesNumber') > 1 ) {
				 $config = array(
				     'upload_path' => $this->config->item('upload_path').'/img/_tmp/',
				     'allowed_types' => 'jpg|png',
				     'multi' => 'ignore'
				 );
				 $this->load->library('upload', $config);
			 	
			 	if ( ! $this->upload->do_upload('files')) {
			 		$this->session->set_flashdata('message', 'Seleziona almeno un file da caricare. I file devono essere in formato jpg.');
			 		redirect(site_url('back/articles'));
			 	} else {	
			 		$data = array();
			 		$files = $this->upload->data();
			 		for ($i = 0; $i < count($files); $i++) {
			 			$this->resizeImg(851,408,$files[$i],$this->config->item('upload_path').'/img/news/medium/'.$files[$i]['file_name'],'banner');
			 			$this->resizeImg(485,253,$files[$i],$this->config->item('upload_path').'/img/news/small/'.$files[$i]['file_name'],'banner');
			 			$this->resizeImg(70,70,$files[$i],$this->config->item('upload_path').'/img/news/extra_small/'.$files[$i]['file_name'],'banner');
			 		
			 			$record = array(
			 				'idArticle' => $this->input->post('idArticle'),
			 				'photoName' => $files[$i]['file_name'],
			 				'isCover' => 0,
			 			);
			 			array_push($data, $record);
			 			// Saving Product to Database
			 			$this->backmodel->c_STN_Photo($record);
			 		}
			 	}
			 } else { 
				// Upload Path
			 	$config = array(
			 	    'upload_path' => $this->config->item('upload_path').'/img/tmp/',
			 	    'allowed_types' => 'jpg|png',
			 	);
			 	$this->load->library('upload', $config);
			 	
			 	if ( ! $this->upload->do_upload('files')) {
			 		$this->session->set_flashdata('message', 'Seleziona almeno un file da caricare. I file devono essere in formato jpg.');
			 		redirect(site_url('back/articles'));
			 	} else {	
			 		$data = array();
			 		$file = $this->upload->data();
			 		
			 		$this->resizeImg(851,408,$file,$this->config->item('upload_path').'/img/news/medium/'.$file['file_name'],'banner');
			 		$this->resizeImg(485,253,$file,$this->config->item('upload_path').'/img/news/small/'.$file['file_name'],'banner');
			 		$this->resizeImg(70,70,$file,$this->config->item('upload_path').'/img/news/extra_small/'.$file['file_name'],'banner');
					
			 		$record = array(
			 			'idArticle' => $this->input->post('idArticle'),
			 			'photoName' => $file['file_name'],
			 			'isCover' => 0,
			 		);
			 		// Saving Product to Database
			 		$this->backmodel->c_STN_Photo($record);
			 	}
			}
		}	
	}
	public function d_STN_Articles() {
		$idArticles = $this->input->post('idArticle');
		$isAllDeleted = true;
		
		try{
			for ($i = 0; $i < count($idArticles); $i++) {
				$photos = $this->backmodel->ra_STN_Photos('',$idArticles[$i]);
				if($photos) {
					foreach ($photos as $photo) {
						$img = $this->backmodel->r_STN_Photo($photo->idPhoto,'');
						unlink($this->config->item('upload_path').'/img/news/medium/'.$img->photoName);
						unlink($this->config->item('upload_path').'/img/news/small/'.$img->photoName);
						unlink($this->config->item('upload_path').'/img/news/extra_small/'.$img->photoName);
						$this->backmodel->d_STN_Photo($photo->idPhoto);
					}
				}
				$this->backmodel->d_LANG_Article('', $idArticles[$i], ''); 
				$this->backmodel->d_STN_Article($idArticles[$i]); 
			}
		}catch (exception $exception) {				
				$response = (object)array(
					'error' => 'Errore 001INT - Cancellazione Incompleta',
					'message' => null
				);
				header('Content-Type: application/x-json; charset=utf-8');
				echo json_encode($response);
				exit();
		}

		if (!$isAllDeleted) {
			$response = (object)array(
				'error' => null,
				'message' => 'Canellazione Incompleta <br> Alcuni valori non sono stati cancellati in quanto associati ad uno o più prodotti <br>'
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
		} else {
			$response = (object)array(
				'error' => null,
				'message' => 'Canellazione Completata'
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
		}
	} // CORRETTA
	public function s_STN_Articles() {
		$column = $this->input->post('column-search');
		$value = $this->input->post('value-search');
		$data = $this->backmodel->s_STN_Article($column, $value,'it');
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	public function dp_STN_Articles() {
		$idArticles = $this->input->post('idArticle');
		$articleNotCopied = false;
		
		foreach ($idArticles as $idArticle) {
			$originalArticle = $this->backmodel->r_STN_Article($idArticle);
			unset($originalArticle->idArticle);
			$originalArticle->createdOn = date('Y-m-d');
			$returnedID = $this->backmodel->c_STN_Article($originalArticle);
			
			$photos = $this->backmodel->ra_STN_Photos('', $idArticle);
			if ($photos) {
				foreach ($photos as $photo) {
					$oldPhoto = explode(".", $photo->photoName);
					
				    if (!copy($this->config->item('upload_path').'/img/news/extra_small/'.$photo->photoName,$this->config->item('upload_path').'/img/news/extra_small/'.$oldPhoto[0].'_c_'.$returnedID.'.'.$oldPhoto[1])) {
				    	$productNotCopied = true;
				    }
				    if (!copy($this->config->item('upload_path').'/img/news/small/'.$photo->photoName,$this->config->item('upload_path').'/img/news/small/'.$oldPhoto[0].'_c_'.$returnedID.'.'.$oldPhoto[1])) {
				    	$productNotCopied = true;
				    }
				    if (!copy($this->config->item('upload_path').'/img/news/medium/'.$photo->photoName,$this->config->item('upload_path').'/img/news/medium/'.$oldPhoto[0].'_c_'.$returnedID.'.'.$oldPhoto[1])) {
				    	$productNotCopied = true;
				    }
				
					unset($photo->idPhoto);
					$photo->photoName = $oldPhoto[0].'_c_'.$returnedID.'.'.$oldPhoto[1];
					$photo->idArticle = $returnedID;
					$this->backmodel->c_STN_Photo($photo);
				}
			}
			
			$translations = $this->backmodel->ra_LANG_Articles('',$idArticle,'');
			if ($translations) {
				foreach ($translations as $translation) {
					unset($translation->idLangArticle);
					$translation->articleName = $translation->articleName.'_Copia';
					$translation->idArticle = $returnedID;
					$this->backmodel->c_LANG_Article($translation);
				}	
			}
			
		}
	}
	// STN_Articles_Categories
	public function c_STN_Articles_Category() {
		//Form Validation
		$this->load->library('form_validation');
		$this->form_validation->set_rules('articlesCategoryName', 'Titolo', 'trim|required');
		$this->form_validation->set_rules('articlesCategoryDescription', 'Descrizione', 'trim|required');
		$this->form_validation->set_message('required', 'Riempi il campo %s');
		// End Form Validation
	
		if ($this->form_validation->run() == FALSE) {
			$response = (object)array(
				'error' => 'Errore 001INT - Salvataggio Incompleto',
				'message' => null
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
			exit();
		} else {
			try{
				$data = array(
					'createdOn' => date("Y-m-d")
				);
				$returnedID = $this->backmodel->c_STN_Articles_Categories($data);
				
				$data = array(
					'idArticlesCategory' => $returnedID,
					'language' => 'it',
					'articlesCategoryName' => $this->input->post('articlesCategoryName'),
					'articlesCategoryDescription' => $this->input->post('articlesCategoryDescription'),
				);
				$this->backmodel->c_LANG_Articles_Categories($data);
				
				$settings = $this->backmodel->r_STN_Settings();
				$languages = array_filter(explode( ',', $settings->shopLanguages ));
				
				foreach ($languages as $language) {
					$data = array(
						'idArticlesCategory' => $returnedID,
						'language' => $language,
						'articlesCategoryName' => $this->translate($this->input->post('articlesCategoryName'),$language),
						'articlesCategoryDescription' => $this->translate($this->input->post('articlesCategoryDescription'),$language),
					);
					$this->backmodel->c_LANG_Articles_Categories($data);
				}
			}catch (exception $exception) {				
				$response = (object)array(
					'error' => 'Errore 004INT - Salvataggio Incompleto',
					'message' => null
				);
				header('Content-Type: application/x-json; charset=utf-8');
				echo json_encode($response);
				exit();
			}
			
			$response = (object)array(
				'error' => null,
				'message' => 'Salvataggio Completato'
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
		}
	} // CORRETTA
	public function u_STN_Articles_Category() {
		$idLangArticlesCategory = $this->input->post('idLangArticlesCategory');
		$articlesCategoryName = $this->input->post('articlesCategoryName');
		$articlesCategoryDescription = $this->input->post('articlesCategoryDescription');
		
		try{
			for ($i = 0; $i < count($idLangArticlesCategory); $i++) {
				$data = array(
					'articlesCategoryName' => $articlesCategoryName[$i],
					'articlesCategoryDescription' => $articlesCategoryDescription[$i]
				);
				$this->backmodel->u_LANG_Articles_Category($idLangArticlesCategory[$i],'',$data,'');
			}
		}catch (exception $exception) {				
			$response = (object)array(
				'error' => 'Errore 004INT - Salvataggio Incompleto',
				'message' => null
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
			exit();
		}
		
		$response = (object)array(
			'error' => null,
			'message' => 'Salvataggio Completato'
		);
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($response);
	} // CORRETTA
	public function d_STN_Articles_Categories() {
		$idArticlesCategories = $this->input->post('idArticlesCategory');
		$isAllDeleted = true;
		
		try{
			for ($i = 0; $i < count($idArticlesCategories); $i++) {
				$articles = $this->backmodel->ra_STN_Articles('','','','',$idArticlesCategories[$i],'');
				if ($articles) {
					unset($idArticlesCategories[$i]);
					$isAllDeleted = true;
				} else {
					$this->backmodel->d_STN_Articles_Categories($idArticlesCategories[$i]); 
					$this->backmodel->d_LANG_Articles_Categories('',$idArticlesCategories[$i],'');
				}
			}
		}catch (exception $exception) {				
			$response = (object)array(
				'error' => 'Errore 001INT - Cancellazione Incompleta',
				'message' => null
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
			exit();
		}
		
		if (!$isAllDeleted) {
			$response = (object)array(
				'error' => null,
				'message' => 'Canellazione Incompleta <br> Alcuni categorie non sono stati cancellate in quanto correlate ad uno o più articoli <br>'
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
		} else {
			$response = (object)array(
				'error' => null,
				'message' => 'Canellazione Completata'
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
		}
	} // CORRETTA
	// STN_Page
	public function c_STN_Page() {
		//Form Validation
		$this->load->library('form_validation');
		// Rules
		$this->form_validation->set_rules('pageName', 'Titolo', 'trim|required');
		$this->form_validation->set_rules('pageDescription', 'Corpo della pagina', 'trim|required');
		// Messages
		$this->form_validation->set_message('required', 'Riempi il campo %s');
		// End Form Validation
	
		if ($this->form_validation->run() == FALSE) {
			// Validation Errors
			$this->session->set_flashdata('message', validation_errors());
		} else {
			// Prepairing the Data for the Product
			$data = array(
				'createdOn' => date("Y-m-d")
			);
			// Saving Product to Database
			$returnedID = $this->backmodel->c_STN_Page($data);
			
			$data = array(
				'idPage' => $returnedID,
				'language' => 'it',
				'pageName' => $this->input->post('pageName'),
				'pageDescription' => $this->input->post('pageDescription'),
			);
			// Saving manufacturer to Database
			$this->backmodel->c_LANG_Page($data);
			
			$settings = $this->backmodel->r_STN_Settings();
			$languages = array_filter(explode( ',', $settings->shopLanguages ));
			
			foreach ($languages as $language) {
				$data = array(
					'idPage' => $returnedID,
					'language' => $language,
					'pageName' => $this->translate($this->input->post('pageName'),$language),
					'pageDescription' => $this->translate($this->input->post('pageDescription'),$language),
				);
				$this->backmodel->c_LANG_Page($data);
			}
			$this->session->set_flashdata('message', 'Pagina inserito con successo');
			redirect(site_url('back/pages'));
		}
	}	
	public function u_STN_Page() {
		$idLangPages = $this->input->post('idLangPage');
		$newPageName = $this->input->post('pageName');
		$newPageDescription = $this->input->post('pageDescription');
		for ($i = 0; $i < count($idLangPages); $i++) {
			$data = array(
				'pageName' => $newPageName[$i],
				'pageDescription' => $newPageDescription[$i]
			);
			$this->backmodel->u_LANG_Page($idLangPages[$i],$data,'');
		}
		$this->session->set_flashdata('message', 'Pagina modificata con successo');
		redirect(site_url('back/pages'));
	}
	public function d_STN_Pages() {
		$message = 'Cancellazione Completata.';
		$idPages = $this->input->post('idPage');
		
		for ($i = 0; $i < count($idPages); $i++) {
			$this->backmodel->d_STN_Page($idPages[$i]);
			$this->backmodel->d_LANG_Page('', $idPages[$i], '');
		}

		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($message);	
	}
	public function s_STN_Pages() {
		$column = $this->input->post('column-search');
		$value = $this->input->post('value-search');
		$data = $this->backmodel->s_STN_Page($column, $value,'it');
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	public function dp_STN_Pages() {
		$idPages = $this->input->post('idPage');
		
		foreach ($idPages as $idPage) {
			$originalPage = $this->backmodel->r_STN_Article($idPage);
			unset($originalPage->idPage);
			$originalPage->createdOn = date('Y-m-d');
			$returnedID = $this->backmodel->c_STN_Page($originalPage);
			
			$translations = $this->backmodel->ra_LANG_Pages('',$idPage,'');
			if ($translations) {
				foreach ($translations as $translation) {
					unset($translation->idLangPage);
					$translation->pageName = $translation->pageName.'_Copia';
					$translation->idPage = $returnedID;
					$this->backmodel->c_LANG_Page($translation);
				}	
			}
			
		}
	}
	// STN_Photo
	public function r_STN_Photos_byArticle() {
		$idArticle = $this->input->post('idArticle');
		$data = $this->backmodel->ra_STN_Photos('',$idArticle);
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}	
	// PRD_Combinations
	public function c_PRD_Combination() {
		// Form Validation
		$this->load->library('form_validation');
		$this->form_validation->set_rules('idProduct', 'ID Prodotto', 'trim|required');
		$this->form_validation->set_message('required', 'Riempi il campo %s');
		//End Form Validation

		if ($this->form_validation->run() == FALSE) {
			$message = 'Errore 001INT - Salvataggio Incompletato';	
		} else {
			try {
				$values = $this->input->post('idValue');
				if($this->input->post('combinationQuantity')) {
					$data = array(
						'idProduct' => $this->input->post('idProduct'),
						'combinationQuantity' => $this->input->post('combinationQuantity')
					);
				} else {
					$data = array(
						'idProduct' => $this->input->post('idProduct'),
					);
				}
				$returnedID = $this->backmodel->c_PRD_Combinations($data);
				
				for ($i = 0; $i < count($values); $i++) {
					$data = array(
						'idCombination' => $returnedID,
						'idValue' => $values[$i]
					);
					$this->backmodel->c_PRD_Groups($data);
				}
			}catch(exception $exception){
				$message = 'Errore 002INT - Salvataggio Incompletato';
			};	
		}	
		$response = (object)array(
			'error' => null,
			'message' => 'Salvataggio Completato',
			'idCombination' => $returnedID
		);
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($response);
	} // CORRETTA
	public function r_PRD_Combinations_byProduct() {
		$idProduct = $this->input->post('idProduct');
		$data = $this->backmodel->r_PRD_Combinations('',$idProduct);
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	public function u_PRD_Combination() {	
		$idProduct = $this->input->post('idProduct');
		$idValue = $this->input->post('idValue');
		
		$combinations = $this->backmodel->r_PRD_Combinations('', $idProduct);
		if($combinations){
			for($i = 0; $i < count($combinations); $i++ ) {
				// CASO: Caratteristica (ProductQuantity == null)
				if(!$combinations[$i]->combinationQuantity){
					$groups = $this->backmodel->ra_PRD_Groups('',$combinations[$i]->idCombination,'','',1,1,'it');
					for($k = 0; $k < count($groups); $k++){
						if (in_array($groups[$k]->idValue, $idValue) == false) {
							$this->backmodel->d_PRD_Groups_byCombination($combinations[$i]->idCombination);
							$this->backmodel->d_PRD_Combinations($combinations[$i]->idCombination, '');
						}
					}
				}
			}
			for($k = 0; $k < count($idValue); $k++){
				$isPresent = false;
				for($i = 0; $i < count($combinations); $i++ ) {
					if(!$combinations[$i]->combinationQuantity){
						$groups = $this->backmodel->ra_PRD_Groups('',$combinations[$i]->idCombination,'','',1,1,'it');
						for($m = 0; $m < count($groups); $m++){
							if($groups[$m]->idValue == $idValue[$k]) {
								$isPresent = true;
								break;
							}
						}
					}
				}
				if($isPresent == false){
					$data = array(
						'idProduct' => $this->input->post('idProduct'),
					);
					$returnedID = $this->backmodel->c_PRD_Combinations($data);
					$data = array(
						'idCombination' => $returnedID,
						'idValue' => $idValue[$k]
					);
					$this->backmodel->c_PRD_Groups($data);
				}
			}
		}else{
			for($i = 0; $i < count($idValue); $i++){
				$data = array(
					'idProduct' => $this->input->post('idProduct'),
				);
				$returnedID = $this->backmodel->c_PRD_Combinations($data);
				$data = array(
					'idCombination' => $returnedID,
					'idValue' => $idValue[$i]
				);
				$this->backmodel->c_PRD_Groups($data);
			}
		}
			

		/*
		$data = array(
			'combinationQuantity' => $this->input->post('combinationQuantity')
		);
		$returnedID = $this->backmodel->u_PRD_Combination($this->input->post('idCombination'), $data);
		*/
		
		// $message = "Combinazione presente in uno o più oridni";
		// header('Content-Type: application/x-json; charset=utf-8');
		// echo json_encode($message);
	}
	public function d_PRD_Combination() {	
		$combination = $this->backmodel->ra_ORD_Orders_Products('', '', '', $this->input->post('idCombination'),'','','');
		if (!$combination) {
			$this->backmodel->d_PRD_Combinations($this->input->post('idCombination'),''); 
			$this->backmodel->d_PRD_Groups_byCombination($this->input->post('idCombination')); 
			
			$message = "Combinazione cancellata con successo";
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($message);
		} else { 
			$message = "Combinazione presente in uno o più oridni";
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($message);
		}
	}
	// PRD_Groups
	public function c_PRD_Group() {	
		//Form Validation
		$this->load->library('form_validation');		
		// Rules
		$this->form_validation->set_rules('idValue', 'ID Valore', 'trim|required');
		$this->form_validation->set_rules('idCombination', 'ID Combinazione', 'trim|required');
		// Messages
		$this->form_validation->set_message('required', 'Riempi il campo %s');
		//End Form Validation
		if ($this->form_validation->run() == FALSE) {
			// Validation Errors
			$this->session->set_flashdata('message', validation_errors());	
		} else {
			// Prepairing the Data for the Combination
			$data = array(
				'idValue' => $this->input->post('idValue'),
				'idCombination' => $this->input->post('idCombination')
			);
			//Saving Combination to Database
			$returnedID = $this->backmodel->c_PRD_Groups($data);	
		}	
	}	
	public function r_PRD_Groups() {
		$data = $this->backmodel->r_PRD_Groups('','',1,'it');
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	public function r_PRD_Groups_byCombinations() {
		$idCombination = $this->input->post('idCombinations');
		$data = array();
		foreach ($idCombination as $combination) {
			$object = (object) [
			    'idCombination' => $combination,
			    'groups' => array(),
			];
			$temp = $this->backmodel->ra_PRD_Groups('', $combination, '', '', 1, 1, 'it');
			$object->groups = $temp;
			array_push($data, $object);
		}
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	// PRD_Photo
	public function c_PRD_Photo() { 
		if( $this->input->post('filesNumber') > 1 ) {
			 $config = array(
			     'upload_path' => $this->config->item('upload_path').'/img/_tmp/',
			     'allowed_types' => 'jpg|png',
			     'multi' => 'ignore'
			 );
			 $this->load->library('upload', $config);
		 	
		 	if ( ! $this->upload->do_upload('files')) {
		 		$this->session->set_flashdata('message', 'Seleziona almeno un file da caricare. I file devono essere in formato jpg.');
		 	} else {	
		 		$data = array();
		 		$files = $this->upload->data();

		 		foreach ($files as $file) {
		 			$this->resizeImg(700,900,$file,$this->config->item('upload_path').'/img/products/large/'.$file['file_name'],'product');
		 			$this->resizeImg(280,357,$file,$this->config->item('upload_path').'/img/products/medium/'.$file['file_name'],'product');
					$this->resizeImg(100,132,$file,$this->config->item('upload_path').'/img/products/small/'.$file['file_name'],'product');
					$this->resizeImg(84,109,$file,$this->config->item('upload_path').'/img/products/extra_small/'.$file['file_name'],'product');
					$this->resizeImg(299,455,$file,$this->config->item('upload_path').'/img/products/special_sale/medium/'.$file['file_name'],'product');
					$this->resizeImg(270,355,$file,$this->config->item('upload_path').'/img/products/special_sale/small/'.$file['file_name'],'product');
		 		
		 			$record = array(
		 				'idProduct' => $this->input->post('idProduct'),
		 				'photoName' => $file['file_name'],
		 				'isCover' => $this->input->post('isCover'),
		 				'createdOn' => date("Y-m-d"),
		 			);
		 			array_push($data, $record);
		 			$this->backmodel->c_PRD_Photo($record);
		 		}
		 	}
		 } else { 
			// Upload Path
		 	$config = array(
		 	    'upload_path' => $this->config->item('upload_path').'/img/products/',
		 	    'allowed_types' => 'jpg|png',
		 	);
		 	$this->load->library('upload', $config);
		 	
		 	if ( ! $this->upload->do_upload('files')) {
		 		$this->session->set_flashdata('message', 'Seleziona almeno un file da caricare. I file devono essere in formato jpg.');
		 	} else {	
		 		$data = array();
		 		$file = $this->upload->data();
		 	
		 		$this->resizeImg(700,900,$file,$this->config->item('upload_path').'/img/products/large/'.$file['file_name'],'product');
		 		$this->resizeImg(280,357,$file,$this->config->item('upload_path').'/img/products/medium/'.$file['file_name'],'product');
		 		$this->resizeImg(100,132,$file,$this->config->item('upload_path').'/img/products/small/'.$file['file_name'],'product');
		 		$this->resizeImg(84,109,$file,$this->config->item('upload_path').'/img/products/extra_small/'.$file['file_name'],'product');
		 		$this->resizeImg(299,455,$file,$this->config->item('upload_path').'/img/products/special_sale/medium/'.$file['file_name'],'product');
		 		$this->resizeImg(270,355,$file,$this->config->item('upload_path').'/img/products/special_sale/small/'.$file['file_name'],'product');
	
		 		$record = array(
		 			'idProduct' => $this->input->post('idProduct'),
		 			'photoName' => $file['file_name'],
		 			'isCover' => $this->input->post('isCover'),
		 			'createdOn' => date("Y-m-d"),
		 		);
		 		// Saving Product to Database
		 		$this->backmodel->c_PRD_Photo($record);
		 	}
		}
		
		$photo = $this->backmodel->ra_PRD_Photos('',$this->input->post('idProduct'),1);
		if ($photo) {
			$this->backmodel->d_PRD_Photo($photo->idPhoto); 
		}
	}
	public function r_PRD_Photos_byProduct() {
		$idProduct = $this->input->post('idProduct');
		$data = $this->backmodel->ra_PRD_Photos('',$idProduct,'');
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}	
	public function u_PRD_Photo() {
		// CANCELLAZIONE FOTOGRAFIE
		$baseUrl = $_SERVER['HTTP_HOST'].'/resources/';
		try {
			$photosToDelete = $this->input->post('idPhoto');
			for ($i = 0; $i < count($photosToDelete); $i++) {
				$img = $this->backmodel->r_PRD_Photo($photosToDelete[$i],'','');
				// unlink('./resources/img/products/'.$img->photoName);
				unlink($this->config->item('upload_path').'/img/products/large/'.$img->photoName);
				unlink($this->config->item('upload_path').'/img/products/medium/'.$img->photoName);
				unlink($this->config->item('upload_path').'/img/products/small/'.$img->photoName);
				unlink($this->config->item('upload_path').'/img/products/extra_small/'.$img->photoName);
				unlink($this->config->item('upload_path').'/img/products/special_sale/medium/'.$img->photoName);
				unlink($this->config->item('upload_path').'/img/products/special_sale/small/'.$img->photoName);
				$this->backmodel->d_PRD_Photo($photosToDelete[$i]); 
			}
		} catch (exception $exception) {
			$response = (object)array(
				'error' => 'Errore 001INT - Salvataggio Incompleto',
				'message' => null,
				'imgToDelete' => null
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($response);
			exit();
		}
		
		// SETTAGGIO FOTOGRAFIE COVER
		try {
			$photos = $this->backmodel->ra_PRD_Photos('',$this->input->post('idProduct'),'');
			if ($this->input->post('idPhotoCover')) {
				$photoToModify = $this->backmodel->r_PRD_Photo($this->input->post('idPhotoCover'),'');
				for ($i = 0; $i < count($photos); $i++) {
					$data = array(
						'isCover' => 0,
					);
					$this->backmodel->u_PRD_Photo($photos[$i]->idPhoto,$data);
				}
				$data = array(
					'isCover' => 1,
				);
				$this->backmodel->u_PRD_Photo($this->input->post('idPhotoCover'),$data);
			} else {
				if (!($_FILES['files']['size'] == 0)) {
					for ($i = 0; $i < count($photos); $i++) {
						$data = array(
							'isCover' => 0,
						);
						$this->backmodel->u_PRD_Photo($photos[$i]->idPhoto,$data);
					}
				}
			}
			if (!$photos) {
				$data = array(
					'idProduct' => $this->input->post('idProduct'),
					'photoName' => 'default.jpg',
					'isCover' => 1,
					'createdOn' => date("Y-m-d"),
				);
				$this->backmodel->c_PRD_Photo($data);
			}
			if (!($_FILES['files']['size'] == 0)) {
				if( $this->input->post('filesNumber') > 1 ) {
					 $config = array(
					     'upload_path' => $this->config->item('upload_path').'/img/_tmp/',
					     'allowed_types' => 'jpg|png',
					     'multi' => 'ignore'
					 );
					 $this->load->library('upload', $config);
				 	
				 	if ( ! $this->upload->do_upload('files')) {
				 		$this->session->set_flashdata('message', 'Seleziona almeno un file da caricare. I file devono essere in formato jpg.');
				 	} else {	
				 		$data = array();
				 		$files = $this->upload->data();
				 		
				 		for ($i = 0; $i < count($files); $i++) {
				 			$this->resizeImg(700,900,$files[$i],$this->config->item('upload_path').'/img/products/large/'.$files[$i]['file_name'],'product');
				 			$this->resizeImg(280,357,$files[$i],$this->config->item('upload_path').'/img/products/medium/'.$files[$i]['file_name'],'product');
				 			$this->resizeImg(100,132,$files[$i],$this->config->item('upload_path').'/img/products/small/'.$files[$i]['file_name'],'product');
				 			$this->resizeImg(84,109,$files[$i],$this->config->item('upload_path').'/img/products/extra_small/'.$files[$i]['file_name'],'product');
				 			$this->resizeImg(299,455,$files[$i],$this->config->item('upload_path').'/img/products/special_sale/medium/'.$files[$i]['file_name'],'product');
				 			$this->resizeImg(270,355,$files[$i],$this->config->item('upload_path').'/img/products/special_sale/small/'.$files[$i]['file_name'],'product');
				 			
				 			if ($i == 0) {
				 				$record = array(
				 					'idProduct' => $this->input->post('idProduct'),
				 					'photoName' => $files[$i]['file_name'],
				 					'createdOn' => date("Y-m-d"),
				 					'isCover' => 1,
				 				);
				 			} else {
				 				$record = array(
				 					'idProduct' => $this->input->post('idProduct'),
				 					'photoName' => $files[$i]['file_name'],
				 					'createdOn' => date("Y-m-d"),
				 				);
				 			}
				 			array_push($data, $record);
				 			$this->backmodel->c_PRD_Photo($record);
				 			
				 			$defaultPhoto = $this->backmodel->ra_PRD_Photos('',$this->input->post('idProduct'),1);
				 			if ($defaultPhoto) {
				 				$this->backmodel->d_PRD_Photo($defaultPhoto[0]->idPhoto); 
				 			}
				 		}
				 	}
				 } else { 
					// Upload Path
				 	$config = array(
				 	    'upload_path' => $this->config->item('upload_path').'/img/products/',
				 	    'allowed_types' => 'jpg|png',
				 	);
				 	$this->load->library('upload', $config);
				 	
				 	if ( ! $this->upload->do_upload('files')) {
				 		$this->session->set_flashdata('message', 'Seleziona almeno un file da caricare. I file devono essere in formato jpg.');
				 	} else {	
				 		$data = array();
				 		$file = $this->upload->data();
				 		
				 		$this->resizeImg(700,900,$file,$this->config->item('upload_path').'/img/products/large/'.$file['file_name'],'product');
				 		$this->resizeImg(280,357,$file,$this->config->item('upload_path').'/img/products/medium/'.$file['file_name'],'product');
				 		$this->resizeImg(100,132,$file,$this->config->item('upload_path').'/img/products/small/'.$file['file_name'],'product');
				 		$this->resizeImg(84,109,$file,$this->config->item('upload_path').'/img/products/extra_small/'.$file['file_name'],'product');
				 		$this->resizeImg(299,455,$file,$this->config->item('upload_path').'/img/products/special_sale/medium/'.$file['file_name'],'product');
				 		$this->resizeImg(270,355,$file,$this->config->item('upload_path').'/img/products/special_sale/small/'.$file['file_name'],'product');
			
				 		$record = array(
				 			'idProduct' => $this->input->post('idProduct'),
				 			'photoName' => $file['file_name'],
				 			'createdOn' => date("Y-m-d"),
				 			'isCover' => 1,
				 		);
				 		// Saving Product to Database
				 		$this->backmodel->c_PRD_Photo($record);
				 		
				 		$defaultPhoto = $this->backmodel->ra_PRD_Photos('',$this->input->post('idProduct'),1);
				 		if ($defaultPhoto) {
				 			$this->backmodel->d_PRD_Photo($defaultPhoto[0]->idPhoto); 
				 		}
				 	}
				}
			}
		} catch (exception $exception) {
			$response = (object)array(
				'error' => 'Errore 002INT - Salvataggio Incompleto',
				'message' => null,
				'imgToDelete' => null
			);
		}

		$response = (object)array(
			'error' => null,
			'message' => 'Salvataggio Completato',
			'imgToDelete' => $photosToDelete
		);
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($response);
	} // CORRETTA
	// LANG
	public function r_LANG_Products() {
		$idProduct = $this->input->post('idProduct');
		$data = $this->backmodel->ra_LANG_Products($idProduct,'');
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	public function r_LANG_Banners() {
		$idBanner = $this->input->post('idBanner');
		$data = $this->backmodel->ra_LANG_Banners('',$idBanner,'');
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	public function r_LANG_Features() {
		$idFeature = $this->input->post('idFeature');
		$data = $this->backmodel->ra_LANG_Features('',$idFeature,'');
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	public function r_LANG_Value() {
		$idValue = $this->input->post('idValue');
		$data = $this->backmodel->ra_LANG_Values('',$idValue,'','');
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	public function r_LANG_Slides() {
		$idSlide = $this->input->post('idSlide');
		$data = $this->backmodel->ra_LANG_Slides('',$idSlide,'');
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	public function r_LANG_Articles() {
		$idArticle = $this->input->post('idArticle');
		$data = $this->backmodel->ra_LANG_Articles('',$idArticle,'');
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	public function r_LANG_Categories() {
		$idCategory = $this->input->post('idCategory');
		$data = $this->backmodel->ra_LANG_Categories('',$idCategory,'');
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	public function r_LANG_Articles_Categories() {
		$idArticlesCategory = $this->input->post('idArticlesCategory');
		$data = $this->backmodel->ra_LANG_Articles_Categories('',$idArticlesCategory,'');
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	public function r_LANG_Page() {
		$idPage = $this->input->post('idPage');
		$data = $this->backmodel->ra_LANG_Pages('',$idPage,'');
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	// ORD_Orders
	public function r_ORD_Order() {
		$idOrder = $this->input->post('idOrder');
		$data = $this->backmodel->r_ORD_Order($idOrder);
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	public function ra_ORD_Orders_byClient() {
		$idClient = $this->input->post('idClient');
		$data = $this->backmodel->ra_ORD_Orders('', '', '', $idClient);
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	// ORD_Orders_Products
	public function ra_ORD_Orders_Products() {
		$idOrder = $this->input->post('idOrder');
		$data = $this->backmodel->ra_ORD_Orders_Products('',1,1,'','',$idOrder,'it');
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	public function ra_ORD_Orders_Products_Array() {
		$idOrders = $this->input->post('idOrders');
		$data = array();
		for ($i = 0; $i < count($idOrders); $i++) {
			$tempProducts = $this->backmodel->ra_ORD_Orders_Products('',1,1,'','',$idOrders[$i],'it');
			$data = array_merge($tempProducts,$data);
		}
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	// ORD_Clients
	public function c_ORD_Client() {
		//Form Validation
		$this->load->library('form_validation');
		$this->form_validation->set_rules('clientName', 'Nome', 'trim|required');
		$this->form_validation->set_rules('clientSurname', 'Cognome', 'trim|required');
		$this->form_validation->set_rules('clientEmail', 'Email', 'trim|required');
		$this->form_validation->set_rules('clientPassword', 'Password', 'trim|required');
		$this->form_validation->set_rules('clientPhone', 'Telefono', 'trim|required');
		$this->form_validation->set_rules('clientAddress', 'Indirizzo', 'trim|required');
		$this->form_validation->set_rules('clientHouseNumber', 'Numero Civico', 'trim|required');
		$this->form_validation->set_rules('clientPostalCode', 'Codice postale', 'trim|required');
		$this->form_validation->set_rules('clientCity', 'Città', 'trim|required');
		$this->form_validation->set_rules('clientState', 'Provincia', 'trim|required');
		$this->form_validation->set_message('required', 'Riempi il campo %s');
		//End Form Validation
		if ($this->form_validation->run() == FALSE) {
			$message = 'Errore 001INT - Salvataggio Incompleto';
		} else {
			try{
				$data = array(
					'clientName' => strtolower($this->input->post('clientName')),
					'clientSurname' => strtolower($this->input->post('clientSurname')),
					'clientEmail' => $this->input->post('clientEmail'),
					'clientPassword' => sha1($this->input->post('clientPassword')),
					'clientPhone' => $this->input->post('clientPhone'),
					'clientAddress' => strtolower($this->input->post('clientAddress')),
					'clientHouseNumber' => $this->input->post('clientHouseNumber'),
					'clientPostalCode' => $this->input->post('clientPostalCode'),
					'clientCity' => $this->input->post('clientCity'),
					'clientState' => $this->input->post('clientState'),
					'idCountry' => $this->input->post('idCountry'),
					'createdOn' => date('Y-m-d')
				);
				$this->backmodel->c_ORD_Client($data);
			}catch (exception $exception) {				
				$response = (object)array(
					'error' => 'Errore 004INT - Salvataggio Incompleto',
					'message' => null
				);
				header('Content-Type: application/x-json; charset=utf-8');
				echo json_encode($response);
				exit();
			}
		}	
		$response = (object)array(
			'error' => null,
			'message' => 'Salvataggio Completato'
		);
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($response);		
	}
	public function r_ORD_Client() {
		$idClient = $this->input->post('idClient');
		$data = $this->backmodel->r_ORD_Client($idClient,1);
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	public function u_ORD_Client() {
		//Form Validation
		$this->load->library('form_validation');
		$this->form_validation->set_rules('clientName', 'Nome', 'trim|required');
		$this->form_validation->set_rules('clientSurname', 'Cognome', 'trim|required');
		$this->form_validation->set_rules('clientEmail', 'Email', 'trim|required');
		$this->form_validation->set_rules('clientPassword', 'Password', 'trim|required');
		$this->form_validation->set_rules('clientPhone', 'Telefono', 'trim|required');
		$this->form_validation->set_rules('clientAddress', 'Indirizzo', 'trim|required');
		$this->form_validation->set_rules('clientHouseNumber', 'Numero Civico', 'trim|required');
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
				'clientEmail' => $this->input->post('clientEmail'),
				'clientPassword' => sha1($this->input->post('clientPassword')),
				'clientPhone' => $this->input->post('clientPhone'),
				'clientAddress' => strtolower($this->input->post('clientAddress')),
				'clientHouseNumber' => $this->input->post('clientHouseNumber'),
				'clientPostalCode' => $this->input->post('clientPostalCode'),
				'clientCity' => $this->input->post('clientCity'),
				'clientState' => $this->input->post('clientState'),
				'idCountry' => $this->input->post('idCountry'),
			);
			$this->backmodel->u_ORD_Client($this->input->post('idClient'),$data);
		}		
	}	
	// CUR_Currencies
	public function r_STN_Currency() {
		$idCurrency = $this->input->post('idCurrency');
		$data = $this->backmodel->r_STN_Currency($idCurrency,'');
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	public function u_STN_Currency() {
		$message = 'Salvataggio Completato';
		$data = array(
			'currencyStatus' => $this->input->post('currencyStatus'),
		);
		$this->backmodel->u_STN_Currency($this->input->post('idCurrency'), $data);
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($message);
	}
	public function ua_STN_Currencies() {
		$message = 'Salvataggio Completato';
		$currencies = $this->backmodel->ra_STN_Currencies('');
		for ($i = 0; $i < count($currencies); $i++) {
			$newRate = $this->getCurrency($currencies[$i]->currencyCode);
		    $data = array(
		    	'currencyValue' => $newRate
		    );
		    $this->backmodel->u_STN_Currency($currencies[$i]->idCurrency,$data);
		}
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($message);
	}
	// STN_Settings
	public function u_STN_Settings() {
		$settings = $this->backmodel->r_STN_Settings();
		$oldLanguages = array_filter(explode( ',', $settings->shopLanguages ));
		array_unshift($oldLanguages, "it");
	
		$languages = $this->input->post('shopLanguages');
		
		$newLanguages = [];
		
		$shopLanguages = "";
		if($languages) {
			for ($i = 0; $i < count($languages); $i++) {
				if ( $i < count($languages)-1 ) {
					$shopLanguages = $shopLanguages.''.$languages[$i].',';
					array_push($newLanguages,$languages[$i]);
				} else {
					$shopLanguages = $shopLanguages.''.$languages[$i];
					array_push($newLanguages,$languages[$i]);
				}
			}
		}
		array_unshift($newLanguages, "it");
		
		$data = array(
			'shopName' => $this->input->post('shopName'),
			'shopAddress' => $this->input->post('shopAddress'),
			'shopPhone' => $this->input->post('shopPhone'),
			'shopEmail' => $this->input->post('shopEmail'),
			'shopLanguages' => $shopLanguages,
			'googleClientID' => $this->input->post('googleClientID'),
			'googleClientSecret' => $this->input->post('googleClientSecret'),
			'googleAuthURL' => $this->input->post('googleAuthURL')
		);
		$this->backmodel->u_STN_Settings(1, $data);
		$this->session->set_flashdata('message', 'Impostazioni modificate con successo');
		
		$languageToDelete = array_diff($oldLanguages, $newLanguages);
		$languageToCreate = array_diff($newLanguages, $oldLanguages);
		
		foreach ($languageToDelete as $language) {
			$this->backmodel->d_LANG_Category('',$language);
			$this->backmodel->d_LANG_Feature('',$language);
			$this->backmodel->d_LANG_Value('',$language);
			$this->backmodel->d_LANG_Product('','',$language);			
			$this->backmodel->d_LANG_Article('',$language);
			$this->backmodel->d_LANG_Articles_Category('',$language);
			$this->backmodel->d_LANG_Banner('',$language);
			$this->backmodel->d_LANG_Page('',$language);
			$this->backmodel->d_LANG_Slide('',$language);
		}
		
		$categories = $this->backmodel->ra_LANG_Categories('','','it');
		$features = $this->backmodel->ra_LANG_Features('','','it');
		$values = $this->backmodel->ra_LANG_Values('','','','it');
		$products = $this->backmodel->ra_LANG_Products('','','it');
		$articles = $this->backmodel->ra_LANG_Articles('','','it');
		$articles_categories = $this->backmodel->ra_LANG_Articles_Categories('','','it');
		$banners = $this->backmodel->ra_LANG_Banners('','','it');
		$pages = $this->backmodel->ra_LANG_Pages('','','it');
		$slides = $this->backmodel->ra_LANG_Slides('','','it');
		
		foreach ($languageToCreate as $language) {
			for ($i = 0; $i < count($categories); $i++) {
				$data = array(
					'idCategory' => $categories[$i]->idCategory,
					'language' => $language,
					'categoryName' => $this->translate($categories[$i]->categoryName,$language),
				);
				$this->backmodel->c_LANG_Category($data);
			}
			for ($i = 0; $i < count($features); $i++) {
				$data = array(
					'idFeature' => $features[$i]->idFeature,
					'language' => $language,
					'featureName' => $this->translate($features[$i]->featureName,$language),
				);
				$this->backmodel->c_LANG_Feature($data);
			}
			for ($i = 0; $i < count($values); $i++) {
				$data = array(
					'idValue' => $values[$i]->idValue,
					'language' => $language,
					'valueName' => $this->translate($values[$i]->valueName,$language),
				);
				$this->backmodel->c_LANG_Value($data);
			}
			for ($i = 0; $i < count($products); $i++) {
				$data = array(
					'idProduct' => $products[$i]->idProduct,
					'language' => $language,
					'productName' => $this->translate($products[$i]->productName,$language),
				);
				$this->backmodel->c_LANG_Product($data);
			}
			for ($i = 0; $i < count($articles); $i++) {
				$data = array(
					'idArticle' => $articles[$i]->idArticle,
					'language' => $language,
					'articleName' => $this->translate($articles[$i]->articleName,$language),
					'articleDescription' => $this->translate($articles[$i]->articleDescription,$language)
				);
				$this->backmodel->c_LANG_Article($data);
			}
			for ($i = 0; $i < count($articles_categories); $i++) {
				$data = array(
					'idArticlesCategory' => $articles_categories[$i]->idArticlesCategory,
					'language' => $language,
					'articlesCategoryName' => $this->translate($articles_categories[$i]->articlesCategoryName,$language),
				);
				$this->backmodel->c_LANG_Articles_Categories($data);
			}
			for ($i = 0; $i < count($banners); $i++) {
				$data = array(
					'idBanner' => $banners[$i]->idBanner,
					'language' => $language,
					'bannerName' => $this->translate($banners[$i]->bannerName,$language),
					'bannerDescription' => $this->translate($banners[$i]->bannerDescription,$language)
				);
				$this->backmodel->c_LANG_Banner($data);
			}
			for ($i = 0; $i < count($pages); $i++) {
				$data = array(
					'idPage' => $pages[$i]->idPage,
					'language' => $language,
					'pageName' => $this->translate($pages[$i]->pageName,$language),
					'pageDescription' => $this->translate($pages[$i]->pageDescription,$language),
				);
				$this->backmodel->c_LANG_Page($data);
			}
			for ($i = 0; $i < count($slides); $i++) {
				$data = array(
					'idSlide' => $slides[$i]->idSlide,
					'language' => $language,
					'slideName' => $this->translate($slides[$i]->slideName,$language),
					'slideDescription' => $this->translate($slides[$i]->slideDescription,$language)
				);
				$this->backmodel->c_LANG_Slide($data);
			}
		}
		redirect(site_url('back/settings'));
	}
	// STN_Tax
	public function c_STN_Tax() {
		//Form Validation
		$this->load->library('form_validation');
		$this->form_validation->set_rules('taxName', 'Nome tassazione', 'trim|required');
		$this->form_validation->set_rules('taxValue', 'Percentuale di tassazione', 'trim|required');
		$this->form_validation->set_message('required', 'Riempi il campo %s');
		//End Form Validation

		if ($this->form_validation->run() == FALSE) {
			$message = 'Errore 001INT - Salvataggio non Completato';
		} else {	
			$data = array(
				'taxName' => $this->input->post('taxName'),
				'taxDescription' => $this->input->post('taxDescription'),
				'taxValue' => $this->input->post('taxValue')
			);
			// Saving manufacturer to Database
			$this->backmodel->c_STN_Tax($data);
			$message = 'Creazione Completato';
		}	
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($message);
	}
	public function r_STN_Tax() {
		$idTax = $this->input->post('idTax');
		$data = $this->backmodel->r_STN_Tax('',$idTax);
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	public function u_STN_Tax() {
		//Form Validation
		$this->load->library('form_validation');
		$this->form_validation->set_rules('taxName', 'Nome tassazione', 'trim|required');
		$this->form_validation->set_rules('taxValue', 'Percentuale tassazione', 'trim|required');
		$this->form_validation->set_message('required', 'Riempi il campo %s');
		//End Form Validation

		if ($this->form_validation->run() == FALSE) {
			$message = 'Errore 001INT - Salvataggio non Completato';
		} else {	
			$data = array(
				'taxName' => $this->input->post('taxName'),
				'taxDescription' => $this->input->post('taxDescription'),
				'taxValue' => $this->input->post('taxValue')
			);
			$this->backmodel->u_STN_Tax($this->input->post('idTax'), $data);		
			$message = 'Salvataggio Completato';	
		}	
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($message);
	}
	public function d_STN_Tax() {
		$message = 'Cancellazione Completata';
		$idTax = $this->input->post('idTax');
		
		for ($i = 0; $i < count($idTax); $i++) {
			$this->backmodel->d_STN_tax($idTax[$i]);
		}
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($message);
	}
	// STN_Messages
	public function c_STN_Message() {
	    $email = $this->session->userdata('accessEmail');
	    $currentUser = $this->backmodel->r_LOG_Access($email);
	    
		$data = array(
			'idSender' => $currentUser->idAccess,
			'idReceiver' => 0,
			'messageText' => $_REQUEST["messageText"],
			'messageTime' => time(),
		);
	    $this->backmodel->c_STN_Message($data); 
	}
	public function ra_STN_Messages() {    
		$temp_messages = $this->backmodel->r_STN_Message('','');
		if (count($temp_messages) > 10) {
			$offset = count($temp_messages)-10;
		} else {
			$offset = '';
		}
		$messages = $this->backmodel->r_STN_Message_After($_REQUEST["messageTime"],$offset);
	    echo json_encode($messages);
	}
	// STN_Commitments
	public function c_STN_Commitment() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('commitmentText', 'Testo', 'trim|required');
		$this->form_validation->set_message('required', 'Riempi il campo %s');
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('message', validation_errors());	
		} else {
			$data = array(
				'commitmentText' => $this->input->post('commitmentText'),
				'createdOn' => date('Y-m-d')
			);
			$this->backmodel->c_STN_Commitment($data);	
		}	
	}	
	public function ra_STN_Commitments() {
		$data = $this->backmodel->ra_STN_Commitments('','');
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	public function u_STN_Commitment() {	
		$data = array(
			'commitmentStatus' => $this->input->post('commitmentStatus')
		);
		$this->backmodel->u_STN_Commitment($this->input->post('idCommitment'), $data);
	}
	public function d_STN_Commitment() {	
		$this->backmodel->d_STN_Commitment($this->input->post('idCommitment')); 
	}
	// EXPORT
	public function exportCSV() {
		$tableName = $this->input->post('tableName');
	    $this->load->dbutil();
	    $this->load->helper('download');
	    $report = $this->backmodel->export($tableName);
	    $csvFile = $this->dbutil->csv_from_result($report);;

	    $data = $csvFile;
	    $name = ucfirst($tableName).'_file.csv';
	    force_download($name, $data); 
    }
	public function exportXML() {
		$tableName = $this->input->post('tableName');
	    $this->load->dbutil();
	    $this->load->helper('download');
	    $report = $this->backmodel->export($tableName);
	    $xmlFile = $this->dbutil->xml_from_result($report);;

	    $data = $xmlFile;
	    $name = ucfirst($tableName).'_file.xml';
	    force_download($name, $data); 
    }
	// 404 NOT FOUND
	public function _remap($method) {
	    if (method_exists($this, $method))
	    {
	        $this->$method();
	        return;
	    } 
		redirect( site_url('back') );
	}
	// SORTING THE CATEGORIES
	public function getChild_categories($categories,$categoryToDelete) {
		$idParentCategories = array($categoryToDelete);

		foreach ($categories as $category) {
			if ( in_array($category->idParentCategory, $idParentCategories) ) {
				array_push($idParentCategories, $category->idCategory);
			}
		}
			
		return $idParentCategories;
	}
	public function getParent_categories($categories,$category) {
		$idCategories = array($category->idCategory);
		$currentIdParent = $category->idParentCategory;
		do {
			for ($i = 0; $i < count($categories); $i++) {
				if ($categories[$i]->idCategory == $currentIdParent) {
					$currentIdParent = $categories[$i]->idParentCategory;
					array_push($idCategories, $categories[$i]->idCategory);
				}
			}
		} while ($currentIdParent != null);
		return $idCategories;
	}
	// UPDATE CURRENCY
	public function getCurrency($currencyCode) {
		$amount = 1;
		$from = 'EUR';
		$to	= $currencyCode;
	
		$url = 'http://finance.yahoo.com/d/quotes.csv?e=.csv&f=sl1d1t1&s='. $from . $to .'=X';
		$handle = @fopen($url, 'r');
		if ($handle) {
			$result = fgets($handle, 4096);
		    fclose($handle);
		}
		 
		if(isset($result)){
			$allData = explode(',',$result); /* Get all the contents to an array */
		    $rate = $allData[1];
		} else {
			$rate = 0;
		}
		return $rate;
	}
	// LANGUAGES
	public function get_languages() {
		// Leggo le lingue disponibili e ci aggiungo l'italiano	
		$settings = $this->backmodel->r_STN_Settings();
		$languages = array_filter(explode( ',', $settings->shopLanguages ));
		array_unshift($languages, "it");
		return $languages;
	}
	// RESIZE
	public function resizeImg($maxPhotoWidth,$maxPhotoHeight,$sourceFile,$newImage,$resizeType) {
		$this->load->library('image_lib');
		
		switch ($resizeType) {
			case 'banner':
				// RESIZING
				if ( $sourceFile['image_width'] != $maxPhotoWidth || $sourceFile['image_height'] != $maxPhotoHeight ) {
				
					if (! file_exists('./resources/img/tmp')) { 
					    mkdir('./resources/img/tmp', 0777, true);
					}
					$manipulation_config = array(
						'image_library' => 'gd2',
						'source_image' => $sourceFile['file_path'].'/'.$sourceFile['file_name'],
						'new_image' => $newImage,
						'maintain_ratio' => TRUE,
						'width' => $maxPhotoWidth,
					);
					$this->image_lib->clear();
					$this->image_lib->initialize($manipulation_config);
					$this->image_lib->resize();
					
					$new_img = getimagesize( $newImage );
					if ( $new_img[1] > $maxPhotoHeight ) {
						$manipulation_config = array(
							'image_library' => 'gd2',
							'source_image' => $newImage,
							'maintain_ratio' => 'FALSE',
							'height' => $maxPhotoHeight,
						);
						$this->image_lib->clear();
						$this->image_lib->initialize($manipulation_config);
						$this->image_lib->crop();
					} else {
						$manipulation_config = array(
							'image_library' => 'gd2',
							// 'source_image' => $sourceImage,
							'source_image' => $newImage,
							'new_image' => $newImage,
							'maintain_ratio' => TRUE,
							'height' => $maxPhotoHeight,
						);
						$this->image_lib->clear();
						$this->image_lib->initialize($manipulation_config);
						$this->image_lib->resize();
						
						
						$blankImage = getimagesize( $newImage );
						$x = ($blankImage[0] - $maxPhotoWidth)/2;
						
						$manipulation_config = array(
							'image_library' => 'gd2',
							'source_image' => $newImage,
							'maintain_ratio' => false,
							'width' => $blankImage[0] - ($x*2),
							'x_axis' => $x
						);
						$this->image_lib->clear();
						$this->image_lib->initialize($manipulation_config);
						$this->image_lib->crop();
					}
				} else {
					copy($sourceFile['file_path'].'/'.$sourceFile['file_name'], $newImage);
				}
				
				// $files = glob('./resources/img/tmp/*'); // get all file names
				// foreach($files as $file){ // iterate files
				// 	if (is_file($file)) {
				//     	unlink($file); // delete file
				//     }
				// }
				// rmdir('./resources/img/tmp');
				// END RESIZING
				break;
			case 'product':
				// RESIZING
				if (! file_exists('./resources/img/tmp')) { 
				    mkdir('./resources/img/tmp', 0777, true);
				}
				if ( $sourceFile['image_width'] != $maxPhotoWidth || $sourceFile['image_height'] != $maxPhotoHeight ) {
					$manipulation_config = array(
						'image_library' => 'gd2',
						'source_image' => $sourceFile['file_path'].'/'.$sourceFile['file_name'],
						'new_image' => $newImage,
						'maintain_ratio' => true,
						// 280
						'width' => $maxPhotoWidth,
					);
					$this->image_lib->clear();
					$this->image_lib->initialize($manipulation_config);
					$this->image_lib->resize();
					
					$new_img = getimagesize( $newImage );
					// 184 > 357
					if ( $new_img[1] > $maxPhotoHeight ) {
						$manipulation_config = array(
							'image_library' => 'gd2',
							'source_image' => $newImage,
							'maintain_ratio' => false,
							'height' => $maxPhotoHeight,
						);
						$this->image_lib->clear();
						$this->image_lib->initialize($manipulation_config);
						$this->image_lib->crop();
					} else {
						$blankImage = getimagesize( $this->config->item('resources_url').'/img/blank.jpg');
						
						// 1280 > 1920
						if ($blankImage[1] > $blankImage[0]) {
							$manipulation_config = array(
								'image_library' => 'gd2',
								'source_image' => './resources/img/blank.jpg',
								'new_image' => './resources/img/tmp/blank.jpg',
								'maintain_ratio' => true,
								'width' => $maxPhotoWidth,
							);
							$this->image_lib->clear();
							$this->image_lib->initialize($manipulation_config);
							$this->image_lib->resize();
							
							$manipulation_config = array(
								'image_library' => 'gd2',
								'source_image' => './resources/img/tmp/blank.jpg',
								'maintain_ratio' => true,
								'y_axis' => $maxPhotoHeight,
							);
							$this->image_lib->clear();
							$this->image_lib->initialize($manipulation_config);
							$this->image_lib->crop();
						} else {
							$manipulation_config = array(
								'image_library' => 'gd2',
								'source_image' => './resources/img/blank.jpg',
								'new_image' => './resources/img/tmp/blank.jpg',
								'maintain_ratio' => true,
								'height' => $maxPhotoHeight,
							);

							$this->image_lib->clear();
							$this->image_lib->initialize($manipulation_config);
							$this->image_lib->resize();
							
							$blankImage = getimagesize( $this->config->item('resources_url').'/img/tmp/blank.jpg');
							$x = ($blankImage[0] - $maxPhotoWidth)/2;

							$manipulation_config = array(
								'image_library' => 'gd2',
								'source_image' => './resources/img/tmp/blank.jpg',
								'new_image' => './resources/img/tmp/blank.jpg',
								'maintain_ratio' => false,
								'width' => $blankImage[0] - ($x*2),
								'x_axis' => $x
							);
							$this->image_lib->clear();
							$this->image_lib->initialize($manipulation_config);
							$this->image_lib->crop();
						}
					
						$manipulation_config = array(
							'image_library' => 'gd2',
							'source_image' => './resources/img/tmp/blank.jpg', 
							'wm_overlay_path' => $newImage, //the overlay image,
							'new_image' => $newImage,
							'wm_type' => 'overlay',
							'wm_opacity' => 100,
							'wm_vrt_alignment' => 'middle',
							'wm_hor_alignment' => 'center',
						);
						$this->image_lib->clear();
						$this->image_lib->initialize($manipulation_config);
						$this->image_lib->watermark();
					}
				} else {
					copy($sourceFile['file_path'].'/'.$sourceFile['file_name'], $newImage);
				}
				
				// $files = glob('./resources/img/tmp/*');
				// foreach($files as $file){ 
				// 	if (is_file($file)) {
				//     	unlink($file); 
				//     }
				// }
				// rmdir('./resources/img/tmp');
				// END RESIZING
				break;
		}
	}
	
	// Ebay Utility
	public function setHeadersEbay($verb) {
		$settings = $this->backmodel->r_STN_Ebay();
		$headers = array (
			'X-EBAY-API-COMPATIBILITY-LEVEL: '.$settings->compatibilityLevel,
			'X-EBAY-API-DEV-NAME: '.$settings->devId,
			'X-EBAY-API-APP-NAME: '.$settings->appId,
			'X-EBAY-API-CERT-NAME: '.$settings->certId,		
			'X-EBAY-API-SITEID: '.$settings->siteId,
			'X-EBAY-API-CALL-NAME: '.$verb
		);
		return $headers;
	}
	public function cURL_Request($serverUrl,$headers,$requestXmlBody) {
		$connection = curl_init();
		curl_setopt($connection, CURLOPT_URL, $serverUrl);
		curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($connection, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($connection, CURLOPT_POST, 1); 
		curl_setopt($connection, CURLOPT_POSTFIELDS, $requestXmlBody);
		curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1); 
		$responseXml = curl_exec($connection);  
		curl_close($connection); 
		return $responseXml;
	}
	public function getSessionID() {
		$verb = 'GetSessionID';
		$headers = $this->setHeadersEbay($verb);
		$settings = $this->backmodel->r_STN_Ebay();
    
		$requestXmlBody = '<?xml version="1.0" encoding="utf-8"?>';
    	$requestXmlBody .= '<GetSessionIDRequest xmlns="urn:ebay:apis:eBLBaseComponents">';	
    	$requestXmlBody .= "<RuName>$settings->ruName</RuName>";
    	$requestXmlBody .= '</GetSessionIDRequest>';
    	
    	$responseXml = $this->cURL_Request($settings->serverUrl,$headers,$requestXmlBody);

		if (stristr($responseXml, 'HTTP 404') || $responseXml == '') {
			die("<p>Errore 001 - Errore durante l'invio della richiesta</p>");
		}
		$responseDoc = new DomDocument();
		$responseDoc->loadXML($responseXml);
		$errors = $responseDoc->getElementsByTagName('Errors');
		if($errors->length > 0) {
			$message = 'Errore 002 - Operazione non completata a causa dei seguenti errori:<br/>';
			$code     = $errors->item(0)->getElementsByTagName('ErrorCode');
			$shortMsg = $errors->item(0)->getElementsByTagName('ShortMessage');
			$longMsg  = $errors->item(0)->getElementsByTagName('LongMessage');
			$message .= '- Errore '.$code->item(0)->nodeValue.': ';
			if(count($longMsg) > 0) {
				$message .= $longMsg->item(0)->nodeValue.'<br/>';
			} else {
				$message .= $shortMsg->item(0)->nodeValue.'<br/>';
			}
			$data = array(
				'error' => $message
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($data);
		} else { 		
			$session = $responseDoc->getElementsByTagName("SessionID")->item(0)->nodeValue;
			$this->u_STN_Ebay_SessionId($session);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($session);
		}	
	}
	public function getToken() {
		$verb = 'FetchToken';
		$headers = $this->setHeadersEbay($verb);
		$settings = $this->backmodel->r_STN_Ebay();
		
		$requestXmlBody = '<?xml version="1.0" encoding="utf-8"?>';
		$requestXmlBody .= '<FetchTokenRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
    	$requestXmlBody .= "<SessionID>$settings->sessionId</SessionID>";
    	$requestXmlBody .= '<ErrorLanguage>it_IT</ErrorLanguage>';
    	$requestXmlBody .= '</FetchTokenRequest>';
    	
    	$responseXml = $this->cURL_Request($settings->serverUrl,$headers,$requestXmlBody);

		if (stristr($responseXml, 'HTTP 404') || $responseXml == '') {
			die("<p>Errore 001 - Errore durante l'invio della richiesta</p>");
		}
		$responseDoc = new DomDocument();
		$responseDoc->loadXML($responseXml);
		$errors = $responseDoc->getElementsByTagName('Errors');	
		if($errors->length > 0) {
			$message = 'Errore 002 - Operazione non completata a causa dei seguenti errori:<br/>';
			$code     = $errors->item(0)->getElementsByTagName('ErrorCode');
			$shortMsg = $errors->item(0)->getElementsByTagName('ShortMessage');
			$longMsg  = $errors->item(0)->getElementsByTagName('LongMessage');
			$message .= '- Errore '.$code->item(0)->nodeValue.': ';
			if(count($longMsg) > 0) {
				$message .= $longMsg->item(0)->nodeValue.'<br/>';
			} else {
				$message .= $shortMsg->item(0)->nodeValue.'<br/>';
			}
			$data = array(
				'error' => $message
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($data);
		} else { 		
			$token = $responseDoc->getElementsByTagName("eBayAuthToken")->item(0)->nodeValue;
			$this->u_STN_Ebay_Token($token);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($token);
    	} 
	}
	public function getUser() {
		$verb = 'GetUser';
		$headers = $this->setHeadersEbay($verb);
		$settings = $this->backmodel->r_STN_Ebay();
		
		$userId = $this->input->post('userId');
		$itemId = $this->input->post('itemId');
    
		$requestXmlBody = '<?xml version="1.0" encoding="utf-8"?>';
		$requestXmlBody .= '<GetUserRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
		$requestXmlBody .= '<RequesterCredentials>';
		$requestXmlBody .= "<eBayAuthToken>$settings->accessToken</eBayAuthToken>";
		$requestXmlBody .= '</RequesterCredentials>';
		$requestXmlBody .= '<IncludeFeatureEligibility>True</IncludeFeatureEligibility>';
		if ($itemId) {
			$requestXmlBody .= "<ItemID>$itemId</ItemID>";
		}
		if ($userId) {
			$requestXmlBody .= "<UserID>$userId</UserID>";
		}
		$requestXmlBody .= '<DetailLevel>ReturnAll</DetailLevel>';
		$requestXmlBody .= '<ErrorLanguage>it_IT</ErrorLanguage>';
		$requestXmlBody .= '</GetUserRequest>';
    	
    	$responseXml = $this->cURL_Request($settings->serverUrl,$headers,$requestXmlBody);

		if (stristr($responseXml, 'HTTP 404') || $responseXml == '') {
			die("<p>Errore 001 - Errore durante l'invio della richiesta</p>");
		}
		$responseDoc = new DomDocument();
		$responseDoc->loadXML($responseXml);
		$errors = $responseDoc->getElementsByTagName('Errors');
		if($errors->length > 0) {
			$message = 'Errore 002 - Operazione non completata a causa dei seguenti errori:<br/>';
			$code     = $errors->item(0)->getElementsByTagName('ErrorCode');
			$shortMsg = $errors->item(0)->getElementsByTagName('ShortMessage');
			$longMsg  = $errors->item(0)->getElementsByTagName('LongMessage');
			$message .= '- Errore '.$code->item(0)->nodeValue.': ';
			if(count($longMsg) > 0) {
				$message .= $longMsg->item(0)->nodeValue.'<br/>';
			} else {
				$message .= $shortMsg->item(0)->nodeValue.'<br/>';
			}
			
			$data = array(
				'error' => $message
			);
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($data);
		} else { 
			if ($userId) {
				$this->u_STN_Ebay_UserId($userId);
			}
			$xml = simplexml_load_string($responseXml);		
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($xml);
    	} 
	}
	public function getStore() 
		$verb = 'GetStore';
		$headers = $this->setHeadersEbay($verb);
		$settings = $this->backmodel->r_STN_Ebay();
		
		$storeName = $this->input->post('storeName');
    
		$requestXmlBody = '<?xml version="1.0" encoding="utf-8"?>';
    	$requestXmlBody .= '<GetStoreRequest xmlns="urn:ebay:apis:eBLBaseComponents">';	
    	$requestXmlBody .= '<RequesterCredentials>';
    	$requestXmlBody .= "<eBayAuthToken>$settings->accessToken</eBayAuthToken>";
    	$requestXmlBody .= '</RequesterCredentials>';
    	$requestXmlBody .= '<CategoryStructureOnly>False</CategoryStructureOnly>';

    	$requestXmlBody .= '</GetStoreRequest>';
    	
    	$responseXml = $this->cURL_Request($settings->serverUrl,$headers,$requestXmlBody);

		if(stristr($responseXml, 'HTTP 404') || $responseXml == '') {
			die('<p>Error sending request');
		}
		$responseDoc = new DomDocument();
		$responseDoc->loadXML($responseXml);
		$errors = $responseDoc->getElementsByTagName('Errors');
		if($errors->length > 0)
		{
			echo '<P><B>eBay returned the following error(s):</B>';
			$code     = $errors->item(0)->getElementsByTagName('ErrorCode');
			$shortMsg = $errors->item(0)->getElementsByTagName('ShortMessage');
			$longMsg  = $errors->item(0)->getElementsByTagName('LongMessage');
			echo '<P>', $code->item(0)->nodeValue, ' : ', str_replace(">", "&gt;", str_replace("<", "&lt;", $shortMsg->item(0)->nodeValue));
			if(count($longMsg) > 0) {
				echo '<BR>', str_replace(">", "&gt;", str_replace("<", "&lt;", $longMsg->item(0)->nodeValue));
			}
		} else { 
			$data = $responseDoc;
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($data);
    	} 
	}
	public function getCategories() {
		$verb = 'GetCategories';
		$headers = $this->setHeadersEbay($verb);
		$settings = $this->backmodel->r_STN_Ebay();
		
		$parentID = $this->input->post('parentCat');
		$levelCategories = $this->input->post('levelCategories');
		
		$requestXmlBody = '<?xml version="1.0" encoding="utf-8"?>';
		$requestXmlBody .= '<GetCategoriesRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
		$requestXmlBody .= '<RequesterCredentials>';
		$requestXmlBody .= "<eBayAuthToken>$settings->accessToken</eBayAuthToken>";
		$requestXmlBody .= '</RequesterCredentials>';
		if($parentID) {
			$requestXmlBody .= "<CategoryParent>$parentID</CategoryParent>";
		}	
		if($levelCategories) {
			$requestXmlBody .= "<LevelLimit>$levelCategories</LevelLimit>";
		} else {
			$requestXmlBody .= '<LevelLimit>1</LevelLimit>';
		}
		$requestXmlBody .= '<DetailLevel>ReturnAll</DetailLevel>';
		$requestXmlBody .= '<ErrorLanguage>it_IT</ErrorLanguage>';
		$requestXmlBody .= '</GetCategoriesRequest>';
		
		$responseXml = $this->cURL_Request($settings->serverUrl,$headers,$requestXmlBody);
	
		if (stristr($responseXml, 'HTTP 404') || $responseXml == '') {
			die("<p>Errore 001 - Errore durante l'invio della richiesta</p>");
		}
		$responseDoc = new DomDocument();
		$responseDoc->loadXML($responseXml);
		$errors = $responseDoc->getElementsByTagName('Errors');
		if($errors->length > 0) {
			$message = 'Errore 002 - Operazione non completata a causa dei seguenti errori:<br/>';
			$code     = $errors->item(0)->getElementsByTagName('ErrorCode');
			$shortMsg = $errors->item(0)->getElementsByTagName('ShortMessage');
			$longMsg  = $errors->item(0)->getElementsByTagName('LongMessage');
			$message .= '- Errore '.$code->item(0)->nodeValue.': ';
			if(count($longMsg) > 0) {
				$message .= $longMsg->item(0)->nodeValue.'<br/>';
			} else {
				$message .= $shortMsg->item(0)->nodeValue.'<br/>';
			}
			
			$data = array(
				'error' => $message
			);
			if($levelCategories) {
				header('Content-Type: application/x-json; charset=utf-8');
				echo( json_encode($data) );
			} else {
				return $data;
			}
		} else {     		
			$categories = $responseDoc->getElementsByTagName('Category');    		
			foreach($categories as $cat) {
				$catName = $cat->getElementsByTagName('CategoryName');
				$catId = $cat->getElementsByTagName('CategoryID');
				$catLevel = $cat->getElementsByTagName('CategoryLevel');
				$data[] = (object) array(
					'idEbayCategory' => $catId->item(0)->nodeValue,
					'name' => $catName->item(0)->nodeValue,
					'level' => $catLevel->item(0)->nodeValue
				);
			}	
			if($levelCategories) {
				header('Content-Type: application/x-json; charset=utf-8');
				echo( json_encode($data) );
			} else {
				return $data;
			}
		}
	}
	
	// STN_Ebay
	public function r_STN_Ebay() {
		$data = $this->backmodel->r_STN_Ebay();
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	public function u_STN_Ebay() {
		// Prepairing the Data for the Product
		$data = array(
			'siteId' => $this->input->post('siteId'),
			'dispatchTimeMax' => $this->input->post('dispatchTimeMax'),
			'listingDuration' => $this->input->post('listingDuration'),
			'returnsOptions' => $this->input->post('returnsOptions'),
			'returnsDescription' => $this->input->post('returnsDescription'),
			'returnsWithinOptions' => $this->input->post('returnsWithinOptions'),
			'shippingCostPaidByOption' => $this->input->post('shippingCostPaidByOption'),
			'paymentsDescription' => $this->input->post('paymentsDescription'),
			'postalTransfert' => $this->input->post('postalTransfert'),
			'personalCheck' => $this->input->post('personalCheck'),
			'creditCard' => $this->input->post('creditCard'),
			'paypalEmail' => $this->input->post('paypalEmail'),
			'paymentsDescription' => $this->input->post('paymentsDescription'),
			'warrantyOfferedOption' => $this->input->post('warrantyOfferedOption'),
			'warrantyDurationOption' => $this->input->post('warrantyDurationOption'),
			'warrantyTypeOption' => $this->input->post('warrantyTypeOption')	
		);
		
		$this->backmodel->u_STN_Ebay($data);
	}
	public function u_STN_Ebay_SessionId($sessionId) {
		$data = array(
			'sessionId' => $sessionId,
		);
		$this->backmodel->u_STN_Ebay($data);
	}
	public function u_STN_Ebay_Token($token) {
		$data = array(
			'accessToken' => $token,
		);
		$this->backmodel->u_STN_Ebay($data);
	}
	public function u_STN_Ebay_UserId($userId) {
		$data = array(
			'userId' => $userId,
		);
		$this->backmodel->u_STN_Ebay($data);
	}
	// STN_Ebay_Category
	public function u_STN_Ebay_Category() {
		$idCategories = $this->input->post('idCategory');
		$idEbayCategories = $this->input->post('idEbayCategory');
		
		$notUpdated = array();
		$message = '';
		for ($i = 0; $i < count($idCategories); $i++) {
			$isPresent = $this->backmodel->ra_STN_Ebay_Category($idCategories[$i]);
			if ($idEbayCategories && $idEbayCategories[$i] != null) {
				if ($isPresent) {
					$data = array(
						'idEbayCategory' => $idEbayCategories[$i]
					);
					$this->backmodel->u_STN_Ebay_Category($idCategories[$i],$data);
				} else {
					$data = array(
						'idCategory' => $idCategories[$i],
						'idEbayCategory' => $idEbayCategories[$i]
					);
					$this->backmodel->c_STN_Ebay_Category($data);
				}
			} else {
				$category = $this->backmodel->ra_LANG_Categories('',$idCategories[$i],'it');
				array_push($notUpdated, $category[0]->categoryName);
			}
		}
		if ($notUpdated) {
			$message = array(
				'error' => 'Salvataggio Incompleto<br> Le seguenti categorie non sono state salvate in quanto la categoria di eBay non era corretta: <br>'
			);
			for ($i = 0; $i < count($notUpdated); $i++) {
				$message['error'] .= '- '.$notUpdated[$i].'<br>';
			}		
		} else {
			$message = 'Salvataggio completato';
		}
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($message);
	}

	public function getFeatures($product) {
		$combinations = $this->backmodel->r_PRD_Combinations('', $product->idProduct);
		$features = array();
		for ($k = 0; $k < count($combinations); $k++) {
			if($combinations[$k]->combinationQuantity == null) {
				// Caratteristiche
				$features = $this->backmodel->ra_PRD_Groups('', $combinations[$k]->idCombination, '','',  1, 1, 'it');
			}
		}			
		return $features;
	}
	public function getAttribute($product) {
		$combinations = $this->backmodel->r_PRD_Combinations('', $product->idProduct);
		$attributes = array();
		for ($k = 0; $k < count($combinations); $k++) {
			if($combinations[$k]->combinationQuantity != null) {
				// Attributi
				$groups = $this->backmodel->ra_PRD_Groups('', $combinations[$k]->idCombination, '','',  1, 1, 'it');
				$_tempAttributes = array();
				for ($j = 0; $j < count($groups); $j++) {
					$object = (object) [
					    'idValue' => $groups[$j]->idValue,
					    'idFeature' => $groups[$j]->idFeature,
					    'valueName' => $groups[$j]->valueName,
					    'featureName' => $groups[$j]->featureName
					];
					array_push($_tempAttributes , $object);
				}
				$object = (object) [
					'idCombination' => $combinations[$k]->idCombination,
				    'featureName' => "Quantità",
				    'valueName' => $combinations[$k]->combinationQuantity
				];
				array_push($_tempAttributes , $object);
				array_push($attributes, $_tempAttributes);
			} 
		}			
		return $attributes;
	}
	public function orderAttributes($attributes) {
		$orderedAttributes = array();
		for ($k = 0; $k < count($attributes[0])-1; $k++) {
			// Prima combinazione
			$object = (object) [
				'idFeature' => $attributes[0][$k]->idFeature,
				'featureName' => $attributes[0][$k]->featureName,
				'values' => array()
			];
			for ($j = 0; $j < count($attributes); $j++) {
				// Combinazioni
				for ($l = 0; $l < count($attributes[$j])-1; $l++) {
					// Valori della singola combinazione
					if ($attributes[$j][$l]->idFeature == $object->idFeature) {
						array_push($object->values, $attributes[$j][$l]);
					}
				}
			}
			array_push($orderedAttributes, $object);
		}
		return $orderedAttributes;
	}
	
	public function postProducts() {
		$verb = 'AddFixedPriceItem';
		$headers = $this->setHeadersEbay($verb);
		$settings = $this->backmodel->r_STN_Ebay();
		
		$products = $this->backmodel->ra_PRD_Products_Ebay('it');
		
		$this->ua_CUR_Currencies();
		$siteId = $this->backmodel->r_STN_Ebay_SiteId($settings->siteId);
		$currency = $this->backmodel->r_STN_Currency('',$siteId->currencyCode);
    	
    	if ($products) {
    		foreach ($products as $product) {
		    	$requestXmlBody ='<?xml version="1.0" encoding="utf-8"?>';
		    	$requestXmlBody .= '<AddFixedPriceItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
		    	$requestXmlBody .= '<ErrorLanguage>it_IT</ErrorLanguage>';
		    	$requestXmlBody .= '<WarningLevel>High</WarningLevel>';
		    	$requestXmlBody .= '<RequesterCredentials>';
		    	$requestXmlBody .= "<eBayAuthToken>$settings->accessToken</eBayAuthToken>";
		    	$requestXmlBody .= '</RequesterCredentials>';
    	
    	  		$requestXmlBody .= '<Item>';
    	  		$requestXmlBody .= "<Title>$product->productName</Title>";
    	  		$requestXmlBody .= '<ConditionID>1000</ConditionID>';
    	  		$requestXmlBody .= "<Description>$product->productDescription</Description>";
    	  		$requestXmlBody .= '<PrimaryCategory>';
    	  		$requestXmlBody .= "<CategoryID>$product->idEbayCategory</CategoryID>";
    	  		$requestXmlBody .= '</PrimaryCategory>';
    	  		// Specifiche per tutte le variazioni dell'oggetto
    	  		$requestXmlBody .= '<ItemSpecifics>';
    	  		$features = $this->getFeatures($product);
    	  		for ($k = 0; $k < count($features); $k++) {
    	  			$requestXmlBody = '<NameValueList>';
    	  			$_temp = $features[$k]->featureName;
    	  			$requestXmlBody .= "<Name>$_temp/Name>";
    	  			$_temp = $features[$k]->valueName;
    	  			$requestXmlBody .= "<Value>$_temp</Value>";
    	  			$requestXmlBody .= '</NameValueList>';
    	  		}
    	  		$requestXmlBody .= '</ItemSpecifics>';
    	  		// fine specifiche
    	  		$requestXmlBody .= "<Currency>$siteId->currencyCode</Currency>";
    	  		$requestXmlBody .= "<PostalCode>24040</PostalCode>";
    	  		$requestXmlBody .= '<Location>US</Location>';
    	  		$requestXmlBody .= '<Country>US</Country>';
    	  		
    	  		$requestXmlBody .= "<DisableBuyerRequirements>True</DisableBuyerRequirements>";
    	  		$requestXmlBody .= "<DispatchTimeMax>$settings->dispatchTimeMax</DispatchTimeMax>";
    	  		$requestXmlBody .= "<ListingDuration>$settings->listingDuration</ListingDuration>";
				$requestXmlBody .= '<ListingType>FixedPriceItem</ListingType>';
				
				// Tipologie di pagamento 
    	    	$requestXmlBody .= '<PaymentMethods>PayPal</PaymentMethods>';
    	    	$requestXmlBody .= "<PayPalEmailAddress>$settings->paypalEmail</PayPalEmailAddress>";
    	    	if( $settings->postalTransfert == 1 ) {
    	    		$requestXmlBody .= '<PaymentMethods>PostalTransfer</PaymentMethods>';
    	    	}
    	    	if( $settings->personalCheck == 1 ) {
    	    		$requestXmlBody .= '<PaymentMethods>PersonalCheck</PaymentMethods>';
    	    	}
    	    	if( $settings->creditCard == 1 ) {
    	    		$requestXmlBody .= '<PaymentMethods>CreditCard</PaymentMethods>'; 
    	    	}
    	    	// Fine tipologie di pagamento
    	    	
    	    	// Skype (aggiungere un flaf per ogni tipologia di contatto e se abilitato o no nella console)
    	    	$requestXmlBody .= '<SkypeContactOption>Chat</SkypeContactOption>';
    	    	$requestXmlBody .= '<SkypeContactOption>Voice</SkypeContactOption>';
    	    	$requestXmlBody .= '<SkypeEnabled>True</SkypeEnabled>';
				$requestXmlBody .= '<SkypeID>Ivan Porta</SkypeID>';
				// Fine Skype
				
				// Immagini
    	    	$requestXmlBody .= '<PictureDetails>';
    	    	$photos = $this->backmodel->ra_PRD_Photos('', $product->idProduct, '');
    	    	for ($k = 0; $k < count($photos); $k++) {
    	    		$_temp = $this->config->item('resources_url')."/img/product/".$photos[$k]->photoName;
    	    		$requestXmlBody .= "<PictureURL>$_temp</PictureURL>";
    	    	}
    	    	$requestXmlBody .= '</PictureDetails>';
    	    	// Fine Immagini
    	    	
			// 	Sincronizzazione categorie negozio
   	    	// $requestXmlBody .= '<Storefront>';
   	    	// $requestXmlBody .= '<StoreCategory2ID></StoreCategory2ID>';
   	    	// $requestXmlBody .= "<StoreCategory2Name></StoreCategory2Name>";
   	    	// $requestXmlBody .= "<StoreCategoryID></StoreCategoryID>";
   	    	// $requestXmlBody .= "<StoreCategoryName></StoreCategoryName>";
   	    	// $requestXmlBody .= '</Storefront>';
            //  Fine sincronizzazione categorie negozio ebay
    	    	
    	    	// Variazioni
				$requestXmlBody .= '<Variations>';
    	      	$requestXmlBody .= '<VariationSpecificsSet>';
				$_tempAttributes = $this->getAttribute($product);
				
				$attributes = $this->orderAttributes($_tempAttributes);
				for ($k = 0; $k < count($attributes); $k++) {
					$requestXmlBody .= '<NameValueList>';
					$_temp = $attributes[$k]->featureName;
					$requestXmlBody .= "<Name>$_temp</Name>";
					
					for ($j = 0; $j < count($attributes[$k]->values); $j++) {
						$_temp = $attributes[$k]->values[$j]->valueName;
						$requestXmlBody .= "<Value>$_temp</Value>";	
					}
					$requestXmlBody .= '</NameValueList>'; 
				}
    	      	$requestXmlBody .= '</VariationSpecificsSet>';	
    	      	for ($k = 0; $k < count($_tempAttributes); $k++) {
    	      		$requestXmlBody .= '<Variation>';
    	      		$_temp = $currency->currencyValue * $product->productPrice;
    	      		$requestXmlBody .= "<StartPrice>$_temp</StartPrice>";
    	      		$_tempIndex = count($_tempAttributes[$k])-1;
    	      		$_temp = $_tempAttributes[$k][$_tempIndex]->valueName;
    	      		$requestXmlBody .= "<Quantity>$_temp</Quantity>";
    	      		$requestXmlBody .= '<VariationSpecifics>';
    	      		for ($l = 0; $l < count($_tempAttributes[$k])-1; $l++) {
    	      			$requestXmlBody .= '<NameValueList>';
    	      			$_temp = $_tempAttributes[$k][$l]->featureName;
    	      			$requestXmlBody .= "<Name>$_temp</Name>";
    	      			$_temp = $_tempAttributes[$k][$l]->valueName;
    	      			$requestXmlBody .= "<Value>$_temp</Value>";
    	      			$requestXmlBody .= '</NameValueList>';	
    	      		}
    	      		$requestXmlBody .= '</VariationSpecifics>';
    	      		$requestXmlBody .= '</Variation>';
    	      	}
    	 		$requestXmlBody .= '</Variations>';
    	    	// Fine Variazioni
    	    	
				// Spedizioni
   	    	// $requestXmlBody .= '<ShippingDetails>';
   	    	// $requestXmlBody .= "<CODCost>$settings->CODCost</CODCost>";
   	    	// $requestXmlBody .= "<GlobalShipping>True</GlobalShipping>";
   	    	// $requestXmlBody .= '<PaymentInstructions>Payment must be received within 7 business days of purchase.</PaymentInstructions>';
   	    	// $requestXmlBody .= '<ShippingType> ShippingTypeCodeType </ShippingType>';
   	    	// $requestXmlBody .= '<RateTableDetails>';
   	    	// $requestXmlBody .= '<DomesticRateTable> string </DomesticRateTable>';
   	    	// $requestXmlBody .= '<InternationalRateTable> string </InternationalRateTable>';
   	    	// $requestXmlBody .= '</RateTableDetails>';
    	    	      
    	    // 	Nazionali
    	    // 	Assicurazione
   	    	// $requestXmlBody .= '<InsuranceDetails>';
   	    	// $requestXmlBody .= '<InsuranceFee> AmountType (double) </InsuranceFee>';
   	    	// $requestXmlBody .= '<InsuranceOption> InsuranceOptionCodeType </InsuranceOption>';
   	    	// $requestXmlBody .= '</InsuranceDetails>';
   	    	// $requestXmlBody .= '<InsuranceFee> AmountType (double) </InsuranceFee>';
   	    	// $requestXmlBody .= '<InsuranceOption> InsuranceOptionCodeType </InsuranceOption>';
    	    // 	Fine Assicurazione
   	    	// $requestXmlBody .= '<ShippingServiceOptions>';
   	    	// $requestXmlBody .= '<FreeShipping> boolean </FreeShipping>';
   	    	// $requestXmlBody .= '<ShippingService> token </ShippingService>';
   	    	// $requestXmlBody .= '<ShippingServiceAdditionalCost> AmountType (double) </ShippingServiceAdditionalCost>';
   	    	// $requestXmlBody .= '<ShippingServiceCost> AmountType (double) </ShippingServiceCost>';
   	    	// $requestXmlBody .= '<ShippingServicePriority> int </ShippingServicePriority>';
   	    	// $requestXmlBody .= '<ShippingSurcharge> AmountType (double) </ShippingSurcharge>';
   	    	// $requestXmlBody .= '</ShippingServiceOptions>';
    	    	// Fine Nazionali
    	    	
    	    	// Internazionali
    	    	// Assicurazione
   	    	// $requestXmlBody .= '<InternationalInsuranceDetails>';
   	    	// $requestXmlBody .= '<InsuranceFee> AmountType (double) </InsuranceFee>';
   	    	// $requestXmlBody .= '<InsuranceOption> InsuranceOptionCodeType </InsuranceOption>';
   	    	// $requestXmlBody .= '</InternationalInsuranceDetails>';
    	    // 	Fine Assicurazione
   	    	// $requestXmlBody .= '<InternationalShippingServiceOption>';
   	    	// $requestXmlBody .= '<ShippingService> token </ShippingService>';
   	    	// $requestXmlBody .= '<ShippingServiceAdditionalCost> AmountType (double) </ShippingServiceAdditionalCost>';
   	    	// $requestXmlBody .= '<ShippingServiceCost> AmountType (double) </ShippingServiceCost>';
   	    	// $requestXmlBody .= '<ShippingServicePriority> int </ShippingServicePriority>';
   	    	// $requestXmlBody .= '<ShipToLocation> string </ShipToLocation>';
   	    	// $requestXmlBody .= '</InternationalShippingServiceOption>';
    	    	// Fine Internazionali
   	    	// $requestXmlBody .= '</ShippingDetails>';
   	    	
   	    	// $requestXmlBody .= '<ShippingPackageDetails>';
   	    	// $requestXmlBody .= '<MeasurementUnit> MeasurementSystemCodeType </MeasurementUnit>';
   	    	// $requestXmlBody .= '<PackageDepth> MeasureType (decimal) </PackageDepth>';
   	    	// $requestXmlBody .= '<PackageLength> MeasureType (decimal) </PackageLength>';
   	    	// $requestXmlBody .= '<PackageWidth> MeasureType (decimal) </PackageWidth>';
   	    	// $requestXmlBody .= '<ShippingIrregular> boolean </ShippingIrregular>';
   	    	// $requestXmlBody .= '<ShippingPackage> ShippingPackageCodeType </ShippingPackage>';
   	    	// $requestXmlBody .= '<WeightMajor> MeasureType (decimal) </WeightMajor>';
   	    	// $requestXmlBody .= '<WeightMinor> MeasureType (decimal) </WeightMinor>';
   	    	// $requestXmlBody .= '</ShippingPackageDetails>';
   	    	// $requestXmlBody .= '<ShippingTermsInDescription> boolean </ShippingTermsInDescription>';
   	    	// $requestXmlBody .= '<ShipToLocations> string </ShipToLocations>';
    	    	// Fine Spedizioni
    	    	
    	    	$requestXmlBody .= '<ShippingDetails>';
    	      	$requestXmlBody .= '<CalculatedShippingRate>';
	    	    $requestXmlBody .= '<OriginatingPostalCode>95125</OriginatingPostalCode>';
	    	    $requestXmlBody .= '<PackageDepth>6</PackageDepth>';
	    	    $requestXmlBody .= '<PackageLength>7</PackageLength>';
	    	    $requestXmlBody .= '<PackageWidth>7</PackageWidth>';
	    	    $requestXmlBody .= '<ShippingPackage>PackageThickEnvelope</ShippingPackage>';
	    	    $requestXmlBody .= '<WeightMajor>2</WeightMajor>';
	    	    $requestXmlBody .= '<WeightMinor>0</WeightMinor>';
    	      	$requestXmlBody .= '</CalculatedShippingRate>';
    	      	$requestXmlBody .= '<PaymentInstructions>Payment must be received within 7 business days of purchase.</PaymentInstructions>';
    	      	$requestXmlBody .= '<ShippingServiceOptions>';
    	        $requestXmlBody .= '<FreeShipping>true</FreeShipping>';
    	        $requestXmlBody .= '<ShippingService>USPSPriority</ShippingService>';
    	        $requestXmlBody .= '<ShippingServicePriority>1</ShippingServicePriority>';
    	      	$requestXmlBody .= '</ShippingServiceOptions>';
    	      	$requestXmlBody .= '<ShippingServiceOptions>';
    	        $requestXmlBody .= '<ShippingService>UPSGround</ShippingService>';
    	        $requestXmlBody .= '<ShippingServicePriority>2</ShippingServicePriority>';
    	      	$requestXmlBody .= '</ShippingServiceOptions>';
    	      	$requestXmlBody .= '<ShippingServiceOptions>';
    	        $requestXmlBody .= '<ShippingService>UPSNextDay</ShippingService>';
    	        $requestXmlBody .= '<ShippingServicePriority>3</ShippingServicePriority>';
    	      	$requestXmlBody .= '</ShippingServiceOptions>';
    	      	$requestXmlBody .= '<ShippingType>Calculated</ShippingType>';
    	    	$requestXmlBody .= '</ShippingDetails>';
    	    	// Return Policy
    	    	$requestXmlBody .= '<ReturnPolicy>';
    	    	$requestXmlBody .= '<ReturnsAcceptedOption>ReturnsAccepted</ReturnsAcceptedOption>';
    	    	$requestXmlBody .= "<RefundOption>$settings->returnsOptions</RefundOption>";
    	    	$requestXmlBody .= "<ReturnsWithinOption>$settings->returnsWithinOptions</ReturnsWithinOption>";
    	    	$requestXmlBody .= "<Description>$settings->returnsDescription</Description>";
    	    	$requestXmlBody .= "<ShippingCostPaidByOption>$settings->shippingCostPaidByOption</ShippingCostPaidByOption>";
    	    	if( $settings->warrantyOfferedOption == 'WarrantyOfferedOption') {
	    	    	$requestXmlBody .= "<WarrantyDurationOption>$settings->warrantyDurationOption</WarrantyDurationOption>";
	    	    	$requestXmlBody .= "<WarrantyOfferedOption>$settings->warrantyOfferedOption</WarrantyOfferedOption>";
	    	    	$requestXmlBody .= "<WarrantyTypeOption>$settings->warrantyTypeOption</WarrantyTypeOption>";
    	    	}
    	    	$requestXmlBody .= '</ReturnPolicy>';
    	    	// Fine Return Policy	
    	  		$requestXmlBody .= '</Item>';
	    		$requestXmlBody .= '</AddFixedPriceItemRequest>​​';
    			

				$responseXml = $this->cURL_Request($settings->serverUrl,$headers,$requestXmlBody);
				
				
				if (stristr($responseXml, 'HTTP 404') || $responseXml == '') {
					die("<p>Errore 001 - Errore durante l'invio della richiesta</p>");
				}
				$responseDoc = new DomDocument();
				$responseDoc->loadXML($responseXml);
				$errors = $responseDoc->getElementsByTagName('Errors');
				if($errors->length > 0) {
					$message = 'Errore 002 - Operazione non completata a causa dei seguenti errori:<br/>';
					$code     = $errors->item(0)->getElementsByTagName('ErrorCode');
					$shortMsg = $errors->item(0)->getElementsByTagName('ShortMessage');
					$longMsg  = $errors->item(0)->getElementsByTagName('LongMessage');
					$message .= '- Errore '.$code->item(0)->nodeValue.': ';
					if(count($longMsg) > 0) {
						$message .= $longMsg->item(0)->nodeValue.'<br/>';
					} else {
						$message .= $shortMsg->item(0)->nodeValue.'<br/>';
					}
					
					$data = array(
						'error' => $message,
						'xml' => $requestXmlBody
					);
					header('Content-Type: application/x-json; charset=utf-8');
					echo json_encode($data);
				} else { 
					$xml = 'Sincronizzazione Completata';		
					header('Content-Type: application/x-json; charset=utf-8');
					echo json_encode($xml);
				}
			}
		}
    } // Da controllare
    
	public function test() {
		$products = $this->backmodel->ra_PRD_Products_Ebay('it');
		foreach ($products as $product) {
			$_tempAttributes = $this->getAttribute($product);
			print_r(count($_tempAttributes[0])-1);
			
			print_r($_tempAttributes[0][count($_tempAttributes[0])-1]);
		}
	}
    
	public function getOrders() {
		$settings = $this->backmodel->r_STN_Ebay();
		$verb = 'GetOrders';
		$now = date('Y-m-d H:i:s');
		
		$headers = array (
			'X-EBAY-API-COMPATIBILITY-LEVEL: ' .$settings->ebayCompatibilityLevel,
			'X-EBAY-API-DEV-NAME: ' .$settings->ebayDevID,
			'X-EBAY-API-APP-NAME: ' .$settings->ebayAppID,
			'X-EBAY-API-CERT-NAME: '.$settings->ebayCertID,
			'X-EBAY-API-CALL-NAME: ' .$verb,			
			'X-EBAY-API-SITEID: ' . $settings->idSite,
		);
		
		$requestXmlBody = '<?xml version="1.0" encoding="utf-8"?>';
		$requestXmlBody .= '<GetOrdersRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
		$requestXmlBody .= '<RequesterCredentials>';
		$requestXmlBody .= "<eBayAuthToken>$settings->ebayToken</eBayAuthToken>";
		$requestXmlBody .= '</RequesterCredentials>';
    	$requestXmlBody .= "<CreateTimeFrom>2015-09-01T20:34:44.000Z</CreateTimeFrom>";
    	$requestXmlBody .= "<CreateTimeTo>$now</CreateTimeTo>";
    	$requestXmlBody .= '<DetailLevel>ReturnAll</DetailLevel>';
    	$requestXmlBody .= '</GetOrdersRequest>';
    	
    	//build eBay headers using variables passed via constructor
    	$connection = curl_init();
    	curl_setopt($connection, CURLOPT_URL, $settings->ebayServerURL);
    	curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);
    	curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);
    	curl_setopt($connection, CURLOPT_HTTPHEADER, $headers);
    	curl_setopt($connection, CURLOPT_POST, 1);
    	curl_setopt($connection, CURLOPT_POSTFIELDS, $requestXmlBody);
    	curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
    	$responseXml = curl_exec($connection);
    	curl_close($connection);

		if(stristr($responseXml, 'HTTP 404') || $responseXml == '') {
			die('<P>Error sending request');
		}
			
		$responseDoc = new DomDocument();
		$responseDoc->loadXML($responseXml);
		$errors = $responseDoc->getElementsByTagName('Errors');
				
		if($errors->length > 0) {
			echo '<P><B>eBay returned the following error(s):</B>';
			$code     = $errors->item(0)->getElementsByTagName('ErrorCode');
			$shortMsg = $errors->item(0)->getElementsByTagName('ShortMessage');
			$longMsg  = $errors->item(0)->getElementsByTagName('LongMessage');
			echo '<P>', $code->item(0)->nodeValue, ' : ', str_replace(">", "&gt;", str_replace("<", "&lt;", $shortMsg->item(0)->nodeValue));
			if(count($longMsg) > 0) {
				echo '<BR>', str_replace(">", "&gt;", str_replace("<", "&lt;", $longMsg->item(0)->nodeValue));
			}
			echo $settings->ebayToken;
		} else {   
		
			//get results nodes
			$responses = $responseDoc->getElementsByTagName("Order");
			foreach ($responses as $response) {
				
				$feeNames = $response->getElementsByTagName("BuyerUserID");
				$feeName = $feeNames->item(0)->nodeValue;
				echo "Cliente eBay = " .$feeName. "\n";
				
				$feeNames = $response->getElementsByTagName("Total");
				$feeName = $feeNames->item(0)->nodeValue;
				$currency = $feeNames->item(0)->attributes->item(0)->nodeValue;
				echo "Importo eBay = ".$feeName." ".$feeNames->item(0)->attributes->item(0)->nodeValue."\n";
				
				$feeNames = $response->getElementsByTagName("PaymentMethods");
				$feeName = $feeNames->item(0)->nodeValue;
				echo "Metodo di pagamento eBay = " .$feeName. "\n";
				
				$feeNames = $response->getElementsByTagName("CreatedTime");
				$feeName = $feeNames->item(0)->nodeValue;
				echo "Creazione ordine eBay = " .$feeName. "\n";
				
				$feeNames = $response->getElementsByTagName("Transaction");
				$feeName = $feeNames->item(0)->childNodes->item(3)->nodeValue;
				echo "Prodotto eBay = " .$feeName. "\n";
				
				$feeNames = $response->getElementsByTagName("Transaction");
				$feeName = $feeNames->item(0)->childNodes->item(4)->nodeValue;
				echo "Quantità eBay = " .$feeName. "\n";
				
				$feeNames = $response->getElementsByTagName("Transaction");
				$feeName = $feeNames->item(0)->childNodes->item(10)->nodeValue;
				echo "Variazione eBay = " .$feeName. "\n\n";
				
				// $feeNames = $response->getElementsByTagName("Transaction");
				// $feeName = $feeNames->item(1)->childNodes;
				// print_r($feeName);
				
				$feeNames = $response->getElementsByTagName("ShippingAddress");
				$feeName = $feeNames->item(0)->childNodes->item(0);
				print_r($feeName);
				
				
				$feeNames = $response->getElementsByTagName("ShippingAddress");
				$feeName = $feeNames->item(0)->childNodes;
				echo "Nome cliente eBay = ".$feeName->item(0)->nodeValue. "\n";
				echo "Indirizzo eBay = ".$feeName->item(1)->nodeValue. "\n";
				echo "Indirizzo 2 eBay = ".$feeName->item(2)->nodeValue. "\n";
				echo "Città eBay = ".$feeName->item(3)->nodeValue. "\n";
				echo "Stato o Provincia eBay = ".$feeName->item(4)->nodeValue. "\n";
				echo "Nazione eBay = ".$feeName->item(5)->nodeValue. "\n";
				
			}
			
			//$token  = $tokens->item(0)->nodeValue;
			$data = array(
				'orderLastUpdate' => $now,
			);
			$this->backmodel->u_STN_Ebay($data);
			
			
			// header('Content-Type: application/x-json; charset=utf-8');
			// echo json_encode('ciao');
    	} 
	} // Da controllare	
	public function getStoreCategories() {
		$settings = $this->backmodel->r_STN_Ebay();
		$verb = 'GetStore';
		$now = date('Y-m-d H:i:s');
		
		$headers = array (
			'X-EBAY-API-COMPATIBILITY-LEVEL: ' .$settings->ebayCompatibilityLevel,
			'X-EBAY-API-DEV-NAME: ' .$settings->ebayDevID,
			'X-EBAY-API-APP-NAME: ' .$settings->ebayAppID,
			'X-EBAY-API-CERT-NAME: '.$settings->ebayCertID,
			'X-EBAY-API-CALL-NAME: ' .$verb,			
			'X-EBAY-API-SITEID: ' . $settings->idSite,
		);
		
		$requestXmlBody = '<?xml version="1.0" encoding="utf-8"?>';
		$requestXmlBody .= '<GetStoreRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
		$requestXmlBody .= '<CategoryStructureOnly>False</CategoryStructureOnly>';
		$requestXmlBody .= '<LevelLimit>3</LevelLimit>';
		// $requestXmlBody .= '<RootCategoryID> long </RootCategoryID>';
		$requestXmlBody .= '<UserID>$setting->ebayShop</UserID>';
		$requestXmlBody .= '<ErrorLanguage>it_IT/ErrorLanguage>';
		$requestXmlBody .= '</GetStoreRequest>';
    	
    	//build eBay headers using variables passed via constructor
    	$connection = curl_init();
    	curl_setopt($connection, CURLOPT_URL, $settings->ebayServerURL);
    	curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);
    	curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);
    	curl_setopt($connection, CURLOPT_HTTPHEADER, $headers);
    	curl_setopt($connection, CURLOPT_POST, 1);
    	curl_setopt($connection, CURLOPT_POSTFIELDS, $requestXmlBody);
    	curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
    	$responseXml = curl_exec($connection);
    	curl_close($connection);

		if(stristr($responseXml, 'HTTP 404') || $responseXml == '') {
			die('<P>Error sending request');
		}
			
		$responseDoc = new DomDocument();
		$responseDoc->loadXML($responseXml);
		$errors = $responseDoc->getElementsByTagName('Errors');
				
		if($errors->length > 0) {
			echo '<P><B>eBay returned the following error(s):</B>';
			$code     = $errors->item(0)->getElementsByTagName('ErrorCode');
			$shortMsg = $errors->item(0)->getElementsByTagName('ShortMessage');
			$longMsg  = $errors->item(0)->getElementsByTagName('LongMessage');
			echo '<P>', $code->item(0)->nodeValue, ' : ', str_replace(">", "&gt;", str_replace("<", "&lt;", $shortMsg->item(0)->nodeValue));
			if(count($longMsg) > 0) {
				echo '<BR>', str_replace(">", "&gt;", str_replace("<", "&lt;", $longMsg->item(0)->nodeValue));
			}
			echo $settings->ebayToken;
		} else {   
		
			//get results nodes
			$responses = $responseDoc->getElementsByTagName("CustomCategory");
			foreach ($responses as $response) {
				$feeNames = $response->getElementsByTagName("CategoryID");
				$feeName = $feeNames->item(0)->nodeValue;
				echo "Cliente eBay = " .$feeName. "\n";
				
				$feeNames = $response->getElementsByTagName("Name");
				$feeName = $feeNames->item(0)->nodeValue;
				$currency = $feeNames->item(0)->attributes->item(0)->nodeValue;
				echo "Importo eBay = ".$feeName." ".$feeNames->item(0)->attributes->item(0)->nodeValue."\n";
			}

			// header('Content-Type: application/x-json; charset=utf-8');
			// echo json_encode('ciao');
    	} 
	} // Da controllare
}