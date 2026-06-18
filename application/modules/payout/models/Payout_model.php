<?php
class Payout_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get_noref($like, $id_majors)
    {

        $query = $this->db->query("SELECT MAX(RIGHT(kas_noref,2)) AS no_max FROM kas WHERE DATE(kas_input_date)=CURDATE() AND kas_majors_id = '$id_majors' AND kas_noref LIKE '$like%' AND kas_category = '1'")->row();

        if (count((array)$query) > 0) {
            $tmp = ((int)$query->no_max) + 1;
            $noref = sprintf("%02s", $tmp);
        } else {
            $noref = "01";
        }

        return date('dmy') . $noref;
    }

    function get_noref_migrasi($like, $id_majors, $tgl)
    {

        $query = $this->db->query("SELECT MAX(RIGHT(kas_noref,2)) AS no_max FROM kas WHERE DATE(kas_input_date)='$tgl' AND kas_majors_id = '$id_majors' AND kas_noref LIKE '$like%' AND kas_category = '1'")->row();

        if (count((array)$query) > 0) {
            $tmp = ((int)$query->no_max) + 1;
            $noref = sprintf("%02s", $tmp);
        } else {
            $noref = "01";
        }

        $tanggal = date('dmy', strtotime($tgl));

        return $tanggal . $noref;
    }

    function get_item_pembayaran($noref_trx)
    {
        $query = "SELECT keterangan, penerimaan FROM v_laporan_jurnal WHERE noref = ?";
        $itemBayar = $this->db->query($query, array($noref_trx))->result_array();
        return $itemBayar;
    }

    function cari_noref($params = array())
    {

        if (isset($params['student_id'])) {
            $student_id = $params['student_id'];
        }

        if (isset($params['date'])) {
            $date = $params['date'];
        }

        $res = $this->db->query("SELECT DISTINCT `kas_noref` FROM `kas` LEFT JOIN `bulan` ON `bulan`.`bulan_noref` = `kas`.`kas_noref` 
        LEFT JOIN `bebas_pay` ON `bebas_pay`.`bebas_pay_noref` = `kas`.`kas_noref` 
        LEFT JOIN `bebas` ON `bebas`.`bebas_id` = `bebas_pay`.`bebas_bebas_id` 
        WHERE (`kas_date` = '$date' AND `bebas`.`student_student_id` = '$student_id') 
        OR (`kas_date` = '$date' AND `bulan`.`student_student_id` = '$student_id') ORDER BY kas_noref ASC");

        return $res->result_array();
    }

    function get_bcode($kas = array())
    {

        if (isset($kas['date'])) {
            $this->db->where('kas_date', $kas['date']);
        }

        if (isset($kas['noref'])) {
            $this->db->where('kas_noref', $kas['noref']);
        }

        $this->db->select('kas_noref, kas_date, account_code, account_description');

        $this->db->join('account', 'account.account_id = kas.kas_account_id', 'left');

        $res = $this->db->get('kas');

        return $res->row_array();
    }

    function list_transaksi_tertunda($sudent_nis, $norefToday)
    {
        $this->db->select('*');
        $this->db->from('v_transaksi_tertunda');
        $this->db->where('nis', $sudent_nis);

        if (!empty($norefToday)) {
            $this->db->where('noref !=', $norefToday);
        }

        $res = $this->db->get();

        return $res->result_array();
    }

    function get_kas($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('kas.kas_id', $params['id']);
        }
        if (isset($params['noref'])) {
            $this->db->where('kas.kas_noref', $params['noref']);
        }

        if (isset($params['period_id'])) {
            $this->db->where('kas.kas_period', $params['period_id']);
        }

        if (isset($params['date'])) {
            $this->db->where('kas.kas_date', $params['date']);
        }

        $this->db->where('majors_status', '1');

        if (isset($params['limit'])) {
            if (!isset($params['offset'])) {
                $params['offset'] = NULL;
            }

            $this->db->limit($params['limit'], $params['offset']);
        }

        if (isset($params['order_by'])) {
            $this->db->order_by($params['order_by'], 'desc');
        } else {
            $this->db->order_by('kas_id', 'desc');
        }

        $this->db->select('kas.kas_id, kas.kas_noref, kas.kas_period, kas.kas_date, kas.kas_month_id, kas.kas_account_id, kas.kas_majors_id, kas.kas_debit, kas.kas_kredit, kas.kas_category');
        $this->db->select('month.month_name');
        $this->db->select(', account.account_code, account.account_description');
        $this->db->select('majors.majors_name, majors.majors_short_name');

        $this->db->join('period', 'period.period_id = kas.kas_period', 'left');
        $this->db->join('month', 'month.month_id = kas.kas_month_id', 'left');
        $this->db->join('account', 'account.account_id = kas.kas_account_id', 'left');
        $this->db->join('majors', 'majors.majors_id = kas.kas_majors_id', 'left');

        $res = $this->db->get('kas');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }
}
