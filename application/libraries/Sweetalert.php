<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sweetalert
{
  protected $CI;

  public function __construct()
  {
    $this->CI = &get_instance();
    $this->CI->load->library('session');
  }

  public function setSwalAlert($judul, $isi, $tipe)
  {
    $this->CI->session->set_flashdata('Swal', [
      'judul' => $judul,
      'isi' => $isi,
      'tipe' => $tipe
    ]);
  }

  public function SwalAlert()
  {
    $swalData = $this->CI->session->flashdata('Swal');
    if ($swalData) {
      $tipe = $swalData['tipe'];
      $judul = $swalData['judul'];
      $isi = $swalData['isi'];

      echo "<script>
                  if(true)
                  {
                    Swal.fire({
                      type: '" . $tipe . "',
                      title: '" . $judul . "',
                      text: '" . $isi . "'
                    })
                  }
                </script>";
    }
  }

  public function setToastNew($tipe, $pesan, $judul)
  {
    $this->CI->session->set_tempdata('SwalToast', [
      'tipe' => $tipe,
      'pesan' => $pesan,
      'judul' => $judul
    ], 5);
  }

  public function SwalToastNew()
  {
    $toastData = $this->CI->session->tempdata('SwalToast');
    if ($toastData) {
      $tipe = $toastData['tipe'];
      $pesan = $toastData['pesan'];
      $judul = $toastData['judul'];

      if (is_array($pesan)) {
        foreach ($pesan as $pes) {
          echo "toastr['" . $tipe . "']('" . $pes . "','" . $judul . "');";
        }
      } else {
        echo "toastr['" . $tipe . "']('" . $pesan . "','" . $judul . "');";
      }
    }
  }
}