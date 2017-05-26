<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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

class UT_translator {

	protected $_grantType = "client_credentials";
	protected $_scopeUrl = 'http://api.microsofttranslator.com';
	protected $_clientID;
	protected $_clientSecret;
	protected $_authenticationUrl = 'https://datamarket.accesscontrol.windows.net/v2/OAuth2-13/';
	protected $_translateUrl = 'http://api.microsofttranslator.com/v2/Http.svc/Translate?';
	protected $_accessToken;
	
	public function __construct() {}
	
	public function initialize(array $params = array()) {
	
		if (isset($params) && is_array($params))
		{
			$this->_clientID = $params['client_id'];
			$this->_clientSecret = $params['client_secret'];
		}
	     
	    log_message('info', 'Translator Class Initialized');
	}
	
	public function setTokens() {
	
        //Create the request Array.
        $params = array (
             'grant_type'    => $this->_grantType,
             'scope'         => $this->_scopeUrl,
             'client_id'     => $this->_clientID,
             'client_secret' => $this->_clientSecret
        );
        
        require_once(APPPATH.'/libraries/utilities/UT_curl.php');
        
		// Load the UT curl
		$curl_file = APPPATH.'/libraries/utilities/UT_curl.php';
		
		file_exists($curl_file) OR show_error('Invalid UT curl');
		require_once($curl_file);
		
		// Instantiate the UT curl
		$driver = 'UT_curl';
		$UT = new $driver($params);
		$UT->initialize();       
        
        // Set the parameters and execute the call
        $params = http_build_query($params);
        $UT->setPost($params);
        $UT->createCurl($this->_authenticationUrl);
        
        if($UT->getHttpNoError()){
        	$error = $UT->getHttpError();
        	throw new exception();
        } else {
        	$httpResponse = $UT->getHttpResponse();
        	$objectResponse = json_decode($httpResponse);
        	
        	if(isset($objectResponse->access_token)) {
        		$this->_accessToken = $objectResponse->access_token;
        	} else {
        		throw new exception();
        	}
        }
	}
	function curlRequest($from, $to, $text){
	
		require_once(APPPATH.'/libraries/utilities/UT_curl.php');
		
		// Load the UT curl
		$curl_file = APPPATH.'/libraries/utilities/UT_curl.php';
		
		file_exists($curl_file) OR show_error('Invalid UT curl');
		require_once($curl_file);
		
		// Instantiate the UT curl
		$library = 'UT_curl';
		$UT = new $library();
		$UT->initialize();  
		
		if($text && $this->_accessToken) {
			// Set the http header
			$httpHeader = array(
				"Authorization: Bearer ".$this->_accessToken, 
				"Content-Type: text/xml"
			);			

			$UT->setHttpHeader($httpHeader);
			
			$UT->createCurl($this->_translateUrl."&text=".urlencode($text)."&from=".$from."&to=".$to);

			if($UT->getHttpNoError()){
				$error = $UT->getHttpError();
				throw new exception();
			} else {
				$httpResponse = $UT->getHttpResponse();
				$xml = simplexml_load_string($httpResponse);
				return $xml[0];
			}
		} else {
			throw new exception();
		}
	}
	
}
// ------------------------------------------------------------------------