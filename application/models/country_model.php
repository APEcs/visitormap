<?php
class Country_model extends CI_Model {

	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
	}

	
	/**
	 * Returns country's id that corresponds to given two caracter country code
	 * Returns NULL if not.
	 * @param String $alpha_2
	 */
	public function get_country_id($alpha_2)
	{
		$alpha_2=strtolower($alpha_2);
		$query = (mysql_query("SELECT id FROM man_countries WHERE alpha_2='$alpha_2'"));
		$result = mysql_fetch_row($query);
		if (!$result)
		{
			return NULL;
		}
		else
		{
			return $result[0];
		}
	}
	
	/**
	 * Returns an array of all countries in following format:
	 * array[seqNo]
	 * 		=> 	'name'
	 * 		=>	'id'
	 * 		=>	'alpha_2'
	 * 		=>	'region_id'
	 */
	public function get_countries()
	{
		
		$this->db->from('man_countries');
		$query = $this->db->get();
		
		
		if($query->num_rows()>0)
		{
			$return = array();
			$x=0;
			foreach ($query->result() as $row)
			{
				$return[$x] = array();
				$return[$x]['name'] = $row->name;
				$return[$x]['id'] = $row->id;
				$return[$x]['alpha_2'] = $row->alpha_2;
				$return[$x]['region_id'] = $row->region_id;
				$x++;
			}
			return $return;
		}
		return NULL;
	}
	
	
}






