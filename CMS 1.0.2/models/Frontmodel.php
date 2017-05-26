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
}