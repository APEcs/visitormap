<?php
class Institution_model extends CI_Model {

	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
		$this->load->model('country_model');
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
	}

	
	/**
	 * Returns an array of institution id-s that are present in the DB
	 */
	public function get_institution_ids()
	{
		$return = array();
		$query = (mysql_query("SELECT * FROM man_institutions"));
	
		$y=0;
		while ($row = mysql_fetch_assoc($query))
		{
			$return[$y] = $row["id"];
			$y++;
		}
		return $return;
	}
	
	/**
	 * Returns an array of institution names, lat/longs and Google maps url stored in the database.
	 * If database problem, returns FALSE.
	 * @return multitype:mixed |boolean
	 */
	public function get_institutions()
	{
		$this->db->select("man_institutions.id, man_institutions.country_id, man_institutions.lat, man_institutions.long,
				man_institutions.name, man_institutions.description, man_institutions.address1,
				man_institutions.address2, man_institutions.city, man_institutions.postal_code, 
				man_countries.name AS country, man_countries.alpha_2");
		$this->db->from("man_institutions");
		$this->db->join("man_countries", "man_institutions.country_id = man_countries.id", "left");
		$this->db->order_by("man_institutions.name", "asc");
		$query = $this->db->get();
		
		
		if ($query->num_rows()>0) 
		{
			$return = array();
			$x=0;
			foreach($query->result_array() as $row) {
			
				$return[$x] = array();
				$return[$x]["id"] = $row["id"];
				$return[$x]["country_id"] = $row["country_id"];
				$return[$x]["country"] = $row["country"];
				$return[$x]["alpha_2"] = $row["alpha_2"];
				$return[$x]["name"] = $row["name"];
				$return[$x]["description"] = $row["description"];
				$return[$x]["address1"] = $row["address1"];
				$return[$x]["address2"] = $row["address2"];
				$return[$x]["city"] = $row["city"];
				$return[$x]["postal_code"] = $row["postal_code"];
				$return[$x]["lat"] = $row["lat"];
				$return[$x]["long"] = $row["long"];
				$return[$x]["maps_url"]= $this->create_maps_url($row["lat"], $row["long"]);
				$x++;
			}
			return $return;
		}
		else
		{
			return FALSE;
		}
	}
	
	/**
	 * Returns properly formated google maps url for certain coordinates.
	 * @param String $lat
	 * @param String $long
	 * @return string
	 */
	public function create_maps_url($lat, $long)
	{
		return "http://maps.google.com/maps?q=".$lat.",".$long;
	}
	
	
	/**
	 * Tries first to search for an institution from the DB, if it fails, uses Google API
	 * If returns from db, gives a two dimensional associative array: array[seqNo][key]
	 * 		with 'name', 'lat', 'long', 'maps url', 'in_db' as keys
	 * If returns from google, gives additional keys:
	 * 		'country_name', 'country_code'
	 * If google search fails, returns NULL
	 * @param String $name
	 */
	public function search_institution($name, $skip_db = FALSE)
	{
		$sql = "SELECT * FROM man_institutions WHERE  `name` LIKE  '%$name%'";
		$query = $this->db->query($sql);
		
		if(!$skip_db && $query->num_rows()>0)
		{
			//we have something in our DB
			$return = array();
			$x=0;
			foreach ($query->result() as $row)
			{
				$return[$x] = array();
				$return[$x]["name"] = htmlentities($row->name);
				$return[$x]["lat"] = $row->lat;
				$return[$x]["long"] = $row->long;
				$return[$x]["maps_url"]= "http://maps.google.com/maps?q=".$row->lat.",".$row->long;
				$return[$x]["in_db"] = TRUE;
				$x++;
			}
			return $return;
		}
		else
		{
			//try google
			$this->load->model('google_model');
			$lat_long = $this->google_model->lookup($name);
			if($lat_long)
			{
				$return=array();
				$return[0]=array();
				$return[0] = array_merge($return[0], $lat_long);
				$return[0]["name"] = $name;
				$return[0]["maps_url"]= "http://maps.google.com/maps?q=".$lat_long["lat"].",+".$lat_long["long"];
				$return[0]["in_db"] = FALSE;

				return $return;
			}
			else
			{
				//both DB and Google have failed, return null
				return NULL;
			}
			
		}
	}
	
	/**
	 * Returns institution's name for selected ID.
	 * If id not, found, returns NULL
	 * @param int $id
	 */
	public function get_institution_name($id)
	{
		$sql = "SELECT * FROM man_institutions WHERE  `id`= ? ";
		$query = $this->db->query($sql, array($id));
		
		if($query->num_rows()==1)
		{
			$row = $query->row();
			return htmlentities($row->name);
		}
		else
		{
			return NULL;
		}
	}
	
	/**
	 * Returns an associative array of specified institution's coordinates
	 * array('lat'=>'value', 'long'=>'value'
	 * If id not present in the database, returns NULL
	 * @param Int $id
	 */
	public function get_institution_coordinates($id)
	{
		$sql = "SELECT * FROM man_institutions WHERE  `id`= ? ";
		$query = $this->db->query($sql, array($id));
		
		if($query->num_rows()==1)
		{
			$return = array();
			$row = $query->row();
			$return["lat"] = $row->lat;
			$return["long"] =$row->long;
			return $return;
		}
		else
		{
			return NULL;
		}
	}
	
	/**
	 * Adds new institution into database
	 * If institution is based in a new country, 
	 * then calls add_country function in country_model
	 * Expects following keys in the data_array:
	 * 		'name', 'lat', 'long', 'maps_url', 'country_name', 'country_code', 
	 * @param unknown $data_array
	 */
	public function save_institution($data_array)
	{
		if($data_array)
		{
			$country_id = $this->country_model->get_country_id($data_array["country_code"]);
			
			$sql = "INSERT INTO man_institutions (`country_id`, `lat`, `long`, `name`) VALUES ( ?, ?, ?, ?)";
			$query = $this->db->query($sql, array($country_id, $data_array["lat"], $data_array["long"], $data_array["name"]));
		}

	}
	
	
	/**
	 * Inserts new institution record.
	 * Needs an associative array with all parameters
	 * Returns the id of new institution
	 * If fails, returns FALSE
	 */
	public function insert_institution($data)
	{
		if($this->db->insert('man_institutions', $data)!= FALSE)
		{
			return $this->db->insert_id();
		}
		return FALSE;
	}
	
	
	/**
	 * Returns true if success, false if fails
	 * @param unknown $visit_id
	 */
	public function delete_institution($inst_id)
	{
		$this->db->delete('man_institutions', array('id' => $inst_id));
		if ($this->get_institution_name($inst_id)) return FALSE;
		return TRUE;
	}
	
	
	/**
	 * Updates information about institution.
	 *
	 * Returns TRUE, if sucess, FALSE, if fails
	 * @param unknown $data
	 */
	public function update_institution($id, $data)
	{
		if($data)
		{
			$this->db->where('id', $id);
			$this->db->update('man_institutions', $data);
			if ($this->db->affected_rows() > 0)
			{
				return TRUE;
			}
			else
			{
				$this->db->get_where('man_institutions', $data);
				if($this->db->count_all_results()==1)
				{
					return TRUE;
				}
				return FALSE;
			}
		}
		return FALSE;
	}
	
}






