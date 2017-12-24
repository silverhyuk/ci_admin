<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: idea
 * Date: 2017-12-09
 * Time: 오후 10:44
 */

class Board extends MY_controller {
    function __construct() {
        parent::__construct();
        $this -> load -> database();
        $this -> load -> model('board_m');
        $this -> load -> helper(array('url','date'));
        $this->output->enable_profiler(TRUE);
    }

    /**
     * 주소에서 메서드가 생략되었을 때 실행되는 기본 메서드
     * http://127.0.0.1/bbs/board/lists/ci_board/page/5
     * http://127.0.0.1/bbs/board/lists?table=ci_board
     */
    public function index() {
        $this -> lists();
    }
    /**
     * 목록 불러오기
     */
    public function lists()
    {

        // 검색어 초기화
        $search_word = $this->input->get('search_word');
        $table = $this->input->get('table');

        /*페이지 네이션 설정*/
        $this->load->library('pagination');
        // 페이징 주소
        $config['base_url'] = site_url('/board/lists').'?table='.$table ;
        // 게시물 전체 개수
        $config['total_rows'] = $this->board_m->get_list($table, 'count', '', '', $search_word);
        // 한 페이지에 표시할 게시물 수
        $config['per_page'] = 5;
        // 페이지 번호가 위치한 세그먼트
        $config['page_query_string'] = TRUE;


        //페이징 디자인 변경
        $config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
        $config['full_tag_close'] = '</ul>';
        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';


        // 페이지네이션 초기화
        $this->pagination->initialize($config);
        // 페이지 링크를 생성하여 view에서 사용하 변수에 할당
        $data['pagination'] = $this->pagination->create_links();

        // 게시물 목록을 불러오기 위한 offset, limit 값 가져오기
        $per_page = $this->input->get('per_page');
        if($this->input->get('per_page') ===false){
            $per_page = 1;
        }
        if ($per_page > 1) {
            $start = (($per_page / $config['per_page'])) * $config['per_page'];
        } else {
            $start = 0;
        }
        $limit = $config['per_page'];
        $data['list'] = $this->board_m->get_list($table, '', $start, $limit);
        $data['table'] = $table;
        $data['per_page'] = $per_page;

        $this->load->template('board/list_v', $data);
    }

    /**
     * 게시물 보기
     */
    function view() {
        // 게시판 이름과 게시물 번호에 해당하는 게시물 가져오기

        $table = $this->input->get('table');
        $board_id = $this->input->get('board_id');
        $per_page = $this->input->get('per_page');
        if($per_page === false){
            $per_page = 0;
        }
        $data['views'] = $this -> board_m -> get_view($table, $board_id);
        $data['table'] = $table;
        $data['board_id'] = $board_id;
        $data['per_page'] = $per_page;

        // view 호출
        $this -> load -> template('board/view_v', $data);
    }

    /**
     * 게시물 수정
     */
    function modify() {
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

        if ( $_POST ) {
            $this -> load -> helper('alert');

            $table = $this->input->get('table');
            $board_id = $this->input->get('board_id');
            $per_page = $this->input->get('per_page');

            if ( $this->input->post('subject', TRUE)===FALSE AND $this->input->post('contents', TRUE)===FALSE) {
                alert('비정상적인 접근입니다.', site_url('/board/lists').'?table='.$table.'&per_page='.$per_page);

                exit;
            }

            $modify_data = array(
                'table' => $table,
                'board_id' => $board_id,
                'subject' => $this->input->post('subject', TRUE),
                'contents' => $this->input->post('contents', TRUE)
            );

            $result = $this->board_m->modify_board($modify_data);

            if ( $result ) {

                alert('수정되었습니다.', site_url('/board/lists').'?table='.$table.'&per_page='.$per_page);
                exit;
            } else {
                alert('다시 수정해 주세요.', site_url('/board/lists').'?table='.$table.'&per_page='.$per_page.'&board_id='.$board_id);
                exit;
            }
        } else {
            $table = $this->input->get('table');
            $board_id = $this->input->get('board_id');
            $data['views'] = $this->board_m->get_view($table, $board_id);

            $this->load->template('board/modify_v', $data);
        }
    }
}