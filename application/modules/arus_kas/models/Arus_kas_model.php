<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Arus_kas_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function get_sum($majors)
    {
        $unit = "";
        if ($majors != 'all') {
            $unit = " AND c.account_majors_id = '$majors'";
        }

        $account_code_like = '1-%';
        $account_code_like2 = '2-%';

        $this->db->where("(c.account_code LIKE '$account_code_like' OR c.account_code LIKE '$account_code_like2') $unit");
        $this->db->select("SUM(jurnal_umum_detail.kredit) AS total_kredit,  SUM(jurnal_umum_detail.debet) AS total_debet");
        $this->db->join('account c', 'jurnal_umum_detail.account_code = c.account_code');

        return $this->db->get("jurnal_umum_detail")->row_array();
    }

    public function get_debet($majors)
    {
        $unit = "";
        if ($majors != 'all') {
            $unit = " AND jurnal_umum.sekolah_id = '$majors'";
        }

        return $this->db->query("SELECT jurnal_umum_detail.account_code,
        account.account_description,
        SUM(kredit) as nominal
        FROM `jurnal_umum_detail`
        JOIN jurnal_umum ON jurnal_umum.id = jurnal_umum_detail.id_jurnal
        JOIN account ON account.account_code = jurnal_umum_detail.account_code
        WHERE 1 $unit 
        AND jurnal_umum_detail.kredit != 0
        AND (jurnal_umum_detail.account_code LIKE '4-%'
        OR jurnal_umum_detail.account_code LIKE '6-%')
        GROUP BY jurnal_umum_detail.account_code
        ORDER BY jurnal_umum_detail.account_code")->result_array();
    }

    public function get_kredit($majors)
    {
        $unit = "";
        if ($majors != 'all') {
            $unit = " AND jurnal_umum.sekolah_id = '$majors'";
        }

        return $this->db->query("SELECT jurnal_umum_detail.account_code,
        account.account_description,
        SUM(debet) as nominal
        FROM `jurnal_umum_detail`
        JOIN jurnal_umum ON jurnal_umum.id = jurnal_umum_detail.id_jurnal
        JOIN account ON account.account_code = jurnal_umum_detail.account_code
        WHERE 1 $unit 
        AND jurnal_umum_detail.debet != 0
        AND (jurnal_umum_detail.account_code LIKE '5-%'
        OR jurnal_umum_detail.account_code LIKE '7-%')
        GROUP BY jurnal_umum_detail.account_code
        ORDER BY jurnal_umum_detail.account_code")->result_array();
    }
}
