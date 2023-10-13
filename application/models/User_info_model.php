<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_info_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}


	public function get_info($id = NULL)
	{
		if (!empty($id)) {
			$user = $this->ion_auth->user($id)->row();

			if ($user) {
				$data['id']         = $user->id;
				$data['ipaddress']  = $user->ip_address;
				$data['username']   = !empty($user->username) ? htmlspecialchars($user->username, ENT_QUOTES, 'UTF-8') : NULL;
				$data['email']      = $user->email;
				$data['created_on'] = $user->created_on;
				$data['lastlogin']  = !empty($user->last_login) ? $user->last_login : NULL;
				$data['active']     = $user->active;
				$data['fullname']  = !empty($user->full_name) ? htmlspecialchars($user->full_name, ENT_QUOTES, 'UTF-8') : NULL;
				$data['title']   = !empty($user->title) ? htmlspecialchars($user->title, ENT_QUOTES, 'UTF-8') : NULL;
				$data['nip']    = !empty($user->nip) ? htmlspecialchars($user->nip, ENT_QUOTES, 'UTF-8') : NULL;
				$data['nik']      = !empty($user->nik) ? $user->nik : NULL;
				$data['img'] = !empty($user->img) ? $user->img : 'gambar.svg';

				return $data;
			}
		}
	}
}
