<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: idea
 * Date: 2017-12-09
 * Time: 오후 10:44
 */

class Board extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this -> load -> database();
        $this -> load -> model('board_m');
        $this -> load -> helper(array('url', 'date'));
        $this->output->enable_profiler(TRUE);
    }

    /**
     * 주소에서 메서드가 생략되었을 때 실행되는 기본 메서드
     */
    public function index() {
        $this -> lists();
    }
    /**
     * 목록 불러오기
     */
    public function lists() {
        $data['list'] = $this -> board_m -> get_list();
        $this -> load -> template('board/list_v', $data);
    }
}