<?php

/**
 * @group Model
 */

class institution_model_test extends CIUnit_TestCase
{
	/**
	 * @var _im
	 */
	private $_im;
	
	public function __construct($name = NULL, array $data = array(), $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
	}
	
	
	public function setUp()
	{
		parent::setUp();
		
		
		$this->CI->load->model('institution_model');
		$this->_im=$this->CI->institution_model;
		$this->dbfixt('man_institutions');
		
	}
	
	
	public function tearDown()
	{
		parent::tearDown();
	}
	
	
	public function test_get_institution_ids()
	{
		$expected = array();
		foreach($this->man_institutions_fixt as $row)
		{
			array_push($expected, $row['id']);
		}
		
		$this->assertEquals($expected, $this->_im->get_institution_ids());
		
	}
	
	
	public function test_get_institutions()
	{
		$expected = array();
		$x=0;
		foreach($this->man_institutions_fixt as $row)
		{
			$expected[$x] = array();
			$expected[$x]["id"] = $row["id"];
			$expected[$x]["name"] = htmlentities($row["name"]);
			$expected[$x]["lat"] = $row["lat"];
			$expected[$x]["long"] = $row["long"];
			$expected[$x]["maps_url"] = "http://maps.google.com/maps?q=".$row['lat'].",".$row['long'];
			$x++;
		}
		$this->CI->load->library('helper');
		$expected = $this->CI->helper->msort($expected, "name");
		
		$this->assertEquals($expected, $this->_im->get_institutions());
	}
	
	
	public function test_create_maps_url()
	{
		$this->assertEquals("http://maps.google.com/maps?q=1.234,-1.234", $this->_im->create_maps_url("1.234", "-1.234"));
	}
	
	

	public function test_search_institution()
	{
		$expected = array(array());

		$row = $this->man_institutions_fixt['row2'];
		
		$expected[0]["name"] = htmlentities($row["name"]);
		$expected[0]["lat"] = $row["lat"];
		$expected[0]["long"] = $row["long"];
		$expected[0]["maps_url"] = "http://maps.google.com/maps?q=".$row['lat'].",".$row['long'];
		$expected[0]["in_db"] = TRUE;
	
		//Returns from db
		$this->assertEquals($expected, $this->_im->search_institution($row["name"]));
		$this->assertNotEquals($expected, $this->_im->search_institution("University"));
		
		//Returns from google api
		$result = $this->_im->search_institution("University of Tallinn");
		$this->assertCount(7, $result[0]);
		$this->assertEquals("University of Tallinn", $result[0]["name"]);
		$this->assertStringMatchesFormat("%f", $result[0]["lat"]);
		
		//All should fail
		$this->assertEquals(NULL, $this->_im->search_institution("asdlfj"));
		
	}

	
	public function test_get_institution_name()
	{
		$expected = $this->man_institutions_fixt['row1']['name'];
		$input_id = $this->man_institutions_fixt['row1']['id'];
		
		$this->assertEquals($expected, $this->_im->get_institution_name($input_id));
		
		$this->assertEquals(NULL, $this->_im->get_institution_name(9999));
	}
	
	

	public function test_get_institution_coordinates()
	{	
		$lat = $this->man_institutions_fixt['row1']['lat'];
		$long = $this->man_institutions_fixt['row1']['long'];
		$expected = array('lat' => $lat, 'long'=> $long);
		
		$input_id = $this->man_institutions_fixt['row1']['id'];
		
		$this->assertEquals($expected, $this->_im->get_institution_coordinates($input_id));
		
		$this->assertEquals(NULL, $this->_im->get_institution_coordinates(9999));
		}
		
		

	public function test_save_institution()
	{
		$data_array = array(		
			'name'=>"Test-uni",
			'lat'=>"1,234",
			'long'=>"-1,234",
			'maps_url'=>"blah",
			'country_name'=>"Albania",
			'country_code'=>"AL");
		
		$this->_im->save_institution($data_array);
		$this->CI->load->database();
		
		$this->assertEquals(6, $this->CI->db->count_all('man_institutions'));
		
		$query = $this->CI->db->query("SELECT * FROM man_institutions WHERE name = 'Test-uni'");
		
		$row = $query->row();
		
		$this->assertEquals($data_array["lat"], $row->lat);
		$this->assertEquals($data_array["long"], $row->long);
		
	}

	
	
}
