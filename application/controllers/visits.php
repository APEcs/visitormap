<?php
class Visits extends CI_Controller {
	
	private $data;
	private $visit_form_validation;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->library('ion_auth');

		$this->load->model('research_group_model');
		$this->load->model('institution_model');
		$this->load->model('person_model');
		$this->load->model('visit_model');
		$this->load->model('visiting_position_model');
		$this->load->model('department_model');
		$this->load->helper('url');
		
		if ($this->ion_auth->logged_in())
		{	
			$user = $this->ion_auth->user()->row();
			$this->data["first_name"] = $user->first_name;
			$this->data["logged_in"] = TRUE;
			$temp = $this->ion_auth->get_users_groups()->result(0);
			$this->data["group"] = $temp[0]["name"];
		}
		else
		{
			redirect('home');
		}
		
		$this->visit_form_validation = array(
				array(	'field'   => 'returning_guest',
						'label'   => 'returning_guest',
						'rules'   => 'is_natural_no_zero|xss_clean'
				),
				array(	'field'   => 'title',
						'label'   => 'Title',
						'rules'   => 'trim|required|max_length[100]|xss_clean'
				),
				array(	'field'   => 'first_name',
						'label'   => 'First Name',
						'rules'   => 'trim|required|max_length[100]|xss_clean|callback_person_exists'
				),
				array(	'field'   => 'last_name',
						'label'   => 'Last Name',
						'rules'   => 'trim|required|max_length[100]|xss_clean'
				),
				array(	'field'   => 'public',
						'label'   => 'Is visitor name public?',
						'rules'   => 'required|greater_than[-1]|less_than[2]|xss_clean'
				),
				array(	'field'   => 'sex',
						'label'   => 'Sex',
						'rules'   => 'required|xss_clean|callback_sex_correct'
				),
				array(	'field'   => 'institution',
						'label'   => 'Home Institution',
						'rules'   => 'required|xss_clean|callback_institution_exsists'
				),
				array(	'field'   => 'department',
						'label'   => 'Department',
						'rules'   => 'required|xss_clean|callback_department_exsists'
				),
				array(	'field'   => 'research_group',
						'label'   => 'Research Group',
						'rules'   => 'required|xss_clean|callback_research_group_exsists'
				),
				array(	'field'   => 'host',
						'label'   => 'Host',
						'rules'   => 'required|xss_clean|callback_person_exsists'
				),
				array(	'field'   => 'from_date',
						'label'   => 'From Date',
						'rules'   => 'required|xss_clean|callback_date_check'
				),
				array(	'field'   => 'to_date',
						'label'   => 'To Date',
						'rules'   => 'required|xss_clean|callback_date_check|callback_to_date_check'
				),
				array(	'field'   => 'visiting_position',
						'label'   => 'Visiting Position',
						'rules'   => 'required|xss_clean|callback_position_exsists'
				),
				array(	'field'   => 'honorary',
						'label'   => 'honorary',
						'rules'   => 'required|is_natural|greater_than[-1]|less_than[2]|xss_clean'
				),
				array(	'field'   => 'returning_guest_id',
						'label'   => 'returning_guest_id',
						'rules'   => 'is_natural_no_zero|xss_clean'
				)
		);
		
	}

	function fetch_data()
	{
		$this->data["research_groups"] = $this->research_group_model->get_research_groups();
		$this->data["institutions"] = $this->institution_model->get_institutions();
		$this->data["hosts"] = $this->person_model->get_host_names();
		$this->data["returning_guests"] = $this->person_model->get_guests();
		$this->data["visiting_positions"] = $this->visiting_position_model->get_visiting_positions();
	}
	
	public function new_visit()
	{
		
		$this->fetch_data();
		
		$this->load->view('template/header', $this->data);
		$this->load->view('template/menu', $this->data);
		$this->load->view('new-visit', $this->data);
		$this->load->view('template/footer');
		$this->load->view('new-institution');
		$this->load->view('new-research-group');
		$this->load->view('new-host');
		$this->load->view('new-department');
	}
	
	
	public function existing_visits()
	{
	
		$this->fetch_data();
	
		$this->load->view('template/header', $this->data);
		$this->load->view('template/menu', $this->data);
		$this->load->view('existing-visits', $this->data);
		$this->load->view('template/footer');
	}	

	
	
	public function edit_visit($visit_id = NULL)
	{
		if(!isset($visit_id)) $visit_id = $this->input->get('id', TRUE);
		$group = array('admin', 'director');
		if (!$this->ion_auth->in_group($group) || $this->visit_exists($visit_id) == FALSE)
		{
			show_404('page');
		}
		else
		{
			$this->fetch_data();
			
			$all_visits = $this->visit_model->get_visits();
				
			foreach($all_visits as $visit) 
			{
				if($visit["id"] == $visit_id) 
				{
					$this->data["visit"] = $visit;
					$from = DateTime::createFromFormat('Y-m-d', $visit["from_date"]);
					$this->data["visit"]["from_date"] = $from->format('d.m.Y');
					$to = DateTime::createFromFormat('Y-m-d', $visit["to_date"]);
					$this->data["visit"]["to_date"] = $to->format('d.m.Y');					
				}
			}
			$this->data["departments"] = $this->department_model->get_departments($this->data["visit"]["institution_id"]);
	
			$this->load->view('template/header', $this->data);
			$this->load->view('template/menu', $this->data);
			$this->load->view('edit-visit', $this->data);
			$this->load->view('template/footer');
			$this->load->view('new-institution');
			$this->load->view('new-research-group');
			$this->load->view('new-host');
			$this->load->view('new-department');
		}
	}
	
	
	public function do_edit_visit()
	{
	
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('security');
	
		$this->form_validation->set_error_delimiters('<div class="text-error"><i class="icon-warning-sign"></i> <small>', '</small></div><br>');
	
		$this->form_validation->set_rules($this->visit_form_validation);
		$this->form_validation->set_rules('visitor_id', 'visitor_id', 'required|xss_clean|callback_person_exists');
		$this->form_validation->set_rules('visit_id', 'visit_id', 'required|xss_clean|callback_visit_exists');
		$this->form_validation->set_rules('person_changed', 'person_changed', 'required|greater_than[-1]|less_than[2]|xss_clean');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->fetch_data();
			
			$all_visits = $this->visit_model->get_visits();
				
			foreach($all_visits as $visit) 
			{
				if($visit["id"] == $this->input->post('visit_id', TRUE)) 
				{
					$this->data["visit"] = $visit;
				}
			}
			
			$this->load->view('template/header', $this->data);
			$this->load->view('template/menu', $this->data);
			$this->load->view('edit-visit', $this->data);
			$this->load->view('template/footer');
		}
		else
		{
			//Passed form validation
			$visit_id = $this->input->post('visit_id', TRUE);
			$title = $this->input->post('title', TRUE);
			$first_name = $this->input->post('first_name', TRUE);
			$last_name = $this->input->post('last_name', TRUE);
			$public = $this->input->post('public', TRUE);
			$sex = $this->input->post('sex', TRUE);
			$returning_guest_id = $this->input->post('returning_guest_id', TRUE);
			$person_update_ok = TRUE;
			
			if($returning_guest_id) 
			{
				//user has selected new person from Returning Guest drop-down list
				$guest_id = $returning_guest_id; 
			}
			else 
			{ 
				$guest_id = $this->input->post('visitor_id', TRUE);
				if($this->input->post('person_changed', TRUE)==1)
				{
					$data = array(
							'type' => 'visitor' ,
							'first_name' => $first_name,
							'last_name' => $last_name,
							'title' => $title,
							'sex' => $sex
					);
					$person_update_ok = $this->person_model->update_person($guest_id, $data);
				}	
			}
			$from = DateTime::createFromFormat('d.m.Y', $this->input->post('from_date', TRUE));
			$to = DateTime::createFromFormat('d.m.Y', $this->input->post('to_date', TRUE));			
			$visit_data = array(
					'group_id' 			=> $this->input->post('research_group', TRUE),
					'institution_id' 	=> $this->input->post('institution', TRUE),
					'department_id' 	=> $this->input->post('department', TRUE),
					'host_id' 			=> $this->input->post('host', TRUE),
					'guest_id' 			=> $guest_id,
					'from_date' 		=> $from->format('Y-m-d'),
					'to_date' 			=> $to->format('Y-m-d'),
					'visiting_position_id' => $this->input->post('visiting_position', TRUE),
					'honorary'			=> $this->input->post('honorary', TRUE),
					'hide_name' 		=> $this->should_we_hide_name($public)
			);
				
			$update_ok = $this->visit_model->update_visit($visit_id, $visit_data);
			
			if ($update_ok && $person_update_ok)
			{
				$this->form_validation->unset_field_data();
				$this->data['update_visit_success'] = TRUE;
				if( $this->input->post('origin', TRUE) == "home") 
				{
					//user started editing visit from the home map, go back there
					redirect('home?visit_edit_success=1');
				}
				else 
				{
					//show exisiting visits list
					$this->existing_visits();	
				}
				
			}
			else
			{
				$this->data['update_visit_success'] = FALSE;
				$this->data['update_person_success'] = $person_update_ok;
				$this->edit_visit($visit_id);
			}
			
		}
	
	}
	
	
	public function do_new_visit()
	{
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('security');
	
		$this->form_validation->set_error_delimiters('<div class="text-error"><i class="icon-warning-sign"></i> <small>', '</small></div><br>');
	
		$this->form_validation->set_rules($this->visit_form_validation);
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->fetch_data();

			$this->load->view('template/header', $this->data);
			$this->load->view('template/menu', $this->data);
			$this->load->view('new-visit', $this->data);
			$this->load->view('template/footer');
		}
		else
		{
			
			$title = $this->input->post('title', TRUE);
			$first_name = $this->input->post('first_name', TRUE);
			$last_name = $this->input->post('last_name', TRUE);
			$public = $this->input->post('public', TRUE);
			$sex = $this->input->post('sex', TRUE);
			$returning_guest_id = $this->input->post('returning_guest_id', TRUE);
			
			if($returning_guest_id) { $guest_id = $returning_guest_id; }
			else { $guest_id = $this->person_model->insert_visitor($first_name, $last_name, $title, $sex); }

			$from = DateTime::createFromFormat('d.m.Y', $this->input->post('from_date', TRUE));
			$to = DateTime::createFromFormat('d.m.Y', $this->input->post('to_date', TRUE));
			$new_visit_data = array(
				'group_id' 			=> $this->input->post('research_group', TRUE),
				'institution_id' 	=> $this->input->post('institution', TRUE),
				'department_id' 	=> $this->input->post('department', TRUE),
				'host_id' 			=> $this->input->post('host', TRUE),
				'guest_id' 			=> $guest_id,
				'from_date' 		=> $from->format('Y-m-d'),
				'to_date' 			=> $to->format('Y-m-d'),
				'visiting_position_id' => $this->input->post('visiting_position', TRUE),
				'honorary'			=> $this->input->post('honorary', TRUE),
				'hide_name' 		=> $this->should_we_hide_name($public)
				);
			
			$new_visit_id = $this->visit_model->insert_visit($new_visit_data);
			
			if ($new_visit_id)
			{
				$this->form_validation->unset_field_data(); 
				$this->data['new_visit_success'] = TRUE;
        		$this->new_visit();

			}
			else
			{
				$this->data['new_visit_success'] = FALSE;
        		$this->new_visit();
			}
		}
	
	}
	
	
	function delete_visit() 
	{
		$visit_id = $this->input->post('id', TRUE);
		$group = array('admin', 'director');
		
		if (!$this->ion_auth->in_group($group) || $this->visit_exists($visit_id) == FALSE)
		{
			show_404('page');
		}
		else
		{
			$visit = $this->visit_model->get_visits(FALSE, $visit_id);
			$visitor_id = $visit[0]["visitor_id"];
			
			$was_visit_delete_success = $this->visit_model->delete_visit($visit_id);
			
			if($this->visit_model->how_many_visits_guest_has($visitor_id)==0)
			{
				$this->person_model->delete_person($visitor_id);
			}
			
			$data["success"] = $was_visit_delete_success;
			
			$this->output
			->set_content_type('application/json; charset=UTF-8')
			->set_output(json_encode($data));

		}
	}
	

	function sex_correct($sex)
	{
		if ($sex == "male" || $sex == "female") return TRUE;
		else return FALSE;
	}
	
	
	function institution_exsists($inst_id)
	{
		$name = $this->institution_model->get_institution_name($inst_id);
		if ($name == NULL) return FALSE;
		else return TRUE;
	}
	
	function department_exsists($dep_id)
	{
		$result = $this->department_model->does_department_exists($dep_id);
		if ($result == TRUE || $dep_id == 0) return TRUE;
		else return FALSE;
	}
	
	function research_group_exsists($group_id)
	{
		$name = $this->research_group_model->get_research_group_name($group_id);
		if ($name == NULL) return FALSE;
		else return TRUE;
	}
	
	
	function person_exsists($host_id)
	{
		$name = $this->person_model->get_person_name($host_id);
		if ($name == NULL)
		{
			$this->form_validation->set_message('host_exsists', 'Specified host does not exist');
			return FALSE;
		}
		else return TRUE;		
	}
	
	function position_exsists($position_id)
	{
		if ($this->visiting_position_model->does_position_exists($position_id))
		{
			return true;
		}
		else
		{
			$this->form_validation->set_message('position_exsists', 'Specified visiting position does not exist');
			return FALSE;
		}
	}	

	function visit_exsists($visit_id)
	{
		if ($this->visit_model->does_visit_exists($visit_id))
		{
			return true;
		}
		else
		{
			$this->form_validation->set_message('visit_exsists', 'Specified visit does not exist');
			return FALSE;
		}
	}	
	
	public function date_check($str)
	{
		$date = explode(".", $str);
	
		if ((count($date)==3) && (checkdate(intval($date[1]), intval($date[0]), intval($date[2]))))
		{
			return TRUE;
		}
		else
		{
			if($str == NULL)
			{
				return TRUE;
			}
			$this->form_validation->set_message('date_check', 'The %s is not a valid date');
			return FALSE;
		}
	}
	
	
	function to_date_check($to_str)
	{
		$from_str = $this->input->get_post("from_date", true);
	
		$from = DateTime::createFromFormat('d.m.Y', $from_str);
		$to = DateTime::createFromFormat('d.m.Y', $to_str);
	
		if($to>$from)
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('to_date_check', 'To Date has to be later than From Date');
			return FALSE;
		}
	
	}
	
	
	function should_we_hide_name($public_choice)
	{
		if ($public_choice==1) RETURN 0;
		else RETURN 1;
	}

	
	function person_exists()
	{
		$first_name = $this->input->post('first_name', TRUE);
		$last_name = $this->input->post('last_name', TRUE);
		$returning_guest_id = $this->input->post('returning_guest_id', TRUE);
		$visitor_id = $this->input->post('visitor_id', TRUE);
		
		if(($this->person_model->is_name_exists($first_name, $last_name)==NULL) || $returning_guest_id || $visitor_id)
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('person_exists', 'This is returning guest! Please select the name from the "Returning Guest?" menu.');
			return FALSE;
		}
	}
	
	function visit_exists($visit_id)
	{
		return $this->visit_model->does_visit_exists($visit_id);
	}
	
	
	public function get_data_about_returning_visitor()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('security');
	
		$this->form_validation->set_rules('id', 'id', 'required|is_natural_no_zero|xss_clean');
	
	
		if ($this->form_validation->run() == FALSE)
		{
			//don't do anything
		}
		else
		{
			$guest_id = $this->input->post('id', TRUE);
			$all_guests = $this->person_model->get_guests();
			
			foreach($all_guests as $guest)
			{
				if($guest["id"]==$guest_id)
				{
					
					$data = $guest;
					break;
				}
			}
			$this->output
			->set_content_type('application/json; charset=UTF-8')
			->set_output(json_encode($data));
		}
	
	}
	
	
	
	
	
	
	
}