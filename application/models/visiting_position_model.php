<?php
class Visiting_position_model extends CI_Model {

	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();

		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
	}

	
	/**
	 * returns an array of visiting positions
	 */
	public function get_visiting_positions()
	{
		$this->db->from("man_visiting_positions");
		
		$query = $this->db->get();

		$return = array();
		$x=0;
		foreach ($query->result() as $row)
		{
			$return[$x] = array();
			$return[$x]["id"] = $row->id;
			$return[$x]["position_name"] = $row->position_name;
			$return[$x]["short"] = $row->short;
			$x++;
		}
		
		return $return;
	}

	
	public function does_position_exists($position_id)
	{
		$this->db->from("man_visiting_positions");
		$this->db->where("id", $position_id);
		
		if ($this->db->count_all_results()==0) return FALSE;
		return TRUE;
	}




}


