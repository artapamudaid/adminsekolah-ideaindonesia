<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Laba_rugi_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get_datatable($aksi = '')
    {
        $get = $this->input->get();
        if ($aksi == 'selectdata') {
            $this->_get_query();
            if ($get['length'] != -1)
                $this->db->limit($get['length'], $get['start']);
            $query = $this->db->get();
            return $query->result();
        } elseif ($aksi == 'countfilter') {
            $this->_get_query();
            $query = $this->db->get();
            return $query->num_rows();
        } elseif ($aksi == 'countall') {
            $this->db->from('jurnal_umum_detail');
            if ($this->session->userdata('umajorsid') > 0) {
                $this->db->join('jurnal_umum b', 'a.id_jurnal = b.id');
                $this->db->where('b.sekolah_id', $this->session->userdata('umajorsid'));
            }
            $this->db->like('account_code', '4-', 'after');
            $this->db->or_like('account_code', '5-', 'after');
            $this->db->or_like('account_code', '6-', 'after');
            $this->db->or_like('account_code', '7-', 'after');
            return $this->db->count_all_results();
        } else {
            $this->_get_query();
            $query = $this->db->get();
            return $query->result();
        }
    }

    private function _get_query()
    {
        $get = $this->input->get();

        $param_order    = array('a.account_code', 'c.account_description', 'a.debet', 'a.kredit');
        $param_search   = array('a.account_code', 'c.account_description', 'a.debet', 'a.kredit');
        $orderby        = 'a.account_code';
        $orderby_value  = 'ASC';
        $i              = 0;

        $today          = date('Y-m-d');
        $yesterday      = date('Y-m-d', strtotime("yesterday"));
        $global_filter  = 0;

        $this->db->select("b.id, b.keterangan, a.account_code, c.account_description, SUM(a.kredit) AS kredit,  SUM(a.debet) AS debet");
        $this->db->from("jurnal_umum_detail a");
        $this->db->join('jurnal_umum b', 'a.id_jurnal = b.id');
        $this->db->join('account c', 'a.account_code = c.account_code');

        $this->db->like('c.account_code', '4-', 'after');
        $this->db->or_like('c.account_code', '5-', 'after');
        $this->db->or_like('c.account_code', '6-', 'after');
        $this->db->or_like('c.account_code', '7-', 'after');

        $this->db->group_by('c.account_code');

        foreach ($param_search as $item) {
            if ($get['search']['value'] != '') {
                $global_filter = 1;
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $get['search']['value']);
                } else {
                    $this->db->or_like($item, $get['search']['value']);
                }
                if (count($param_search) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }

        if ($get['columns'][1]['search']['value'] > 0) {
            $this->db->where('b.sekolah_id', $get['columns'][1]['search']['value']);
        }

        if ($this->session->userdata('umajorsid') > 0) {
            $this->db->where('b.sekolah_id', $this->session->userdata('umajorsid'));
        }

        if (isset($get['order'])) {
            $this->db->order_by($param_order[$get['order']['0']['column']], $get['order']['0']['dir']);
        } else if ($orderby != '') {
            $this->db->order_by($orderby, $orderby_value);
        }
    }

    public function _jurnal($idd)
    {
        $this->db->select("*");
        $this->db->from("jurnal_umum");
        $this->db->where('id', $idd);
        return $this->db->get();
    }

    public function _jurnal_detail($idd)
    {
        $this->db->select("a.*, b.account_description");
        $this->db->from("jurnal_umum_detail a");
        $this->db->join('account b', 'a.account_code = b.account_code');
        $this->db->where('a.id_jurnal', $idd);
        $this->db->order_by('a.id', "ASC");
        return $this->db->get()->result();
    }

    function get_data_simpanan($kategori)
    {

        if (isset($_REQUEST['tgl_dari']) && isset($_REQUEST['tgl_samp'])) {
            $tgl_dari = $_REQUEST['tgl_dari'];
            $tgl_samp = $_REQUEST['tgl_samp'];
        } else {
            $tgl_dari = date('Y') . '-01-01';
            $tgl_samp = date('Y') . '-12-31';
        }

        if (!empty($kategori) && $kategori == 'PENDAPATAN') {
            $this->db->like('kd_aktiva', '4-', 'after');
            $this->db->or_like('kd_aktiva', '6-', 'after');
        }

        if (!empty($kategori) && $kategori == 'BIAYA') {
            $this->db->or_like('kd_aktiva', '5-', 'after');
            $this->db->or_like('kd_aktiva', '7-', 'after');
        }

        $this->db->select('*');
        $this->db->from('v_laba_rugi');
        $this->db->where('DATE(tanggal) >= ', '' . $tgl_dari . '');
        $this->db->where('DATE(tanggal) <= ', '' . $tgl_samp . '');

        $majors_id = NULL;
        if ($majors_id != "") {
            $majors_id = $_REQUEST['majors_id'];
            $this->db->where('majors_id', $majors_id);
        }

        $this->db->order_by('tanggal', 'ASC');
        $this->db->group_by('kd_aktiva');
        // $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $out = $query->result_array();
            return $out;
        } else {
            return array();
        }
    }
}
