<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class logout extends CI_Controller {
	function index(){
		$this->load->model("M_login");
		$this->M_login->addlogs();
		$this->session->sess_destroy();
		redirect('login');
	}
}