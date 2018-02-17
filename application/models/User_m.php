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
        $sql = "SELECT * FROM ci_user ORDER BY reg_date DESC " . $limit_query;
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
}