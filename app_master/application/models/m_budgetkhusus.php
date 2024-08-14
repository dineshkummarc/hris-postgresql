<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class m_budgetkhusus extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function getBudget($years)
	{
		$this->load->database();
		$q = $this->db->query("SELECT codeid,id,nama,coa,avail,jan,feb,mar,apr,mei,jun,jul,agu,sep,okt,nov,des,periode
		FROM pjdinas.kodesapbudget_khusus WHERE periode = '" . $years . "' ORDER BY codeid ASC");
		$this->db->close();
		return $q->result_array();
	}
	function getSatker($years)
	{
		$this->load->database();
		$q = $this->db->query("select a.nama, substr(a.nama, 16) as satkerbudget, a.satker as satkerid from pjdinas.kodesapbudget a where a.nama LIKE '%Accomodation - %' GROUP BY a.id, a.nama, a.satker");
		$this->db->close();
		return $q->result_array();
	}
	function tambah($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
		INSERT INTO pjdinas.kodesapbudget_khusus(codeid,id,nama,satker,periode,jan,feb,mar,apr,mei,jun,jul,agu,sep,okt,nov,des)
		SELECT COALESCE(MAX(codeid)+1,1),
		'" . $params['id'] . "',
		CONCAT('" . $params['jenisbudget'] . "', ' - ', '" . $params['nama'] . "'),
		'" . $params['satkerid'] . "',
		'" . $params['periode'] . "',
		'" . $params['jan'] . "',
		'" . $params['feb'] . "',
		'" . $params['mar'] . "',
		'" . $params['apr'] . "',
		'" . $params['mei'] . "',
		'" . $params['jun'] . "',
		'" . $params['jul'] . "',
		'" . $params['agu'] . "',
		'" . $params['sep'] . "',
		'" . $params['okt'] . "',
		'" . $params['nov'] . "',
		'" . $params['des'] . "'
		FROM pjdinas.kodesapbudget_khusus
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
	function ubah($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			UPDATE pjdinas.kodesapbudget SET id=?,nama=?,periode=?,jan=?,
			feb=?,mar=?,apr=?,mei=?,jun=?,jul=?,agu=?,sep=?,
			okt=?,nov=?,des=? WHERE id = '" . $params['id'] . "'
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
	function hapus($id)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("DELETE FROM pjdinas.kodesapbudget_khusus WHERE id = ?", array($id));
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
}
