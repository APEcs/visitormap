<?php
class Googleapi extends CI_Controller {
	
	private $data;
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->library('ion_auth');
		$this->load->helper('url');
		
		$this->load->model('google_model');
		$this->load->model('institution_model');
		
	}

	
	public function index()
	{
		if ($this->ion_auth->logged_in())
		{
			$user = $this->ion_auth->user()->row();
			$this->data["first_name"] = $user->first_name;
			
			$this->data["institutions"] = $this->institution_model->get_institutions();
			
			$this->load->view('data-generator/template/header');
			$this->load->view('data-generator/template/menu',$this->data);
			$this->load->view('data-generator/googleapi', $this->data);
			$this->load->view('data-generator/template/footer');			
		}
		
		else 
		{
			redirect('data-generator/login');			
		}
		

	}
	
	public function search()
	{
		if ($this->ion_auth->logged_in())
		{
			$user = $this->ion_auth->user()->row();
			$this->data["first_name"] = $user->first_name;
				
			$this->data["institutions"] = $this->institution_model->get_institutions();

			$this->load->library('security');
			$search = $this->security->xss_clean($this->input->post('inst'));
			
			$this->data["search_results"] = $this->institution_model->search_institution($search);
			
			if((isset($this->data["search_results"])) && ($this->data["search_results"][0]["in_db"]==FALSE))
			{
				$search_result = $this->session->userdata('search_result');
				if(isset($search_result)) $this->session->unset_userdata('search_result');
				unset($search_result);
				
				$this->session->set_userdata('search_result', $this->data["search_results"][0]);
			}
			
			$this->data["search"] = $search;
			
			$this->load->view('data-generator/template/header');
			$this->load->view('data-generator/template/menu',$this->data);
			$this->load->view('data-generator/googleapi', $this->data);
			$this->load->view('data-generator/template/footer');
		}
		
		else
		{
			redirect('data-generator/login');
		}
	}
	
	
	public function save()
	{
		if ($this->ion_auth->logged_in())
		{
			$search_result = $this->session->userdata('search_result');
			if(isset($search_result)) 
			{
				
				$this->institution_model->save_institution($search_result);
				
				$this->data["save_success"] = TRUE;
				$this->index();
			}
		}
		
		else
		{
			redirect('data-generator/login');
		}
	}
	
	
}