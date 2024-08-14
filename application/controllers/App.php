<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class app extends Web_Controller
{
	function App()
	{
		parent::Web_Controller();
	}
	public function index()
	{
		if ($this->session->userdata('log_in') != 1) {
			$this->view_login();
		} else {
			header("Location: " . base_url() . 'portal.php');
		}
	}
	private function view_login()
	{
		$content = "v_login";
		$data = array();
		$this->load->view($content, $data);
	}
}
