<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Json {
	
	/**
	 * @var _im
	 */
	private $CI;
	
	/**
	 * @var earliest_date
	 */
	private $earliest_date;
	
	/**
	 * @var latest_date
	 */
	private $latest_date;
	
	public function __construct()
	{

		$this->CI =& get_instance();
		
		$this->CI->load->model('visit_model');
		$this->CI->load->model('institution_model');
	
	}
	
	
	/**
	 * Returns json encoded object about all visits, grouped by location
	 * It has following structure:
	 * 'points'->
	 * 		'institution' (the name)
	 * 		'institution_id'
	 * 		'region'
	 * 		'lat'
	 * 		'long'
	 * 		'earliest_date'
	 * 		'latest_date'
	 * 		'visits'->
	 * 	  			'id'
	 * 				'group' (name of group)
	 * 				'institution' (name of institution)
	 * 				'institution_id' (id of institution)
	 * 				'host_first_name'
	 * 				'host_last_name
	 * 				'visitor_first_name'
	 * 				'visitor_last_name'
	 * 				'from_date' (yyyy-mm-dd)
	 * 				'to_date' (yyyy-mm-dd)
	 */
	public function get_points_for_home_map($hide_visitor_name=FALSE)
	{
		$visits = $this->CI->visit_model->get_visits();
		
		if($visits==NULL) 
		{
			return NULL;
		}
		
		usort($visits, array($this, 'sortByInstitution_id'));
		
		$dummy_late = new DateTime("01.01.1900", new DateTimeZone("Europe/London"));
		$dummy_early = new DateTime("01.01.2100", new DateTimeZone("Europe/London"));
		
		$points = array();
		$points["points"] = array();
		$p =& $points["points"];
		$x=-1; //counter for points
		foreach($visits as $visit)
		{
			//point exists for that institution, just add new visit
			if(isset($p[$x]['institution_id']) && ($p[$x]['institution_id']==$visit['institution_id']))
			{
				$p[$x]['visits'] = $this->add_new_visit_to_point($visit, $p[$x]['visits'], $hide_visitor_name);
				
				$p[$x]['earliest_date'] = $this->is_earliest_date($visit["from_date"]);
				$p[$x]['latest_date'] = $this->is_latest_date($visit["from_date"]);
			}
			//new institution, create new point
			else 
			{
				$this->earliest_date = $dummy_early; 
				$this->latest_date = $dummy_late;
				$x++;
				
				$new_inst = array();
				$new_inst['institution'] = $visit['institution'];
				$new_inst['institution_id'] = $visit['institution_id'];					
				$new_inst['country'] = $visit['country'];
				$new_inst['country_iso'] = $visit['country_iso'];
				$new_inst['country_id'] = $visit['country_id'];
				$new_inst['region'] = $visit['region'];
				$new_inst['region_id'] = $visit['region_id'];
				
				$new_inst['lat'] = $visit['lat'];
				$new_inst['long'] = $visit['long'];
				
				$new_inst['earliest_date'] = $this->is_earliest_date($visit["from_date"]);
				$new_inst['latest_date'] = $this->is_latest_date($visit["from_date"]);
				
				$new_inst['visits'] = array();
				$new_inst['visits'] = $this->add_new_visit_to_point($visit, $new_inst['visits'], $hide_visitor_name);
				array_push($p, $new_inst);
				
			}
			
		}
		
		return json_encode($points);
	}
	
	
	/**
	 * Helper function to sort an array coming from visit_model->getvisits();
	 */
	function sortByInstitution_id($a, $b) {
		$a = intval($a['institution_id']);
		$b = intval($b['institution_id']);
		return $a-$b;
	}
	
	

	
	function add_new_visit_to_point($visit, $point, $hide_visitor_name=FALSE)
	{
		$new_visit = array();
		$new_visit['id'] = $visit['id'];
		
		if($hide_visitor_name == TRUE && $visit['hide_name'] == 1) 
		{
			$new_visit['visitor_title'] = " ";
			$new_visit['visitor_first_name'] = "Anonymous";
			$new_visit['visitor_last_name'] = " ";
			$new_visit['visitor_sex'] = "Anonymous";
		}
		else
		{
			$new_visit['visitor_title'] = $visit['visitor_title'];
			$new_visit['visitor_first_name'] = $visit['visitor_first_name'];
			$new_visit['visitor_last_name'] = $visit['visitor_last_name'];
			$new_visit['visitor_sex'] = $visit['visitor_sex'];
		}
		
		$new_visit['visitor_id'] = $visit['visitor_id'];
		$new_visit['from_date'] = $visit['from_date'];
		$new_visit['to_date'] = $visit['to_date'];
		$new_visit['host_title'] = $visit['host_title'];
		$new_visit['host_first_name'] = $visit['host_first_name'];
		$new_visit['host_last_name'] = $visit['host_last_name'];
		$new_visit['host_id'] = $visit['host_id'];
		
		if($visit['honorary']==1)
		{ 
			$new_visit['position_name'] = "Honorary ".$visit['position_name'];	
		}
		else
		{
			$new_visit['position_name'] = $visit['position_name'];
		}
		
		$new_visit['group'] = $visit['group'];
		$new_visit['group_id'] = $visit['group_id'];
		$new_visit['hide_name'] = $visit['hide_name'];
		$new_visit['department'] = $visit['department'];
		$new_visit['department_id'] = $visit['department_id'];	
		
		array_push($point, $new_visit);
		return $point;
	}
	
	
	
	function is_earliest_date($new_date)
	{
		$new = new DateTime($new_date, new DateTimeZone("Europe/London"));
		
		if($new<$this->earliest_date)
		{
			$this->earliest_date = $new;
			return $new_date;
		}
		else
		{
			return $this->earliest_date->format("Y-m-d");;
		}
		
	}
	
	function is_latest_date($new_date)
	{
		$new = new DateTime($new_date, new DateTimeZone("Europe/London"));
		
		if($new>$this->latest_date)
		{
			$this->latest_date = $new;
			return $new_date;
		}
		else
		{
			return $this->latest_date->format("Y-m-d");
		}
	}
	
}