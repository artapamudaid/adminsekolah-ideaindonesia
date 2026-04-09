<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 *
 * Model General_model
 *
 * This Model for ...
 * 
 * @package		CodeIgniter
 * @category	Model
 * @author    Setiawan Jodi <jodisetiawan@fisip-untirta.ac.id>
 * @link      https://github.com/setdjod/myci-extension/
 * @param     ...
 * @return    ...
 *
 */

class General_model extends CI_Model
{

  // ------------------------------------------------------------------------

  public function __construct()
  {
    parent::__construct();
  }

  // ------------------------------------------------------------------------


  // ------------------------------------------------------------------------
  public function checkDataExists($tableName, $conditions, $value = null)
  {
    if (!is_array($conditions)) {
      $this->db->where($conditions, $value);
      return $this->db->count_all_results($tableName) > 0;
    }

    $this->_applyConditions($conditions);

    return $this->db->count_all_results($tableName) > 0;
  }

  // ------------------------------------------------------------------------
  /**
   * Recursive: AND / OR / nested conditions
   */
  private function _applyConditions($conditions, $logic = 'AND')
  {
    foreach ($conditions as $key => $value) {
      $keyUpper = strtoupper($key);
      if ($keyUpper === 'AND' || $keyUpper === 'OR') {
        if ($keyUpper === 'AND') {
          $this->db->group_start();
        } else {
          $this->db->or_group_start();
        }
        $this->_applyConditions($value, $keyUpper);
        $this->db->group_end();
        continue;
      }

      if (is_numeric($key) && is_array($value)) {
        if ($logic === 'OR') {
          $this->db->or_group_start();
        } else {
          $this->db->group_start();
        }
        // $this->_applyConditions($value, $logic);
        $this->_applyConditions($value, 'AND');
        $this->db->group_end();
        continue;
      }

      if (is_array($value)) {
        $this->_applyConditions($value, $logic);
        continue;
      }

      if ($logic === 'AND') {
        $this->db->where($key, $value);
      } else {
        $this->db->or_where($key, $value);
      }
    }
  }
}

/* End of file General_model.php */
/* Location: ./application/models/General_model.php */