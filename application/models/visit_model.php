<?php
class Visit_model extends CI_Model {

	

	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		
		$this->load->library('session');
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
		$this->load->model('institution_model');
		$this->load->model('person_model');
		$this->load->model('research_group_model');
	}

	

	/**
	 * Generates random visits according to specified parameters and saves them
	 * in the 'visits' session userdata in following format: array[seqNo][key]
	 * where keys are:
	 * 			'group_id'
	 * 			'group' (group's name)
	 * 			'host_id'
	 * 			'host_first_name'
	 * 			'host_last_name'
	 * 			'institution_id'
	 * 			'institution' (institution's name)
	 * 			'from_date' (Y-m-d)
	 * 			'to_date' (Y-m-d)
	 * 			'visitor_first_name'
	 * 			'visitor_last_name'
	 * @param unknown $qty - How many visits to generate
	 * @param unknown $from_date - Date from when visits can start in dd.mm.yyyy format
	 * @param unknown $to_date - Date until when visits can last in dd.mm.yyyy format
	 * @param unknown $min_stay - How many days is the shortest visit
	 * @param unknown $max_stay - How many days is the longest visit
	 */
	public function generate_visits($qty, $from_date, $to_date, $min_stay, $max_stay)
	{
		
		//Settings for Visitor Generator
		define("VISIT_QTY", $qty); // How many visits to generate
		define("FROM_DATE", $from_date); //Date from when visits can start in dd.mm.yyyy format
		define("TO_DATE", $to_date); //Date until when visits can last in dd.mm.yyyy format
		define("MIN_STAY", $min_stay); //How many days is the shortest visit
		define("MAX_STAY", $max_stay); //How many days is the longest visit
		
		$visits = $this->session->userdata('visits');
		if(isset($visits)) $this->session->unset_userdata('visits');
		unset($visits);

		$visits = array();
		
		for ($x=0;$x<VISIT_QTY;$x++)
		{
			$visits[$x] = array();
			//randomly select a group for visitor
			$groups = $this->research_group_model->get_research_group_ids();
			$visits[$x]["group_id"] = $groups[array_rand($groups)];
			$visits[$x]["group"] = $this->research_group_model->get_research_group_name($visits[$x]["group_id"]);
			
			//randomly select which host the visitor will visit in that research group.
			$hosts = $this->person_model->get_hosts($visits[$x]["group_id"]);
			$visits[$x]["host_id"] = $hosts[array_rand($hosts)];
			$visits[$x]["host_first_name"] = $this->person_model->get_person_name($visits[$x]["host_id"], "first");
			$visits[$x]["host_last_name"] = $this->person_model->get_person_name($visits[$x]["host_id"], "last");
			
			//randomly select the institution for visitor
			$institutions = $this->institution_model->get_institution_ids();
			$visits[$x]["institution_id"] = $institutions[array_rand($institutions)];
			$visits[$x]["institution"] = $this->institution_model->get_institution_name($visits[$x]["institution_id"]);

			//randomly select the length of the stay
			$length= rand(MIN_STAY, MAX_STAY);
			
			//randomly select the start date for the visit
			//keeping in mind the TO_DATE and MAX_STAY, so the stay wouldn't go over TO_DATE
			$from = new DateTime(FROM_DATE, new DateTimeZone("Europe/London"));
			$to = new DateTime(TO_DATE, new DateTimeZone("Europe/London"));

			$interval = $from->diff($to);
			$days_between = $interval->format('%a');
			
			$max_allowed_start_offset = $days_between-$length;
			
			$start = rand(0, $max_allowed_start_offset);
			$start_date = $from->add(new DateInterval("P".$start."D"));

			$visits[$x]["from_date"] = $start_date->format("Y-m-d");
			
			//calculate the end date
			$temp = clone $start_date;
			$end_date = $temp->add(new DateInterval("P".$length."D"));
			$visits[$x]["to_date"] = $end_date->format("Y-m-d");
			
			//choose random name for the visitor
			$visits[$x]["visitor_first_name"] = $this->person_model->get_random_name("first");
			$visits[$x]["visitor_last_name"] = $this->person_model->get_random_name("last");
			
		}
		
		$this->session->set_userdata('visits', $visits);
		
	}
	
	
	/**
	 * Saves visits that are stored in the session.
	 * Used in the data generator
	 * Returns true, if things went well
	 * Returns false, if something went wrong
	 */
	public function save_visits()
	{
		$visits = $this->session->userdata('visits');

		if(isset($visits) && $visits!=FALSE)
		{
			foreach ($visits as $visit)
			{
				
				//if same person has already visited, use the same id, othervise create new man_person entry
				$guest_id = $this->person_model->is_name_exists($visit["visitor_first_name"], $visit["visitor_last_name"]);
				if ($guest_id==NULL)
				{
					$guest_id = $this->person_model->insert_visitor($visit["visitor_first_name"], $visit["visitor_last_name"]);
				}
				
				$insert = sprintf("INSERT INTO man_visits (`group_id`, `institution_id`, `host_id`, `guest_id`, `from_date`, `to_date`) VALUES ('%s', '%s', '%s', '%s', '%s', '%s');",
						$visit["group_id"],
						$visit["institution_id"],
						$visit["host_id"],
						$guest_id,
						$visit["from_date"],
						$visit["to_date"]
				);
				$res = mysql_query($insert);
				if (!$res) return FALSE;
				
			}
			$this->session->unset_userdata('visits');
			return TRUE;
		}
		return FALSE;
	}
	
	
	/**
	 * Returns an array of visits, sorted chronologically
	 * If institution_id is specified, returns only those visits
	 * that have specified institution.
	 * If argument is empty, returns all visits
	 * Array structure: array[seqNo][key]
	 * where keys are:
	 * 				id
	 * 				group  
	 *				group_id  
	 *				institution 
	 *				institution_id			
	 *				host_first_name
	 *				host_last_name
	 *				host_id
	 *				visitor_first_name
	 *				visitor_last_name
	 *				visitor_id 
	 *				from_date 
	 *				to_date
	 *				country
	 *				country_id
	 *				region
	 *				region_id
	 *				lat
	 *				long
	 *				hide_name
	 * returns NULL, if no visits.
	 */
	public function get_visits($institution_id = FALSE, $visit_id = FALSE, $anonymous = FALSE)
	{
		$sql = "SELECT man_visits.id, man_research_groups.name AS 'group', man_visits.group_id, 
				man_institutions.name AS  'institution', man_visits.institution_id, 
				man_visits.department_id, man_departments.name AS 'department',
				host.first_name AS  'host_first_name', host.last_name AS 'host_last_name',
				host.title AS 'host_title', man_visits.honorary,
				man_visits.host_id, man_persons.first_name AS  'visitor_first_name',
				man_persons.last_name AS 'visitor_last_name', man_persons.title,
				man_persons.sex AS 'visitor_sex', man_visits.guest_id, man_countries.name AS  'country', 
				man_countries.id AS  'country_id', man_countries.alpha_2 AS 'country_iso', man_regions.name AS  'region', 
				man_regions.id AS  'region_id', man_visits.from_date, 
				man_visits.to_date, man_institutions.lat, man_institutions.long, man_visits.hide_name,
				man_visiting_positions.id AS 'position_id', man_visiting_positions.position_name,
				man_visiting_positions.short AS 'visiting_position_short'
				FROM man_visits
				LEFT JOIN (
					man_institutions
					LEFT JOIN (
						man_countries
						LEFT JOIN man_regions ON man_countries.region_id = man_regions.id
					) ON man_institutions.country_id = man_countries.id
				) ON man_visits.institution_id = man_institutions.id
				LEFT JOIN man_persons AS host ON man_visits.host_id = host.id
				LEFT JOIN man_persons ON man_visits.guest_id = man_persons.id
				LEFT JOIN man_research_groups ON man_visits.group_id = man_research_groups.id
				LEFT JOIN man_departments ON man_visits.department_id = man_departments.id
				LEFT JOIN man_visiting_positions ON man_visits.visiting_position_id = man_visiting_positions.id";
		
		if($institution_id)
		{
			$add = sprintf(" WHERE institution_id ='%d'",
					mysql_real_escape_string($institution_id));
			$sql = $sql.$add;
		}
		if($visit_id)
		{
			$add = sprintf(" WHERE man_visits.id ='%d'",
					mysql_real_escape_string($visit_id));
			$sql = $sql.$add;
		}		
		
		$sql = $sql." ORDER BY `from_date` ASC";

		$result = mysql_query($sql);
		
		if ($result!= FALSE && mysql_num_rows($result)>0) 
		{
			$return = array();
			$x=0;
			while ($row = mysql_fetch_assoc($result)) {
				$return[$x] = array();
				$return[$x]["id"] = $row["id"];
				$return[$x]["group"] = $row["group"];
				$return[$x]["group_id"] = $row["group_id"];
				$return[$x]["institution"] = $row["institution"];
				$return[$x]["institution_id"] = $row["institution_id"];	
				$return[$x]["department"] = $row["department"];
				$return[$x]["department_id"] = $row["department_id"];
				$return[$x]["host_id"] = $row["host_id"];
				$return[$x]["visitor_id"] = $row["guest_id"];
				$return[$x]["position_id"] = $row["position_id"];
				$return[$x]["position_name"] = $row["position_name"];
				$return[$x]["honorary"] = $row["honorary"];
				$return[$x]["visiting_position_short"] = $row["visiting_position_short"];
				$return[$x]["from_date"] = $row["from_date"];
				$return[$x]["to_date"] = $row["to_date"];
				$return[$x]["country"] = $row["country"];
				$return[$x]["country_iso"] = $row["country_iso"];
				$return[$x]["country_id"] = $row["country_id"];
				$return[$x]["region"] = $row["region"];
				$return[$x]["region_id"] = $row["region_id"];
				$return[$x]["lat"] = $row["lat"];
				$return[$x]["long"] = $row["long"];
				$return[$x]["hide_name"] = $row["hide_name"];
				
				if(!$anonymous)
				{
					$return[$x]["host_title"] = $row["host_title"];							
					$return[$x]["host_first_name"] = $row["host_first_name"];
					$return[$x]["host_last_name"] = $row["host_last_name"];	
					$return[$x]["visitor_title"] = $row["title"];
					$return[$x]["visitor_first_name"] = $row["visitor_first_name"];
					$return[$x]["visitor_last_name"] = $row["visitor_last_name"];
					$return[$x]["visitor_sex"] = $row["visitor_sex"];				
				}
				$x++;
			}
			return $return;
		}
		else
		{
			return NULL;
		}
	}


	public function delete_all_visits()
	{
		$sql = "DELETE FROM man_visits";
		$sql2 = "ALTER TABLE `man_visits` AUTO_INCREMENT =1";
		
		mysql_query($sql);
		mysql_query($sql2);
	}
	
	
	/**
	 * Updates information about visits.
	 * 
	 * Returns TRUE, if sucess, FALSE, if fails
	 * @param unknown $data
	 */
	public function update_visit($id, $data)
	{
		if($data)
		{
			$this->db->where('id', $id);	
			$this->db->update('man_visits', $data);
			if ($this->db->affected_rows() > 0) 
			{
				return TRUE;
			} 
			else 
			{
				$this->db->get_where('man_visits', $data);
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
	 * Inserts new visit record.
	 * Needs an associative array with all parameters
	 * Returns the id of new visit
	 * If fails, returns FALSE
	 */
	public function insert_visit($data)
	{
		if($this->db->insert('man_visits', $data)!= FALSE)
		{
			return $this->db->insert_id();
		}
		return FALSE;
	}
	
	
	public function does_visit_exists($visit_id)
	{
		$this->db->from("man_visits");
		$this->db->where("id", $visit_id);
		
		if ($this->db->count_all_results()==0) return FALSE;
		return TRUE;
	}
	
	/**
	 * Returns true if success, false if fails
	 * @param unknown $visit_id
	 */
	public function delete_visit($visit_id)
	{
		$this->db->delete('man_visits', array('id' => $visit_id));
		return !$this->does_visit_exists($visit_id);
	}
	

	
	public function how_many_visits_guest_has($guest_id) 
	{
		$this->db->from("man_visits");
		$this->db->where("guest_id", $guest_id);
		return $this->db->count_all_results();	
	}
	
	public function how_many_visits_inst_has($inst_id)
	{
		$this->db->from("man_visits");
		$this->db->where("institution_id", $inst_id);
		return $this->db->count_all_results();
	}
	
	
	/**
	 * Counts how many visitors host or group has had.
	 * Second parameter can be either "host_id" or "group_id"
	 * 		Default is "host_id"
	 * If third parameter is true, counts only unique visitors
	 * Returns int value.
	 */
	public function how_many_visitors($id, $who="host_id", $unique=FALSE)
	{
		$this->db->from("man_visits");
		$this->db->select("guest_id");
		if($unique) 
		{
			$this->db->group_by("guest_id");
			$this->db->distinct();
		}
		$this->db->where($who, $id);
		$query = $this->db->get();
		return $query->num_rows();
	}
	
}






