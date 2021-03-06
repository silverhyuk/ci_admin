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
            $sword = ' WHERE brd.subject like "%' . $search_word . '%" or brd.contents like "%' . $search_word . '%" ';
        }
        $limit_query = '';
        if ($limit != '' OR $offset != '') {
            // 페이징이 있을 경우 처리
            $limit_query = ' LIMIT ' . $offset . ', ' . $limit;
        }
        $sql = " SELECT 	brd.board_id, ";
        $sql .= " brd.board_pid, ";
        $sql .= " brd.user_id,  ";
        $sql .= " brd.subject,  ";
        $sql .= " brd.contents,  ";
        $sql .= " brd.hits,  ";
        $sql .= " brd.reg_date, ";
        $sql .= " usr.user_id,  ";
        $sql .= " usr.user_name,  ";
        $sql .= " usr.nick_name, ";
        $sql .= " usr.email,  ";
        $sql .= " usr.password,  ";
        $sql .= " usr.role_id ";
        $sql .= " FROM ci_board brd ";
        $sql .= " LEFT JOIN ci_user usr ";
        $sql .= " ON brd.user_id = usr.user_id ";
        $sql .= $sword . " ORDER BY brd.board_id DESC " . $limit_query;

        $query = $this -> db -> query($sql);
        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result();
        }
        return $result;
    }

    function get_total_count($table = 'ci_board', $search_word = '') {
        $sword = '';
        if ($search_word != '') {
            // 검색어 있을 경우
            $sword = ' WHERE subject like "%' . $search_word . '%" or contents like "%' . $search_word . '%" ';
        }

        $sql = "SELECT count(board_id) as total_count FROM " . $table . $sword ;
        $query = $this -> db -> query($sql);
        $result = $query->row();
        return $result->total_count;
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

        $sql = " SELECT brd.board_id, ";
        $sql .= " brd.board_pid, ";
        $sql .= " brd.user_id,  ";
        $sql .= " brd.subject,  ";
        $sql .= " brd.contents,  ";
        $sql .= " brd.hits,  ";
        $sql .= " brd.reg_date, ";
        $sql .= " usr.user_id,  ";
        $sql .= " usr.user_name,  ";
        $sql .= " usr.nick_name, ";
        $sql .= " usr.email,  ";
        $sql .= " usr.password,  ";
        $sql .= " usr.role_id ";
        $sql .= " FROM ci_board brd ";
        $sql .= " LEFT JOIN ci_user usr ";
        $sql .= " ON brd.user_id = usr.user_id ";
        $sql .= " WHERE brd.board_id = '" . $id . "'";
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
    /**
     * 게시물 작성자 아이디 반환
     *
     * @param string $table 게시판 테이블
     * @param string $board_id 게시물번호
     * @return string 작성자 아이디
     */
    function writer_check($table, $board_id)
    {
        $sql = "SELECT user_id FROM ".$table." WHERE board_id = '".$board_id."'";
        $query = $this->db->query($sql);
        return $query->row();
    }
    /**
     * 게시물 삭제
     *
     * @param string $table 테이블명
     * @param string $no 게시물번호
     * @return boolean 삭제 성공여부
     */
    function delete_content($table, $no)
    {
        $delete_array = array(
            'board_id' => $no
        );

        $result = $this->db->delete($table, $delete_array);

        //결과 반환
        return $result;
    }

}