<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class m_unitkerja extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function get_unitkerja($node)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT * FROM strukturnew.vwunitkerja
			WHERE (LENGTH(id) = LENGTH(?) + 3 OR LENGTH(id) = LENGTH(?) + 2) AND id LIKE ? || '%'
		", array($node, $node, $node));
		$this->db->close();
		return $q;
	}

	function tambah($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("SELECT public.addsatker(?,?)", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function ubah($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("UPDATE satker SET satker = ? WHERE satkerid = ?", array($params['v_text'], $params['v_node']));
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function hapus($satkerid)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("DELETE FROM satker WHERE satkerid = ?", array($satkerid));
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
}
