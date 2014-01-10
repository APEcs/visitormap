<?php
class Generator extends CI_Controller {
	
	private $data;
	
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->library('ion_auth');
		$this->load->helper('url');
		
		$this->load->model('visit_model');
	}

	
	public function index()
	{
		if ($this->ion_auth->logged_in())
		{
			
			$user = $this->ion_auth->user()->row();
			$this->data["first_name"] = $user->first_name;
			
			
			$this->data["visits"] = $this->visit_model->get_visits();

			$this->load->view('data-generator/template/header');
			$this->load->view('data-generator/template/menu',$this->data);
			$this->load->view('data-generator/generator', $this->data);
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
			$this->data["save_success"] = $this->visit_model->save_visits();
			
			$this->index();
			
		}
		
		else 
		{
			redirect('data-generator/login');			
		}
	}
	
	public function generate()
	{
		if ($this->ion_auth->logged_in())
		{
			
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			$this->load->library('security');
			
			$this->form_validation->set_error_delimiters('<span class="help-inline">', '</span>');
			
			$this->form_validation->set_rules('qty', 'Qty', 'required|greater_than[0]|less_than[500]|xss_clean');
			$this->form_validation->set_rules('from_date', 'From Date', 'callback_date_check|callback_from_date_check|xss_clean');
			$this->form_validation->set_rules('to_date', 'To Date', 'callback_date_check|callback_to_date_check|xss_clean');
			$this->form_validation->set_rules('min_stay', 'Min stay length', 'required|greater_than[0]|less_than[600]|xss_clean');
			$this->form_validation->set_rules('max_stay', 'Max stay length', 'required|greater_than[0]|less_than[600]|callback_max_check|xss_clean');
			
			if ($this->form_validation->run() == FALSE)
			{
				
				$this->data["form_error"] = TRUE;
			}
			else
			{
				$qty = $this->input->get_post("qty", true);
				$from_date = $this->input->get_post("from_date", true);
				$to_date = $this->input->get_post("to_date", true);
				$min_stay = $this->input->get_post("min_stay", true);
				$max_stay = $this->input->get_post("max_stay", true);
				
				$this->visit_model->generate_visits($qty, $from_date, $to_date, $min_stay, $max_stay);
				$this->data["new_visits"] = $this->session->userdata('visits');
			}
			$this->index();
				
		}
	
		else
		{
			redirect('data-generator/login');
		}
	}
	
	
	public function date_check($str)
	{
		$date = explode(".", $str);
		
		if ((count($date)==3) && (checkdate(intval($date[1]), intval($date[0]), intval($date[2]))))
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('date_check', 'The %s is not a valid date');
			return FALSE;
		}
	}
	
	
	public function from_date_check($str)
	{
		$date = explode(".", $str);

		if(intval($date[2])>=1995)
		{
			return TRUE;
		}
		else 
		{
			$this->form_validation->set_message('from_date_check', 'Earliest date is 01.01.1995');
			return FALSE;
		}

	}
	
	public function to_date_check($to_str)
	{
		$from_str = $this->input->get_post("from_date", true);
		$max_stay = intval($this->input->get_post("max_stay", true));
		
		$from = new DateTime($from_str);
		$to = new DateTime($to_str);
		$from_with_max_stay = clone $from;
		$from_with_max_stay = $from_with_max_stay->add(new DateInterval("P".$max_stay."D"));

		if($to>$from)
		{
			if($to>=$from_with_max_stay)
			{
				return TRUE;
			}
			else
			{
			$this->form_validation->set_message('to_date_check', 'Not enough days between From and To Dates to fit Max stay');
			return FALSE;
			}	
		
		}
		else
		{
			$this->form_validation->set_message('to_date_check', 'To Date has to be later than From Date');
			return FALSE;
		}
	
	}
	
	public function max_check($str)
	{
		$max_stay = intval($str);
		$min_stay = intval($this->input->get_post("min_stay", true));
		
		if($max_stay>=$min_stay)
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('max_check', "Max Stay can't be smaller than Min Stay");
			return FALSE;
		}
	}
	
	
	
	public function delete_all()
	{
		if ($this->ion_auth->logged_in())
		{
				
			$this->visit_model->delete_all_visits();
				
			$this->index();
				
		}
		
		else
		{
			redirect('data-generator/login');
		}
	}
	
}