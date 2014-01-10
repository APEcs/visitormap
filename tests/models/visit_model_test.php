<?php

/**
 * @group Model
 */

class visit_model_test extends CIUnit_TestCase
{
	/**
	 * @var _vm
	 */
	private $_vm;
	
	public function __construct($name = NULL, array $data = array(), $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
	}
	
	
	public function setUp()
	{
		parent::setUp();
		
		
		$this->CI->load->model('visit_model');
		$this->_vm=$this->CI->visit_model;
		$this->dbfixt('man_visits');
		$this->CI->load->library('session');
		
	}
	
	
	public function tearDown()
	{
		parent::tearDown();
		$this->CI->session->sess_destroy();
	}
	


	
	public function test_generate_visits()
	{

		$qty=3;
		$from_date="01.01.2000";
		$to_date="31.12.2000";
		$min_stay=1;
		$max_stay=10;
		
		
		$this->_vm->generate_visits($qty, $from_date, $to_date, $min_stay, $max_stay);
		
		
		$visits = $this->CI->session->userdata('visits');
		
		$this->assertEquals(3, count($visits));
		
		$this->assertStringMatchesFormat('%d', $visits[0]['group_id']);
		$this->assertStringMatchesFormat('%s', $visits[0]['group']);
		$this->assertStringMatchesFormat('%d', $visits[0]['institution_id']);
		$this->assertStringMatchesFormat('%s', $visits[0]['institution']);
		$this->assertStringMatchesFormat('%s', $visits[0]['from_date']);
		$this->assertStringMatchesFormat('%s', $visits[0]['to_date']);
		$this->assertStringMatchesFormat('%s', $visits[0]['visitor_first_name']);
		$this->assertStringMatchesFormat('%s', $visits[0]['visitor_last_name']);
		
	}
	

	public function test_save_visits()
	{
		$visit = array(array(
				'group_id'=>1,
				'institution_id'=>1,
				'host_id'=>2,
				'guest_id'=>134,
				'from_date'=>"2002-01-01",
				'to_date'=>"2002-03-05",
				'visitor_first_name'=>'Jolene',
				'visitor_last_name'=>'Hensley'));
	
		$this->CI->session->set_userdata('visits', $visit);
		
		$this->_vm->save_visits();

		$this->CI->load->database();
		
		$query = $this->CI->db->query("SELECT * FROM man_visits");
		$row = $query->row(5);
		
		$this->assertEquals(6, $query->num_rows());
		$this->assertEquals($visit[0]['group_id'], $row->group_id);
		$this->assertEquals($visit[0]['institution_id'], $row->institution_id);
		$this->assertEquals($visit[0]['host_id'], $row->host_id);
		$this->assertEquals($visit[0]['guest_id'], $row->guest_id);
		$this->assertEquals($visit[0]['from_date'], $row->from_date);
		$this->assertEquals($visit[0]['to_date'], $row->to_date);
		
		$this->CI->session->unset_userdata('visits');
		
		$this->assertEquals(NULL, $this->_vm->save_visits());
		
	}
	

	public function test_get_visits()
	{
		$result = $this->_vm->get_visits();
		
		$this->assertEquals(5, count($result));
		$this->assertStringMatchesFormat('%s', $result[0]['from_date']);

		$expected_keys = array('id', 'group', 'group_id', 'institution', 'institution_id', 'host_first_name',
				'host_last_name', 'host_id', 'visitor_first_name','visitor_last_name', 'visitor_id', 
				'from_date', 'to_date', 'country', 
				'country_id', 'region', 'region_id', 'lat', 'long', 'hide_name');
		$this->assertEquals($expected_keys, array_keys($result[0]));

		$result2 = $this->_vm->get_visits($this->man_visits_fixt["row1"]["institution_id"]);
		$this->assertEquals(1, count($result2));
	}

	
	
	public function test_update_visit()
	{
		$this->CI->load->database();
		
		$new_data = array(
				'id' => $this->man_visits_fixt["row3"]["id"],
				'hide_name' => 1);
		
		$this->_vm->update_visit($new_data);
		
		$this->CI->db->where('id', $this->man_visits_fixt["row3"]["id"]);
		$query = $this->CI->db->get("man_visits");
		$row = $query->row();
		
		$this->assertEquals(1, $row->hide_name);
		
	}
	
}
