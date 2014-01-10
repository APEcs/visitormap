<?php

/**
 * @group Model
 */

class google_model_test extends CIUnit_TestCase
{
	/**
	 * @var _gm
	 */
	private $_gm;
	
	public function __construct($name = NULL, array $data = array(), $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
	}
	
	
	public function setUp()
	{
		parent::setUp();
		
		
		$this->CI->load->model('google_model');
		$this->_gm=$this->CI->google_model;
		
	}
	
	
	public function tearDown()
	{
		parent::tearDown();
	}
	

	
	/**
	 * Returns an array with following keys:
	 * 	'lat', 'long', 'country_name', 'country_code'
	 * If error, returns NULL
	 * @param String $string - the address to look for
	 * @return NULL|multitype:unknown
	 */
	public function test_lookup()
	{

		$result=$this->_gm->lookup("Tallinn University of Technology");
		
		$this->assertEquals("Estonia", $result["country_name"]);
		$this->assertEquals("EE", $result["country_code"]);
		$this->assertStringMatchesFormat("%f", $result["lat"]);
		$this->assertStringMatchesFormat("%f", $result["long"]);
		
		$result2 = $this->_gm->lookup("jbkadhlsfk");
		$this->assertEquals(NULL, $result2);
		
	}
	
	
	
	


	
	
}
