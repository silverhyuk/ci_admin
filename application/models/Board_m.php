<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: idea
 * Date: 2017-12-10
 * Time: 오후 9:02
 */

class Board_m extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    function get_list($table = 'ci_board', $type = '', $offset = '', $limit = '') {
        $limit_query = '';
        if ($limit != '' OR $offset != '') {
            // 페이징이 있을 경우 처리
            $limit_query = ' LIMIT ' . $offset . ', ' . $limit;
        }
        $sql = "SELECT * FROM " . $table . " ORDER BY board_id DESC " . $limit_query;
        $query = $this -> db -> query($sql);
        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result();
        }
        return $result;
    }
}