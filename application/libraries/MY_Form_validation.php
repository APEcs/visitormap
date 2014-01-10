<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {
    
    function unset_field_data()
    {    
        unset($this->_field_data);
        $this->_field_data = array();
        log_message('debug', "Form Validation Field Data Unset");
    }
} 