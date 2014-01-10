<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 


/**
 * This class is used to set the default from parameter in e-mails
 * The values for default from address and name are taken from email conf file
 * @author karlkerem
 *
 */

class MY_Email extends CI_Email {
	
	var $def_from = "admin@example.com";
	var $def_from_name = "Admin";

	protected $CI;
	
	public function __construct($config = array())
	{
		parent::__construct($config);
		
		$this->CI =& get_instance();
		$this->CI->load->config('email');
		
		
		$this->def_from = $this->CI->config->item('def_from');
		$this->def_from_name = $this->CI->config->item('def_from_name');
		
		
		$this->from($this->def_from, $this->def_from_name);
	}
	



}