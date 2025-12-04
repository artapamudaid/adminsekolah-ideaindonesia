<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Neraca_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get_aktiva($end = null, $majors_id = null)
    {
        // Default end = akhir tahun berjalan
        if (empty($end)) {
            $end = date('Y') . '-12-31';
        }

        // Filter majors
        if ($majors_id != "all") {
            $whereMajors = "AND a.account_majors_id = '$majors_id' AND a.account_category != 0";
        } else {
            $whereMajors = "AND a.account_category != 0";
        }

        $query = $this->db->query("
            SELECT 
                a.account_code, 
                a.account_description,
                COALESCE(d.saldo_awal_nominal, 0) +
                COALESCE(SUM(CASE 
                    WHEN c.tanggal <= '$end' THEN COALESCE(c.penerimaan, 0) ELSE 0 
                END), 0) -
                COALESCE(SUM(CASE 
                    WHEN c.tanggal <= '$end' THEN COALESCE(c.pengeluaran, 0) ELSE 0 
                END), 0) AS saldo
            FROM account a 
            LEFT JOIN v_laporan_jurnal c 
                ON c.kodeKas = a.account_code AND (c.tanggal <= '$end' OR c.tanggal IS NULL)
            LEFT JOIN saldo_awal d 
                ON d.saldo_awal_account = a.account_id 
            WHERE a.account_code LIKE '1%' 
            $whereMajors
            GROUP BY a.account_code, a.account_description, d.saldo_awal_nominal
        ");

        return $query->result_array();
    }

    function get_pasiva($end = null, $majors_id = null)
    {
        if (empty($end)) {
            $end = date('Y') . '-12-31';
        }

        if ($majors_id != "all") {
            $whereMajors = "AND a.account_majors_id = '$majors_id' AND a.account_category != 0";
        } else {
            $whereMajors = "AND a.account_category != 0";
        }

        $query = $this->db->query("
            SELECT 
                a.account_code, 
                a.account_description,
                COALESCE(d.saldo_awal_nominal, 0) +
                COALESCE(SUM(CASE 
                    WHEN c.tanggal <= '$end' THEN COALESCE(c.penerimaan, 0) ELSE 0 
                END), 0) -
                COALESCE(SUM(CASE 
                    WHEN c.tanggal <= '$end' THEN COALESCE(c.pengeluaran, 0) ELSE 0 
                END), 0) AS saldo
            FROM account a 
            LEFT JOIN v_laporan_jurnal c 
                ON c.akunBayar = a.account_code AND (c.tanggal <= '$end' OR c.tanggal IS NULL)
            LEFT JOIN saldo_awal d 
                ON d.saldo_awal_account = a.account_id 
            WHERE a.account_code LIKE '2%' 
            $whereMajors
            GROUP BY a.account_code, a.account_description, d.saldo_awal_nominal
        ");

        return $query->result_array();
    }

    function get_modal($end = null, $majors_id = null)
    {
        if (empty($end)) {
            $end = date('Y') . '-12-31';
        }

        if ($majors_id != "all") {
            $whereMajors = "AND a.account_majors_id = '$majors_id' AND a.account_category != 0";
        } else {
            $whereMajors = "AND a.account_category != 0";
        }

        $whereTanggal = "AND (c.tanggal <= '$end' OR c.tanggal IS NULL)";

        $saldo = "
            COALESCE(d.saldo_awal_nominal, 0) +
            COALESCE(SUM(CASE WHEN c.tanggal <= '$end' THEN COALESCE(b.debet, 0) ELSE 0 END), 0) -
            COALESCE(SUM(CASE WHEN c.tanggal <= '$end' THEN COALESCE(b.kredit, 0) ELSE 0 END), 0) AS saldo
        ";

        $query = $this->db->query("
            SELECT a.account_code, a.account_description, $saldo
            FROM account a 
            LEFT JOIN jurnal_umum_detail b ON b.account_code = a.account_code 
            LEFT JOIN jurnal_umum c ON c.id = b.id_jurnal 
            $whereTanggal
            LEFT JOIN saldo_awal d ON d.saldo_awal_account = a.account_id 
            WHERE a.account_code LIKE '3%'
            $whereMajors
            GROUP BY a.account_code, a.account_description, d.saldo_awal_nominal
        ");

        return $query->result_array();
    }

    function get_laba_rugi($end = null, $majors_id = null)
    {
        if (empty($end)) {
            $end = date('Y') . '-12-31';
        }

        $whereMajors = "";
        if ($majors_id != "all") {
            $whereMajors = "AND c.idMajors = '$majors_id'";
        }

        $query = $this->db->query("
            SELECT 
                SUM(CASE 
                    WHEN c.akunBayar LIKE '4%' THEN COALESCE(c.penerimaan, 0)
                    ELSE 0 
                END) -
                SUM(CASE 
                    WHEN c.akunBayar LIKE '5%' THEN COALESCE(c.pengeluaran, 0)
                    ELSE 0 
                END) AS nominal
            FROM v_laporan_jurnal c
            WHERE c.tanggal <= '$end'
            AND (c.akunBayar LIKE '4%' OR c.akunBayar LIKE '5%')
            $whereMajors
        ");

        return $query->row_array();
    }

    function get_aktiva_unit($period_id, $majors_id, $start, $end)
    {
        $data = $this->db->query("SELECT account_code, account_description, (saldo_awal_debit + SUM(kas_debit) - SUM(kas_kredit)) as saldo FROM account JOIN saldo_awal ON saldo_awal.saldo_awal_account = account.account_id LEFT JOIN kas ON account.account_id = kas.kas_account_id LEFT JOIN month ON kas.kas_month_id = month.month_id WHERE account.account_majors_id = '$majors_id' AND account_note IN (SELECT account_id FROM account WHERE account_category = '0' AND account_note != '0' AND account_majors_id = '$majors_id' AND account_code LIKE '1%' AND account_description LIKE '%Aktiva%') AND kas_period = '$period_id' AND month.month_id BETWEEN '$start' AND '$end' GROUP BY kas_account_id ORDER BY account_majors_id ASC, account_code ASC")->result_array();

        return $data;
    }

    function get_aktiva_all($period_id, $start, $end)
    {
        $data = $this->db->query("SELECT account_code, account_description, (saldo_awal_debit + SUM(kas_debit) - SUM(kas_kredit)) as saldo FROM account JOIN saldo_awal ON saldo_awal.saldo_awal_account = account.account_id LEFT JOIN kas ON account.account_id = kas.kas_account_id LEFT JOIN month ON kas.kas_month_id = month.month_id WHERE account_note IN (SELECT account_id FROM account WHERE account_category = '0' AND account_note != '0' AND account_code LIKE '1%' AND account_description LIKE '%Aktiva%') AND kas_period = '$period_id' AND month.month_id BETWEEN '$start' AND '$end' GROUP BY kas_account_id ORDER BY account_majors_id ASC, account_code ASC")->result_array();

        return $data;
    }

    function get_piutang_unit($period_id, $majors_id, $start, $end, $start_id, $end_id)
    {
        $data = $this->db->query("SELECT account_id, account_code, account_description, sum(total) as total FROM (
        SELECT
        account.account_id,
        account.account_code, account.account_description,
        SUM(bulan.bulan_bill) AS total
        FROM account
        LEFT JOIN pos ON account.account_id = pos.piutang_account_id
        LEFT JOIN payment ON pos.pos_id = payment.pos_pos_id
        LEFT JOIN bulan ON payment.payment_id = bulan.payment_payment_id 
        WHERE account.account_majors_id = '$majors_id' 
        AND payment.period_period_id = '$period_id'
        AND bulan.month_month_id BETWEEN '$start' AND '$end'
        AND bulan.bulan_status= '0'
        GROUP BY account_id
        UNION
        SELECT
        account.account_id,
        account.account_code, account.account_description,
        (SUM(bebas.bebas_bill)-SUM(bebas.bebas_total_pay)) AS total
        FROM account
        LEFT JOIN pos ON account.account_id = pos.piutang_account_id
        LEFT JOIN payment ON pos.pos_id = payment.pos_pos_id
        LEFT JOIN bebas ON payment.payment_id = bebas.payment_payment_id
        WHERE account.account_majors_id = '$majors_id' 
        AND payment.period_period_id = '$period_id'
        AND bebas.bebas_last_update >= '$start_id' AND bebas.bebas_last_update < '$end_id'
        GROUP BY account_id        
        ) account
        GROUP BY account_id")->result_array();

        return $data;
    }

    function get_piutang_all($period_id, $start, $end, $start_id, $end_id)
    {
        $data = $this->db->query("SELECT account_id, account_code, account_description, sum(total) as total FROM (
        SELECT
        account.account_id,
        account.account_code, account.account_description,
        SUM(bulan.bulan_bill) AS total
        FROM account
        LEFT JOIN pos ON account.account_id = pos.piutang_account_id
        LEFT JOIN payment ON pos.pos_id = payment.pos_pos_id
        LEFT JOIN bulan ON payment.payment_id = bulan.payment_payment_id 
        WHERE payment.period_period_id = '$period_id'
        AND bulan.month_month_id BETWEEN '$start' AND '$end'
        AND bulan.bulan_status= '0'
        GROUP BY account_id
        UNION
        SELECT
        account.account_id,
        account.account_code, account.account_description,
        (SUM(bebas.bebas_bill)-SUM(bebas.bebas_total_pay)) AS total
        FROM account
        LEFT JOIN pos ON account.account_id = pos.piutang_account_id
        LEFT JOIN payment ON pos.pos_id = payment.pos_pos_id
        LEFT JOIN bebas ON payment.payment_id = bebas.payment_payment_id
        WHERE payment.period_period_id = '$period_id'
        AND bebas.bebas_last_update >= '$start_id' AND bebas.bebas_last_update < '$end_id'
        GROUP BY account_id        
        ) account
        GROUP BY account_id")->result_array();

        return $data;
    }
}
