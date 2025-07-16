<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Modal_model extends CI_Model
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
}
