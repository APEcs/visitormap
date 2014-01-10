<?php
class Departments extends CI_Controller {
	
	private $data;
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->library('ion_auth');

		$this->load->model('visit_model');
		$this->load->model('department_model');
		$this->load->model('institution_model');
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

	public function existing_departments()
	{
		$this->data["institutions"] = $this->institution_model->get_institutions();
	
		$this->load->view('template/header', $this->data);
		$this->load->view('template/menu', $this->data);
		$this->load->view('existing-departments', $this->data);
		$this->load->view('template/footer');
	}
	
	
	public function get_departments()
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
			$inst_id = $this->input->post('id', TRUE);
			$data = $this->department_model->get_departments($inst_id);
			$x=0;
			
			foreach($data as $dep)
			{
				$data[$x]["no_of_visits"] = $this->visit_model->how_many_visitors($dep["id"], "department_id");
				$x++;
			}
	
			$this->output
			->set_content_type('application/json; charset=UTF-8')
			->set_output(json_encode($data));
		}
	
	}
	
	
	public function new_department()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('security');
	
		$this->form_validation->set_rules('name', 'Department Name', 'required|max_length[250]|xss_clean');
		$this->form_validation->set_rules('institution_id', 'institution id', 'required|is_natural_no_zero|xss_clean|callback_institution_exsists');
		
		if ($this->form_validation->run() == FALSE)
		{
			//we have a client side form check, if form validation fails here, then somebody is trying to break in.
			redirect('home/logout');
		}
		else
		{
			$new_department_data = array(
					'institution_id' => $this->input->post('institution_id', TRUE),
					'name' 	=> $this->input->post('name', TRUE)
					
			);
				
			$new_department_id = $this->department_model->insert_department($new_department_data);
				
			if ($new_department_id)
			{
				$data = array(
						'id' => $new_department_id,
						'name' => $new_department_data['name']);
	
				$this->output
				->set_content_type('application/json; charset=UTF-8')
				->set_output(json_encode($data));
					
			}
			else
			{
				//Something wen't wrong in saving the department
				redirect('visits/new_visit');
			}
				
		}
	}
	
	
	
	public function do_edit_department()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('security');
	
		$this->form_validation->set_rules('name', 'Department Name', 'required|max_length[250]|xss_clean');
		$this->form_validation->set_rules('institution_id', 'institution id', 'required|is_natural_no_zero|xss_clean|callback_institution_exsists');
		$this->form_validation->set_rules('delete', 'delete', 'required|greater_than[-1]|less_than[2]|xss_clean');
		
		if ($this->form_validation->run() == FALSE)
		{
			//we have a client side form check, if form validation fails here, then somebody is trying to break in.
			//redirect('home/logout');
		}
		else
		{
			$dep_id = $this->input->post('id', TRUE);
			if($this->input->post('delete', TRUE) == 1 && $this->visit_model->how_many_visitors($dep_id, "department_id")==0)
			{
				//delete is selected
				$data = array(
						'id' => $dep_id,
						'success_delete' => $this->department_model->delete_department($dep_id)
						);
				
				$this->output
				->set_content_type('application/json; charset=UTF-8')
				->set_output(json_encode($data));
				
			}
			
			
			elseif(intval($dep_id)!=0 && $this->department_model->does_department_exists($dep_id)) 
			{
				//Existing department, let's do update
				$old_department_data = array(
					'institution_id' => $this->input->post('institution_id', TRUE),
					'name' 	=> $this->input->post('name', TRUE)
				);
				$data = array(
						'id' => $dep_id,
						'success' => $this->department_model->update_department($dep_id, $old_department_data));
				
				$this->output
				->set_content_type('application/json; charset=UTF-8')
				->set_output(json_encode($data));				
				
				
			}
			else
			{
				//New department
				$new_department_data = array(
						'institution_id' => $this->input->post('institution_id', TRUE),
						'name' 	=> $this->input->post('name', TRUE)
				);
				$new_department_id = $this->department_model->insert_department($new_department_data);
				
				if ($new_department_id)
				{
					$new_data = array(
							'id' => $dep_id,
							'success' => TRUE);
				
					$this->output
					->set_content_type('application/json; charset=UTF-8')
					->set_output(json_encode($new_data));
						
				}				
			}
		}
	}
	
	
	function institution_exsists($inst_id)
	{
		$name = $this->institution_model->get_institution_name($inst_id);
		if ($name == NULL) return FALSE;
		else return TRUE;
	}
	
	
}