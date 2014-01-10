<?php
class Institutions extends CI_Controller {
	
	private $data;
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->library('ion_auth');

		$this->load->model('research_group_model');
		$this->load->model('institution_model');
		$this->load->model('department_model');
		$this->load->model('person_model');
		$this->load->model('visit_model');
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
	}

	
	public function search_institution()
	{
		$this->load->library('security');
		
		$search = $this->security->xss_clean($this->input->post('institution'));
		
		//second parameter signals to always use google for searching
		$data = $this->institution_model->search_institution($search, TRUE);
			
		//var_dump(json_encode($data));
		$this->output
		->set_content_type('application/json; charset=UTF-8')
		->set_output(json_encode($data));
	}
	
	
	public function get_countries()
	{
		$this->load->model('country_model');
		
		$countries = $this->country_model->get_countries();

 		$this->output
 		->set_content_type('application/json; charset=UTF-8')
 		->set_output(json_encode($countries));		
	
	}
	
	
	public function new_institution()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('security');
		$this->load->model('country_model');
		
		$this->form_validation->set_rules('institution_name', 'Institution', 'required|max_length[250]|xss_clean');
		$this->form_validation->set_rules('description', 'Description', 'max_length[250]|xss_clean');
		$this->form_validation->set_rules('address1', 'Address 1', 'max_length[250]|xss_clean');
		$this->form_validation->set_rules('address2', 'Address 2', 'max_length[250]|xss_clean');
		$this->form_validation->set_rules('postal_code', 'Postal Code', 'max_length[250]|xss_clean');
		$this->form_validation->set_rules('city', 'City', 'max_length[250]|xss_clean');
		$this->form_validation->set_rules('countrySelector', 'Country', 'required|max_lenght[3]|xss_clean');
		$this->form_validation->set_rules('lat', 'Latitude', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('long', 'Longitude', 'required|numeric|xss_clean');
		
		if ($this->form_validation->run() == FALSE)
		{
			//we have a client side form check, if form validation fails here, then somebody is trying to break in.
			redirect('home/logout');
				
		}
		else
		{
			$new_inst_data = array(
				'country_id' 	=> $this->country_model->get_country_id(
										$this->input->post('countrySelector', TRUE)),
				'lat' 			=> $this->input->post('lat', TRUE),
				'long' 			=> $this->input->post('long', TRUE),
				'name' 			=> $this->input->post('institution_name', TRUE),
				'description' 	=> $this->input->post('description', TRUE),
				'address1' 		=> $this->input->post('address1', TRUE),
				'address2' 		=> $this->input->post('address2', TRUE),
				'city' 			=> $this->input->post('city', TRUE),
				'postal_code' 	=> $this->input->post('postal_code', TRUE)
				);
			
			$new_inst_id = $this->institution_model->insert_institution($new_inst_data);
			
			if ($new_inst_id)
			{
				$data = array(
						'id' => $new_inst_id,
						'name' => $new_inst_data['name']);
				
				$this->output
				->set_content_type('application/json; charset=UTF-8')
				->set_output(json_encode($data));
			
			}
			else
			{
				//Something wen't wrong in saving the institution
				redirect('visits/new_visit');
			}
			
		}
	}
	
	public function existing_institutions()
	{
		
		$this->load->view('template/header', $this->data);
		$this->load->view('template/menu', $this->data);
		$this->load->view('existing-institutions', $this->data);
		$this->load->view('template/footer');
		$this->load->view('new-institution.php');
	}
	
	
	public function edit_institution($inst_id = NULL)
	{
		if(!isset($inst_id)) $inst_id = $this->input->get('id', TRUE);
		$group = array('admin', 'director');
		if (!$this->ion_auth->in_group($group) || !$this->institution_model->get_institution_name($inst_id))
		{
			show_404('page');
		}
		else
		{
				
			$all_inst = $this->institution_model->get_institutions();
		
			foreach($all_inst as $inst)
			{
				if($inst["id"] == $inst_id)
				{
					$this->data["inst"] = $inst;
				}
			}
			$this->data["countries"] = $this->country_model->get_countries();
	
			$this->load->view('template/header', $this->data);
			$this->load->view('template/menu', $this->data);
			$this->load->view('edit-institution', $this->data);
			$this->load->view('template/footer');
		}
	}
	
	
	public function do_edit_institution()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('security');
		$this->load->model('country_model');
	
		$this->form_validation->set_rules('id', 'ID', 'required|callback_does_inst_exist');
		$this->form_validation->set_rules('institution_name', 'Institution', 'required|max_length[250]|xss_clean');
		$this->form_validation->set_rules('address1', 'Address 1', 'max_length[250]|xss_clean');
		$this->form_validation->set_rules('address2', 'Address 2', 'max_length[250]|xss_clean');
		$this->form_validation->set_rules('postal_code', 'Postal Code', 'max_length[250]|xss_clean');
		$this->form_validation->set_rules('city', 'City', 'max_length[250]|xss_clean');
		$this->form_validation->set_rules('alpha_2', 'Country', 'required|max_lenght[3]|xss_clean');
		$this->form_validation->set_rules('lat', 'Latitude', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('long', 'Longitude', 'required|numeric|xss_clean');
	
		if ($this->form_validation->run() == FALSE)
		{
			//we have a client side form check, if form validation fails here, then somebody is trying to break in.
			redirect('home/logout');
		}
		else
		{
			$inst_data = array(
					'country_id' 	=> $this->country_model->get_country_id(
							$this->input->post('alpha_2', TRUE)),
					'lat' 			=> $this->input->post('lat', TRUE),
					'long' 			=> $this->input->post('long', TRUE),
					'name' 			=> $this->input->post('institution_name', TRUE),
					'description' 	=> $this->input->post('description', TRUE),
					'address1' 		=> $this->input->post('address1', TRUE),
					'address2' 		=> $this->input->post('address2', TRUE),
					'city' 			=> $this->input->post('city', TRUE),
					'postal_code' 	=> $this->input->post('postal_code', TRUE)
			);
				
			$inst_id = $this->input->post('id', TRUE);
			$success = $this->institution_model->update_institution($inst_id, $inst_data);
			
			
			$data = array(
					'success' => $success
					);

			$this->output
			->set_content_type('application/json; charset=UTF-8')
			->set_output(json_encode($data));
		
		}
	}
	
	
	public function data()
	{
		$x = 0;
		$data = $this->institution_model->get_institutions();
		
		foreach($data as $inst)
		{
			$data[$x]["no_of_departments"] = count($this->department_model->get_departments($inst["id"]));
			$data[$x]["no_of_visits"] = $this->visit_model->how_many_visits_inst_has($inst["id"]);
			$x++;
		}
		$this->output
			->set_content_type('application/json; charset=UTF-8')
			->set_output(json_encode($data));
	}
	
	
	
	public function delete_institution()
	{
		$inst_id = $this->input->post('id', TRUE);
		$no_of_visits = $this->visit_model->how_many_visits_inst_has($inst_id);
		$group = array('admin', 'director');
	
		if (!$this->ion_auth->in_group($group) || !$this->institution_model->get_institution_name($inst_id) || ($no_of_visits > 0))
		{
			show_404('page');
		}
		else
		{	
			$was_inst_delete_success = $this->institution_model->delete_institution($inst_id);
				
			if(count($this->department_model->get_departments($inst_id)>0))
			{
				$this->department_model->delete_departments($inst_id);
			}
				
			$data["success"] = $was_inst_delete_success;
				
			$this->output
			->set_content_type('application/json; charset=UTF-8')
			->set_output(json_encode($data));
	
		}
	}
	
	function does_inst_exist($inst_id)
	{
		if ($this->institution_model->get_institution_name($inst_id))
		{
			return true;
		}
		else
		{
			$this->form_validation->set_message('does_inst_exist', 'Specified institution does not exist');
			return FALSE;
		}
	}
	
	
}