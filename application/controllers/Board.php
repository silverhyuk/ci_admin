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
     * http://127.0.0.1/bbs/board/lists/ci_board/page/5
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
        $search_word = $page_url = '';
        $uri_segment = 5;

        // 주소 중에서 q(검색어) 세그먼트가 있는 지 검사하기 위해 주소를 배열로 반환
        $uri_array = $this -> segment_explode($this -> uri -> uri_string());

        if (in_array('q', $uri_array)) {
            // 주소에 검색어가 있을 경우 처리
            $search_word = urldecode($this -> url_explode($uri_array, 'q'));

            // 페이지네이션 용 주소
            $page_url = '/q/' . $search_word;

            $uri_segment = 7;
        }


        /*페이지 네이션 설정*/
        $this->load->library('pagination');
        // 페이징 주소
        $config['base_url'] = '/bbs/board/lists/ci_board'. $page_url .'/page/';
        // 게시물 전체 개수
        $config['total_rows'] = $this->board_m->get_list($this->uri->segment(3), 'count', '', '', $search_word);
        // 한 페이지에 표시할 게시물 수
        $config['per_page'] = 5;
        // 페이지 번호가 위치한 세그먼트
        $config['uri_segment'] = $uri_segment;


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
        $page = $this->uri->segment($uri_segment, 1);
        if ($page > 1) {
            $start = (($page / $config['per_page'])) * $config['per_page'];
        } else {
            $start = ($page - 1) * $config['per_page'];
        }
        $limit = $config['per_page'];
        $data['list'] = $this->board_m->get_list($this->uri->segment(3), '', $start, $limit);


        $this->load->template('board/list_v', $data);
    }

    /**
     * 게시물 보기
     */
    function view() {
        // 게시판 이름과 게시물 번호에 해당하는 게시물 가져오기
        $data['views'] = $this -> board_m -> get_view($this -> uri -> segment(3), $this -> uri -> segment(4));

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

            $uri_array = $this->segment_explode($this->uri->uri_string());

            if ( in_array('page', $uri_array)) {
                $pages = urldecode($this->url_explode($uri_array, 'page'));
            } else {
                $pages = 1;
            }

            if ( $this->input->post('subject', TRUE)===FALSE AND $this->input->post('contents', TRUE)===FALSE) {
                alert('비정상적인 접근입니다.', '/bbs/board/lists/'.$this->uri->segment(3).'/page/'.$pages);

                exit;
            }


            $modify_data = array(
                'table' => $this->uri->segment(3),
                'board_id' => $this->uri->segment(5),
                'subject' => $this->input->post('subject', TRUE),
                'contents' => $this->input->post('contents', TRUE)
            );

            $result = $this->board_m->modify_board($modify_data);

            if ( $result ) {

                alert('수정되었습니다.', '/bbs/board/lists/'.$this->uri->segment(3).'/page/'.$pages);
                exit;
            } else {
                alert('다시 수정해 주세요.', '/bbs/board/view/'.$this->uri->segment(3).'/board_id/'.$this->uri->segment(5).'/page/'.$pages);
                exit;
            }
        } else {
            $data['views'] = $this->board_m->get_view($this->uri->segment(3), $this->uri->segment(5));

            $this->load->template('board/modify_v', $data);
        }
    }


    /**
     * url 중 키 값을 구분하여 값을 가져오도록
     *
     * @param Array $url : segment_explode 한 url 값
     * @param String $key :  가져오려는 값의 key
     * @return String $url[$k] : 리턴 값
     */

    function url_explode($url, $key) {
        $cnt = count($url);

        for ($i = 0; $cnt > $i; $i++) {
            if ($url[$i] == $key) {
                $k = $i + 1;
                return $url[$k];
            }
        }
    }

    /**
     * HTTP의 URL을 "/"를 Delimiter로 사용하여 배열로 바꿔 리턴한다.
     *
     * @param String 대상이 되는 문자열
     * @return string[]
     */
    function segment_explode($seg) {
        // 세그먼트 앞 뒤 "/" 제거 후 uri를 배열로 반환
        $len = strlen($seg);

        if (substr($seg, 0, 1) == '/') {
            $seg = substr($seg, 1, $len);
        }
        $len = strlen($seg);

        if (substr($seg, -1) == '/') {
            $seg = substr($seg, 0, $len - 1);
        }
        $seg_exp = explode("/", $seg);
        return $seg_exp;
    }
}