<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mpdf extends MY_Controller
{

	public function index()
	{
		$data = $this->load->view('backup/mpdf_v');
	}

	public function printPDF()
	{
		$mpdf = new \Mpdf\Mpdf();
		$data['title'] = "Coba";
		$html = $this->load->view('backup/hasilPrint', $data, TRUE);
		$mpdf->WriteHTML($html);
		$mpdf->Output();
	}
}
