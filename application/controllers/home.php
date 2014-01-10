<?php
class Home extends CI_Controller {
	
	private $data;
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->library('ion_auth');
		$this->load->library('json');
		$this->load->helper('url');
		$this->load->model('institution_model');
		$this->load->model('google_model');
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

		$this->load->view('home', $this->data);
		$this->load->view('template/footer');			
		
		if (!$this->ion_auth->logged_in())
		{
			$this->load->view('login', $this->data);
		}
		$this->load->view('report-problem');
	}

	
	public function data()
	{
		if ($this->ion_auth->logged_in())
		{
			$hide_visitor_name = FALSE;
		}
		else
		{
			$hide_visitor_name = TRUE;
		}
		
		$data = $this->json->get_points_for_home_map($hide_visitor_name);
		$this->output
			->set_content_type('application/json; charset=UTF-8')
			->set_output($data);
	}
	
	
	public function logout()
	{
		$this->ion_auth->logout();
		redirect('home');
	}
	
	
	
	
	public function login()
	{
		$this->validate_form();
		$this->try_login();
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
			$this->data["login_error"] = TRUE;
			$this->index();
		}
	}
	
	
	public function try_login()
	{
		
		
		$this->load->library('security');
			
		$email = $this->security->xss_clean($this->input->post('email'));
		$password = $this->security->xss_clean($this->input->post('password'));

		$this->ion_auth->is_first_login($email);
		
		if ($this->ion_auth->login($email, $password, FALSE))
		{
			
			if($this->ion_auth->is_first_login($email))
			{
				redirect('home/forcePasswordChange');
			}
			else 
			{
				redirect('home');
			}
		}
		else
		{
			//Something went wrong
			$this->data["login_error"] = '<div class="text-error"> <i class="icon-warning-sign"> </i> Incorrect username or password</div>';
			$this->index();
		}
			
	}
	
	
	public function forcePasswordChange()
	{
		$this->load->view('template/header', $this->data);
		$this->load->view('template/menu', $this->data);
			
		$this->load->view('home', $this->data);
		$this->load->view('template/footer');
		
		$this->load->view('change-password', $this->data);
	}
	
	
	
	public function report_problem() 
	{
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('security');
		$this->form_validation->set_rules('visit_id', 'visit_id', 'required|xss_clean|callback_visit_exists');
		$this->form_validation->set_rules('problem_text', 'problem_text', 'required|xss_clean');
		$this->form_validation->set_rules('problem_reporter_email', 'problem_reporter_email', 'required|xss_clean');
		
		if ($this->form_validation->run() == FALSE)
		{
			//should never happen, we have client side form check.
			//if we are here, somebody tries to hack in.
		}	
		else
		{
			$visit_id = $this->input->post('visit_id', TRUE);
			$this->load->library('email');
			
			
			$this->email->to($this->config->item('master_admin_email'));
			$this->email->subject('CS Research Visitors Map - Problem with a visit report');
				
			$msg = $this->load->view('template/report-email-template', '', true);
			$search = array("{email}", "{visitor}", "{institution}", "{from_date}", "{to_date}", "{problem-description}", "{visit_id}");

			$visit_data = $this->visit_model->get_visits(FALSE, $visit_id);			
			$replace = array(
					$this->input->post('problem_reporter_email', TRUE), 
					$visit_data[0]["visitor_title"]." ".$visit_data[0]["visitor_first_name"]." ".$visit_data[0]["visitor_last_name"],
					$visit_data[0]["institution"],
					$visit_data[0]["from_date"],
					$visit_data[0]["to_date"],
					$this->input->post('problem_text', TRUE),
					$visit_id);
			
			$output = str_replace($search, $replace, $msg);
				
			$this->email->message($output);
			$result = $this->email->send();			
			
			if(!$result)
			{
				//sending failed.
				error_log($this->email->print_debugger());
				$data = array(
					'success' => FALSE);
			}
			else
			{
				//email was successfully sent
				$data = array(
					'success' => TRUE);
			}
			
			$this->output
				->set_content_type('application/json; charset=UTF-8')
				->set_output(json_encode($data));
			
		}		
	}

	function visit_exsists($visit_id)
	{
		if ($this->visit_model->does_visit_exists($visit_id))
		{
			return true;
		}
		else
		{
			$this->form_validation->set_message('visit_exsists', 'Specified visit does not exist');
			return FALSE;
		}
	}
	
	
}