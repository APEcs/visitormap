<?php
class Research_group_model extends CI_Model {

	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();

		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
		$this->load->model('institution_model');
		$this->load->model('person_model');
	}

	
	
	
	/**
	 * Returns an array of existing research group id-s
	 * Returns NULL if there's no research groups in the db
	 */
	public function get_research_group_ids()
	{
		$return = array();
		$groups_query = (mysql_query("SELECT * FROM man_research_groups"));
	
		$y=0;
		while ($row = mysql_fetch_assoc($groups_query))
		{
			$return[$y] = $row["id"];
			$y++;
		}
		
		if(count($return)==0) return NULL;
		
		return $return;
	}
	
	/**
	 * Returns the name of specified reasearch group
	 * Returns NULL if research group's id wasn't found
	 * @param int $id - group's id
	 * @return string|NULL
	 */
	public function get_research_group_name($id)
	{
		$sql = "SELECT * FROM man_research_groups WHERE  `id`= ?";
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
	 * Returns an array of all research group names
	 * @param int $id - group's id
	 * @return string|NULL
	 */
	public function get_all_research_group_names()
	{
		$this->db->select('name');
		$this->db->from('man_research_groups');
		$this->db->order_by('name', 'asc');
		$query = $this->db->get();
		
		
		if($this->db->count_all_results()>0)
		{
			$return = array();
			foreach ($query->result() as $row)
			{
				array_push($return, $row->name);
			}
			return $return;
		}
		return NULL;

	}
	
	
	/**
	 * Returns an array of research group info
	 */
	public function get_research_groups()
	{
		$this->db->from("man_research_groups");
		$this->db->order_by("name", "asc");
		$query = $this->db->get();
		
		$return = array();
		$x=0;
		foreach ($query->result() as $row)
		{
			$return[$x] = array();
			$return[$x]["id"] = $row->id;
			$return[$x]["name"] = $row->name;
			$return[$x]["acronym"] = $row->acronym;
			$x++;
		}
	
		return $return;
	}
	
	
	
	/**
	 * Returns the id of searched research group
	 * If not found, returns NULL
	 * @param String $name
	 * @return int|NULL
	 */
	public function get_research_group_id($name)
	{
		$this->db->select('id');
		$this->db->from('man_research_groups');
		$this->db->where('name', $name);
		
		$query = $this->db->get();
		
		if($query->num_rows()>0)
		{
			$row = $query->row();
			return $row->id;
		}
		return NULL;
	}
	
	
	
	/**
	 * Inserts new Research Group record.
	 * Needs an associative array with all parameters
	 * Returns the id of new research group
	 * If fails, returns FALSE
	 */
	public function insert_research_group($data)
	{
		if($this->db->insert('man_research_groups', $data)!= FALSE)
		{
			return $this->db->insert_id();
		}
		return FALSE;
	}
	
	
	public function update_research_group($id, $data)
	{
		if($data)
		{
			$this->db->where('id', $id);
			$this->db->update('man_research_groups', $data);
			if ($this->db->affected_rows() > 0)
			{
				return TRUE;
			}
			else
			{
				$this->db->get_where('man_research_groups', $data);
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
	 * Returns true if success, false if fails
	 * @param unknown $visit_id
	 */
	public function delete_group($group_id)
	{
		$this->db->delete('man_research_groups', array('id' => $group_id));
		if($this->get_research_group_name($group_id)==NULL) return TRUE;
		return FALSE;
	}
	
	
}





