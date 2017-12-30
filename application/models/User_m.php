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
        return $this->db->query("SELECT * FROM user")->result();
    }

    function add($option)
    {
        $this->db->set('username', $option['username']);
        $this->db->set('nickname', $option['nickname']);
        $this->db->set('email', $option['email']);
        $this->db->set('password', $option['password']);
        $this->db->set('created', 'NOW()', false);
        $this->db->insert('user');
        $result = $this->db->insert_id();
        return $result;
    }

    function getByEmail($option)
    {
        $result = $this->db->get_where('user', array('email'=>$option['email']))->row();
        return $result;
    }

}