<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class m_reportcuti extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function reportListStatusPegawai($params)
	{
		$this->load->database();

		$cond_where = '';
		if (!empty($params['v_satkerid'])) {
			if (!empty($params['v_nama'])) {
				$cond_where = " WHERE UPPER(nama) LIKE '%' || UPPER('" . $params['v_nama'] . "') || '%' AND satkerid LIKE '" . $params['v_satkerid'] . "' || '%'";
			} else {
				$cond_where = " WHERE satkerid LIKE '" . $params['v_satkerid'] . "' || '%'";
			}
		} else {
			if (!empty($params['v_nama'])) {
				$cond_where = " WHERE UPPER(nama) LIKE '%' || UPPER('" . $params['v_nama'] . "') || '%'";
			}
		}

		$query = "
		SELECT * FROM reporthris.vwreportsisacuti
		" . $cond_where
			. " ORDER BY idnew
	      ";

		$q = $this->db->query("
			SELECT a.*
			FROM (" . $query . ") a
			OFFSET " . $params['v_start'] . " LIMIT " . $params['v_limit'] . "
		", $params);

		$q2 = $this->db->query("
			SELECT COUNT(*) as jml
			FROM (" . $query . ") a
		");

		$this->db->close();
		$result = array('success' => true, 'count' => $q2->first_row()->jml, 'data' => $q->result_array());
		return $result;
	}

	function getReportListBatalCutiBersama($params)
	{
		$this->load->database();

		$cond_where = '';
		if (!empty($params['v_satkerid'])) {
			if (!empty($params['v_date'])) {
				$cond_where = " WHERE satkerid LIKE '" . $params['v_satkerid'] . "' || '%' AND tglmulai = '" . $params['v_date'] . "' ORDER BY tglmulai ASC";
			} else {
				$cond_where = " WHERE satkerid LIKE '" . $params['v_satkerid'] . "' || '%' ORDER BY tglmulai ASC";
			}
		} else {
			if (!empty($params['v_date'])) {
				$cond_where = " WHERE tglmulai = '" . $params['v_date'] . "' ORDER BY tglmulai ASC";
			} else {
				$cond_where = " ORDER BY b.tglmulai ASC";
			}
		}

		$query = "
		SELECT * FROM reporthris.vwreportbatalcutber
		" . $cond_where;

		$q = $this->db->query("
			SELECT a.*
			FROM (" . $query . ") a
			OFFSET " . $params['v_start'] . " LIMIT " . $params['v_limit'] . "
		", $params);

		$q2 = $this->db->query("
			SELECT COUNT(*) as jml
			FROM (" . $query . ") a
		");

		$this->db->close();
		$result = array('success' => true, 'count' => $q2->first_row()->jml, 'data' => $q->result_array());
		return $result;
	}

	function getListCutiPegawai($params)
	{
		$this->load->database();

		$cond_where = '';

		if (!empty($params['v_satkerid'])) {
			$cond_where = "AND satkerid LIKE '" . $params['v_satkerid'] . "%' ORDER BY tglmulai DESC";
		} else {
			$cond_where = "ORDER BY tglmulai DESC";
		}

		$query = "
			SELECT * FROM reporthris.vwreportcuti1
			WHERE (( to_date(tglmulai,'DD/MM/YYYY') BETWEEN TO_DATE('" . $params['v_mulai'] . "','DD/MM/YYYY') AND TO_DATE('" . $params['v_selesai'] . "','DD/MM/YYYY') ) OR
			( to_date(tglselesai,'DD/MM/YYYY') BETWEEN TO_DATE('" . $params['v_selesai'] . "','DD/MM/YYYY') AND TO_DATE('" . $params['v_selesai'] . "','DD/MM/YYYY') ))
		" . $cond_where;

		$q = $this->db->query("
			SELECT a.*
			FROM (" . $query . ") a
			OFFSET " . $params['v_start'] . " LIMIT " . $params['v_limit'] . "
		", $params);

		$q2 = $this->db->query("
			SELECT COUNT(*) as jml
			FROM (" . $query . ") a
		");

		$this->db->close();
		$result = array('success' => true, 'count' => $q2->first_row()->jml, 'data' => $q->result_array());
		return $result;
	}

	function getreporthistorycuti($params)
	{
		$this->load->database();

		$cond_where = '';

		$query = "
			SELECT * FROM reporthris.vwreporthistorycuti
			WHERE pegawaiid = '" . $params['v_pegawaiid'] . "'
		" . $cond_where;

		$q = $this->db->query("
			SELECT a.*
			FROM (" . $query . ") a
			ORDER BY to_date(a.tglmulai, 'dd/mm/yyyy') DESC
			OFFSET " . $params['v_start'] . " LIMIT " . $params['v_limit'] . "

		", $params);

		$q2 = $this->db->query("
			SELECT COUNT(*) as jml
			FROM (" . $query . ") a
		");

		$this->db->close();
		$result = array('success' => true, 'count' => $q2->first_row()->jml, 'data' => $q->result_array());
		return $result;
	}
}
