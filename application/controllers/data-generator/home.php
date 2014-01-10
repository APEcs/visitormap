<?php
class Home extends CI_Controller {
	
	private $data;
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->library('ion_auth');
		$this->load->helper('url');
		$this->load->model('institution_model');
		$this->load->model('google_model');
		
	}

	
	public function index()
	{
		if ($this->ion_auth->logged_in())
		{
			$user = $this->ion_auth->user()->row();
			$data["first_name"] = $user->first_name;
			
			$this->load->view('data-generator/template/header');
			$this->load->view('data-generator/template/menu',$data);
			$this->load->view('data-generator/home', $data);
			$this->load->view('data-generator/template/footer');			
		}
		
		else 
		{
			$this->load->view('data-generator/template/header');
			$this->load->view('data-generator/login');
			$this->load->view('data-generator/template/footer');			
		}
		

	}
	
	
	public function logout()
	{
		$this->ion_auth->logout();
		redirect('data-generator/login');
	}
	
	
}