<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Jurnal_umum_model extends CI_Model
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
        } elseif ($aksi == 'alldata') {
            $this->_get_query();
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
        $param_order = array('b.tanggal', 'a.account_code', 'c.account_description', 'b.keterangan', 'a.debet', 'a.kredit', 'd.majors_short_name');
        $param_search = array('b.tanggal', 'a.account_code', 'c.account_description', 'b.keterangan', 'a.debet', 'a.kredit', 'd.majors_short_name');
        $orderby = 'b.tanggal,a.id';
        $orderby_value = 'ASC';
        $i = 0;
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime("yesterday"));
        $global_filter = 0;
        $this->db->select("b.id, b.keterangan, b.noref, b.tanggal, b.pencatatan, a.account_code, c.account_description,  a.debet, a.kredit, d.majors_short_name");
        $this->db->from("jurnal_umum_detail a");
        $this->db->join('jurnal_umum b', 'a.id_jurnal = b.id');
        $this->db->join('account c', 'a.account_code = c.account_code');
        $this->db->join('majors d', 'b.sekolah_id = d.majors_id');

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

    public function list_all_coa_jurnal($kword, $account_majors_id)
    {
        $this->db->select("account_code, account_description");
        $this->db->from("account");
        if ($kword != '') {
            $this->db->like('account_code', $kword);
            $this->db->or_like('account_description', $kword);
        }
        $this->db->where('account_majors_id', $account_majors_id);
        $this->db->order_by("account_code", "ASC");
        return $this->db->get();
    }

    function get_noref($id_majors)
    {

        $query = $this->db->query("SELECT MAX(RIGHT(noref,2)) AS no_max
                                    FROM jurnal_umum
                                    WHERE DATE(tanggal)=CURDATE()
                                    AND sekolah_id = '$id_majors'
                                    AND noref LIKE 'JU%'")->row();

        if (count((array)$query) > 0) {
            $tmp = ((int)$query->no_max) + 1;
            $noref = sprintf("%02s", $tmp);
        } else {
            $noref = "01";
        }

        return 'JU' . date('dmy') . $noref;
    }

    function simpanjurnal()
    {
        $post = $this->input->post();
        $noref = $this->get_noref($post["sekolah_id"]);

        $array = array(
            'keterangan '           => $post["keterangan"],
            'sekolah_id '           => $post["sekolah_id"],
            'noref'                 => $noref,
            'keterangan_lainnya '   => $post["keterangan_lainnya"],
            'tanggal'               => $post["tanggal"]
        );

        $this->db->trans_begin();
        $this->db->insert("jurnal_umum", $array);
        $id_jurnal = $this->db->insert_id();
        $kode_akun = $this->input->post("kode_akun");
        $debet = $this->input->post("debet");
        $kredit = $this->input->post("kredit");

        for ($i = 0; $i < count($kode_akun); $i++) {
            $saldo_kredit = _desimal($kredit[$i]);
            $saldo_debet = _desimal($debet[$i]);
            $param_detail = array(
                'id_jurnal' => $id_jurnal,
                'account_code' => $kode_akun[$i],
                'debet' => $saldo_debet,
                'kredit' => $saldo_kredit,
            );
            $this->db->insert("jurnal_umum_detail", $param_detail);
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    function hapusdata()
    {
        $post = $this->input->post();
        $this->db->trans_begin();
        $this->db->where('id_jurnal', $post['idd'])->delete("jurnal_umum_detail");
        $this->db->where('id', $post['idd'])->delete("jurnal_umum");
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
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

    function updatedata()
    {
        $post = $this->input->post();
        $this->db->trans_begin();
        $this->db->where('id_jurnal', $post['idd'])->delete("jurnal_umum_detail");
        $array = array(
            'keterangan ' =>  $post["keterangan"],
            'sekolah_id ' =>  $post["sekolah_id"],
            'keterangan_lainnya ' =>  $post["keterangan_lainnya"],
            'tanggal' => $post["tanggal"]
        );
        $this->db->where('id', $post['idd'])->update("jurnal_umum", $array);

        $id_jurnal = $post['idd'];
        $kode_akun = $this->input->post("kode_akun");
        $debet = $this->input->post("debet");
        $kredit = $this->input->post("kredit");
        for ($i = 0; $i < count($kode_akun); $i++) {
            $saldo_kredit = _desimal($kredit[$i]);
            $saldo_debet = _desimal($debet[$i]);
            $param_detail = array(
                'id_jurnal' => $id_jurnal,
                'account_code' => $kode_akun[$i],
                'debet' => $saldo_debet,
                'kredit' => $saldo_kredit,
            );
            $this->db->insert("jurnal_umum_detail", $param_detail);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }
}
