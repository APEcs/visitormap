<?php
class Login extends CI_Controller {
	
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
		
		$this->load->view('data-generator/template/header');
		$this->load->view('data-generator/login');
		$this->load->view('data-generator/template/footer');
	}
	
	
	public function action() 
	{
		$action = $this->input->post("action");
	
		$this->validate_form();
		
		switch($action)
		{
			case 'sign_in':
				$this->try_login();
				break;
			case 'create_new_user':
				$this->create_new_user();
			
		}
	}
	
	
	public function validate_form()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('security');
		
		$this->form_validation->set_error_delimiters('<div class="text-error pull-left"> <i class="icon-warning-sign"></i> ', '</div><br>');
		
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required');
		
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('data-generator/template/header');
			$this->load->view('data-generator/login');
			$this->load->view('data-generator/template/footer');
		}
	}
	
	
	
	
	public function try_login() 
	{
		
		$this->load->library('security');
			
		$email = $this->security->xss_clean($this->input->post('email'));
		$password = $this->security->xss_clean($this->input->post('password'));
		
		if ($this->ion_auth->login($email, $password, FALSE))
		{
			//Login was success
			redirect('data-generator/home');
		}	
		else 
		{
			//Something went wrong
			$data["error"] = '<div class="text-error"> <i class="icon-warning-sign"> </i> Incorrect username or password</div><br>';
			$this->load->view('data-generator/template/header');
			$this->load->view('data-generator/login', $data);
			$this->load->view('data-generator/template/footer');
		}
			
	}
	
	public function create_new_user()
	{
		$data["email"] = $this->security->xss_clean($this->input->post('email'));
		$data["password"] = $this->security->xss_clean($this->input->post('password'));

		$this->load->view('data-generator/template/header');
		$this->load->view('data-generator/create_new_user', $data);
		$this->load->view('data-generator/template/footer');
		
	}
	
}