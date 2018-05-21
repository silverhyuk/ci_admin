<?php if (!defined('BASEPATH'))    exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: 최은혁
 * Date: 2017-12-29
 * Time: 오후 2:05
 */

class User_m extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }


    function gets(){
        return $this->db->query("SELECT * FROM ci_user")->result();
    }

    function add($option)
    {
        $this->db->set('user_name', $option['user_name']);
        $this->db->set('nick_name', $option['nick_name']);
        $this->db->set('email', $option['email']);
        $this->db->set('password', $option['password']);
        $this->db->set('role_id', $option['role_id']);
        $this->db->set('reg_date', 'NOW()', false);
        $this->db->insert('ci_user');
        $result = $this->db->insert_id();
        return $result;
    }

    function getByEmail($option)
    {
        //$result = $this->db->get_where('ci_user', array('email'=>$option['email']))->row();

        $sql = "SELECT usr.user_id, ";
        $sql .= "usr.user_name,  ";
        $sql .= "usr.nick_name,  ";
        $sql .= "usr.email,  ";
        $sql .= "usr.password,  ";
        $sql .= "usr.role_id,  ";
        $sql .= "usr.reg_date, ";
        $sql .= "rol.role_id,  ";
	    $sql .= "rol.role_name,  ";
	    $sql .= "rol.role_type  ";
        $sql .= "FROM ci_user AS usr ";
        $sql .= " LEFT JOIN ci_role AS rol ";
        $sql .= " ON usr.role_id =  rol.role_id " ;
        $sql .= " WHERE email = '".$option['email']."' ";

        $query = $this->db->query($sql);
        $result = $query->row();
        return $result;
    }
    function getUserList($offset = '', $limit = '') {
        $limit_query = '';
        if ($limit != '' OR $offset != '') {
            // 페이징이 있을 경우 처리
            $limit_query = ' LIMIT ' . $offset . ', ' . $limit;
        }
        $sql = $sql = "SELECT usr.user_id, ";
        $sql .= "usr.user_name,  ";
        $sql .= "usr.nick_name,  ";
        $sql .= "usr.email,  ";
        $sql .= "usr.password,  ";
        $sql .= "usr.role_id,  ";
        $sql .= "usr.reg_date, ";
        $sql .= "rol.role_id,  ";
        $sql .= "rol.role_name,  ";
        $sql .= "rol.role_type  ";
        $sql .= "FROM ci_user AS usr ";
        $sql .= " LEFT JOIN ci_role AS rol ";
        $sql .= " ON usr.role_id =  rol.role_id " ;
        $sql .= " ORDER BY reg_date DESC " . $limit_query;
        $query = $this -> db -> query($sql);
        $result = $query -> result();
        return $result;
    }
    function getUserTotalCount() {

        $sql = "SELECT count(user_id) as total_count FROM ci_user " ;
        $query = $this -> db -> query($sql);
        $result = $query->row();
        return $result->total_count;
    }
    /**
     * 회원 상세보기 가져오기
     *
     * @param string $table 게시판 테이블
     * @param string $id 게시물 번호
     * @return array
     */
    function get_view($user_id) {
        $sql = "SELECT * FROM ci_user WHERE user_id = '" . $user_id . "'";
        $query = $this -> db -> query($sql);
        // 게시물 내용 반환
        $result = $query -> row();
        return $result;

    }
    /**
     * 회원 수정
     *
     * @param array $arrays 테이블 명, 게시물 번호, 게시물 제목, 게시물 내용
     * @return boolean 성공 여부
     */
    function modify_user($arrays) {
        $modify_array = array(
            'password' => $arrays['password'],
            'email' => $arrays['email'],
            'user_name' => $arrays['user_name'],
            'nick_name' => $arrays['nick_name'],
            'role_id' => $arrays['role_id']
        );
        $where = array(
            'user_id' => $arrays['user_id']
        );
        $result = $this->db->update('ci_user', $modify_array, $where);
        return $result;
    }
}