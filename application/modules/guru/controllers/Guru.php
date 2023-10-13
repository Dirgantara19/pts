<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Guru extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in() or !$this->ion_auth->is_teacher()) {
			redirect('auth/login', 'refresh');
		} else {

			$this->load->model(array('user_info_model'));
			$this->load->model(array('M_Guru'));
			// Load langage
			$this->lang->load(array('backend'));

			$this->data['title'] = 'Guru';

			$this->data['lang_full_name']         = $this->lang->line('full_name');
			$this->data['lang_email']              = $this->lang->line('email');
			$this->data['lang_nip']       		= $this->lang->line('nip');
			$this->data['lang_nik']              = $this->lang->line('nik');
			$this->data['lang_title']       = $this->lang->line('title');
			$this->data['lang_password']           = $this->lang->line('password');
			$this->data['lang_password_confirm']   = $this->lang->line('password_confirm');
			$this->data['lang_password_if_change'] = $this->lang->line('password_if_change');
			$this->data['lang_cancel']             = $this->lang->line('cancel');
			$this->data['lang_save']             = $this->lang->line('save');


			$this->data['theme']     = (!empty($this->config->item('dp_theme_teacher'))) ? $this->config->item('dp_theme_teacher') : 'default';
			$this->data['theme_url'] = base_url($this->config->item('dp_theme_teacher_url'));
		}
	}

	public function render()
	{
		if (!isset($this->data['subtitle'])) {
			$this->data['separate'] = NULL;
			$this->data['subtitle'] = NULL;
		} else {
			$this->data['separate'] = ' | ';
		}
		$this->data['user'] = $this->user_info_model->get_info($this->session->userdata('user_id'));

		$this->data['pagetitle'] = $this->data['title'] . $this->data['separate'] . $this->data['subtitle'];

		// Include nav header
		$this->data['navbar'] = $this->parser->parse('guru/_theme/navbar', $this->data, TRUE);

		// Include navside
		$this->data['sidebar'] = $this->parser->parse('guru/_theme/sidebar', $this->data, TRUE);

		// Include content
		$this->data['content'] = $this->parser->parse($this->data['page_content'], $this->data, TRUE);



		$this->data['data'] = $this->data;
		$this->parser->parse('_theme/template', $this->data);
	}


	public function dashboard()
	{
		$this->data['subtitle'] = 'Dashboard';
		$this->data['page_content'] = 'guru/index';
		$this->render();
	}

	// Siswa

	public function siswa()
	{


		$id = $this->data['user_info']['id'];
		$this->data['users'] = $this->M_Guru->teachers($id);
		$this->data['tahunsemester'] = $this->M_Guru->tahunsemester();
		$this->data['subtitle']    = 'Data Siswa';
		$this->data['page_content'] = 'guru/data/siswa';
		$this->render();
	}
	// Nilai
	public function nilai()
	{
		$id = $this->data['user_info']['id'];
		$this->data['users'] = $this->M_Guru->teachers($id);
		$this->data['subtitle']    = 'Data Nilai';
		$this->data['page_content'] = 'guru/data/nilai';
		$this->render();
	}


	// Profile

	public function profile()
	{
		$this->data['subtitle'] = 'Profile';
		$this->data['page_content'] = 'profile/index';
		$this->render();
	}
}