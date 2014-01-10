<?php

/**
 * @group Model
 */

class research_group_model_test extends CIUnit_TestCase
{
	/**
	 * @var _rgm
	 */
	private $_rgm;
	
	public function __construct($name = NULL, array $data = array(), $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
	}
	
	
	public function setUp()
	{
		parent::setUp();
		
		
		$this->CI->load->model('research_group_model');
		$this->_rgm=$this->CI->research_group_model;
		$this->dbfixt('man_research_groups');
		
	}
	
	
	public function tearDown()
	{
		parent::tearDown();
	}
	


	public function test_get_research_group_ids()
	{
		$expected = array();
		
		foreach($this->man_research_groups_fixt as $row)
		{
			array_push($expected, $row["id"]);
		}
		
		$this->assertEquals($expected, $this->_rgm->get_research_group_ids());
	}


	
	public function test_get_research_group_name()
	{
		$expected = $this->man_research_groups_fixt["row1"]["name"];
		$id = $this->man_research_groups_fixt["row1"]["id"];

		$this->assertEquals($expected, $this->_rgm->get_research_group_name($id));
		$this->assertEquals(NULL, $this->_rgm->get_research_group_name(9999));
		
	}
	
	
	
	
}
