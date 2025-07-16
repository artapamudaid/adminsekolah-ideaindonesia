<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Prestasi_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function kategori_prestasi()
    {
        $this->db->select('*');
        $this->db->from('kategori_prestasi');
        $res = $this->db->get();

        return $res->result_array();
    }

    function listPrestasi()
    {
        $this->db->select('prestasi.id, prestasi.prestasi, prestasi.poin, prestasi.id_kategori, kategori_prestasi.kategori');
        $this->db->from('prestasi');
        $this->db->join('kategori_prestasi', 'kategori_prestasi.id = prestasi.id_kategori');

        return $this->db->get()->result();
    }

    public function insert_batch_prestasi($data)
    {
        return $this->db->insert_batch('prestasi', $data);
    }
}
