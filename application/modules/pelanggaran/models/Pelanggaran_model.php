
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pelanggaran_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function kategori_pelanggaran()
    {
        $this->db->select('*');
        $this->db->from('kategori_pelanggaran');
        $res = $this->db->get();

        return $res->result_array();
    }

    function listPelanggaran()
    {
        $this->db->select('pelanggaran.id, pelanggaran.pelanggaran, pelanggaran.poin, pelanggaran.id_kategori, kategori_pelanggaran.kategori');
        $this->db->from('pelanggaran');
        $this->db->join('kategori_pelanggaran', 'kategori_pelanggaran.id = pelanggaran.id_kategori');

        return $this->db->get()->result();
    }
}
