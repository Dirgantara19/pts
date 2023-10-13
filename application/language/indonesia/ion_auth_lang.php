<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Ion Auth Lang - English
*
* Author: Ben Edmunds
*         ben.edmunds@gmail.com
*         @benedmunds
*
* Location: http://github.com/benedmunds/ion_auth/
*
* Created:  03.14.2010
*
* Description:  English language file for Ion Auth messages and errors
*
*/

// Pembuatan Akun
$lang['account_creation_successful']            = 'Akun Berhasil Dibuat';
$lang['account_creation_unsuccessful']          = 'Tidak Dapat Membuat Akun';
$lang['account_creation_duplicate_email']       = 'Email Sudah Digunakan atau Tidak Valid';
$lang['account_creation_duplicate_identity']    = 'Identitas Sudah Digunakan atau Tidak Valid';
$lang['account_creation_missing_default_group'] = 'Grup Default Belum Ditetapkan';
$lang['account_creation_invalid_default_group'] = 'Nama Grup Default Tidak Valid';


// Kata Sandi
$lang['password_change_successful']          = 'Kata Sandi Berhasil Diubah';
$lang['password_change_unsuccessful']        = 'Tidak Dapat Mengubah Kata Sandi';
$lang['forgot_password_successful']          = 'Email Reset Kata Sandi Terkirim';
$lang['forgot_password_unsuccessful']        = 'Tidak Dapat Mengirim Email Tautan Reset Kata Sandi';

// Aktivasi
$lang['activate_successful']                 = 'Akun Diaktifkan';
$lang['activate_unsuccessful']               = 'Tidak Dapat Mengaktifkan Akun';
$lang['deactivate_successful']               = 'Akun Dinonaktifkan';
$lang['deactivate_unsuccessful']             = 'Tidak Dapat Menonaktifkan Akun';
$lang['activation_email_successful']         = 'Email Aktivasi Terkirim. Silakan cek inbox atau spam';
$lang['activation_email_unsuccessful']       = 'Tidak Dapat Mengirim Email Aktivasi';
$lang['deactivate_current_user_unsuccessful']= 'Anda tidak dapat Menonaktifkan diri sendiri.';

// Masuk / Keluar
$lang['login_successful']                    = 'Berhasil Masuk';
$lang['login_unsuccessful']                  = 'Login Salah';
$lang['login_unsuccessful_not_active']       = 'Akun tidak aktif';
$lang['login_timeout']                       = 'Sementara Terkunci. Coba lagi nanti.';
$lang['logout_successful']                   = 'Berhasil Keluar';

// Perubahan Akun
$lang['update_successful']                   = 'Informasi Akun Berhasil Diperbarui';
$lang['update_unsuccessful']                 = 'Tidak Dapat Memperbarui Informasi Akun';
$lang['delete_successful']                   = 'Pengguna Dihapus';
$lang['delete_unsuccessful']                 = 'Tidak Dapat Menghapus Pengguna';

// Grup
$lang['group_creation_successful']           = 'Grup Berhasil Dibuat';
$lang['group_already_exists']                = 'Nama Grup Sudah Ada';
$lang['group_update_successful']             = 'Detail Grup Diperbarui';
$lang['group_delete_successful']             = 'Grup Dihapus';
$lang['group_delete_unsuccessful']           = 'Tidak Dapat Menghapus Grup';
$lang['group_delete_notallowed']             = 'Tidak dapat menghapus grup administrator';
$lang['group_name_required']                 = 'Nama Grup adalah bidang yang diperlukan';
$lang['group_name_admin_not_alter']          = 'Nama grup admin tidak dapat diubah';

// Email Aktivasi
$lang['email_activation_subject']            = 'Aktivasi Akun';
$lang['email_activate_heading']              = 'Aktifkan akun untuk %s';
$lang['email_activate_subheading']           = 'Silakan klik tautan ini untuk %s.';
$lang['email_activate_link']                 = 'Aktifkan Akun Anda';

// Email Lupa Kata Sandi
$lang['email_forgotten_password_subject']    = 'Verifikasi Lupa Kata Sandi';
$lang['email_forgot_password_heading']       = 'Reset Kata Sandi untuk %s';
$lang['email_forgot_password_subheading']    = 'Silakan klik tautan ini untuk %s.';
$lang['email_forgot_password_link']          = 'Reset Kata Sandi Anda';

// Email Kata Sandi Baru
$lang['email_new_password_subject']          = 'Kata Sandi Baru';
$lang['email_new_password_heading']          = 'Kata Sandi Baru untuk %s';
$lang['email_new_password_subheading']       = 'Kata sandi Anda telah direset ke: %s';