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
	// Language
	function switchLanguage() {
		$language = $this->uri->segment(3);
        $language = ($language != "") ? $language : "italian";
        $this->session->set_userdata('site_lang', $language);
		
		$languageCode = $this->uri->segment(4);
		$languageCode = ($languageCode != "") ? $languageCode : "it";
		$this->session->set_userdata('site_lang_code', $languageCode);
		
        redirect($this->input->get('currentURL'));
    }
    // Currency
    function switchCurrency() {
    	$currencyCode = $this->uri->segment(3);
        $currencyCode = ($currencyCode != "") ? $currencyCode : "EUR";
        $this->session->set_userdata('site_currency', $currencyCode);
        
        $currency = $this->frontmodel->ra_STN_Currencies('', $currencyCode);
        $this->session->set_userdata('site_currency_value', $currency[0]->currencyValue);
        $this->session->set_userdata('site_currency_symbol', $currency[0]->currencySymbol);
        redirect($this->input->get('currentURL'));
    }
	// Login to Back
	function autenticate() {
		$this->load->library('form_validation');	
		$this->form_validation->set_rules('email', 'Email', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$this->form_validation->set_message('required', 'Compila il campo %s.');
		
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('message', 'Compila i campi obbligatori!');
			redirect('login');
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
					'username' => $email,
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
	// Login to Private Area
	function autenticate_Client() {
		$this->load->library('form_validation');	
		$this->form_validation->set_rules('loginClientEmail', 'Email', 'trim|required');
		$this->form_validation->set_rules('loginClientPassword', 'Password', 'trim|required');
		$this->form_validation->set_message('required', 'Compila il campo %s.');
		
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('message', 'Compila i campi obbligatori!');
			redirect('signin');
		} else {
			$this->validate_credentials_Client($this->input->post('loginClientEmail'), $this->input->post('loginClientPassword'));
		}
	}
	function validate_credentials_Client($email, $password) {	
		$query = $this->frontmodel->validate_Client($email, $password);
		if ($query) {
			$data = array(
				'idClient' => $query[0]->idClient,
				'clientName' => $query[0]->clientName,
				'client_is_logged_in' => true
			);
			$this->session->set_userdata($data);
			redirect('PrivateArea');
			
			$currentCartProducts = $this->session->userdata('cartProducts');
			if ( $currentCartProducts ) {
				
				$previewsCart = $this->frontmodel->ra_ORD_Cart('',$this->session->userdata('idClient'),'');
				if (! $previewsCart) {
					$data = array(
						'idClient' => $this->session->userdata('idClient'),
						'createdOn' => date('Y-m-d H:i:s') ,
					);
					$returnedID = $this->frontmodel->c_ORD_Cart($data);
					foreach ($currentCartProducts as $cartProduct) {
						$data = array(
							'idCart' => $returnedID,
							'idProduct' => $cartProduct->idProduct,
							'idCombination' => $cartProduct->idCombination,
							'productQuantity' => $cartProduct->quantity
						);
						$this->frontmodel->c_ORD_Carts_Product($data,0);
					}
				} else {
					foreach ($previewsCart as $previewsCartProduct) {
						foreach ($currentCartProducts as $cartProduct) {
							if ($previewsCartProduct->idProduct == $cartProduct->idProduct && $previewsCartProduct->idCombination == $cartProduct->idCombination ) {
								$combination = $this->frontmodel->r_PRD_Combinations($this->input->post('idCombination'),'','');
								if ( ($previewsCartProduct->idCombination + $cartProduct->idCombination) <= $combination[0]->combinationQuantity ) {
									$data = array(
										'productQuantity' => $previewsCartProduct->productQuantity + $cartProduct->idCombination
									);
									$this->frontmodel->u_ORD_Cart_Products($previewsCartProduct->idCartsProduct,$data);
								} else {
									$data = array(
										'productQuantity' => $combination[0]->combinationQuantity
									);
									$this->frontmodel->u_ORD_Cart_Products($previewsCartProduct->idCartsProduct,$data);
								}
							} else {
								$data = array(
									'idCart' => $previewsCart->idCart,
									'idProduct' => $cartProduct->idProduct,
									'idCombination' => $cartProduct->idCombination,
									'productQuantity' => $cartProduct->quantity
								);
								$this->frontmodel->c_ORD_Carts_Product($data,0);
							}
						}
					}
				}
				
			}
		} else {
			$this->session->set_flashdata('message', 'Utente non trovato. Controlla le tue credenziali e prova di nuovo!');
			redirect('signin');
		}
	}
// End Login

// ACTIONS
	// i_PRD_Likes
	public function i_PRD_Likes() {
		 $this->frontmodel->u_PRD_Likes($this->input->post('idProduct'));
	}
	// addCart
	public function addCart() {
		$message= '';
		if (! $this->session->userdata('idClient')) { 
			// IL CLIENTE NON HA LOGGATO
			$currentCartProducts = $this->session->userdata('cartProducts');
			if ( $currentCartProducts == null ) {
				$currentCartProducts = array();
				$object = (object) [
					'idProduct' => $this->input->post('idProduct'),
				    'idCombination' => $this->input->post('idCombination'),
				    'productQuantity' => $this->input->post('quantity')
				];
				array_push($currentCartProducts, $object);
				$message = 'prodotto inserito correttamente nel carrello';
			} else {
				foreach ($currentCartProducts as $cartProduct) {
					if ($cartProduct->idProduct == $this->input->post('idProduct') && $cartProduct->idCombination == $this->input->post('idCombination')) {
						$combination = $this->frontmodel->r_PRD_Combinations($this->input->post('idCombination'),'','');
						if ( $cartProduct->productQuantity < $combination[0]->combinationQuantity ) {
							$cartProduct->productQuantity ++;
							$message = 'Quantità del prodotto aggiornata';
						} else {
							$message = 'Quantità inserita ['.$cartProduct->productQuantity.'] pari alla quantità disponibile ['.$combination[0]->combinationQuantity.']';
						}
					} else {
						$object = (object) [
							'idProduct' => $this->input->post('idProduct'),
						    'idCombination' => $this->input->post('idCombination'),
						    'productQuantity' => $this->input->post('quantity')
						];
						array_push($currentCartProducts, $object);
						$message = 'prodotto inserito nel carrello';
					}
				}
			}
			$this->session->set_userdata('cartProducts',$currentCartProducts);
		} else { 
			// IL CLIENTE HA LOGGATO
			$currentCart = $this->frontmodel->ra_ORD_Cart('',$this->session->userdata('idClient'),'');
			if (! $currentCart) {
				$data = array(
					'idClient' => $this->session->userdata('idClient'),
					'createdOn' => date('Y-m-d H:i:s') ,
				);
				$returnedID = $this->frontmodel->c_ORD_Cart($data);
				
				$data = array(
					'idCart' => $returnedID,
					'idProduct' => $this->input->post('idProduct'),
					'idCombination' => $this->input->post('idCombination'),
					'productQuantity' => $this->input->post('quantity')
				);
				$this->frontmodel->c_ORD_Carts_Product($data,0);
				$message = $this->lang->line("product_added");
			} else {
				// IL CARRELLO E' GIA' PRESENTE
				$cartProducts = $this->frontmodel->r_ORD_Cart_Products('',$currentCart[0]->idCart, '', '', '','', '');
				$isPresent = Null;
				if ($cartProducts) {
					for ($i = 0; $i < count($cartProducts); $i++) {
						if ($this->input->post('idCombination') == $cartProducts[$i]->idCombination) {
							$isPresent = $cartProducts[$i];
						}
					}
					if ( $isPresent != Null) {
						$combination = $this->frontmodel->r_PRD_Combinations($isPresent->idCombination,'','');
						$possibleQuantity = $isPresent->productQuantity + $this->input->post('quantity');
						if ( $possibleQuantity  <= $combination[0]->combinationQuantity ) {
							$data = array(
								'productQuantity' => $this->input->post('quantity')
							);
							$this->frontmodel->u_ORD_Cart_Products($isPresent->idCartsProduct,$data);
							$message = $this->lang->line("product_quantity_updated");
						} else {
							$message = $this->lang->line("product_quanity_max_available");
						}
					} else {
						$data = array(
							'idCart' => $currentCart[0]->idCart,
							'idProduct' => $this->input->post('idProduct'),
							'idCombination' => $this->input->post('idCombination'),
							'productQuantity' => $this->input->post('quantity')
						);
						$this->frontmodel->c_ORD_Carts_Product($data,0);
						$message = $this->lang->line("product_added");
					}
				}
			}
		}
		header('Content-Type: appliation/x-json; charset=utf-8');
		echo json_encode($message);
	}
	public function u_ORD_Carts_Product() {
		if($this->session->userdata('idClient')) {
			$data = array(
				'productQuantity' => $this->input->post('productQuantity'),
			);
			$this->frontmodel->u_ORD_Cart_Products($this->input->post('idCartsProduct'), $data);
		} else {
			$cartProducts = $this->session->userdata('cartProducts');
			foreach ($cartProducts as $cartProduct) {
				if ($cartProduct->idCombination == $this->input->post('idCombination') ) {
					$cartProduct->productQuantity = $this->input->post('productQuantity');
				}
			}
			$this->session->set_userdata('cartProducts', $cartProducts);
		}
	}
	public function r_ORD_Carts_Products() {
		if($this->session->userdata('idClient')) {
			$products = $this->frontmodel->r_ORD_Cart_Products('', '', $this->session->userdata('idClient'), 1, 1, 1, $this->session->userdata('site_lang_code'));
		} else {
			$products = [];
			$cartProducts = $this->session->userdata('cartProducts');
			foreach ($cartProducts as $cartProduct) {
				$product = $this->frontmodel->r_PRD_Product($cartProduct->idProduct,'',1,$this->session->userdata('site_lang_code'));
				$completeProduct = (object) array_merge((array) $cartProduct, (array) $product);
				array_push($products, $completeProduct);
			}
		}
		header('Content-Type: appliation/x-json; charset=utf-8');
		echo json_encode($products);
	}
	public function d_ORD_Carts_Product() {
		if($this->session->userdata('idClient')) {
			$this->frontmodel->d_ORD_Carts_Product($this->input->post('removeCartsProduct'));	
			$products = $this->frontmodel->r_ORD_Cart_Products('', '', $this->session->userdata('idClient'), 1, 1, 1, $this->session->userdata('site_lang_code'));
			header('Content-Type: appliation/x-json; charset=utf-8');
			echo json_encode($products);
		} else {
			$cartProducts = $this->session->userdata('cartProducts');
			for ($i = 0; $i < count($cartProducts); $i++) {
				if ($cartProducts[$i]->idCombination == $this->input->post('idCombination') ) {
					unset($cartProducts[$i]);
				}
			}
			$this->session->set_userdata('cartProducts', $cartProducts);
		}
	}
	public function r_PRD_Product() {
		$product = $this->frontmodel->r_PRD_Product($this->input->post('idProduct'),'','','');
		header('Content-Type: appliation/x-json; charset=utf-8');
		echo json_encode($product);
	}
	public function r_PRD_Combinations() {
		$combination = $this->frontmodel->r_PRD_Combinations($this->input->post('idCombination'), '', '');
		header('Content-Type: appliation/x-json; charset=utf-8');
		echo json_encode($combination);
	}
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
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('message', validation_errors());
		} else {
			$q = $this->frontmodel->ra_ORD_Clients('',$this->input->post('clientEmail'));
			if ( $q != NULL ) {
				$this->session->set_flashdata('message','Errore: Registrazione non completata, in quanto esiste già un utente registrato con la seguente email');
				redirect('signin');
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
					'createdOn' => date('Y-m-d'),
				);
				$returnedID = $this->frontmodel->c_ORD_Client($data);
				
				
				$tableProducts = '<tr style="font-size: 11px;"><td style="width: 120px;">'.$this->lang->line("login_name").': </td><td style="width: 120px; padding: 0px 10px;">'.$this->input->post('clientName').'</td><td style="width: 120px;">'.$this->lang->line("login_surname").': </td><td style="width: 120px; text-align: right;">'.$this->input->post('clientSurname').'</td></tr><tr style="font-size: 11px;"><td style="width: 120px;">'.$this->lang->line("login_phone").': </td><td style="width: 120px; padding: 0px 10px;">'.$this->input->post('clientPhone').'</td><td style="width: 120px;">'.$this->lang->line("login_email").': </td><td style="width: 120px; text-align: right;">'.$this->input->post('clientEmail').'</td></tr><tr style="font-size: 11px;"><td style="width: 120px;">'.$this->lang->line("login_address").': </td><td style="width: 120px; padding: 0px 10px;">'.$this->input->post('clientAddress').''.$this->input->post('clientHouseNumber').'</td><td style="width: 120px;">'.$this->lang->line("login_zip").': </td><td style="width: 120px; text-align: right;">'.$this->input->post('clientPostalCode').'</td></tr><tr style="font-size: 11px;"><td style="width: 120px;">'.$this->lang->line("login_city").': </td><td style="width: 120px; padding: 0px 10px;">'.$this->input->post('clientCity').'</td><td style="width: 120px;">'.$this->lang->line("login_state").': </td><td style="width: 120px; text-align: right;">'.$this->input->post('clientState').'</td></tr>';
				$messageFooter = '';
				
				switch ($this->session->userdata('site_lang_code')) {
					default: 
						$subject = '[SUBJECT]'; 
						$orderTitle = '[ORDER_TITLE]';
						$orderNumber = 'Registration confirmation';
						$messageTitle = 'registration data Summary';
						$messageSubtitle = 'Dear '.$this->input->post('clientName').' '.$this->input->post('clientSurname').', <br> Your data were recorded with success and we welcome you in [COMPANY_NAME]. Here is a brief summary of your registration data:';
						$mailFooter = '[MAIL_FOLDER]';
						break;
					case 'ar': 
						$subject = '[SUBJECT]'; 
						$orderTitle = '[ORDER_TITLE]';
						$orderNumber = 'تأكيد التسجيل؛';
						$messageTitle = 'ملخص بيانات التسجيل';
						$messageSubtitle = 'عزيزي '.$this->input->post('clientName').' '.$this->input->post('clientSurname').', <br> تم تسجيل البيانات بنجاح، ونحن نرحب بكم في [COMPANY_NAME]. وفيما يلي ملخص موجز بيانات التسجيل الخاصة بك:';
						$mailFooter = '[MAIL_FOLDER]';
						break;
					case 'ca':	
						$subject = '[SUBJECT]';
						$orderTitle = '[ORDER_TITLE]';
						$orderNumber = 'Resum de les dades de registre';
						$messageTitle = 'registration data Summary';
						$messageSubtitle = 'Benvolgut '.$this->input->post('clientName').' '.$this->input->post('clientSurname').', <br> Les seves dades s han registrat amb èxit, i ens donarà la benvinguda a [COMPANY_NAME]. Heus aquí un breu resum de la informació de registre: ';
						$mailFooter = '[MAIL_FOLDER]';
						break;
					case 'zh-CN': 
						$subject = '[SUBJECT]';
						$orderTitle = '[ORDER_TITLE]';
						$orderNumber = '报名确认';
						$messageTitle = '登记资料“的总结';
						$messageSubtitle = '尊敬的客户 '.$this->input->post('clientName').' '.$this->input->post('clientSurname').', <br> 您的资料已成功记录，我们欢迎您的[COMPANY_NAME]。这里是您的注册信息的简短摘要：';
						$mailFooter = '[MAIL_FOLDER]';
						break;
					case 'cs':
						$subject = '[SUBJECT]';
						$orderTitle = '[ORDER_TITLE]';
						$orderNumber = 'Potvrzení Registrace ';
						$messageTitle = 'Souhrnné údaje o registraci ';
						$messageSubtitle = 'Vážený zákazníku '.$this->input->post('clientName').' '.$this->input->post('clientSurname').', <br> Vaše data byla úspěšně zaznamenán, a vítáme Vás v [COMPANY_NAME]. Zde je stručné shrnutí své registrační údaje: ';
						$mailFooter = '[MAIL_FOLDER]';
						break;
					case 'nl':
						$subject = '[SUBJECT]';
						$orderTitle = '[ORDER_TITLE]';
						$orderNumber = 'Registratie Bevestiging ';
						$messageTitle = 'Samenvatting van de registratiegegevens ';
						$messageSubtitle = 'Geachte klant '.$this->input->post('clientName').' '.$this->input->post('clientSurname').', <br> Uw data is met succes opgenomen, en verwelkomen wij u in [COMPANY_NAME]. Hier is een korte samenvatting van uw registratie-informatie: ';
						$mailFooter = '[MAIL_FOLDER]';
						break;
					case 'et':
						$subject = '[SUBJECT]';
						$orderTitle = '[ORDER_TITLE]';
						$orderNumber = 'Registreerimine Kinnituse';
						$messageTitle = 'Kokkuvõte registreerimisandmed ';
						$messageSubtitle = 'Hea klient '.$this->input->post('clientName').' '.$this->input->post('clientSurname').', <br> Sinu andmed on salvestatud edukalt ning me tervitame teid [COMPANY_NAME]. Siin on lühike kokkuvõte oma registreerimise kohta: ';
						$mailFooter = '[MAIL_FOLDER]';
						break;
					case 'fr':
						$subject = '[SUBJECT]';
						$orderTitle = '[ORDER_TITLE]';
						$orderNumber = 'Confirmation de la inscription ';
						$messageTitle = 'Résumé des données d enregistrement';
						$messageSubtitle = 'Cher '.$this->input->post('clientName').' '.$this->input->post('clientSurname').', <br> Vos données ont été enregistrées avec succès, et nous vous accueillons dans [COMPANY_NAME]. Voici un bref résumé de vos informations d inscription: ';
						$mailFooter = '[MAIL_FOLDER]';
						break;
					case 'de':	
						$subject = '[SUBJECT]';
						$orderTitle = '[ORDER_TITLE]';
						$orderNumber = 'Anmeldebestätigung';
						$messageTitle = 'Zusammenfassung der Registrierungsdaten';
						$messageSubtitle = 'Sehr geehrter Kunde '.$this->input->post('clientName').' '.$this->input->post('clientSurname').', <br> Ihre Daten wurden erfolgreich aufgenommen, und wir begrüßen Sie in [COMPANY_NAME]. Hier ist eine kurze Zusammenfassung Ihrer Registrierungsinformationen: ';
						$mailFooter = '[MAIL_FOLDER]';
						break;
					case 'el':
						$subject = '[SUBJECT]';
						$orderTitle = '[ORDER_TITLE]';
						$orderNumber = 'επιβεβαίωση εγγραφής';
						$messageTitle = 'Περίληψη των στοιχείων της καταχώρησης';
						$messageSubtitle = 'Αγαπητέ πελάτη '.$this->input->post('clientName').' '.$this->input->post('clientSurname').', <br> Τα δεδομένα σας έχει καταγραφεί με επιτυχία, και σας καλωσορίζουμε στο [COMPANY_NAME]. Εδώ είναι μια σύντομη περίληψη των πληροφοριών εγγραφής σας:';
						$mailFooter = '[MAIL_FOLDER]';
						break;
					case 'iw':
						$subject = '[SUBJECT]';
						$orderTitle = '[ORDER_TITLE]';
						$orderNumber = 'אישור רישום ';
						$messageTitle = 'סיכום של נתוני הרישום ';
						$messageSubtitle = 'לקוח יקר '.$this->input->post('clientName').' '.$this->input->post('clientSurname').', <br> הנתונים שלך נקלטו בהצלחה, ואנחנו מברכים אותך [COMPANY_NAME]. הנה סיכום קצר של פרטי הרישום שלך: ';
						$mailFooter = '[MAIL_FOLDER]';
						break;
					case 'id':
						$subject = '[SUBJECT]';
						$orderTitle = '[ORDER_TITLE]';
						$orderNumber = 'Konfirmasi Pendaftaran';
						$messageTitle = 'Ringkasan dari data registrasi ';
						$messageSubtitle = 'Pelanggan yang terhormat '.$this->input->post('clientName').' '.$this->input->post('clientSurname').', <br> Data Anda telah direkam dengan sukses, dan kami menyambut Anda di [COMPANY_NAME]. Berikut adalah ringkasan singkat dari informasi pendaftaran Anda: ';
						$mailFooter = '[MAIL_FOLDER]';
						break;
					case 'it':
						$subject = '[SUBJECT]';
						$orderTitle = '[ORDER_TITLE]';
					    $orderNumber = 'Conferma registrazione';
					    $messageTitle = 'Riepilogo dati di registrazione';
					    $messageSubtitle = 'Gentile '.$this->input->post('clientName').' '.$this->input->post('clientSurname').',<br> I tuoi dati sono stati registrati con successo e ti diamo il benvenuto in [COMPANY_NAME]. Ecco un breve riepilogo dei tuoi dati di registrazione: <br>';
					    $mailFooter = '[MAIL_FOLDER]';
					    break;
					case 'ja':
						$subject = '[SUBJECT]';
						$orderTitle = '[ORDER_TITLE]';
						$orderNumber = '登録確認';
						$messageTitle = '登録データ」の概要';
						$messageSubtitle = 'お客様各位 '.$this->input->post('clientName').' '.$this->input->post('clientSurname').',<br> あなたのデータが正常に記録されている、と私たちは[COMPANY_NAME]であなたを歓迎します。ここにあなたの登録情報の概要は次のとおりです。';
						$mailFooter = '[MAIL_FOLDER]';
						break;
					case 'ko':
						$subject = '[SUBJECT]';
						$orderTitle = '[ORDER_TITLE]';
						$orderNumber = '등록 확인 ';
						$messageTitle = '등록 데이터 의 요약';
						$messageSubtitle = '친애하는 고객 '.$this->input->post('clientName').' '.$this->input->post('clientSurname').',<br> 데이터가 성공적으로 기록되었습니다, 우리는 [COMPANY_NAME]에서 당신을 환영합니다. 다음은 등록 정보의 간략한 요약이다 : ';
						$mailFooter = '[MAIL_FOLDER]';
						break;
					case 'ms':
						$subject = '[SUBJECT]';
						$orderTitle = '[ORDER_TITLE]';
						$orderNumber = 'Pengesahan Pendaftaran ';
						$messageTitle = 'Ringkasan data pendaftaran ';
						$messageSubtitle = 'Pelanggan yang dihormati '.$this->input->post('clientName').' '.$this->input->post('clientSurname').',<br> Data anda telah direkodkan dengan jayanya, dan kami mengalu-alukan anda di [COMPANY_NAME]. Berikut adalah ringkasan maklumat pendaftaran anda: ';
						$mailFooter = '[MAIL_FOLDER]';
						break;
					case 'pl':
						$subject = '[SUBJECT]';
						$orderTitle = '[ORDER_TITLE]';
						$orderNumber = 'Potwierdzenie rejestracji';
						$messageTitle = 'Podsumowanie danych rejestracyjnych';
						$messageSubtitle = 'Szanowny kliencie '.$this->input->post('clientName').' '.$this->input->post('clientSurname').',<br> Twoje dane zostały zapisane prawidłowo, a my zapraszamy w [COMPANY_NAME]. Oto krótkie podsumowanie informacji rejestracyjnej: ';
						$mailFooter = '[MAIL_FOLDER]';
						break;
					case 'pt':
						$subject = '[SUBJECT]';
						$orderTitle = '[ORDER_TITLE]';
						$orderNumber = 'Confirmação de Inscrição ';
						$messageTitle = 'Resumo dos dados de registo ';
						$messageSubtitle = 'Caro '.$this->input->post('clientName').' '.$this->input->post('clientSurname').',<br> Seus dados foram gravados com sucesso, e nós recebê-lo em [COMPANY_NAME]. Aqui está um breve resumo de suas informações de registro: ';
						$mailFooter = '[MAIL_FOLDER]';
						break;
					case 'ru':
						$subject = '[SUBJECT]';
						$orderTitle = '[ORDER_TITLE]';
						$orderNumber = 'Подтверждение регистрации ';
						$messageTitle = 'Резюме регистрационных данных ';
						$messageSubtitle = 'Уважаемый клиент '.$this->input->post('clientName').' '.$this->input->post('clientSurname').',<br> Ваши данные были записаны успешно, и мы рады приветствовать Вас в [COMPANY_NAME]. Вот краткое описание вашей регистрационной информации: ';
						$mailFooter = '[MAIL_FOLDER]';
						break;
					case 'es':
						$subject = '[SUBJECT]';
						$orderTitle = '[ORDER_TITLE]';
						$orderNumber = 'Confirmación de Registro ';
						$messageTitle = 'Resumen de los datos de registro ';
						$messageSubtitle = 'Estimado '.$this->input->post('clientName').' '.$this->input->post('clientSurname').',<br> Sus datos se han registrado con éxito, y nos dará la bienvenida en [COMPANY_NAME]. He aquí un breve resumen de la información de registro: ';
						$mailFooter = '[MAIL_FOLDER]';
						break;
					case 'tr':
						$subject = '[SUBJECT]';
						$orderTitle = '[ORDER_TITLE]';
						$orderNumber = 'Kayıt onayı ';
						$messageTitle = 'Kayıt verilerinin özeti';
						$messageSubtitle = 'Sayın müşterimiz '.$this->input->post('clientName').' '.$this->input->post('clientSurname').',<br> Verileriniz başarıyla kaydedildi ve biz [COMPANY_NAME] sizi ağırlamak. İşte kayıt bilgilerinin kısa bir özetidir: ';
						$mailFooter = '[MAIL_FOLDER]';
						break;
				}
				
				$this->orderMail($subject, $this->input->post('clientEmail'), $orderTitle, $orderNumber, $messageTitle, $messageSubtitle, $tableProducts, $messageFooter, $mailFooter);
				
				if( $this->input->post('newsletterInscription') == 1) {
					$data = array(
						'idClient' => $returnedID,
						'clientEmail' => $this->input->post('clientEmail'),
						'createdOn' => date('Y-m-d')
					);
					$this->frontmodel->c_ORD_Newsletter($data);
				}
				
				$this->session->set_flashdata('message', 'Registrazione effettuata con successo');
				redirect(site_url('front'));
			}
		}		
	}
	public function u_ORD_Client() {
		//Form Validation
		$this->load->library('form_validation');
		$this->form_validation->set_rules('clientEmail', 'Email', 'trim|required');
		$this->form_validation->set_rules('clientAddress', 'Indirizzo', 'trim|required');
		$this->form_validation->set_rules('clientPostalCode', 'Codice postale', 'trim|required');
		$this->form_validation->set_rules('clientCity', 'Città', 'trim|required');
		$this->form_validation->set_rules('clientState', 'Provincia', 'trim|required');
		$this->form_validation->set_message('required', 'Riempi il campo %s');
		//End Form Validation
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('message', validation_errors());
		} else {
			$oldEmail = $this->frontmodel->r_ORD_Client( $this->input->post('idClient') );
			if ( $oldEmail != $this->input->post('clientEmail') ) {
				$q = $this->frontmodel->check_email( $this->input->post('clientEmail') );
				if ( $q != NULL ) {
					$this->session->set_flashdata('message','Errore: Utente già registrato con la seguente Email');
					redirect('account_info');
				} else {
					$data = array(
						'clientEmail' => $this->input->post('clientEmail'),
						'clientMobilePhone' => $this->input->post('clientMobilePhone'),
						'clientHomePhone' => $this->input->post('clientHomePhone'),
						'clientAddress' => strtolower($this->input->post('clientAddress')),
						'clientPostalCode' => $this->input->post('clientPostalCode'),
						'clientCity' => $this->input->post('clientCity'),
						'clientState' => $this->input->post('clientState'),
					);
					$this->frontmodel->u_ORD_Client($this->input->post('idClient'),$data);
					redirect(site_url('front'));
				}
			}
		}		
	}
	public function r_STN_Article() {
		$idArticle = $this->input->post('idArticle');
		$data = $this->frontmodel->r_STN_Article($idArticle);
		header('Content-Type: application/x-json; charset=utf-8');
		echo json_encode($data);
	}
	// c_ORD_Newsletter
	public function c_ORD_Newsletter() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('clientEmail', 'Email', 'trim|required');
		$this->form_validation->set_message('required', 'Riempi il campo %s');
		
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('message', validation_errors());
		} else {
			$client = $this->frontmodel->ra_ORD_Clients('',$this->input->post('clientEmail'));
			$newsletter = $this->frontmodel->r_ORD_Newsletter('',$this->input->post('clientEmail'));
			
			if (!$newsletter) {
				if ($client) {
					$data = array(
						'idClient' => $client[0]->idClient,
						'clientEmail' => $this->input->post('clientEmail'),
						'createdOn' => date('Y-m-d')
					);
				} else {
					$data = array(
						'clientEmail' => $this->input->post('clientEmail'),
						'createdOn' => date('Y-m-d')
					);
				}
				$this->frontmodel->c_ORD_Newsletter($data);
				$message = 'Registrazione alla newsletter avvenuta con successo';
			}
			else {
				$message = 'Attenzione: Utente già registrato alla newsletter';
			}
			header('Content-Type: application/x-json; charset=utf-8');
			echo json_encode($message);
		}		
	}
	// Navigation
	public function nav_lang() {
		$settings = $this->frontmodel->r_STN_Settings();
		$languages = array_filter(array_filter(explode( ',', $settings->shopLanguages )));
		array_unshift($languages, "it");
		return $languages;
	} 
	public function nav_categories_old($categories) {
		// CATEGORIES TREE (NAV)
		$navCategories = array();
		if ($categories) {
			foreach ($categories as $category) {
				$currentIdParent = $category->idParentCategory;
				$idSubcategory = $category->idCategory;
				$subcategoryName = $category->categoryName;
				$idParentsubcategory = $category->idParentCategory;
				
				$idCategory = $category->idCategory;
				$categoryName = $category->categoryName;
				
				$isPresent = false;
				$navSubcategories = array();
				
				do {
					foreach ($categories as $category) {
						if ($category->idCategory == $currentIdParent) {
							$currentIdParent = $category->idParentCategory;
							$idCategory = $category->idCategory;
							$categoryName = $category->categoryName;
						}
					}
				} while ($currentIdParent != null || $currentIdParent != '' );
				
				foreach ($navCategories as $navCategory) {
					if ($navCategory->idCategory == $idCategory) {
						$isPresent = true;
						
						$object = (object) [
							'idSubcategory' => $idSubcategory,
						    'subcategoryName' => $subcategoryName,
						];
						
						array_push($navCategory->subcategories, $object);
					}
				}
				if ($idParentsubcategory != null && $idParentsubcategory != '') {
					$object = (object) [
						'idSubcategory' => $idSubcategory,
					    'subcategoryName' => $subcategoryName,
					];
					array_push($navSubcategories, $object);
				}
				if ($isPresent == false) {
					$object = (object) [
						'idCategory' => $idCategory,
					    'categoryName' => $categoryName,
					    'subcategories' => $navSubcategories,
					];
					array_push($navCategories, $object);
				}
			}
		}
		return $navCategories;
		// END CATEGORIES TREE
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
	// Contact Form
	public function sendMail() {
		//Form Validation
		$this->load->library('form_validation');
		$this->form_validation->set_rules('clientName', 'Nome', 'trim|required');
		$this->form_validation->set_rules('clientEmail', 'Email', 'trim|required');
		$this->form_validation->set_rules('clientPhone', 'Telefono', 'trim|required');
		$this->form_validation->set_rules('clientMessage', 'Messaggio', 'trim|required');
		$this->form_validation->set_message('required', 'Riempi il campo %s');
		//End Form Validation
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('message', validation_errors());
			redirect(site_url('front/contact'));
		} else {
			$this->load->library('email');
			$config['mailtype'] = 'html';
			$this->email->initialize($config);
			$this->email->from($this->input->post('clientEmail'));
			$this->email->subject('[COMPANY_NAME] | Richiesta informazioni dal sito web');
			$this->email->to('[MAIL_TO]');
			$this->email->message('Nome: '.$this->input->post('clientName').'<br> Telefono: '.$this->input->post('clientPhone').'<br> Email: '.$this->input->post('clientEmail').'<br> Messaggio: '.$this->input->post('clientMessage'));
			$this->email->send();
			$this->session->set_flashdata('message', 'Email inviata con successo');
			redirect(site_url('front/contact'));
		}
	}
	public function orderMail($subject, $receiver, $orderTitle, $orderNumber, $messageTitle, $messageSubtitle, $tableProducts, $messageFooter, $mailFooter) {
		$this->load->library('email');
		$config['mailtype'] = 'html';
		$this->email->initialize($config);
		$this->email->from('customerservice@[COMPANY_NAME]');
		$this->email->subject('[COMPANY_NAME] | '.$subject);
		$this->email->to( $receiver );
		$this->email->message('[MAIL_BODY]');
		$this->email->send();
	}	
	public function recoverPassword() {
		$client = ra_ORD_Clients('', $this->input->post('clientEmail'));
		$newPassword = random_string('alnum', 8);
		$data = array(
			'clientPassword' => $newPassword
		);
		$this->frontmodel->u_ORD_Client($client->idClient,$data);
		
		$this->load->library('email');
		$config['mailtype'] = 'html';
		$this->email->initialize($config);
		$this->email->from($this->input->post('clientEmail'));
		$this->email->subject('[COMPANY_NAME] | Password Recover');
		$this->email->to($this->input->post('clientEmail'));
		$this->email->message('Password: '.$newPassword);
		$this->email->send();
		
	}
	// Payments
	public function cartPayment() {
		$cartPrice = 0;
		$cartWeight = 0;
		$this->load->helper('string');
		
		#Payment Page Information -->
		$config['business'] 			= 'paypal@[COMPANY_NAME]';
        $config["invoice"]				= random_string('numeric',8); //The invoice id
		$config['return'] 				= site_url('Front/paymentCompleted');
		$config['cancel_return'] 		= site_url('Front/cancelPayment');
		$config['notify_url'] 			= site_url('Front/checkPayment'); //IPN Post
		$config["shopping_url"]			= site_url('Front');
		$config['production'] 			= TRUE; //Its false by default and will use sandbox
		// Impostiamo la currency a EUR, per eevitare problemi relativi al prezzo quando vado asalvare l'ordine
		// $config["currency_code"] 		= $this->session->userdata('site_currency');
		$config["currency_code"] 		= 'EUR';
		$config["lc"] 					= 'it'; // $this->session->userdata('site_lang')
//		$config["tax_cart"]				= '0';
//		$config["weight_cart"]			= '10';
//		$config["weight_unit"]			= 'kgs';
		$config["image_url"]			= $this->config->item('resources_url').'/paypal/logo.jpg';
		$config["cpp_logo_image"]       = $this->config->item('resources_url').'/paypal/logo_cpp.jpg';
		
		if ( isset($_POST['differentShipping']) ) {
			#Shipping Option
			#Customer Information -->
			$country = $this->frontmodel->r_STN_Country($this->input->post('shippingCountry'));
			$config["first_name"] 		= $this->input->post('shippingName');
			$config["last_name"] 		= $this->input->post('shippingSurname');
			$config["address1"] 		= $this->input->post('shippingAddress').' '.$this->input->post('shippingHouseNumber');
			$config["city"] 			= $this->input->post('shippingCity');
			// Necessario codice ISO-2
			// $config["state"] 		= $this->input->post('billingState');
			$config["country"] 			= $country->countryCode;
			$config["zip"] 				= $this->input->post('shippingPostalCode');
			$config["payer_email"] 		= $this->input->post('billingEmail');
//			$config["email"] 			= $this->input->post('billingEmail');
			$config["night_phone_a"] 	= $this->input->post('billingPhone');
		} else {
			#Billing Option
			#Customer Information -->
			$country = $this->frontmodel->r_STN_Country($this->input->post('billingCountry'));
			$config["first_name"] 		= $this->input->post('billingName');
			$config["last_name"] 		= $this->input->post('billingSurname');
			$config["address1"] 		= $this->input->post('billingAddress').' '.$this->input->post('billingHouseNumber');
			$config["city"] 			= $this->input->post('billingCity');
			// Necessario codice ISO-2
			// $config["state"] 		= $this->input->post('billingState');
			$config["country"] 			= $country->countryCode;
                        $config["state"] 		= '';
			$config["zip"] 				= $this->input->post('billingPostalCode');
			$config["payer_email"] 		= $this->input->post('billingEmail');
//			$config["email"] 			= $this->input->post('billingEmail');
			$config["night_phone_a"] 	= $this->input->post('billingPhone');
		}
		
		$output = implode(', ', array_map(
		    function ($v, $k) { return $k . '=' . $v; }, 
		    $config, 
		    array_keys($config)
		));	
		
		if ( $this->session->userdata('idClient') ) {
			$config["custom"] = $this->session->userdata('idClient').'-'.$this->session->userdata('site_lang_code');
		} else {
			$config["custom"] 	= 'NULL-'.$this->session->userdata('site_lang_code');
		}

		$this->load->library('paypal',$config);
		
		if ( $this->session->userdata('idClient') ) {
			// Il cliente ha loggato
			// Compongo il campo custom con idClienti-SiteLangCode
			$currentCart = $this->frontmodel->ra_ORD_Cart('',$this->session->userdata('idClient'),'');
			$products = $this->frontmodel->r_ORD_Cart_Products('', $currentCart[0]->idCart, '', '', 1, '', $this->session->userdata('site_lang_code'));
			if ($products) {
				foreach ($products as $product) {
					// Controllo se lo sconto speciale è attivo e con la datazione valida
					if ($product->specialSaleDiscount != null && date('Y-m-d',strtotime($product->specialSaleStart)) <= date('Y-m-d') && date('Y-m-d',strtotime($product->specialSaleEnd)) >= date('Y-m-d') ) {
						$discount = $product->specialSaleDiscount;
					} else {
						$discount = $product->productDiscount;	
					}
					// Controllo il peso del prodotto
					$settings = $this->frontmodel->r_STN_Settings();
					$volumetricWeight = $product->productHeight * $product->productLenght * $product->productWidth * $settings->volumetricWeightCoeficent;
					if ($volumetricWeight >= $product->productWeight) {
						$cartWeight += $volumetricWeight;
					} else {
						$cartWeight += $product->productWeight;
					}
					// Aggiungo il prodotto
					// $this->paypal->add(<name>,<price>,<quantity>[Default 1],<code>[Optional],<discount>[Optional])
					// Passo come Product_id il numero della combinazione, in modo da riuscire a recuperare tutte le informazioni in face di generazione ordine
					$this->paypal->add( $product->productName, $product->productPrice * $this->session->userdata('site_currency_value'), $product->productQuantity, $product->idProduct.'-'.$product->idCombination, $discount);
				}
			}	
		} else {
			// Il cliente NON ha loggato
			// Compongo il campo custom con idClienti-SiteLangCode
			$products = [];
			$cartProducts = $this->session->userdata('cartProducts');
			if ($cartProducts) {
				foreach ($cartProducts as $cartProduct) {
					$product = $this->frontmodel->r_PRD_Product($cartProduct->idProduct,'',1,$this->session->userdata('site_lang_code'));
					// Controllo se lo sconto speciale è attivo e con la datazione valida
					if ($product->specialSaleDiscount != null && date('Y-m-d',strtotime($product->specialSaleStart)) <= date('Y-m-d') && date('Y-m-d',strtotime($product->specialSaleEnd)) >= date('Y-m-d') ) {
						$discount = $product->specialSaleDiscount * $this->session->userdata('site_currency_value');
					} else {
						$discount = $product->productDiscount * $this->session->userdata('site_currency_value');	
					}
					// Controllo il peso del prodotto
					$settings = $this->frontmodel->r_STN_Settings();
					$settings = $this->frontmodel->r_STN_Settings();
					$volumetricWeight = $product->productHeight * $product->productLenght * $product->productWidth * $settings->volumetricWeightCoeficent;
					if ($volumetricWeight >= $product->productWeight) {
						$cartWeight += $volumetricWeight;
					} else {
						$cartWeight += $product->productWeight;
					}
					// Aggiungo il prodotto
					// $this->paypal->add(<name>,<price>,<quantity>[Default 1],<code>[Optional],<discount>[Optional])
					// Passo come Product_id il numero della combinazione, in modo da riuscire a recuperare tutte le informazioni in face di generazione ordine
					$this->paypal->add( $product->productName, $product->productPrice * $this->session->userdata('site_currency_value'), $cartProduct->productQuantity, $cartProduct->idProduct.'-'.$cartProduct->idCombination, $discount);
				}
			}
		}
		
		// Trovo il corriere dei riferimento
		if ( isset($_POST['differentShipping']) ) {
			$courier = $this->frontmodel->ra_ORD_Couriers('', $this->session->userdata('shippingCountry') );
		} else {
			$courier = $this->frontmodel->ra_ORD_Couriers('', $this->session->userdata('billingCountry') );
		}
		$range = $this->frontmodel->ra_ORD_Couriers_Ranges('', $courier[0]->idCourier, $cartWeight);
		if ($range) {
			$this->paypal->shipping( '0.00' );
		} else {
			// 	$config["no_shipping"]
			//	Allowable values are:
			//	0 — prompt for an address, but do not require one
			//	1 — do not prompt for any address
			//	2 — prompt for an address, and require one
			$config["no_shipping"]    = 1;
		}
		
		$this->paypal->pay(); //Proccess the payment
	}
	public function checkPayment() {
		// Send an empty HTTP 200 OK response to acknowledge receipt of the notification 
		header('HTTP/1.1 200 OK');
		
		$risks = array();
		for ($i = 1; $i < 17; $i++) {
			array_push($risks, $_POST['fraud_management_pending_filters_'.$i]);
		}		
		
		// Build the required acknowledgement message out of the notification just received
		$req = 'cmd=_notify-validate';               		
		foreach ($_POST as $key => $value) {         
		    $value = urlencode(stripslashes($value));  
		    $req  .= "&$key=$value";                  
		}
		
		// Set up the acknowledgement request headers
		$header  = "POST /cgi-bin/webscr HTTP/1.1\r\n";                 
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: ".strlen($req)."\r\n\r\n";
		
		$num_cart_items	= $_POST['num_cart_items'];
		$residenceCountry = $this->frontmodel->ra_STN_Countries('', $_POST['residence_country'] );
		$addressCountry = $this->frontmodel->ra_STN_Countries('', $_POST['address_country_code'] );
		$payerName = $_POST['first_name'].' '.$_POST['last_name'];
		if ($_POST['payer_status'] == 'verified') {
			$verificationPaypal = 1;
		} else {
			$verificationPaypal = 0;
		}
		
		// Scompongo il campo custom con idClienti-SiteLangCode
		$custom = $_POST['custom'];
		$orderData = explode("-", $custom);
		$tableProducts = '';
		
		$to = "[TO_MAIL]";
		$subject = "IPN";
		$txt = $req;
		$headers = "From: [TO_MAIL]";
		mail($to,$subject,$txt,$headers);
		
		if ($orderData[0] == 'NULL') {
			// Non ha loggato
			// Creo l'ordine
			$data = array(
				'billingName' => $payerName,
				'billingIdCountry' => $residenceCountry[0]->idCountry,
				'billingState' => 'N.D',
				'billingCity' => 'N.D',
				'billingAddress' => 'N.D',
				'billingZip' => 'N.D',
				'billingEmail' => $_POST['payer_email'],
				'shippingName' => $_POST['address_name'],
				'shippingIdCountry' => $addressCountry[0]->idCountry,
				'shippingState' => 'N.D',
				'shippingCity' => $_POST['address_city'],
				'shippingAddress' => $_POST['address_street'],
				'shippingZip' => $_POST['address_zip'],
				'shippingNote' => '',
				'idPaypal' => $_POST['txn_id'],
				'verificationPaypal' => $verificationPaypal,
				'orderStatus' => 0,
				'orderAmount' => $_POST['mc_gross'],
				'shippingAmount' => $_POST['mc_shipping']
			);
			$returnedID = $this->frontmodel->c_ORD_Order($data);
			for ($i = 1; $i <= $num_cart_items; $i++) {
				// item_number è formato da: idProduct-idComibnation
				$productData = explode("-", $_POST['item_number'.$i]);
				$data = array(
					'idOrder' => $returnedID,
					'idProduct' => $productData[0],
					'idCombination' => $productData[1],
					'productQuantity' => $_POST['quantity'.$i],
					'productAmount' => $_POST['mc_gross_'.$i],
				);
				$this->frontmodel->c_ORD_Orders_Product($data,'');
				// Aggiorno la quantità della combinazione
				$currentCombination = $this->frontmodel->r_PRD_Combinations($productData[1],'','');
				$data = array (
					'combinationQuantity' => $currentCombination[0]->combinationQuantity - $_POST['quantity'.$i]
				);
				$this->frontmodel->u_PRD_Combination($productData[1], $data);
				// Leggo il prodotto e nella lingua corrente e popolo la tabella del riepilogo ordine
				$product = $this->frontmodel->r_PRD_Product($productData[0], 0, 1, $orderData[1]);
				$tableProducts .= '<tr style="font-size: 11px;"><td style="width: 70px;"><img style="width: 100%;" src="'.$this->config->item('resources_url').'/img/products/medium/'.$product->photoName.'"></td><td style="width: 360px; padding: 0px 10px;">'.$product->productName.'</td><td style="width: 30px;">x'.$_POST['quantity'.$i].'</td><td style="width: 140px; text-align: right;">'.$_POST['mc_gross_'.$i].' '.$_POST['mc_currency'].'</td></tr>';
			}
		} else {
			// Ha loggato
			// Creo l'ordine
			$dataOrder = array(
				'idClient' => $orderData[0],
				'billingName' => $payerName,
				'billingIdCountry' => $residenceCountry[0]->idCountry,
				'billingState' => 'N.D',
				'billingCity' => 'N.D',
				'billingAddress' => 'N.D',
				'billingZip' => 'N.D',
				'billingEmail' => $_POST['payer_email'],
				'shippingName' => $_POST['address_name'],
				'shippingIdCountry' => $addressCountry[0]->idCountry,
				'shippingState' => 'N.D',
				'shippingCity' => $_POST['address_city'],
				'shippingAddress' => $_POST['address_street'],
				'shippingZip' => $_POST['address_zip'],
				'shippingNote' => '',
				'idPaypal' => $_POST['txn_id'],
				'verificationPaypal' => $verificationPaypal,
				'orderStatus' => 0,
				'orderAmount' => $_POST['mc_gross'],
				'shippingAmount' => $_POST['mc_shipping']
			);
			
			$to = "[TO_MAIL]";
			$subject = "DATA DB";
			$txt = $dataOrder;
			$headers = "From: [TO_MAIL]";
			mail($to,$subject,$txt,$headers);
			
			$returnedID = $this->frontmodel->c_ORD_Order($dataOrder);
			for ($i = 1; $i <= $num_cart_items; $i++) {
				// item_number è formato da: idProduct-idComibnation
				$productData = explode("-", $_POST['item_number'.$i]);
				$data = array(
					'idOrder' => $returnedID,
					'idProduct' => $productData[0],
					'idCombination' => $productData[1],
					'productQuantity' => $_POST['quantity'.$i],
					'productAmount' => $_POST['mc_gross_'.$i],
				);
				$this->frontmodel->c_ORD_Orders_Product($data,'');
				// Aggiorno la quantità della combinazione
				$currentCombination = $this->frontmodel->r_PRD_Combinations($productData[1],'','');
				$data = array (
					'combinationQuantity' => $currentCombination[0]->combinationQuantity - $_POST['quantity'.$i]
				);
				$this->frontmodel->u_PRD_Combination($productData[1], $data);
				// Leggo il prodotto e nella lingua corrente e popolo la tabella del riepilogo ordine
				$product = $this->frontmodel->r_PRD_Product($productData[0], 0, 1, $orderData[1]);
				$tableProducts .= '<tr><td><img src="'.$this->config->item('resources_url').'/img/products/medium/'.$product->photoName.'"></td><td>'.$product->productName.'</td><td>'.$_POST['quantity'.$i].'</td><td>'.$_POST['mc_gross_'.$i].' '.$_POST['mc_currency'].'</td></tr>';
			}
		}
		// Mando la mail riepilogativa la cliente
		$receiver = $_POST['payer_email']; 
		
		switch ($orderData[1]) {
			default: 
				$subject = '[COMPANY_NAME] - Shipping Confirmation'; 
				$orderTitle = 'SHIPPING CONFIRMATION';
				$orderNumber = 'Order n°'.$_POST['txn_id'];
				$messageTitle = 'Dear '.$_POST['address_name'].',';
				$messageSubtitle = 'We inform you that your order has been successfully received and is being processed. From this moment on it is no longer possible to make any changes. If you want to return an item, view or modify other orders, visit the section "Returns" of [COMPANY_NAME].';
				$messageFooter = 'Depending on the selected shipping method, the tracking information may not be immediately available. For more information on the traceability of your order and the couriers used, see the relevant section "Shipping" of [COMPANY_NAME]';
				$mailFooter = '[MAIL_FOLDER]';
				break;
			case 'ar': 
				$subject = '[COMPANY_NAME] - النقل البحري تأكيدا'; 
				$orderTitle = 'تأكيد الشحن';
				$orderNumber = 'ترتيب n°'.$_POST['txn_id'];
				$messageTitle = 'عزيزي '.$_POST['address_name'].',';
				$messageSubtitle = 'علينا أن أبلغكم أن النظام الخاص بك قد تم تلقيها بنجاح وجاري معالجته. من هذه اللحظة على أنه لم يعد ممكناً إجراء أي تغييرات. إذا كنت ترغب في إرجاع صنف، عرض أو تعديل أوامر أخرى، قم بزيارة قسم "العودة" من [COMPANY_NAME].';
				$messageFooter = 'اعتماداً على طريقة الشحن المحدد، قد لا تتوفر على الفور معلومات التعقب. لمزيد من المعلومات حول التعقب طلبك والسعاة المستخدمة، انظر الفرع ذي الصلة "الشحن" من [COMPANY_NAME]';
				$mailFooter = '[MAIL_FOLDER]';
				break;
			case 'ca':	
				$subject = "[COMPANY_NAME] - Confirmació d'Enviament"; 
				$orderTitle = 'CONFIRMACIÓ DE NAVILI';
				$orderNumber = 'Ordre n°'.$_POST['txn_id'];
				$messageTitle = 'Estimat '.$_POST['address_name'].',';
				$messageSubtitle = 'Us informem que el seu ordre s&#39;ha rebut correctament i s&#39;està processant. Des d&#39;aquest moment ja no és possible fer cap canvi. Si voleu tornar un element, veure o modificar altres ordres, visiteu l&#39;apartat "Devolucions" de [COMPANY_NAME].';
				$messageFooter = 'Segons el mètode d&#39;enviament seleccionada, aquesta informació pot no estar immediatament disponible. Per a més informació sobre la traçabilitat de la comanda i dels correus que s&#39;utilitza, vegeu la secció corresponent "Navili" de [COMPANY_NAME]';
				$mailFooter = '[MAIL_FOLDER]';
				break;
			case 'zh-CN': 
				$subject = '[COMPANY_NAME] - 航运确认'; 
				$orderTitle = '发货确认信息';
				$orderNumber = '阶 n°'.$_POST['txn_id'];
				$messageTitle = '亲爱的'.$_POST['address_name'].',';
				$messageSubtitle = '我们通知您，您的订单已被成功接收，以及正在处理。从这一刻就是不再可能作出的任何更改。如果你想要返回的项目，查看或修改其他订单，请访问部分的 [COMPANY_NAME] 的"回报"。';
				$messageFooter = '根据所选的运输方法，跟踪信息可能不会立即可用。您的订单和信使使用的可追溯性的详细信息，请参阅相关部分"航运"的 [COMPANY_NAME]';
				$mailFooter = '[MAIL_FOLDER]';
				break;
			case 'cs':
				$subject = '[COMPANY_NAME] - Lodní Potvrzení'; 
				$orderTitle = 'POTVRZENÍ';
				$orderNumber = 'Pořadí n°'.$_POST['txn_id'];
				$messageTitle = 'Drahá '.$_POST['address_name'].',';
				$messageSubtitle = 'Informujeme vás, že vaše objednávka byla úspěšně přijata a je zpracováván. Od této chvíle již není možné provádět žádné změny. Pokud chcete vrátit položku, zobrazit nebo upravit jiné příkazy, navštivte sekci "Výnosy" z [COMPANY_NAME].';
				$messageFooter = 'Vybraný způsob dopravy zboží nemusí být okamžitě k dispozici informace o sledování. Získáte další informace o sledovatelnosti vaší objednávky a kurýři, použité, v příslušné části "Doprava" [COMPANY_NAME]';
				$mailFooter = '[MAIL_FOLDER]';
				break;
			case 'nl':
				$subject = '[COMPANY_NAME] - Verzending van de Bevestiging'; 
				$orderTitle = 'BEVESTIGING VAN DE VERZENDING';
				$orderNumber = 'Orde n°'.$_POST['txn_id'];
				$messageTitle = 'Lieve '.$_POST['address_name'].',';
				$messageSubtitle = 'Wij informeren u dat uw bestelling succesvol ontvangen is en wordt verwerkt. Vanaf dit moment daarop is niet meer mogelijk wijzigingen aan te brengen. Als u wilt om een item te retourneren, bekijken of wijzigen van andere bestellingen, ga naar de sectie "Rendement" van [COMPANY_NAME].';
				$messageFooter = 'Afhankelijk van de geselecteerde verzendmethode, het bijhouden van informatie mogelijk niet onmiddellijk beschikbaar. Voor meer informatie over de traceerbaarheid van uw bestelling en de koeriers gebruikt, zie de desbetreffende sectie "Verzendkosten" van [COMPANY_NAME]';
				$mailFooter = '[MAIL_FOLDER]';
				break;
			case 'et':
				$subject = '[COMPANY_NAME] - Shipping Kinnitus'; 
				$orderTitle = 'LAEVANDUSE KINNITUS';
				$orderNumber = 'Tellimuse n°'.$_POST['txn_id'];
				$messageTitle = 'Kallis '.$_POST['address_name'].',';
				$messageSubtitle = 'Me teatame teile, et teie tellimus on edukalt saanud ja on menetluses. Alates sellest hetkest, see pole enam võimalik teha mingeid muudatusi. Kui soovite üksust, saate vaadata või muuta teiste tellimuste, külastada jagu "Pöördub" [COMPANY_NAME].';
				$messageFooter = 'Sõltuvalt valitud meresõidu meetod jälitusteavet ei pruugi kohe saadaval. Rohkem infot jälgitavuse tellimuse ja kasutada kullerit, vt vastavas lahtris "Lähetamine" [COMPANY_NAME]';
				$mailFooter = '[MAIL_FOLDER]';
				break;
			case 'fr':
				$subject = '[COMPANY_NAME] - Confirmation d&#39;Expédition'; 
				$orderTitle = 'CONFIRMATION DE L&#39;EXPÉDITION';
				$orderNumber = 'Arrêté n°'.$_POST['txn_id'];
				$messageTitle = 'Dear '.$_POST['address_name'].',';
				$messageSubtitle = 'Nous vous informons que votre commande a été reçue avec succès et est en cours de traitement. Dès lors il n&#39;est plus possible d&#39;apporter des modifications. Si vous souhaitez renvoyer un article, afficher ou modifier les autres ordres, visitez la section « Retours » de [COMPANY_NAME].';
				$messageFooter = 'Selon la méthode de livraison sélectionnée, les informations de suivi ne soient pas immédiatement disponibles. Pour plus d&#39;informations sur la traçabilité de votre commande et les couriers utilisés, reportez-vous au paragraphe correspondant « Shipping » de [COMPANY_NAME]';
				$mailFooter = '[MAIL_FOLDER]';
				break;
			case 'de':	
				$subject = '[COMPANY_NAME] - Versandbestätigung'; 
				$orderTitle = 'VERSANDBESTÄTIGUNG';
				$orderNumber = 'Ordnung n°'.$_POST['txn_id'];
				$messageTitle = 'Sehr geehrte '.$_POST['address_name'].',';
				$messageSubtitle = 'Wir informieren Sie, dass Ihre Bestellung erfolgreich eingegangen und verarbeitet wird. Von diesem Moment an es ist nicht mehr möglich, Änderungen vorzunehmen. Wenn Sie einen Artikel zurückgeben möchten, anzeigen oder andere Aufträge zu ändern, besuchen Sie den Bereich "Renditen" von [COMPANY_NAME].';
				$messageFooter = 'Abhängig von der gewählten Versandart kann die Tracking-Informationen nicht sofort verfügbar sein. Weitere Informationen über die Rückverfolgbarkeit Ihrer Bestellung und die Kuriere verwendet finden Sie im Abschnitt relevanten "Versand" von [COMPANY_NAME]';
				$mailFooter = '[MAIL_FOLDER]';
				break;
			case 'el':
				$subject = '[COMPANY_NAME] - αποστολή επιβεβαίωσης'; 
				$orderTitle = 'ΕΠΙΒΕΒΑΊΩΣΗ ΑΠΟΣΤΟΛΉΣ';
				$orderNumber = 'Σειρά n°'.$_POST['txn_id'];
				$messageTitle = 'Αγαπητέ '.$_POST['address_name'].',';
				$messageSubtitle = 'Σας ενημερώνουμε ότι η παραγγελία σας έχει ληφθεί με επιτυχία και την επεξεργάζεται. Από αυτή τη στιγμή σε αυτό είναι πλέον δυνατό να κάνετε αλλαγές. Εάν θέλετε να επιστρέψετε οποιοδήποτε είδος, δείτε ή τροποποιήσετε άλλες παραγγελίες, επισκεφθείτε την ενότητα «Επιστροφές» του [COMPANY_NAME].';
				$messageFooter = 'Ανάλογα με την επιλεγμένη μέθοδο αποστολής, ενδέχεται να μην αμέσως διαθέσιμες πληροφορίες παρακολούθησης. Για περισσότερες πληροφορίες σχετικά με την ανιχνευσιμότητα της παραγγελίας σας και οι μεταφορείς χρησιμοποιούνται, δείτε το σχετικό τμήμα «Αποστολή» της [COMPANY_NAME]';
				$mailFooter = '[MAIL_FOLDER]';
				break;
			case 'iw':
				$subject = '[COMPANY_NAME] - משלוח אישור'; 
				$orderTitle = 'משלוח אישור';
				$orderNumber = 'הזמנה n°'.$_POST['txn_id'];
				$messageTitle = 'יקירתי '.$_POST['address_name'].',';
				$messageSubtitle = 'אנו להודיע לך כי הזמנתך התקבלה בהצלחה, מעובדת. מרגע זה והלאה זה עוד אפשרי לבצע שינויים כלשהם. אם ברצונך להחזיר פריט, להציג או לשנות הזמנות אחרות, בקר בסעיף "חוזר" של [COMPANY_NAME].';
				$messageFooter = 'בהתאם לשיטת המשלוח שנבחר, לא ייתכן המידע הזמין באופן מיידי. לקבלת מידע נוסף על עקיבות הזמנתך, השליחים בשימוש, עיין בסעיף הרלוונטי "משלוח" של [COMPANY_NAME]';
				$mailFooter = '[MAIL_FOLDER]';
				break;
			case 'id':
				$subject = '[COMPANY_NAME] - Pengiriman Konfirmasi'; 
				$orderTitle = 'KONFIRMASI PENGIRIMAN';
				$orderNumber = 'Urutan n°'.$_POST['txn_id'];
				$messageTitle = 'Sayang '.$_POST['address_name'].',';
				$messageSubtitle = 'Kami menginformasikan bahwa pesanan Anda telah berhasil diterima dan sedang diproses. Dari saat ini hal ini tidak mungkin lagi untuk membuat perubahan. Jika Anda ingin mengembalikan barang, lihat atau mengubah pesanan lain, kunjungi bagian "Kembali" dari [COMPANY_NAME].';
				$messageFooter = 'Tergantung pada metode pengiriman yang dipilih, informasi pelacakan mungkin tidak akan tersedia segera. Untuk informasi lebih lanjut tentang penelusuran pesanan Anda dan kurir yang digunakan, lihat bagian yang relevan "Pengiriman" [COMPANY_NAME]';
				$mailFooter = '[MAIL_FOLDER]';
				break;
			case 'it':
				$subject = '[COMPANY_NAME] - Conferma di Spedizione'; 
				$orderTitle = 'CONFERMA DI SPEDIZIONE';
				$orderNumber = 'Ordine n°'.$_POST['txn_id'];
				$messageTitle = 'Caro '.$_POST['address_name'].',';
				$messageSubtitle = 'Vi informiamo che il vostro ordine è stato ricevuto correttamente e che è in fase di elaborazione. Da questo momento su di esso non è più possibile apportare modifiche. Se si desidera restituire un articolo, visualizzare o modificare altri ordini, visita la sezione "Resi" di [COMPANY_NAME].';
				$messageFooter = 'A seconda del metodo di spedizione selezionato, le informazioni di rilevamento possono non essere immediatamente disponibili. Per ulteriori informazioni sulla rintracciabilità del vostro ordine e i corrieri utilizzati, vedere l&#39;apposita sezione "Spedizione" di [COMPANY_NAME]';
				$mailFooter = '[MAIL_FOLDER]';
				break;
			case 'ja':
				$subject = '[COMPANY_NAME] - 出荷確認'; 
				$orderTitle = '出荷の確認';
				$orderNumber = '注文 n°'.$_POST['txn_id'];
				$messageTitle = '親愛なります。'.$_POST['address_name'].',';
				$messageSubtitle = 'お知らせご注文が正常に受信され、されます。それをこの瞬間からはや何も変更することが可能です。アイテムを取得する場合は、表示または他の注文を変更、[COMPANY_NAME] の「リターン」のセクションをご覧ください。';
				$messageFooter = '選択した配送方法によっては、追跡情報をすぐに使用できない場合があります。ご注文と宅配便使用のトレーサビリティについてを参照してください関連する [COMPANY_NAME] の「送料」';
				$mailFooter = '[MAIL_FOLDER]';
				break;
			case 'ko':
				$subject = '[COMPANY_NAME] - 배송 확인'; 
				$orderTitle = '배송 확인';
				$orderNumber = '순서 n°'.$_POST['txn_id'];
				$messageTitle = '친애 하는 '.$_POST['address_name'].',';
				$messageSubtitle = '주문이 성공적으로 수신 및 처리 되 고 우리는 당신을 알릴. 그것은이 순간부터 변경 수 이상입니다. 항목을 반환 하려면 보기 또는 다른 주문 수정, [COMPANY_NAME]의 "반환" 섹션을 방문 하십시오.';
				$messageFooter = '선택한 배송 방법에 따라 추적 정보 수 없습니다 즉시 사용할 수 있습니다. 자세한 내용은 추적에 주문을 사용 하는 특사의 참조 하십시오 관련 섹션 [COMPANY_NAME]의 "배송"';
				$mailFooter = '[MAIL_FOLDER]';
				break;
			case 'ms':
				$subject = '[COMPANY_NAME] - Pengesahan Penghantaran';  
				$orderTitle = 'PENGESAHAN PENGHANTARAN';
				$orderNumber = 'Pesanan n°'.$_POST['txn_id'];
				$messageTitle = 'Dear '.$_POST['address_name'].',';
				$messageSubtitle = 'Kami akan memaklumkan kepada anda bahawa pesanan anda telah berjaya diterima dan sedang diproses. Dari saat ini ia didapati tidak lagi untuk membuat sebarang perubahan. Jika anda ingin kembali item, melihat atau mengubahsuai arahan lain, melawat bahagian "Pulangan" [COMPANY_NAME].';
				$messageFooter = 'Bergantung kepada kaedah penghantaran yang terpilih, maklumat pengesanan tidak boleh didapati dengan segera. Untuk maklumat lanjut mengenai kebolehan pesanan anda dan Kurir yang digunakan, lihat seksyen yang berkaitan "Penghantaran" [COMPANY_NAME]';
				$mailFooter = '[MAIL_FOLDER]';
				break;
			case 'pl':
				$subject = '[COMPANY_NAME] - Wysyłka Potwierdzenie'; 
				$orderTitle = 'POTWIERDZENIA WYSYŁKI';
				$orderNumber = 'Porządku n°'.$_POST['txn_id'];
				$messageTitle = 'Szanowni Państwo '.$_POST['address_name'].',';
				$messageSubtitle = 'Informujemy, że Twoje zamówienie zostało pomyślnie odebrane i jest przetwarzana. Od tej chwili na to już nie jest możliwe dokonanie zmian. Jeśli chcesz zwrotu towaru, widok lub modyfikować inne zamówienia, przejdź do sekcji "Zwraca" [COMPANY_NAME].';
				$messageFooter = 'W zależności od wybranej metody wysyłki informacje śledzenia mogą nie być dostępne natychmiast. Uzyskać więcej informacji na temat śledzenia zamówienia i kurierów, używane zobacz sekcję odpowiednich "Wysyłka" [COMPANY_NAME]';
				$mailFooter = '[MAIL_FOLDER]';
				break;
			case 'pt':
				$subject = '[COMPANY_NAME] - Confirmação de Envio'; 
				$orderTitle = 'CONFIRMAÇÃO DE ENVIO';
				$orderNumber = 'Ordem n°'.$_POST['txn_id'];
				$messageTitle = 'Querida '.$_POST['address_name'].',';
				$messageSubtitle = 'Informamos que seu pedido foi recebido com sucesso e está sendo processado. A partir deste momento já não é possível fazer qualquer alteração. Se você quiser retornar um item, Visualizar ou modificar outras ordens, visite a seção "Devoluções" de [COMPANY_NAME].';
				$messageFooter = 'Dependendo do método de envio selecionado, as informações de rastreamento podem não estar imediatamente disponíveis. Para obter mais informações sobre a rastreabilidade de sua ordem e os correios usados, consulte a seção relevante "Transporte" de [COMPANY_NAME]';
				$mailFooter = '[MAIL_FOLDER]';
				break;
			case 'ru':
				$subject = '[COMPANY_NAME] - доставка подтверждения'; 
				$orderTitle = 'ПОДТВЕРЖДЕНИЕ ДОСТАВКИ';
				$orderNumber = 'Порядка n°'.$_POST['txn_id'];
				$messageTitle = 'Дорогой '.$_POST['address_name'].',';
				$messageSubtitle = 'Сообщаем вам, что ваш заказ был успешно принят и обрабатывается. С этого момента это больше не возможно сделать любые изменения. Если вы хотите вернуть товар, просматривать или изменять другие заказы, посетите раздел «Возвращает» [COMPANY_NAME].';
				$messageFooter = 'В зависимости от выбранного способа доставки данные отслеживания не может быть немедленно доступны. Для получения дополнительной информации об отслеживании вашего заказа и курьеры используются смотрите соответствующий раздел «Отправка» [COMPANY_NAME]';
				$mailFooter = '[MAIL_FOLDER]';
				break;
			case 'es':
				$subject = '[COMPANY_NAME] - Confirmación de Envío';  
				$orderTitle = 'CONFIRMACIÓN DE ENVÍO';
				$orderNumber = 'Orden n°'.$_POST['txn_id'];
				$messageTitle = 'Estimado '.$_POST['address_name'].',';
				$messageSubtitle = 'Le informamos que su pedido ha sido recibido correctamente y se está procesando. A partir de este momento ya no es posible realizar cambios. Si desea devolver un artículo, ver o modificar otras órdenes, visite la sección "Devoluciones" de [COMPANY_NAME].';
				$messageFooter = 'Dependiendo del método de envío seleccionado, la información de rastreo no puede estar inmediatamente disponible. Para más información sobre la trazabilidad de su orden y las empresas de transporte utilizados, vea la sección "Envío" de [COMPANY_NAME]';
				$mailFooter = '[MAIL_FOLDER]';
				break;
			case 'tr':
				$subject = '[COMPANY_NAME] - Onay Nakliye'; 
				$orderTitle = 'NAKLİYE ONAY';
				$orderNumber = 'Sipariş n°'.$_POST['txn_id'];
				$messageTitle = 'Sevgili '.$_POST['address_name'].',';
				$messageSubtitle = 'Siparişiniz başarıyla alınan ve işleniyor bilgilendirmek. Şu andan itibaren artık herhangi bir değişiklik yapmak mümkün değil. Bir öğeyi döndürmek istiyorsanız, görüntülemek veya diğer emirleri değiştirmek, [COMPANY_NAME] "döner" bölümünü ziyaret edin.';
				$messageFooter = 'Seçili sevkiyat yöntemine bağlı olarak, izleme bilgileri hemen kullanılamayabilir. Sipariş ve kullanılan kuryeler izlenebilirlik üzerinde daha fazla bilgi için "Nakliye" [COMPANY_NAME] ilgili bölümüne bakın';
				$mailFooter = '[MAIL_FOLDER]';
				break;
		}
		$this->orderMail($subject, $receiver, $orderTitle, $orderNumber, $messageTitle, $messageSubtitle, $tableProducts, $messageFooter, $mailFooter);
	}
	
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
