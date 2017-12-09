<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: idea
 * Date: 2017-12-09
 * Time: 오후 10:44
 */

class Main extends CI_Controller {

    public function index(){
        $this->load->template('main_v');
    }


}