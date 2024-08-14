<?php
class M_login extends CI_Model
{
	function M_login()
	{
		parent::__construct();
	}

	function check_login($username, $password, $passing)
	{
		$this->load->database();

		if ($passing) {
			$str_password = '';
		} else {
			$str_password = "
				AND password = '" . $password . "'";
		}

		$q = $this->db->query("
			SELECT * FROM eservices.vwdatalogin WHERE username = '" . $username . "' " . $str_password);
		$data = $q->result();
		return $data;
	}

	function addlogs()
	{
		$params = array(
			'nik' => $this->session->userdata('username'),
			'url' => $_SERVER['REQUEST_URI'],
			'ipuser' => $_SERVER['REMOTE_ADDR'],
		);
		$this->load->database();
		$this->db->query("
				INSERT INTO reporthris.userlogs VALUES (?,?,NOW(),?)  ", $params);
	}
}
