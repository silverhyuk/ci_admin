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

    function get_list($table = 'ci_board') {
        $sql = "SELECT * FROM ".$table." ORDER BY board_id DESC";
        $query = $this -> db -> query($sql);
        $result = $query -> result();
        // $result = $query->result_array();

        return $result;
    }
}