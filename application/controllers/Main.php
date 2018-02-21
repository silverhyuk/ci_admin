<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: idea
 * Date: 2017-12-09
 * Time: 오후 10:44
 */

class Main extends MY_Controller {

    function __construct() {
        parent::__construct();
        parent::_require_login();
        $this -> load -> database();
    }
    public function index(){
        $this->load->template('main_v');
    }


}