<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in() or !$this->ion_auth->is_admin()) {
			redirect('auth/login', 'refresh');
		} else {
			// Load model
			$this->load->model(array('backend_tools_model'));
			$this->load->model(array('user_info_model'));
			$this->load->model(array('M_Admin'));

			// Theme
			$this->data['theme']     = (!empty($this->config->item('dp_theme_admin'))) ? $this->config->item('dp_theme_admin') : 'default';
			$this->data['theme_url'] = base_url($this->config->item('dp_theme_admin_url'));


			$this->lang->load(array('backend'));
			$this->data['title'] = $this->lang->line('administration');
			// Common language
			$this->data['lang_dashboard']          = $this->lang->line('dashboard');
			$this->data['lang_security_groups']    = $this->lang->line('security_groups');
			$this->data['lang_users']              = $this->lang->line('users');
			$this->data['lang_maintenance']        = $this->lang->line('maintenance');
			$this->data['lang_list']               = $this->lang->line('list');
			$this->data['lang_actions']            = $this->lang->line('actions');
			$this->data['lang_help']               = $this->lang->line('help');
			$this->data['lang_edit']               = $this->lang->line('edit');
			$this->data['lang_export_list']        = $this->lang->line('export_list');
			$this->data['lang_import_list']        = $this->lang->line('import_list');
			$this->data['lang_add_user']           = $this->lang->line('add_user');
			$this->data['lang_email']              = $this->lang->line('email');
			$this->data['lang_group']              = $this->lang->line('group');
			$this->data['lang_role']              = $this->lang->line('role');
			$this->data['lang_group_name']         = $this->lang->line('group_name');
			$this->data['lang_status']             = $this->lang->line('status');
			$this->data['lang_active']             = $this->lang->line('active');
			$this->data['lang_inactive']           = $this->lang->line('inactive');
			$this->data['lang_see']                = $this->lang->line('see');
			$this->data['lang_file']               = $this->lang->line('file');
			$this->data['lang_full_name']         = $this->lang->line('full_name');
			$this->data['lang_nip']       		= $this->lang->line('nip');
			$this->data['lang_nik']              = $this->lang->line('nik');
			$this->data['lang_title']       = $this->lang->line('title');
			$this->data['lang_password']           = $this->lang->line('password');
			$this->data['lang_password_confirm']   = $this->lang->line('password_confirm');
			$this->data['lang_password_if_change'] = $this->lang->line('password_if_change');
			$this->data['lang_cancel']             = $this->lang->line('cancel');
			$this->data['lang_create']             = $this->lang->line('create');
			$this->data['lang_save']               = $this->lang->line('save');
			$this->data['lang_add_group']          = $this->lang->line('add_group');
			$this->data['lang_name']               = $this->lang->line('name');
			$this->data['lang_color']              = $this->lang->line('color');
			$this->data['lang_description']        = $this->lang->line('description');
			$this->data['lang_delete']             = $this->lang->line('delete');
			$this->data['lang_import']             = $this->lang->line('import');
			$this->data['lang_yes']                = $this->lang->line('yes');
			$this->data['lang_no']                 = $this->lang->line('no');
		}
	}

	public function render()
	{
		// Gestion title and subtitle
		if (!isset($this->data['subtitle'])) {
			$this->data['separate'] = NULL;
			$this->data['subtitle'] = NULL;
		} else {
			$this->data['separate'] = ' | ';
		}


		$this->data['pagetitle'] = $this->data['title'] . $this->data['separate'] . $this->data['subtitle'];

		// Include nav header
		$this->data['navbar'] = $this->parser->parse('admin/_theme/navbar', $this->data, TRUE);

		// Include navside
		$this->data['sidebar'] = $this->parser->parse('admin/_theme/sidebar', $this->data, TRUE);

		// Include content
		$this->data['content'] = $this->parser->parse($this->data['page_content'], $this->data, TRUE);

		// Render template
		$this->data['data'] = $this->data;
		$this->parser->parse('_theme/template', $this->data);
	}

	public function dashboard()
	{

		$count_user = $this->M_Admin->count_users();

		if ($count_user >= 1) {
			$this->data['lang_user_plural'] = plural($this->lang->line('user'));
		} else {
			$this->data['lang_user_plural'] = $this->lang->line('user');
		}
		$count_gurupengampu = $this->M_Admin->count_teachers();
		$count_siswa = $this->M_Admin->count_siswa();
		$count_kelas = $this->M_Admin->count_kelas();

		$this->data['nbr_user']     = $count_user;
		$this->data['nbr_gurupengampu'] = $count_gurupengampu;
		$this->data['nbr_siswa']     = $count_siswa;
		$this->data['nbr_kelas']     = $count_kelas;
		$this->data['subtitle']     = $this->lang->line('dashboard');
		$this->data['page_content'] = 'admin/dashboard';

		$this->render();
	}


	// Gurupengampu

	public function gurupengampu()
	{
		$this->data['subtitle'] = 'Data Guru Pengampu';
		$this->data['page_content'] = 'admin/data/gurupengampu';

		$teacher_group = $this->config->item('teacher_group', 'ion_auth');

		$this->data['guru'] = $this->M_Admin->teachers([$teacher_group])->result();
		$this->data['kelas'] = $this->crud->table('kelas')->get_all();
		$this->data['mapel'] = $this->crud->table('mapel')->get_all();

		$this->render();
	}
	// Siswa
	public function siswa()
	{
		$this->data['subtitle'] = 'Data Siswa';
		$this->data['kelas'] = $this->crud->table('kelas')->get_all();
		$this->data['page_content'] = 'admin/data/siswa';

		$this->render();
	}

	// Kelas

	public function kelas()
	{
		$this->data['subtitle'] = 'Data Kelas';
		$this->data['page_content'] = 'admin/data/kelas';

		$this->render();
	}

	// Mapel

	public function mapel()
	{
		$this->data['subtitle'] = 'Data Mata Pelajaran';
		$this->data['page_content'] = 'admin/data/mapel';

		$this->render();
	}

	// Users

	public function users()
	{
		$this->data['subtitle']     = $this->lang->line('users');
		$this->data['page_content'] = 'admin/data/users';

		$this->render();
	}

	// Profile

	public function profile()
	{
		$this->data['subtitle'] = 'Profile';
		$this->data['page_content'] = 'profile/index';
		$this->render();
	}
	// Akumulasi Nilai

	public function nilai()
	{
		$this->data['subtitle'] = 'Nilai';
		$this->data['kelas'] = $this->crud->table('kelas')->get_all();
		$this->data['mapel'] = $this->crud->table('mapel')->get_all();
		$this->data['tahun'] = $this->crud->table('tahun')->get_all();
		$this->data['page_content'] = 'admin/data/nilai';
		$this->render();
	}

	// Tahun ajaran

	public function tahunajaran()
	{
		$this->data['subtitle']     = "Tahun Ajaran";
		$this->data['page_content'] = 'admin/data/tahunajaran';

		$this->render();
	}
}