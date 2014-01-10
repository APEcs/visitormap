<?php
class Person_model extends CI_Model {

	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();

		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
		$this->load->model('institution_model');
	}

	
	/**
	 * Returns a random name from man_random_names table
	 * by default returns first name.
	 * If type param is specified as "last", returns last name
	 */
	public function get_random_name($type="first")
	{
		$query = (mysql_query("SELECT first_name, last_name FROM man_random_names ORDER BY RAND() LIMIT 1"));
		$result = mysql_fetch_row($query);
		
		if($type=="first")
		{
			return $result[0];
		}
		else if($type=="last")
		{
			return $result[1];
		}
		
	}

	/**
	 * Inserts specified name into database as visitor
	 * Returns new person's id.
	 * @param string $first_name
	 * @param string $last_name
	 * @param string $title
	 * @param string $sex
	 * @return int
	 */
	public function insert_visitor($first_name = " ", $last_name = " ", $title = " ", $sex = "male")
	{	
		$data = array(
			'type' => 'visitor' ,
			'first_name' => $first_name,
			'last_name' => $last_name,
			'title' => $title,
			'sex' => $sex
			);
		
		$this->db->insert('man_persons', $data);

		return $this->is_name_exists($first_name, $last_name);
	}
	
	
	
	public function update_person($id, $data)
	{
		if($data)
		{
			$this->db->where('id', $id);
			$this->db->update('man_persons', $data);
			if ($this->db->affected_rows() > 0)
			{
				return TRUE;
			}
			else
			{
				$this->db->get_where('man_persons', $data);
				if($this->db->count_all_results()==1)
				{
					return TRUE;
				}
				return FALSE;
			}
		}
		return FALSE;
	}
	
	
	/**
	 * Returns person's id, if he/she has already visited
	 * Returns NULL if this is first visit.
	 * @param String $name
	 */
	public function is_name_exists($first_name=" ", $last_name=" ", $type = "visitor")
	{
		$query = (mysql_query("SELECT id FROM man_persons WHERE first_name='$first_name' AND last_name='$last_name' AND type='$type'"));
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
	 * Returns the first or last name of specified person
	 * By default returns first name, if second parameter
	 * is defined as "last", returns last name
	 * If not found, returns NULL
	 * @param int $id
	 * @param string $type
	 * @return string|NULL
	 */
	public function get_person_name($id, $type="first")
	{
		$sql = "SELECT * FROM man_persons WHERE  `id`= ? ";
		$query = $this->db->query($sql, array($id));
	
		if($query->num_rows()==1)
		{
			$row = $query->row();
			if($type == 'first')
			{
				return htmlentities($row->first_name);
			}
			else if($type == 'last')
			{
				return htmlentities($row->last_name);
			}
			
		}
		else
		{
			return NULL;
		}
	}
	
	
	/**
	 * Returns an array of person id-s that are hosts in specified research group
	 * Array structure: array[seqNo] => 'person_id'
	 * If nobody's host in that group, returns NULL
	 * @param int $group_id
	 */
	public function get_hosts($group_id)
	{
		
		$sql = "SELECT person_id FROM man_research_group_members WHERE group_id='$group_id'";
	
		$group_query = $this->db->query($sql);
		$y=0;
		
		if($group_query->num_rows()==0)
		{
			return NULL;
		}
		
		$return = array();
		foreach($group_query->result() as $row)
		{
			$return[$y] = $row->person_id;
			$y++;
	
		}

		return $return;
	}

	
	/**
	 * Returns an array of host names
	 */
	public function get_host_names()
	{
		$this->db->from("man_persons");
		$this->db->where("type", "host");
		$this->db->order_by("first_name", "asc");
		$query = $this->db->get();
		
		$return = array();
		$x=0;
		foreach ($query->result() as $row)
		{
			$return[$x] = array();
			$return[$x]["id"] = $row->id;
			$return[$x]["title"] = $row->title;
			$return[$x]["first_name"] = $row->first_name;
			$return[$x]["last_name"] = $row->last_name;
			$return[$x]["sex"] = $row->sex;
			$x++;
		}
		
		return $return;
	}
	
	
	/**
	 * Returns an array of host names
	 */
	public function get_guests()
	{
		$this->db->from("man_persons");
		$this->db->where("type", "visitor");
		$this->db->order_by("first_name", "asc");
		$query = $this->db->get();
	
		$return = array();
		$x=0;
		foreach ($query->result() as $row)
		{
			$return[$x] = array();
			$return[$x]["id"] = $row->id;
			$return[$x]["title"] = $row->title;
			$return[$x]["sex"] = $row->sex;
			$return[$x]["first_name"] = $row->first_name;
			$return[$x]["last_name"] = $row->last_name;
			$x++;
		}
	
		return $return;
	}
	
	/**
	 * Inserts specified name into database as host
	 * And inserts that host as research group member
	 * Returns new person's id.
	 * @param String $name
	 */
	public function insert_host($first_name=" ", $last_name=" ", $group_id)
	{
		$insert_person = sprintf("INSERT INTO man_persons (`type`, `first_name`, `last_name`) VALUES ('%s', '%s', '%s');",
				"host",
				$first_name,
				$last_name
		);
		mysql_query($insert_person);
		error_log("Name: " .$first_name." ".$last_name);
		$id = $this->is_name_exists($first_name, $last_name, "host");
		
		
		$data = array(
				'group_id' => $group_id,
				'person_id' => $id 
		);
		
		$this->db->insert('man_research_group_members', $data);
		
		return $id;
	}

	
	/**
	 * Inserts new Person record.
	 * Needs an associative array with all parameters
	 * Returns the id of new person
	 * If fails, returns FALSE
	 */
	public function insert_host_fast($data)
	{
		if($this->db->insert('man_persons', $data)!= FALSE)
		{
			return $this->db->insert_id();
		}
		return FALSE;
	}
	
	/**
	 * Returns true if success, false if fails
	 * @param unknown $visit_id
	 */
	public function delete_person($person_id)
	{
		$this->db->delete('man_persons', array('id' => $person_id));
		if($this->get_person_name($person_id)==NULL) return TRUE;
		return FALSE;
	}
	
	
	
	
	/**
	 * Helper function only used once to split name to first and last
	 */
	public function split_name()
	{

		$query = $this->db->get('man_persons');
		
		
		foreach ($query->result() as $row)
		{
			$temp = explode(" ", $row->first_name);

			if(count($temp)==2)
			{
				$data = array(
						'first_name' => $temp[0],
						'last_name' => $temp[1]
				);
					
				$this->db->where('id', $row->id);
				$this->db->update('man_persons', $data);
			}
		}
		
	}
	
}







