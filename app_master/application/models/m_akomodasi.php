<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class m_akomodasi extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function getmasterlokasi($node)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT lokasiid as id, lokasi as text FROM lokasi
		", array($node));
		$this->db->close();
		return $q;
	}
	function getKursDollar()
	{
		$this->load->database();
		$q = $this->db->query("SELECT a.id, a.dollar FROM pjdinas.kursdollar a");
		$this->db->close();
		return $q->result_array();
	}
	function getAkomodasi()
	{
		$this->load->database();
		$q = $this->db->query("SELECT a.id,b.level,a.hotelprice,a.uangsaku,a.uangmakan,a.transport FROM pjdinas.rincianbiaya a LEFT JOIN level b ON a.levelid = b.levelid ORDER BY a.gol ASC");
		$this->db->close();
		return $q->result_array();
	}
	function getAkomodasiLn()
	{
		$this->load->database();
		$q = $this->db->query("
		select a.id, c.jenisperjalanan, b.level, a.southeastasia, a.non_southeastasia, a.japan, a.eropa_usa_dubai
		from pjdinas.rincianbiayaluarnegeri a
		LEFT JOIN level b ON a.levelid = b.levelid
		LEFT JOIN pjdinas.jenisperjalanan c on a.jenisperjalananid = c.id
		ORDER BY a.jenisperjalananid, a.gol ASC
		");
		$this->db->close();
		return $q->result_array();
	}
	function tambah($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			INSERT INTO lokasi(lokasiid, lokasi, kodelokasi)
			SELECT COALESCE(MAX(lokasiid)+1,1),?,?
			FROM lokasi
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
	function tambahln($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			INSERT INTO lokasi(lokasiid, lokasi, kodelokasi)
			SELECT COALESCE(MAX(lokasiid)+1,1),?,?
			FROM lokasi
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
			UPDATE pjdinas.rincianbiaya SET hotelprice = ?, uangsaku = ? ,uangmakan = ?, transport = ? WHERE id = ?
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
	function ubahln($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			UPDATE pjdinas.rincianbiayaluarnegeri SET southeastasia = ?, non_southeastasia = ? ,japan = ?, eropa_usa_dubai = ? WHERE id = ?
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
	function ubahkursdollar($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query(" UPDATE pjdinas.kursdollar SET dollar = ? WHERE id = ?
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
	function hapus($id)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("DELETE FROM pjdinas.rincianbiaya WHERE id = ?", array($id));
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
	function hapusln($id)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("DELETE FROM pjdinas.rincianbiayaluarnegeri WHERE id = ?", array($id));
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
}
