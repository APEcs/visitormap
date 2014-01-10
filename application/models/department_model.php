<?php
class Department_model extends CI_Model {

	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();

		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
	}

	
	/**
	 * returns an array of departments that institution has
	 * if inst_id not specified, returns all departments
	 */
	public function get_departments($inst_id = NULL)
	{
		$this->db->from("man_departments");
		if($inst_id) 
		{
			$this->db->where('institution_id', $inst_id);
		}
		$query = $this->db->get();

		$return = array();
		$x=0;
		foreach ($query->result() as $row)
		{
			$return[$x] = array();
			$return[$x]["id"] = $row->id;
			$return[$x]["name"] = $row->name;
			$x++;
		}
		
		return $return;
	}

	
	public function does_department_exists($dep_id)
	{
		$this->db->from("man_departments");
		$this->db->where("id", $dep_id);
		
		if ($this->db->count_all_results()==0) return FALSE;
		return TRUE;
	}


	/**
	 * Inserts new Department record.
	 * Needs an associative array with all parameters
	 * Returns the id of new department
	 * If fails, returns FALSE
	 */
	public function insert_department($data)
	{
		if($this->db->insert('man_departments', $data)!= FALSE)
		{
			return $this->db->insert_id();
		}
		return FALSE;
	}

	/**
	 * Deletes all departments with specified institution id's
	 * Returns true if success, false if fails
	 * @param unknown $inst_id
	 */
	public function delete_departments($inst_id)
	{
		if(!$inst_id) return FALSE;
		
		$this->db->delete('man_departments', array('institution_id' => $inst_id));
		
		if (count($this->get_departments($inst_id))>0) return FALSE;
		return TRUE;
		
	}
	
	
	public function update_department($id, $data)
	{
		if($data)
		{
			$this->db->where('id', $id);
			$this->db->update('man_departments', $data);
			if ($this->db->affected_rows() > 0)
			{
				return TRUE;
			}
			else
			{
				$this->db->get_where('man_departments', $data);
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
	public function delete_department($dep_id)
	{
		$this->db->delete('man_departments', array('id' => $dep_id));
		if ($this->does_department_exists($dep_id)) return FALSE;
		return TRUE;
	}

}


