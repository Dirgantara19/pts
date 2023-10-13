<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends MX_Controller
{
	protected $data = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->library('sweetalert');
		$this->load->model('Crud_model', 'crud');
		$this->load->helper('export');



		// Meta charset
		$this->data['charset'] = (!empty($this->config->item('charset'))) ? $this->config->item('charset') : 'UTF-8';

		// Ion Auth
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		// If logged request info user
		if ($this->ion_auth->logged_in()) {
			$this->data['user_info']     = $this->user_info_model->get_info($this->ion_auth->user()->row()->id);
			$this->data['user_fullname'] = $this->data['user_info']['fullname'];
		}

		// Protection
		$this->output->set_header('X-Content-Type-Options: nosniff');
		$this->output->set_header('X-Frame-Options: DENY');
		$this->output->set_header('X-XSS-Protection: 1; mode=block');
	}

	public function __response($type, $message, $extra = null)
	{

		if (is_array($type) && is_array($message)) {
			$response = [];
			foreach ($type as $key => $t) {
				$response[$t] = $message[$key];
			}
		} else {

			$response = [$type => $message];
		}

		return $response;
	}
}