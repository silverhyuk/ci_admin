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

    function get_list($table = 'ci_board', $type = '', $offset = '', $limit = '', $search_word = '') {
        $sword = '';
        if ($search_word != '') {
            // 검색어 있을 경우
            $sword = ' WHERE subject like "%' . $search_word . '%" or contents like "%' . $search_word . '%" ';
        }
        $limit_query = '';
        if ($limit != '' OR $offset != '') {
            // 페이징이 있을 경우 처리
            $limit_query = ' LIMIT ' . $offset . ', ' . $limit;
        }
        $sql = "SELECT * FROM " . $table . $sword . " ORDER BY board_id DESC " . $limit_query;
        $query = $this -> db -> query($sql);
        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result();
        }
        return $result;
    }

    /**
     * 게시물 상세보기 가져오기
     *
     * @param string $table 게시판 테이블
     * @param string $id 게시물 번호
     * @return array
     */
    function get_view($table, $id) {
        // 조횟수 증가
        $sql0 = "UPDATE " . $table . " SET hits = hits + 1 WHERE board_id='" . $id . "'";
        $this -> db -> query($sql0);

        $sql = "SELECT * FROM " . $table . " WHERE board_id = '" . $id . "'";
        $query = $this -> db -> query($sql);

        // 게시물 내용 반환
        $result = $query -> row();

        return $result;

    }
    /**
     * 게시물 수정
     *
     * @param array $arrays 테이블 명, 게시물 번호, 게시물 제목, 게시물 내용
     * @return boolean 성공 여부
     */
    function modify_board($arrays) {
        $modify_array = array(
            'subject' => $arrays['subject'],
            'contents' => $arrays['contents']
        );
        $where = array(
            'board_id' => $arrays['board_id']
        );
        $result = $this->db->update($arrays['table'], $modify_array, $where);
        return $result;
    }

    /**
     * 게시물 입력
     *
     *
     * @param array $arrays 테이블명, 게시물제목, 게시물내용, 아이디 1차 배열
     * @return boolean 입력 성공여부
     */
    function insert_board($arrays)
    {
        $insert_array = array(
            'board_id' => 0, //원글이라 0을 입력, 댓글일 경우 원글번호 입력
            'user_id' => $arrays['user_id'],
            'user_name' => $arrays['user_name'],
            'subject' => $arrays['subject'],
            'contents' => $arrays['contents'],
            'reg_date' => date("Y-m-d H:i:s")
        );

        $result = $this->db->insert($arrays['table'], $insert_array);

        //결과 반환
        return $result;
    }

}