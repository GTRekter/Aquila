<?php
defined('BASEPATH') OR exit('No direct script access allowed');
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

class Front extends CI_Controller {
	// Initialize
	function __construct() {
		parent::__construct();
		
		if (!$this->session->userdata('site_lang')) {
			$this->session->set_userdata('site_lang', "italian");
		};
		if (!$this->session->userdata('site_lang_code')) {
			$this->session->set_userdata('site_lang_code', "it");
		};
		if (!$this->session->userdata('site_currency')) {
			$this->session->set_userdata('site_currency_value', "1");
			$this->session->set_userdata('site_currency_symbol', "€");
		};
	}
	// Login to Back
	function autenticate() {
		$this->load->library('form_validation');	
		$this->form_validation->set_rules('email', 'Email', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$this->form_validation->set_message('required', 'Compila il campo %s.');
		
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('message', 'Compila i campi obbligatori!');
			redirect('front/login');
		} else {
			$this->validate_credentials($this->input->post('email'), $this->input->post('password'));
		}
	}
	function validate_credentials($email, $password) {	
		$blocked_ip = $this->frontmodel->r_LOG_Blocked_IP($_SERVER['REMOTE_ADDR']);
		if (!$blocked_ip) {
			$query = $this->frontmodel->validate($email, $password);
			if ($query) {
				$data = array(
					'idAccess' => $query[0]->idAccess,
					'accessName' => $query[0]->accessName,
					'accessEmail' => $query[0]->accessEmail,
					'is_logged_in' => true
				);
				$this->session->set_userdata($data);
				$_SESSION['failed_login'] = 0;
				redirect('back');
			} else {
				if (empty($_SESSION['failed_login'])) {
				    $_SESSION['failed_login'] = 1;
				} else {
				    $_SESSION['failed_login'] ++;
				}
				if ($_SESSION['failed_login'] >= 5) {
					$data = array(
						'IP' => $_SERVER['REMOTE_ADDR']
					);
					$this->frontmodel->c_LOG_Blocked_IP($data);
				}
				$this->load->view('pages/login');
			}
		}
	}	
// End Login

// PRESENTATIONS
	public function index() {	
		$data['page_name'] = 'login';
		$data['blocked_ip'] = $this->frontmodel->r_LOG_Blocked_IP($_SERVER['REMOTE_ADDR']);
		$this->load->view('pages/login',$data);
	}	
// END PRESENTATIONS
// ACTIONS
	// 404 NOT FOUND
	public function _remap($method) {
	    if (method_exists($this, $method))
	    {
	        $this->$method();
	        return;
	    } 
		redirect( site_url('front') );
	}
// END ACTIONS
}
