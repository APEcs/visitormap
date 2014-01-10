<?php
class CreateUser extends CI_Controller {
	
	private $data;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->library('ion_auth');	
		$this->load->helper('url');
		
	}

	
	public function index()
	{
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
		
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('data-generator/template/header');
			$this->load->view('data-generator/create_new_user');
			$this->load->view('data-generator/template/footer');
		}
		else 
		{
			$first_name = $this->security->xss_clean($this->input->post('first_name'));
			$last_name = $this->security->xss_clean($this->input->post('last_name'));
			$email = $this->security->xss_clean($this->input->post('email'));
			$password = $this->security->xss_clean($this->input->post('password'));
			
			$additional_data = array(
					'first_name' => $first_name,
					'last_name' => $last_name,
			);
			
			if ($this->ion_auth->register($email, $password, $email, $additional_data))
			{
				
				$this->ion_auth->login($email, $password, FALSE);
				redirect('data-generator');
			}
			else
			{
				echo "something went wrong";
				
			}
		}

	}
	
}