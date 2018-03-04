<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: idea
 * Date: 2017-12-09
 * Time: 오후 10:44
 */

class Tasks extends CI_Controller {

    function __construct() {
        //CLI 를 통해서만 실행 가능!!
        if(is_cli()){
            parent::__construct();
            $this -> load -> database();
        }else{
            echo 'Executed only through CLI !!!';
            exit;
        }
    }

    /**
     * >php index.php tasks message
     * >php index.php tasks message "Ehchoi"
     * @param string $to
     */
    public function message($to = 'World')
    {
        echo "Hello {$to}!".PHP_EOL;
        self::writeLogMessage('debug',"Hello {$to}!");
    }

    public function scheduleDay(){
        if(self::job01() === true){
            self::writeLogMessage('debug', 'scheduleDay Job01 Success !!!');
            echo "scheduleDay Job01 Success !!!".PHP_EOL;
        }else{
            self::writeLogMessage('error', 'scheduleDay Job01 Fail . . .');
            echo "scheduleDay Job01 Fail . . .".PHP_EOL;
        }
    }

    private function job01(){
        $bRet = false;



        return $bRet;
    }

    public function writeLogMessage($level, $message){
        log_message($level,__METHOD__.'['.__LINE__.']::'.$message);
    }
}