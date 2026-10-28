<?php

if (!defined('BASEPATH'))
    exit('No direct script are allowed');

class Setting_model extends CI_Model
{

    public function get($param = array())
    {

        if (isset($param['id'])) {
            $this->db->where('setting_id', $param['id']);
        }

        if (isset($param['setting_school'])) {
            $this->db->where('setting_school', $param['setting_school']);
        }

        if (isset($param['name'])) {
            $this->db->where('setting_name', $param['name']);
        }

        if (isset($param['setting_logo'])) {
            $this->db->where('setting_logo', $param['setting_logo']);
        }

        if (isset($param['setting_kartu_depan'])) {
            $this->db->where('setting_kartu_depan', $param['setting_kartu_depan']);
        }

        if (isset($param['setting_kartu_belakang'])) {
            $this->db->where('setting_kartu_belakang', $param['setting_kartu_belakang']);
        }

        if (isset($param['id']) or isset($param['setting_school']) or isset($param['name'])) {
            return $this->db->get('setting')->row_array();
        } else {
            return $this->db->get('setting')->result_array();
        }
    }

    public function get_value($params = array())
    {
        $setting = $this->get($params);

        if (!empty($setting['setting_value']))
            return $setting['setting_value'];
        else
            return '';
    }

    public function save($param = array())
    {
        if (isset($param['setting_school'])) {
            $this->db->set('setting_value', $param['setting_school']);
            $this->db->where('setting_id', 1);
            $this->db->update('setting');
        }
        if (isset($param['setting_address'])) {
            $this->db->set('setting_value', $param['setting_address']);
            $this->db->where('setting_id', 2);
            $this->db->update('setting');
        }
        if (isset($param['setting_phone'])) {
            $this->db->set('setting_value', $param['setting_phone']);
            $this->db->where('setting_id', 3);
            $this->db->update('setting');
        }

        if (isset($param['setting_district'])) {
            $this->db->set('setting_value', $param['setting_district']);
            $this->db->where('setting_id', 4);
            $this->db->update('setting');
        }

        if (isset($param['setting_city'])) {
            $this->db->set('setting_value', $param['setting_city']);
            $this->db->where('setting_id', 5);
            $this->db->update('setting');
        }

        if (isset($param['setting_logo'])) {
            $this->db->set('setting_value', $param['setting_logo']);
            $this->db->where('setting_id', 6);
            $this->db->update('setting');
        }

        if (isset($param['setting_level'])) {
            $this->db->set('setting_value', $param['setting_level']);
            $this->db->where('setting_id', 7);
            $this->db->update('setting');
        }

        if (isset($param['setting_user_sms'])) {
            $this->db->set('setting_value', $param['setting_user_sms']);
            $this->db->where('setting_id', 8);
            $this->db->update('setting');
        }

        if (isset($param['setting_pass_sms'])) {
            $this->db->set('setting_value', $param['setting_pass_sms']);
            $this->db->where('setting_id', 9);
            $this->db->update('setting');
        }

        if (isset($param['setting_sms'])) {
            $this->db->set('setting_value', $param['setting_sms']);
            $this->db->where('setting_id', 10);
            $this->db->update('setting');
        }

        if (isset($param['setting_nip_kepsek'])) {
            $this->db->set('setting_value', $param['setting_nip_kepsek']);
            $this->db->where('setting_id', 11);
            $this->db->update('setting');
        }

        if (isset($param['setting_nama_kepsek'])) {
            $this->db->set('setting_value', $param['setting_nama_kepsek']);
            $this->db->where('setting_id', 12);
            $this->db->update('setting');
        }

        if (isset($param['setting_nip_katu'])) {
            $this->db->set('setting_value', $param['setting_nip_katu']);
            $this->db->where('setting_id', 13);
            $this->db->update('setting');
        }

        if (isset($param['setting_nama_katu'])) {
            $this->db->set('setting_value', $param['setting_nama_katu']);
            $this->db->where('setting_id', 14);
            $this->db->update('setting');
        }

        if (isset($param['setting_nip_bendahara'])) {
            $this->db->set('setting_value', $param['setting_nip_bendahara']);
            $this->db->where('setting_id', 15);
            $this->db->update('setting');
        }

        if (isset($param['setting_nama_bendahara'])) {
            $this->db->set('setting_value', $param['setting_nama_bendahara']);
            $this->db->where('setting_id', 16);
            $this->db->update('setting');
        }

        if (isset($param['setting_wa_center'])) {
            $this->db->set('setting_value', $param['setting_wa_center']);
            $this->db->where('setting_id', 17);
            $this->db->update('setting');
        }

        if (isset($param['setting_kartu_depan'])) {
            $this->db->set('setting_value', $param['setting_kartu_depan']);
            $this->db->where('setting_id', 18);
            $this->db->update('setting');
        }

        if (isset($param['setting_kartu_belakang'])) {
            $this->db->set('setting_value', $param['setting_kartu_belakang']);
            $this->db->where('setting_id', 19);
            $this->db->update('setting');
        }

        if (isset($param['setting_notif'])) {
            $this->db->set('setting_value', $param['setting_notif']);
            $this->db->where('setting_id', 20);
            $this->db->update('setting');
        }

        if (isset($param['setting_wa_key'])) {
            $this->db->set('setting_value', $param['setting_wa_key']);
            $this->db->where('setting_id', 21);
            $this->db->update('setting');
        }

        if (isset($param['setting_email'])) {
            $this->db->set('setting_value', $param['setting_email']);
            $this->db->where('setting_id', 90);
            $this->db->update('setting');
        }

        if (isset($param['setting_website'])) {
            $this->db->set('setting_value', $param['setting_website']);
            $this->db->where('setting_id', 91);
            $this->db->update('setting');
        }

        if (isset($param['setting_whatsapp'])) {
            $this->db->set('setting_value', $param['setting_whatsapp']);
            $this->db->where('setting_id', 92);
            $this->db->update('setting');
        }

        if (isset($param['setting_facebook'])) {
            $this->db->set('setting_value', $param['setting_facebook']);
            $this->db->where('setting_id', 93);
            $this->db->update('setting');
        }

        if (isset($param['setting_instagram'])) {
            $this->db->set('setting_value', $param['setting_instagram']);
            $this->db->where('setting_id', 94);
            $this->db->update('setting');
        }

        if (isset($param['setting_youtube'])) {
            $this->db->set('setting_value', $param['setting_youtube']);
            $this->db->where('setting_id', 95);
            $this->db->update('setting');
        }

        if (isset($param['setting_package'])) {
            $this->db->set('setting_value', $param['setting_package']);
            $this->db->where('setting_id', 100);
            $this->db->update('setting');
        }

        if (isset($param['setting_date'])) {
            $this->db->set('setting_value', $param['setting_date']);
            $this->db->where('setting_id', 101);
            $this->db->update('setting');
        }

        if (isset($param['use_limit'])) {
            $this->db->set('setting_value', $param['use_limit']);
            $this->db->where('setting_id', 208);
            $this->db->update('setting');
        }

        if (isset($param['limit_tabungan'])) {
            $this->db->set('setting_value', $param['limit_tabungan']);
            $this->db->where('setting_id', 209);
            $this->db->update('setting');
        }

        if (isset($param['scheduler_status'])) {
            $this->db->set('setting_value', $param['scheduler_status']);
            $this->db->where('setting_id', 400);
            $this->db->update('setting');
        }

        if (isset($param['scheduler_date'])) {
            $this->db->set('setting_value', $param['scheduler_date']);
            $this->db->where('setting_id', 401);
            $this->db->update('setting');
        }

        if (isset($param['scheduler_time'])) {
            $this->db->set('setting_value', $param['scheduler_time']);
            $this->db->where('setting_id', 402);
            $this->db->update('setting');
        }

        if (isset($param['scheduler_domain'])) {
            $this->db->set('setting_value', $param['scheduler_domain']);
            $this->db->where('setting_id', 403);
            $this->db->update('setting');
        }
    }
}
