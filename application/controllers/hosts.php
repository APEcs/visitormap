<?php
class Hosts extends CI_Controller {
	
	private $data;
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->library('ion_auth');
		$this->load->model('visit_model');
		$this->load->model('person_model');
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

	
	
	public function new_host()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('security');
		
		$this->form_validation->set_rules('host_title', 'Title', 'required|max_length[30]|xss_clean');
		$this->form_validation->set_rules('host_first_name', 'First Name', 'required|max_length[100]|xss_clean');
		$this->form_validation->set_rules('host_last_name', 'Last Name', 'required|max_length[100]|xss_clean');
		$this->form_validation->set_rules('host_sex', 'Sex', 'required|xss_clean|callback_sex_correct');
		
		if ($this->form_validation->run() == FALSE)
		{
			//we have a client side form check, if form validation fails here, then somebody is trying to break in.
			redirect('home/logout');	
		}
		else
		{
			$new_host_data = array(
				'type'			=> 'host',
				'first_name' 	=> $this->input->post('host_first_name', TRUE),
				'last_name' 	=> $this->input->post('host_last_name', TRUE),
				'title' 		=> $this->input->post('host_title', TRUE),
				'sex' 			=> $this->input->post('host_sex', TRUE)
				);
			
			$new_host_id = $this->person_model->insert_host_fast($new_host_data);
			
			if ($new_host_id)
			{
				$data = array(
						'id' => $new_host_id,
						'name' => $new_host_data['first_name']." ".$new_host_data['last_name']);
				
				$this->output
				->set_content_type('application/json; charset=UTF-8')
				->set_output(json_encode($data));
			
			}
			else
			{
				//Something wen't wrong in saving the host
				redirect('visits/new_visit');
			}
			
		}
	}
	
	
	function sex_correct($sex)
	{
		if ($sex == "male" || $sex == "female") return TRUE;
		else return FALSE;
	}
	
	
	public function existing_hosts()
	{
	
		$this->load->view('template/header', $this->data);
		$this->load->view('template/menu', $this->data);
		$this->load->view('existing-hosts', $this->data);
		$this->load->view('template/footer');
		$this->load->view('new-host.php');
	}
	
	
	public function data()
	{
		$x = 0;
		$data = $this->person_model->get_host_names();
	
		foreach($data as $host)
		{
			$data[$x]["unique_visitors"] = $this->visit_model->how_many_visitors($host["id"],"host_id", TRUE);
			$data[$x]["visits"] = $this->visit_model->how_many_visitors($host["id"]);
			$x++;
		}
		$this->output
		->set_content_type('application/json; charset=UTF-8')
		->set_output(json_encode($data));
	}
	
	
	public function get_edit_data()
	{
		$host_id = $this->input->post('id', TRUE);
		$group = array('admin', 'director');
		if (!$this->ion_auth->in_group($group) || !$this->person_model->get_person_name($host_id))
		{
			show_404('page');
		}
		else
		{
			$all_hosts = $this->person_model->get_host_names();

			foreach ($all_hosts as $host)
			{
				if($host["id"]==$host_id)
				{
					$data=$host;
				}
			}
			$this->output
			->set_content_type('application/json; charset=UTF-8')
			->set_output(json_encode($data));		
		}
	}
	
	
	public function do_edit_host()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('security');
	
		$this->form_validation->set_rules('host_id', 'ID', 'required|xss_clean|callback_host_exist');
		$this->form_validation->set_rules('host_title', 'Title', 'required|max_length[30]|xss_clean');
		$this->form_validation->set_rules('host_first_name', 'First Name', 'required|max_length[100]|xss_clean');
		$this->form_validation->set_rules('host_last_name', 'Last Name', 'required|max_length[100]|xss_clean');
		$this->form_validation->set_rules('host_sex', 'Sex', 'required|xss_clean|callback_sex_correct');
	
		if ($this->form_validation->run() == FALSE)
		{
			//we have a client side form check, if form validation fails here, then somebody is trying to break in.
			redirect('home/logout');
		}
		else
		{
			
			$host_data = array(
				'type'			=> 'host',
				'first_name' 	=> $this->input->post('host_first_name', TRUE),
				'last_name' 	=> $this->input->post('host_last_name', TRUE),
				'title' 		=> $this->input->post('host_title', TRUE),
				'sex' 			=> $this->input->post('host_sex', TRUE)
				);
			
			$host_id = $this->input->post('host_id', TRUE);
			
			if ($this->person_model->update_person($host_id, $host_data))
			{
				$data = array(
						'update_success' => TRUE,
						'name' => $host_data['first_name']." ".$host_data['last_name']);
				
				$this->output
				->set_content_type('application/json; charset=UTF-8')
				->set_output(json_encode($data));
			
			}
			else
			{
				//Something wen't wrong in saving the host
				redirect('visits/new_visit');
			}
	
		}
	}
	
	public function delete_host()
	{
		$host_id = $this->input->post('id', TRUE);
		$no_of_visits = $this->visit_model->how_many_visitors($host_id, "host_id");
		$group = array('admin', 'director');
	
		if (!$this->ion_auth->in_group($group) || !$this->person_model->get_person_name($host_id) || ($no_of_visits > 0))
		{
			show_404('page');
		}
		else
		{
			$was_host_delete_success = $this->person_model->delete_person($host_id);
	
			$data["success"] = $was_host_delete_success;
	
			$this->output
			->set_content_type('application/json; charset=UTF-8')
			->set_output(json_encode($data));
	
		}
	}
	
	function host_exist($host_id)
	{
		if ($this->person_model->get_person_name($host_id))
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('host_exist', 'Specified host does not exist');
			return FALSE;
		}
	}
	
	
}