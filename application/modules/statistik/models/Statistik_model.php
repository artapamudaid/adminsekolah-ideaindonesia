<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Statistik_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function count_trx($data = array())
    {
        $this->db->select('COUNT(*) AS totalBaris');
        $this->db->select('SUM(penerimaan) AS totalPenerimaan');
        $this->db->select('SUM(pengeluaran) AS totalPengeluaran');
        $this->db->from('v_laporan_jurnal');

        if (isset($data['search'])) {
            $search = $data['search'];
            $this->db->group_start();

            $this->db->like("nama", $search);
            $this->db->or_like("nis", $search);
            $this->db->or_like("kelas", $search);
            $this->db->or_like("namaKas", $search);
            $this->db->or_like("tanggal", $search);
            $this->db->or_like("akunBayar", $search);
            $this->db->or_like("keterangan", $search);

            $this->db->group_end();
        }

        if (isset($data['date_start']) and isset($data['date_end'])) {
            $this->db->where('tanggal >=', $data['date_start']);
            $this->db->where('tanggal <=', $data['date_end']);
        }

        if (isset($data['majors_id'])) {
            if ($data['majors_id'] !== 'all') {
                $this->db->where('idMajors', $data['majors_id']);
            }
        }

        if (isset($data['kas_account_id'])) {
            $this->db->where_in('idKas', $data['kas_account_id']);
        }

        $query = $this->db->get();

        // Memeriksa apakah query berhasil dan mengembalikan hasilnya
        if ($query->num_rows() > 0) {
            return $query->row_array();  // Mengembalikan hasil dalam bentuk object
        } else {
            return null;  // Jika tidak ada hasil
        }
    }

    function getSaldoAwal($data = array())
    {
        $this->db->select('(SUM(penerimaan) - SUM(pengeluaran)) AS saldoAwal');
        $this->db->from('v_laporan_jurnal');

        if (isset($data['search'])) {
            $search = $data['search'];
            $this->db->group_start();

            $this->db->like("nama", $search);
            $this->db->or_like("nis", $search);
            $this->db->or_like("kelas", $search);
            $this->db->or_like("namaKas", $search);
            $this->db->or_like("tanggal", $search);
            $this->db->or_like("akunBayar", $search);
            $this->db->or_like("keterangan", $search);

            $this->db->group_end();
        }

        if (isset($data['date_start'])) {
            $this->db->where('tanggal <', $data['date_start']);
        }

        if (isset($data['majors_id'])) {
            if ($data['majors_id'] !== 'all') {
                $this->db->where('idMajors', $data['majors_id']);
            }
        }

        if (isset($data['kas_account_id'])) {
            $this->db->where_in('idKas', $data['kas_account_id']);
        }

        $query = $this->db->get();

        // Memeriksa apakah query berhasil dan mengembalikan hasilnya
        if ($query->num_rows() > 0) {
            return $query->row_array();  // Mengembalikan hasil dalam bentuk object
        } else {
            return null;  // Jika tidak ada hasil
        }
    }

    function rekap_jurnal($limit, $start, $search, $data = array())
    {
        $this->db->select('akunBayar, jenisBayar, SUM(penerimaan) AS penerimaan, SUM(pengeluaran) AS pengeluaran');
        $this->db->from('v_laporan_jurnal');

        if (isset($search) and !empty($search)) {
            $search = $data['search'];
            $this->db->group_start();

            $this->db->like("jenisBayar", $search);

            $this->db->group_end();
        }

        if (isset($data['date_start']) and isset($data['date_end'])) {
            $this->db->where('tanggal >=', $data['date_start']);
            $this->db->where('tanggal <=', $data['date_end']);
        }

        if (isset($data['majors_id'])) {
            $this->db->where('idMajors', $data['majors_id']);
        }

        $this->db->group_by('akunBayar,jenisBayar');
        $this->db->limit($limit, $start);

        $query = $this->db->get();
        return $query->result_array();
    }

    function pemasukanAkun($data = array())
    {
        $this->db->select('vpj.akunBayar, vpj.jenisBayar AS account_description, mj.majors_short_name, SUM(vpj.penerimaan) AS total_deb');
        $this->db->from('v_laporan_jurnal vpj');
        $this->db->join('majors mj', 'mj.majors_id = vpj.idMajors');
        $this->db->where('vpj.jenisTransaksi !=', 'Kredit');

        if (isset($data['majors_id'])) {
            $this->db->where('idMajors', $data['majors_id']);
        }

        if (isset($data['account_id'])) {
            $this->db->where_in('idAkun', $data['account_id']);
        }

        $this->db->group_by('vpj.akunBayar,vpj.jenisBayar');
        $this->db->order_by('total_deb', 'DESC');
        $query = $this->db->get();

        return $query->result_array();
    }

    function pemasukanKas($data = array())
    {
        $this->db->select('vpj.kodeKas, vpj.namaKas AS account_description, mj.majors_short_name, SUM(vpj.penerimaan) AS total_deb');
        $this->db->from('v_laporan_jurnal vpj');
        $this->db->join('majors mj', 'mj.majors_id = vpj.idMajors');
        $this->db->where('vpj.jenisTransaksi !=', 'Kredit');

        if (isset($data['majors_id'])) {
            $this->db->where('idMajors', $data['majors_id']);
        }

        if (isset($data['account_id'])) {
            $this->db->where_in('idKas', $data['account_id']);
        }

        $this->db->group_by('vpj.kodeKas,vpj.namaKas');
        $this->db->order_by('total_deb', 'DESC');
        $query = $this->db->get();

        return $query->result_array();
    }

    function tagihanSiswa($idBulan)
    {
        $this->db->select("
            s.student_nis, 
            s.student_full_name, 
            c.class_name, 
            COALESCE(bl.tagihanBulan, 0) AS tagihanBulan, 
            COALESCE(bb.bebasTagihan, 0) AS bebasTagihan, 
            COALESCE(bb.bebasTerbayar, 0) AS bebasTerbayar
        ");
        $this->db->from("student s");
        $this->db->join("class c", "c.class_id = s.class_class_id", "inner");

        // Subquery untuk tagihan bulanan
        $subquery_bulan = "(SELECT student_student_id, SUM(bulan_bill) AS tagihanBulan 
                    FROM bulan 
                    WHERE month_month_id <= " . $idBulan . " AND bulan_status = 0 
                    GROUP BY student_student_id) bl";
        $this->db->join($subquery_bulan, "bl.student_student_id = s.student_id", "left");

        // Subquery untuk tagihan bebas
        $subquery_bebas = "(SELECT student_student_id, SUM(bebas_bill) AS bebasTagihan, 
                    SUM(bebas_total_pay) AS bebasTerbayar 
                    FROM bebas 
                    GROUP BY student_student_id) bb";
        $this->db->join($subquery_bebas, "bb.student_student_id = s.student_id", "left");

        // Filter status siswa
        $this->db->where("s.student_status", 1);

        // Eksekusi query
        $query = $this->db->get();
        return $query->result_array();
    }

    function tagihanSiswaRekapPerKelas($data = array())
    {
        $idBulan  = $data['id_bulan'];
        $idPeriod = $data['id_period'];

        $this->db->select("
            c.class_name,
            COUNT(s.student_id) AS total_siswa,
            COALESCE(SUM(bl.tagihanBulan), 0) AS total_tagihan_bulan,
            COALESCE(SUM(bb.bebasTagihan), 0) AS total_tagihan_bebas,
            COALESCE(SUM(bb.bebasTerbayar), 0) AS total_bebas_terbayar
        ");
        $this->db->from("student s");
        $this->db->join("class c", "c.class_id = s.class_class_id", "inner");

        // Subquery untuk tagihan bulanan
        $subquery_bulan = "(SELECT student_student_id, SUM(bulan_bill) AS tagihanBulan 
                FROM bulan
                JOIN payment pbl ON pbl.payment_id = bulan.payment_payment_id 
                WHERE month_month_id <= " . $idBulan . " AND bulan_status = 0 
                     AND pbl.period_period_id = $idPeriod
                GROUP BY student_student_id) bl";
        $this->db->join($subquery_bulan, "bl.student_student_id = s.student_id", "left");

        // Subquery untuk tagihan bebas
        $subquery_bebas = "(SELECT student_student_id, 
                SUM(bebas_bill) AS bebasTagihan, 
                SUM(bebas_total_pay) AS bebasTerbayar 
                FROM bebas
                JOIN payment pbb ON pbb.payment_id = bebas.payment_payment_id 
                WHERE pbb.period_period_id = $idPeriod 
                GROUP BY student_student_id) bb";
        $this->db->join($subquery_bebas, "bb.student_student_id = s.student_id", "left");

        // Filter status siswa
        $this->db->where("s.student_status", 1);

        if (isset($data['majors_id'])) {
            $this->db->where('c.majors_majors_id', $data['majors_id']);
        }

        // Group by kelas
        $this->db->group_by("c.class_id");

        // Eksekusi query
        $query = $this->db->get();
        return $query->result_array();
    }
}
