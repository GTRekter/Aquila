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

class UT_curl {

	protected $_url;
	protected $_followlocation;
	protected $_timeout;
	protected $_maxRedirects;
	protected $_post;
	protected $_postFields;
	
	protected $_session;
	protected $_webpage;
	protected $_includeHeader;
	protected $_includeHttpHeader;
	protected $_httpHeader;
	protected $_noBody;
	protected $_status;
	protected $_error;
	protected $_noError;
	protected $_useragent;
	protected $_referer;
	
	public    $ssl = false;
	public    $authentication = 0;
	public    $auth_name      = '';
	public    $auth_pass      = '';
	
	public function __construct() {}
	
	public function initialize($url = '',$followlocation = true,$timeOut = 30,$maxRedirecs = 4,$includeHeader = false,$noBody = false) {
	     $this->_url = $url;
	     $this->_followlocation = $followlocation;
	     $this->_timeout = $timeOut;
	     $this->_maxRedirects = $maxRedirecs;
	     $this->_noBody = $noBody;
	     $this->_includeHeader = $includeHeader;
	     $this->_httpHeader = array('Expect:');
	     
	     log_message('info', 'cURL Class Initialized');
	}
	public function useSsl($use) {
	   $this->ssl = false;
	   if($use == true) $this->ssl = true;
	}
	public function useAuth($use) {
	   $this->authentication = 0;
	   if($use == true) $this->authentication = 1;
	}
	public function setHttpHeader($httpHeader){
		$this->_includeHttpHeader = true;
		$this->_httpHeader = $httpHeader;
	}
	public function setName($name) {
	   $this->auth_name = $name;
	}
	public function setPass($pass) {
	   $this->auth_pass = $pass;
	}
	public function setReferer($referer) {
	   $this->_referer = $referer;
	}
	public function setPost ($postFields) {
	    $this->_post = true;
	    $this->_postFields = $postFields;
	}
	public function setUserAgent($userAgent) {
	     $this->_useragent = $userAgent;
	}
	public function createCurl($url = 'null') {
		if($url != 'null') {
			$this->_url = $url;
		}
		$s = curl_init();
		curl_setopt($s,CURLOPT_URL,$this->_url);
		if($this->_includeHttpHeader == true){
			curl_setopt($s,CURLOPT_HTTPHEADER,$this->_httpHeader);
		}
		curl_setopt($s,CURLOPT_TIMEOUT,$this->_timeout);
		curl_setopt($s,CURLOPT_MAXREDIRS,$this->_maxRedirects);
		curl_setopt($s,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($s,CURLOPT_FOLLOWLOCATION,$this->_followlocation);
		if($this->ssl == true){
			curl_setopt($s, CURLOPT_SSL_VERIFYPEER, true);
		}
		if($this->authentication == 1){
			curl_setopt($s, CURLOPT_USERPWD, $this->auth_name.':'.$this->auth_pass);
		}
		if($this->_post) {
			curl_setopt($s,CURLOPT_POST,true);
			curl_setopt($s,CURLOPT_POSTFIELDS,$this->_postFields);
		}
		if($this->_includeHeader) {
			curl_setopt($s,CURLOPT_HEADER,true);
		}
		if($this->_noBody) {
			curl_setopt($s,CURLOPT_NOBODY,true);
		}
		curl_setopt($s,CURLOPT_USERAGENT,$this->_useragent);
		curl_setopt($s,CURLOPT_REFERER,$this->_referer);	
			
		$this->_webpage = curl_exec($s);
		$this->_noError = curl_errno($s);
		$this->_error = curl_error($s);
		$this->_status = curl_getinfo($s,CURLINFO_HTTP_CODE);
		curl_close($s);
    }
	public function getHttpStatus() {
	   return $this->_status;
	}
	public function getHttpResponse(){
	  return $this->_webpage;
	}
	public function getHttpError(){
	  return $this->_error;
	}
	public function getHttpNoError(){
	  return $this->_noError;
	}
	
}
