<?php if (!defined('BASEPATH'))    exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * menu: 최은혁
 * Date: 2017-12-29
 * Time: 오후 2:05
 */

class Menu_m extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }


    function gets(){
        return $this->db->query("SELECT * FROM ci_menu")->result();
    }

    function add($option)
    {
        $this->db->set('menu_name', $option['menu_name']);
        $this->db->set('menu_price', $option['menu_price']);
        $this->db->set('store_id', $option['store_id']);
        $this->db->set('reg_id', $option['reg_id']);
        $this->db->set('reg_date', 'NOW()', false);
        $this->db->insert('ci_menu');
        $result = $this->db->insert_id();
        return $result;
    }


    function getMenuList($store_id, $offset = '', $limit = '') {
        $limit_query = '';
        if ($limit != '' OR $offset != '') {
            // 페이징이 있을 경우 처리
            $limit_query = ' LIMIT ' . $offset . ', ' . $limit;
        }

        $sql = "SELECT 	s.menu_id, ";
        $sql .= "	s.menu_name, ";
        $sql .= "	s.menu_price, ";
        $sql .= "	s.reg_id, ";
        $sql .= "	s.reg_date,";
        $sql .= "   usr.user_id,  ";
        $sql .= "   usr.user_name,  ";
        $sql .= "   usr.nick_name, ";
        $sql .= "   usr.email  ";
        $sql .= "FROM    ci_menu  s";
        $sql .= " LEFT JOIN ci_user usr ";
        $sql .= " ON s.reg_id = usr.user_id ";
        $sql .= " WHERE store_id = ".$store_id;
        $sql .= " ORDER BY s.reg_date DESC " . $limit_query;
        $query = $this -> db -> query($sql);
        $result = $query -> result();
        return $result;
    }
    function getMenuTotalCount($store_id) {

        $sql = "SELECT count(menu_id) as total_count FROM ci_menu WHERE store_id = ".$store_id ;
        $query = $this -> db -> query($sql);
        $result = $query->row();
        return $result->total_count;
    }
    /**
     * 메뉴 상세보기 가져오기
     *
     * @param string $table 게시판 테이블
     * @param string $id 게시물 번호
     * @return array
     */
    function getView($menu_id) {
        $sql = "SELECT * FROM ci_menu WHERE menu_id = '" . $menu_id . "'";
        $query = $this -> db -> query($sql);
        // 게시물 내용 반환
        $result = $query -> row();
        return $result;

    }
    /**
     * 메뉴 수정
     *
     * @param array $arrays 테이블 명, 게시물 번호, 게시물 제목, 게시물 내용
     * @return boolean 성공 여부
     */
    function modifyMenu($arrays) {
        $modify_array = array(
            'menu_name' => $arrays['menu_name'],
            'menu_price' => $arrays['menu_price']
        );
        $where = array(
            'menu_id' => $arrays['menu_id']
        );
        $result = $this->db->update('ci_menu', $modify_array, $where);
        return $result;
    }
}