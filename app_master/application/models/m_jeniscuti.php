<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class m_jeniscuti extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	function getJenisCuti(){
		$this->load->database();
		$q = $this->db->query("select jeniscutiid as id, jeniscuti as text from eservices.jeniscuti where jeniscutiid not in('6') order by id");
		return $q->result_array();
	}
	function getDetailJenisCuti($jeniscutiid){
		$this->load->database();
		$q = $this->db->query("
			SELECT detailjeniscutiid AS id, detailjeniscuti AS text, jatahcuti, satuan 
			FROM eservices.detailjeniscuti 
			WHERE jeniscutiid = ?
			ORDER BY detailjeniscutiid		
		", array($jeniscutiid));
		return $q->result_array();
	}
}