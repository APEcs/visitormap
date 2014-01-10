<?php

/**
 * @group Model
 */

class person_model_test extends CIUnit_TestCase
{
	/**
	 * @var _pm
	 */
	private $_pm;
	
	public function __construct($name = NULL, array $data = array(), $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
	}
	
	
	public function setUp()
	{
		parent::setUp();
		
		
		$this->CI->load->model('person_model');
		$this->_pm=$this->CI->person_model;
		$this->dbfixt('man_persons');
		$this->dbfixt('man_random_names');
		$this->dbfixt('man_research_group_members');
		
	}
	
	
	public function tearDown()
	{
		parent::tearDown();
	}
	


	public function test_get_random_name()
	{
		$options = array();
		foreach($this->man_random_names_fixt as $row)
		{
			array_push($options, $row["first_name"]);
		}
		
		$this->assertContains($this->_pm->get_random_name("first"), $options);
	
		$options2 = array();
		foreach($this->man_random_names_fixt as $row)
		{
			array_push($options, $row["last_name"]);
		}
		
		$this->assertContains($this->_pm->get_random_name("last"), $options);	
		
	}
	

	public function test_insert_visitor()
	{
		$first_name = "Karl";
		$last_name = "Kerem";
		$this->_pm->insert_visitor($first_name, $last_name);
		
		$this->CI->load->database();
		$query = $this->CI->db->query("SELECT * FROM man_persons WHERE first_name = '$first_name' AND last_name = '$last_name'");
		$row = $query->row();
		
		$this->assertEquals($first_name, $row->first_name);
		$this->assertEquals($last_name, $row->last_name);
	}
	

	
	public function test_get_person_name()
	{
		$first_name = $this->man_persons_fixt["row2"]["first_name"];
		$last_name = $this->man_persons_fixt["row2"]["last_name"];
		$id = $this->man_persons_fixt["row2"]["id"];
		
		$this->assertEquals($first_name, $this->_pm->get_person_name($id, "first"));
		$this->assertEquals($last_name, $this->_pm->get_person_name($id, "last"));
		$this->assertEquals(NULL, $this->_pm->get_person_name("ajsghak"));
	}
	
	
	
	public function test_get_hosts()
	{
		$group_id = $this->man_research_group_members_fixt["row1"]["group_id"];
		$person_id = $this->man_research_group_members_fixt["row1"]["person_id"];
		
		$result = $this->_pm->get_hosts($group_id);
		
		$this->assertEquals($person_id, $result[0]['person_id']);
	
		$this->assertEquals(NULL, $this->_pm->get_hosts(99999));
	}
	
	
	
	public function test_get_host_names()
	{
		$expected = array();
		
		$x=0;
		foreach($this->man_persons_fixt as $row)
		{
			if($row["type"]=="host")
			{
				$expected[$x] = array();
				$expected[$x]["id"] = $row["id"];
				$expected[$x]["first_name"] = $row["first_name"];
				$expected[$x]["last_name"] = $row["last_name"];
				$x++;
			}
		}
		$this->CI->load->library('helper');
		$expected = $this->CI->helper->msort($expected, "first_name");
		
		$this->assertEquals($expected, $this->_pm->get_host_names());
		
	}
	

	
	
	
}
