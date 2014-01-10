<?php
class Users extends CI_Controller {
	
	private $data;
	
	public function __construct()
	{
		parent::__construct();	
		$this->load->library('session');
		$this->load->library('ion_auth');	
		$this->load->helper('url');
		
		if (!$this->ion_auth->logged_in())
		{
			redirect("home");
		}

		$user = $this->ion_auth->user()->row();
		$this->data["first_name"] = $user->first_name;
		$this->data["logged_in"] = TRUE;
		
		$temp = $this->ion_auth->get_users_groups()->result(0);
		$this->data["group"] = $temp[0]["name"];
	}

	
	public function new_user()
	{
		$this->load->model('research_group_model');
		$this->data["research_groups"] = $this->research_group_model->get_all_research_group_names();

		$this->load->view('template/header', $this->data);
		$this->load->view('template/menu', $this->data);
		$this->load->view('new-user', $this->data);
		$this->load->view('template/footer');
		
	}
	
	public function user_list()
	{

		$this->load->view('template/header', $this->data);
		$this->load->view('template/menu', $this->data);
		$this->load->view('user-list', $this->data);
		$this->load->view('template/footer');
		
	}
	
	public function edit_account()
	{
		
		$this->load->view('template/header', $this->data);
		$this->load->view('template/menu', $this->data);
		$this->load->view('edit-account', $this->data);
		$this->load->view('template/footer');
	}
	
	
	
	public function do_new_user()
	{
		$this->load->model('research_group_model');
		$this->data["research_groups"] = $this->research_group_model->get_all_research_group_names();
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('security');
		
		$email = $this->security->xss_clean($this->input->post('email'));
		
		$this->form_validation->set_error_delimiters('<div class="text-error"> <i class="icon-warning-sign"></i> <small>', '</small></div><br>');
		$this->form_validation->set_message('is_unique', 'It looks <b>'.$email.'</b> is already registered. Use different e-mail.');
		
		$this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]|xss_clean');
		$this->form_validation->set_rules('email_repeat', 'Repeat Email', 'matches[email]|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'min_length[6]|xss_clean');
		$this->form_validation->set_rules('password_repeat', 'Repeat Password', 'matches[password]|xss_clean');
		$this->form_validation->set_rules('user_type', 'User Type', 'required|xss_clean');
		$this->form_validation->set_rules('host', 'Host', 'required|xss_clean');
		$this->form_validation->set_rules('research_group', 'Research Group', 'required|xss_clean');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('template/header', $this->data);
			$this->load->view('template/menu', $this->data);
			$this->load->view('new-user', $this->data);
			$this->load->view('template/footer');
		}
		else 
		{
			$first_name = $this->security->xss_clean($this->input->post('first_name'));
			$last_name = $this->security->xss_clean($this->input->post('last_name'));
			$email = $this->security->xss_clean($this->input->post('email'));
			$password = $this->security->xss_clean($this->input->post('password'));
			$user_type = $this->security->xss_clean($this->input->post('user_type'));
			$additional_data = array(
					'first_name' => $first_name,
					'last_name' => $last_name,
			);
			$group = array($user_type);
			
				
			if ($this->ion_auth->register($email, $password, $email, $additional_data, $group))
			{
				
				$this->send_email($email, $password);

				$host = $this->security->xss_clean($this->input->post('host'));
				//if new user is host, add him to persons registry as well
				if($host="yes")
				{
					$this->load->model('person_model');
					
					$group_name = $this->security->xss_clean($this->input->post('research_group'));
					$this->person_model->insert_host($first_name, $last_name, $this->research_group_model->get_research_group_id($group_name));
				}
				
				$this->data["new_user"] = $first_name;
				$this->user_list();
			}
			else
			{
				//Something wen't wrong in ion_auth
				redirect('users/user_list');
				
			}
		}

	}
	
	
	
	protected function send_email($email, $password)
	{
		$this->load->library('email');
			
		$this->email->to($email);
		$this->email->subject('Welcome to CS Research Visitors Map!');
			
		$msg = $this->load->view('template/email-template', '', true);
		$search = array("{username}", "{password}");
		$replace = array($email, $password);
		$output = str_replace($search, $replace, $msg);
			
		$this->email->message($output);
			
		$this->email->send();
	}
	
	public function change_password()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('security');

	
		$this->form_validation->set_rules('password', 'Password', 'min_length[6]|max_length[99]|xss_clean');
		$this->form_validation->set_rules('password_repeat', 'Repeat Password', 'matches[password]|xss_clean');
		
		if ($this->form_validation->run() == FALSE)
		{
			//we have a client side form check, if form validation fails here, then somebody is trying to break in.
			redirect('home/logout');
		}
		else
		{
			$password = $this->security->xss_clean($this->input->post('password'));
			$id = $user = $this->ion_auth->user()->row()->id;
			$this->ion_auth->update($id, array('password' => $password));
			$this->ion_auth->set_pass_changed(1, $id);

			redirect('home');
		}
	}
	
}