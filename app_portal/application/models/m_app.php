<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class m_app extends CI_Model
{
  function __construct()
  {
    parent::__construct();
  }

  public function record_count()
  { //menghitung jumlah postingan upload
    return $this->db->count_all("pegawai");
  }

  public function errcount()
  { //menghitung jumlah error upload
    return $this->db->count_all("errmsg");
  }

  public function getData($limit, $start)
  { //query report hasil upload
    $this->load->database();
    $query = $this->db->query("
			SELECT * FROM reporthris.vwreportupload
      ORDER BY pegawaiid desc 
			OFFSET ? ROWS
            FETCH NEXT ? ROWS ONLY
		", array($start, $limit));

    if ($query->num_rows() > 0) {
      foreach ($query->result() as $row) {
        $data[] = $row;
      }
      return $data;
    }
    return false;
  }

  function getListModul($userid)
  {//ambil data list modul per user
    $this->load->database();
    $q = $this->db->query("
			SELECT um.modulid, um.modul, um.moduldesc, um.iconid
			FROM userusergroup uu
			LEFT JOIN usergroup ug ON uu.usergroupid = ug.usergroupid
			LEFT JOIN usermngmodul um ON ug.modulid = um.modulid
			WHERE uu.userid = ?
			ORDER BY case when um.modulid = '10' then '1'
			when um.modulid = '2' then '2'
			else '3' end 
		", array($userid));
    $this->db->close();
    $result = array('success' => true, 'data' => $q->result_array());
    return $result;
  }

  public function errdata($limit, $start)
  { //tampilkan error message saat upload
    $this->load->database();
    $query = $this->db->query("
			SELECT field||row namakolom, msg errormessage FROM errmsg
      ORDER BY row , field
			OFFSET ? ROWS
            FETCH NEXT ? ROWS ONLY
		", array($start, $limit));

    if ($query->num_rows() > 0) {
      foreach ($query->result() as $row) {
        $errmsg[] = $row;
      }
      return $errmsg;
    }
    return false;
  }

  function update($pegawaiid)
  {//update change password
    $data = array(
      'password' => MD5($this->input->post('passconf')),
    );
    $this->db->where('userid', $pegawaiid);
    $this->db->update('users', $data);
  }

  function isOldPassword($oldpassword)
  {//cek validasi password lama
    $this->db->select('userid');
    $this->db->where('password', MD5($oldpassword));
    $query = $this->db->get('users');

    if ($query->num_rows() > 0) {
      return true;
    } else {
      return false;
    }
  }

  function addlogs()
  {//add logs report
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
