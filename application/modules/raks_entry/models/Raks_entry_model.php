<?php if (! defined('BASEPATH')) exit('No direct script access allowed');


class Raks_entry_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }


    // Get account from database
    function get($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('raks_entry.id', $params['id']);
        }

        if (isset($params['majors_id'])) {
            $this->db->where('raks_entry.majors_id', $params['majors_id']);
        }

        if ($this->session->userdata('umajorsid') != '0') {
            $this->db->where('majors_id', $this->session->userdata('umajorsid'));
        }

        if (isset($params['limit'])) {
            if (!isset($params['offset'])) {
                $params['offset'] = NULL;
            }

            $this->db->limit($params['limit'], $params['offset']);
        }

        $this->db->where('majors_status', '1');

        $this->db->select('raks_entry.id, raks_entry.kode, raks_entry.nama, raks_entry.parent, majors.majors_id, majors.majors_short_name');
        $this->db->join('majors', 'majors.majors_id = raks_entry.majors_id', 'left');
        $res = $this->db->get('raks_entry');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    // function get_entry($majors_id, $period_id)
    // {
    //     $sql_main = "WITH RECURSIVE entryHierarchy AS ( 
    //         SELECT id, kode, nama, 0 AS level, parent, proposal_status, approval_status 
    //         FROM raks_entry 
    //         WHERE parent = 0 AND majors_id = ? AND period_id = ?

    //         UNION ALL

    //         SELECT a.id, a.kode, a.nama, ah.level + 1, a.parent, a.proposal_status, a.approval_status
    //         FROM raks_entry a 
    //         INNER JOIN entryHierarchy ah ON a.parent = ah.id
    //         WHERE a.majors_id = ? AND a.period_id = ?
    //     ) 
    //     SELECT eh.id, eh.kode, eh.nama, eh.level, eh.parent, eh.proposal_status, eh.approval_status,
    //         CONCAT(REPEAT('&nbsp', level * 2)) AS indented,
    //         CASE 
    //            WHEN EXISTS (SELECT 1 FROM raks_entry e WHERE e.parent = eh.id) 
    //            THEN 1 
    //            ELSE 0 
    //         END AS has_child,
    //         COALESCE(ed.totalPrice, 0) AS totalPrice
    //     FROM entryHierarchy eh
    //     LEFT JOIN (
    //         SELECT entry_id, SUM(totalPrice) AS totalPrice
    //         FROM entry_detail
    //         GROUP BY entry_id
    //     ) ed ON eh.id = ed.entry_id
    //     ORDER BY kode, level";

    //     $main_entry = $this->db->query($sql_main, array($majors_id, $period_id, $majors_id, $period_id))->result();

    //     return $main_entry;
    // }

    function get_entry($majors_id, $period_id)
    {
        $sql_main = "
        WITH RECURSIVE entryHierarchy AS (
            SELECT 
                id, 
                kode, 
                nama, 
                0 AS level, 
                parent, 
                proposal_status, 
                approval_status
            FROM raks_entry
            WHERE parent = 0 AND majors_id = ? AND period_id = ?

            UNION ALL

            SELECT 
                a.id, 
                a.kode, 
                a.nama, 
                eh.level + 1, 
                a.parent, 
                a.proposal_status, 
                a.approval_status
            FROM raks_entry a
            INNER JOIN entryHierarchy eh ON a.parent = eh.id
            WHERE a.majors_id = ? AND a.period_id = ?
        ),

        recursiveTotal AS (
            SELECT 
                entry_id, 
                SUM(totalPrice) AS totalPrice
            FROM entry_detail
            GROUP BY entry_id
        ),

        aggregated AS (
            SELECT 
                eh.*, 
                COALESCE(rt.totalPrice, 0) AS selfPrice
            FROM entryHierarchy eh
            LEFT JOIN recursiveTotal rt ON rt.entry_id = eh.id
        )

        SELECT 
            a.id,
            a.kode,
            a.nama,
            a.level,
            a.parent,
            a.proposal_status,
            a.approval_status,
            CONCAT(REPEAT('&nbsp;', a.level * 2)) AS indented,
            CASE 
                WHEN EXISTS (
                    SELECT 1 FROM raks_entry e WHERE e.parent = a.id
                ) THEN 1 ELSE 0
            END AS has_child,

            (
                SELECT SUM(x.selfPrice)
                FROM aggregated x
                WHERE x.id = a.id OR x.parent = a.id
            ) AS totalPrice

        FROM aggregated a
        ORDER BY kode, level
    ";

        // Kirim parameter 4x sesuai query di atas
        $main_entry = $this->db->query($sql_main, array(
            $majors_id,
            $period_id,
            $majors_id,
            $period_id
        ))->result();

        return $main_entry;
    }


    function get_entry_status($majors_id, $period_id)
    {
        $this->db->select('approval_status');
        $this->db->from("raks_entry");
        $this->db->where('raks_entry.majors_id', $majors_id);
        $this->db->where('raks_entry.period_id', $period_id);

        $res = $this->db->get();
        $data = $res->row_array();

        return $data['approval_status'] ?? '';
    }

    function updateEntryStatus($majors_id, $period_id, $approval)
    {
        $this->db->set('approval_status', $approval);
        $this->db->where('raks_entry.majors_id', $majors_id);
        $this->db->where('raks_entry.period_id', $period_id);
        $this->db->update('raks_entry');

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : TRUE;
    }

    // Add and update to database
    function add($data = array())
    {
        if (isset($data['kode'])) {
            $this->db->set('kode', $data['kode']);
        }

        if (isset($data['nama'])) {
            $this->db->set('nama', $data['nama']);
        }

        if (isset($data['parent'])) {
            $this->db->set('parent', $data['parent']);
        }

        if (isset($data['majors_id'])) {
            $this->db->set('majors_id', $data['majors_id']);
        }

        if (isset($data['period_id'])) {
            $this->db->set('period_id', $data['period_id']);
        }

        if (isset($data['user'])) {
            $this->db->set('user_id', $data['user']);
        }

        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('raks_entry');
            $id = $data['id'];
        } else {
            $this->db->insert('raks_entry');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function updateStatusProposalEntry($id, $status)
    {
        $this->db->set('proposal_status', $status);
        $this->db->where('id', $id);
        $this->db->update('raks_entry');
    }

    function saveDetail($data = array())
    {
        if (isset($data['entry_id'])) {
            $this->db->set('entry_id', $data['entry_id']);
        }

        if (isset($data['inventoryCode'])) {
            $this->db->set('inventoryCode', $data['inventoryCode']);
        }

        if (isset($data['nama'])) {
            $this->db->set('nama', $data['nama']);
        }

        if (isset($data['qty'])) {
            $this->db->set('qty', $data['qty']);
        }

        if (isset($data['volume'])) {
            $this->db->set('volume', $data['volume']);
        }

        if (isset($data['unitPrice'])) {
            $this->db->set('unitPrice', $data['unitPrice']);
        }

        if (isset($data['totalPrice'])) {
            $this->db->set('totalPrice', $data['totalPrice']);
        }

        if (isset($data['executionTime'])) {
            $this->db->set('executionTime', $data['executionTime']);
        }

        if (isset($data['status'])) {
            $this->db->set('status', $data['status']);
        }

        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('entry_detail');
            $id = $data['id'];
        } else {
            $this->db->insert('entry_detail');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function deleteSourceDetail($id_detail)
    {
        $this->db->where('detail_id', $id_detail);
        $this->db->delete('source_entry_detail');
    }

    function insertBatchSourceDetail($data_insert)
    {
        $this->db->insert_batch("source_entry_detail", $data_insert);
    }

    // Delete account to database
    function delete($id)
    {
        // Cek apakah entry_id masih digunakan di entry_detail
        $this->db->where('entry_id', $id);
        $query = $this->db->get('entry_detail');

        if ($query->num_rows() > 0) {
            // Ada relasi, tidak boleh hapus
            return [
                'status' => false,
                'message' => 'Data tidak dapat dihapus karena masih memiliki entry detail.'
            ];
        }

        // Aman untuk dihapus
        $this->db->where('id', $id);
        $this->db->delete('raks_entry');

        return [
            'status' => true,
            'message' => 'Data berhasil dihapus.'
        ];
    }

    function getDetail($id)
    {
        $this->db->select('ed.*');
        $this->db->select("GROUP_CONCAT(CONCAT_WS('[-]', source.nama, source.nominal) SEPARATOR '[--]') AS dataSource");
        $this->db->select("rp.noPermintaan, rp.id AS idProposal");
        $this->db->from("entry_detail ed");
        $this->db->join("(SELECT sed.detail_id, sed.nama, sed.nominal FROM source_entry_detail sed) AS source", "source.detail_id = ed.id");
        $this->db->join("request_proposal rp", "rp.detail_id = ed.id", "left");
        $this->db->where('ed.entry_id', $id);
        $this->db->group_by('ed.id');

        $res = $this->db->get();
        $data = $res->result_array();

        return $data;
    }

    function deleteDetail($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('entry_detail');
    }

    function updateStatusDetail($id, $status)
    {
        $this->db->set('status', $status);
        $this->db->where('id', $id);
        $this->db->update('entry_detail');
    }

    function requestProposal($id_detail, $noPermintaan)
    {
        $this->db->from("request_proposal");
        $this->db->where('request_proposal.detail_id', $id_detail);
        $this->db->where('request_proposal.noPermintaan', $noPermintaan);

        $res = $this->db->get();
        $data = $res->row_array();

        return $data;
    }

    function saveRequest($data = array())
    {
        if (isset($data['detail_id'])) {
            $this->db->set('detail_id', $data['detail_id']);
        }

        if (isset($data['noPermintaan']) and isset($data['id'])) {
            if ($data['id'] == 0) {
                $this->db->set('noPermintaan', $data['noPermintaan']);
            }
        }

        if (isset($data['targetDate'])) {
            $this->db->set('targetDate', $data['targetDate']);
        }

        if (isset($data['requestType'])) {
            $this->db->set('requestType', $data['requestType']);
        }

        if (isset($data['costCenter'])) {
            $this->db->set('costCenter', $data['costCenter']);
        }

        if (isset($data['attachment1'])) {
            $this->db->set('attachment1', $data['attachment1']);
        }

        if (isset($data['attachment2'])) {
            $this->db->set('attachment2', $data['attachment2']);
        }

        if (isset($data['attachment3'])) {
            $this->db->set('attachment3', $data['attachment3']);
        }

        if (isset($data['attachment4'])) {
            $this->db->set('attachment4', $data['attachment4']);
        }

        if (isset($data['attachment5'])) {
            $this->db->set('attachment5', $data['attachment5']);
        }

        if (isset($data['notes'])) {
            $this->db->set('notes', $data['notes']);
        }

        if (isset($data['user'])) {
            $this->db->set('user', $data['user']);
        }

        if (isset($data['id'])) {
            if ($data['id'] == 0) {
                $this->db->insert('request_proposal');
                $id = $this->db->insert_id();
            }
        } else {
            if (isset($data['noPermintaan'])) {
                $this->db->where('noPermintaan', $data['noPermintaan']);
                $this->db->where('detail_id', $data['detail_id']);
                $this->db->update('request_proposal');
                $id = $data['noPermintaan'];
            }
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $status;
    }

    function deleteRequest($id_detail, $noPermintaan)
    {
        $this->db->where('request_proposal.detail_id', $id_detail);
        $this->db->where('request_proposal.noPermintaan', $noPermintaan);

        if ($this->db->delete('request_proposal')) {
            $this->db->where('id', $id_detail);
            $this->db->update('entry_detail', ['status' => 'CREATED']);

            return ['status' => 'success', 'message' => 'Data berhasil dihapus.'];
        } else {
            return ['status' => 'failed', 'message' => 'Gagal menghapus data.'];
        }
    }

    function disapproveRequest($id_detail, $noPermintaan)
    {
        $this->db->where('request_proposal.detail_id', $id_detail);
        $this->db->where('request_proposal.noPermintaan', $noPermintaan);

        if ($this->db->delete('request_proposal')) {
            $this->db->where('id', $id_detail);
            $this->db->update('entry_detail', ['status' => 'CREATED']);

            return ['status' => 'success', 'message' => 'Data berhasil dihapus.'];
        } else {
            return ['status' => 'failed', 'message' => 'Gagal menghapus data.'];
        }
    }
}
