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

class HttpTranslator {

	function curlRequest($url, $authHeader, $postData=''){
		try {
		    //Initialize the Curl Session.
		    $ch = curl_init();
		    //Set the Curl url.
		    curl_setopt ($ch, CURLOPT_URL, $url);
		    //Set the HTTP HEADER Fields.
		    curl_setopt ($ch, CURLOPT_HTTPHEADER, array($authHeader,"Content-Type: text/xml"));
		    //CURLOPT_RETURNTRANSFER- TRUE to return the transfer as a string of the return value of curl_exec().
		    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
		    //CURLOPT_SSL_VERIFYPEER- Set FALSE to stop cURL from verifying the peer's certificate.
		    curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, False);
		    if($postData) {
		        //Set HTTP POST Request.
		        curl_setopt($ch, CURLOPT_POST, TRUE);
		        //Set data to POST in HTTP "POST" Operation.
		        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		    }
		    //Execute the  cURL session.
		    $curlResponse = curl_exec($ch);
		    //Get the Error Code returned by Curl.
		    $curlErrno = curl_errno($ch);
		    if ($curlErrno) {
		        $curlError = curl_error($ch);
		        throw new exception($curlError);
		    }
		    //Close a cURL session.
		    curl_close($ch);
		    return $curlResponse;
	    } catch (exception $exception) {
	    	exit();
	    }
	}

	function createReqXML($languageCode) {
	    //Create the Request XML.
	    $requestXml = '<ArrayOfstring xmlns="http://schemas.microsoft.com/2003/10/Serialization/Arrays" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">';
	    if($languageCode) {
	        $requestXml .= "<string>$languageCode</string>";
	    } else {
	        throw new Exception('Language Code is empty.');
	    }
	    $requestXml .= '</ArrayOfstring>';
	    return $requestXml;
	} 
}