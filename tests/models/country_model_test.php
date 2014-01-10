<?php

/**
 * @group Model
 */

class country_model_test extends CIUnit_TestCase
{
	/**
	 * @var _cm
	 */
	private $_cm;
	
	public function __construct($name = NULL, array $data = array(), $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
	}
	
	
	public function setUp()
	{
		parent::setUp();
		
		
		$this->CI->load->model('country_model');
		$this->_cm=$this->CI->country_model;
		$this->dbfixt('man_countries');
		
	}
	
	
	public function tearDown()
	{
		parent::tearDown();
	}
	

	
	public function test_get_country_id()
	{
		$id = $this->man_countries_fixt['row3']['id'];
		$alpha_2 = $this->man_countries_fixt['row3']['alpha_2'];
		
		$this->assertEquals($id, $this->_cm->get_country_id($alpha_2));
		$this->assertEquals(NULL, $this->_cm->get_country_id("dfalkšdsjf"));
		
	}
	
	
	
	


	
	
}
