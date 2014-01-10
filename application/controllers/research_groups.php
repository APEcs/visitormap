<?php
class Research_groups extends CI_Controller {
	
	private $data;
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->library('ion_auth');
		$this->load->model('visit_model');
		$this->load->model('research_group_model');
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

	
	public function existing_groups()
	{
	
		$this->load->view('template/header', $this->data);
		$this->load->view('template/menu', $this->data);
		$this->load->view('existing-research-groups', $this->data);
		$this->load->view('template/footer');
		$this->load->view('new-research-group.php');
	}
	
	
	public function new_research_group()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('security');
		
		$this->form_validation->set_rules('group_acronym', 'Group Acronym', 'required|max_length[40]|xss_clean');
		$this->form_validation->set_rules('group_name', 'Group Name', 'required|max_length[250]|xss_clean');
		
		if ($this->form_validation->run() == FALSE)
		{
			//we have a client side form check, if form validation fails here, then somebody is trying to break in.
			redirect('home/logout');	
		}
		else
		{
			$new_group_data = array(
				'name' 	=> $this->input->post('group_name', TRUE),
				'acronym' => $this->input->post('group_acronym', TRUE)
				);
			
			$new_group_id = $this->research_group_model->insert_research_group($new_group_data);
			
			if ($new_group_id)
			{
				$data = array(
						'id' => $new_group_id,
						'name' => $new_group_data['name']);
				
				$this->output
				->set_content_type('application/json; charset=UTF-8')
				->set_output(json_encode($data));
			
			}
			else
			{
				//Something wen't wrong in saving the group
				redirect('visits/new_visit');
			}
			
		}
	}
	
	
	public function data()
	{
		$x = 0;
		$data = $this->research_group_model->get_research_groups();
	
		foreach($data as $group)
		{
			$data[$x]["unique_visitors"] = $this->visit_model->how_many_visitors($group["id"],"group_id", TRUE);
			$data[$x]["visits"] = $this->visit_model->how_many_visitors($group["id"], "group_id");
			$x++;
		}
		$this->output
		->set_content_type('application/json; charset=UTF-8')
		->set_output(json_encode($data));
	}
	
	
	public function get_edit_data()
	{
		$group_id = $this->input->post('id', TRUE);
		$group = array('admin', 'director');
		if (!$this->ion_auth->in_group($group) || !$this->research_group_model->get_research_group_name($group_id))
		{
			show_404('page');
		}
		else
		{
			$all_groups = $this->research_group_model->get_research_groups();
	
			foreach ($all_groups as $group)
			{
				if($group["id"]==$group_id)
				{
					$data=$group;
				}
			}
			$this->output
			->set_content_type('application/json; charset=UTF-8')
			->set_output(json_encode($data));
		}
	}
	
	
	
	public function do_edit_group()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('security');
	
		$this->form_validation->set_rules('group_id', 'ID', 'required|xss_clean|callback_group_exist');
		$this->form_validation->set_rules('group_acronym', 'Group Acronym', 'required|max_length[40]|xss_clean');
		$this->form_validation->set_rules('group_name', 'Group Name', 'required|max_length[250]|xss_clean');
	
		if ($this->form_validation->run() == FALSE)
		{
			//we have a client side form check, if form validation fails here, then somebody is trying to break in.
			redirect('home/logout');
		}
		else
		{
			$group_data = array(
					'name' 	=> $this->input->post('group_name', TRUE),
					'acronym' => $this->input->post('group_acronym', TRUE)
			);
				
			$group_id = $this->input->post('group_id', TRUE);
			
			
				
			if ($this->research_group_model->update_research_group($group_id, $group_data))
			{
				$data = array(
						'update_success' => TRUE);
	
				$this->output
				->set_content_type('application/json; charset=UTF-8')
				->set_output(json_encode($data));
					
			}
			else
			{
				//Something wen't wrong in updating the group
				redirect('research_groups/existing_groups');
			}
				
		}
	}
	
	
	public function delete_group()
	{
		$group_id = $this->input->post('id', TRUE);
		$no_of_visits = $this->visit_model->how_many_visitors($group_id, "group_id");;
		$group = array('admin', 'director');
	
		if (!$this->ion_auth->in_group($group) || !$this->group_exist($group_id) || ($no_of_visits > 0))
		{
			show_404('page');
		}
		else
		{
			$was_group_delete_success = $this->research_group_model->delete_group($group_id);
	
			$data["success"] = $was_group_delete_success;
	
			$this->output
			->set_content_type('application/json; charset=UTF-8')
			->set_output(json_encode($data));
	
		}
	}
	
	
	function group_exist($group_id) {
		
		if ($this->research_group_model->get_research_group_name($group_id))
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('group_exist', 'Specified group does not exist');
			return FALSE;
		}
	}
	
}