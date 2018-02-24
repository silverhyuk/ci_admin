<?php if (!defined('BASEPATH'))    exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * store: 최은혁
 * Date: 2017-12-29
 * Time: 오후 2:05
 */

class Store_m extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }


    function gets(){
        return $this->db->query("SELECT * FROM ci_store")->result();
    }
    function getStoreTypeList(){
        return $this->db->query("SELECT * FROM dim_type WHERE type_group='store'")->result();
    }
    function getStatusList(){
        return $this->db->query("SELECT * FROM dim_status WHERE status_group='store'")->result();
    }

    function add($option)
    {
        $this->db->set('store_name', $option['store_name']);
        $this->db->set('store_type_id', $option['store_type_id']);
        $this->db->set('status_id', $option['status_id']);
        $this->db->set('store_tel', $option['store_tel']);
        $this->db->set('reg_id', $option['reg_id']);
        $this->db->set('reg_date', 'NOW()', false);
        $this->db->insert('ci_store');
        $result = $this->db->insert_id();
        return $result;
    }


    function getStoreList($offset = '', $limit = '') {
        $limit_query = '';
        if ($limit != '' OR $offset != '') {
            // 페이징이 있을 경우 처리
            $limit_query = ' LIMIT ' . $offset . ', ' . $limit;
        }

        $sql = "SELECT 	s.store_id, ";
        $sql .= "	s.store_type_id, ";
        $sql .= "	s.status_id, ";
        $sql .= "	s.store_name, ";
        $sql .= "	s.store_tel, ";
        $sql .= "   ds.status_name, ";
        $sql .= "   dt.type_name as store_type_name, ";
        $sql .= "	s.reg_id, ";
        $sql .= "	s.reg_date,";
        $sql .= "   usr.user_id,  ";
        $sql .= "   usr.user_name,  ";
        $sql .= "   usr.nick_name, ";
        $sql .= "   usr.email  ";
        $sql .= "FROM    ci_store  s";
        $sql .= " LEFT JOIN ci_user usr ";
        $sql .= " ON s.reg_id = usr.user_id ";
        $sql .= " LEFT JOIN dim_status ds ";
        $sql .= " ON s.status_id = ds.status_id ";
        $sql .= " LEFT JOIN dim_type dt ";
        $sql .= " ON s.store_type_id = dt.type_id ";
        $sql .= " ORDER BY s.reg_date DESC " . $limit_query;
        $query = $this -> db -> query($sql);
        $result = $query -> result();
        return $result;
    }
    function getStoreTotalCount() {

        $sql = "SELECT count(store_id) as total_count FROM ci_store " ;
        $query = $this -> db -> query($sql);
        $result = $query->row();
        return $result->total_count;
    }
    /**
     * 상점 상세보기 가져오기
     *
     * @param string $table 게시판 테이블
     * @param string $id 게시물 번호
     * @return array
     */
    function getView($store_id) {
        $sql = "SELECT * FROM ci_store WHERE store_id = '" . $store_id . "'";
        $query = $this -> db -> query($sql);
        // 게시물 내용 반환
        $result = $query -> row();
        return $result;

    }
    /**
     * 상점 수정
     *
     * @param array $arrays 테이블 명, 게시물 번호, 게시물 제목, 게시물 내용
     * @return boolean 성공 여부
     */
    function modifyStore($arrays) {
        $modify_array = array(
            'store_type_id' => $arrays['store_type_id'],
            'status_id' => $arrays['status_id'],
            'store_name' => $arrays['store_name'],
            'store_tel' => $arrays['store_tel']
        );
        $where = array(
            'store_id' => $arrays['store_id']
        );
        $result = $this->db->update('ci_store', $modify_array, $where);
        return $result;
    }
}