<?php
class Stats extends CI_Controller {
	
	private $data;
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->library('ion_auth');
		$this->load->helper('url');
		$this->load->model('visit_model');
		
		if ($this->ion_auth->logged_in())
		{
			$user = $this->ion_auth->user()->row();
			$this->data["first_name"] = $user->first_name;
			$this->data["logged_in"] = TRUE;
			$temp = $this->ion_auth->get_users_groups()->result(0);
			$this->data["group"] = $temp[0]["name"];
		}
		
	}

	
	public function index()
	{
		$this->load->view('template/header', $this->data);
		$this->load->view('template/menu', $this->data);

		$this->load->view('stats', $this->data);
		$this->load->view('template/footer');	

		if (!$this->ion_auth->logged_in())
		{
			$this->load->view('login', $this->data);
		}
	}

	
	public function data()
	{
		$data = $this->visit_model->get_visits(FALSE, FALSE, TRUE);
		$this->output
			->set_content_type('application/json; charset=UTF-8')
			->set_output(json_encode($data));
	}
	
	
	
	
	
}