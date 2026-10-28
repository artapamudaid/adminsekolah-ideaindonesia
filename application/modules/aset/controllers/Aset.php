<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Aset extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        //load model
        // $this->load->model('easet/ModelAset', 'ma');
        // $this->load->model('easet/ModelBarang', 'mb');
        // $this->load->model('easet/ModelLokasi', 'ml');
        // $this->load->model('easet/ModelKategori', 'mk');

        //load library
        $this->load->library('ciqrcode');
        $this->load->library('uuid');
        $this->load->library('form_validation');
        $this->load->helper('terbilang');
    }

    function detail_aset($id_aset = null)
    {
        $schoolName = "SELECT setting_value as name FROM `setting` WHERE setting_name = 'setting_school'";
        $resultName = $this->db->query($schoolName)->row();

        $sql = "
            SELECT *
            FROM asets a
            JOIN barang b ON b.id_barang = a.id_barang
            JOIN lokasi_aset c ON c.id_lokasi = a.id_lokasi
            JOIN kategori_barang d ON d.id_kategori = b.id_kategori
            WHERE a.id_aset = ?
        ";
        $aset = $this->db->query($sql, [$id_aset])->result_array();

        $data = array(
            'instansi' => $resultName->name,
            'aset' => $aset
        );

        $this->load->view('aset/d_wujud', $data);
    }
}
