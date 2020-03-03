<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller{
    public function __construct() {
        parent::__construct();

        $this->load->helper(array('url'));
        // $this->load->library('encrypt');

        define('SITE',site_url());
        define('ASSETS_SITE',base_url('assets'));
        define('ASSETS_CSS',base_url('assets/css'));
        define('ASSETS_IMG',base_url('assets/img'));
        define('ASSETS_JS',base_url('assets/js'));

        define('ERROR_INVALID_MODE','Error mode is invalid!!');
    }
    
    protected function render($template = '', $page_name = '', $content = null, $data = null, $assets_js = null){
        switch($template){
            case 'normal_page':
                $this->load->view('template/head');
                $this->load->view('template/sidebar');
                $date_header['page'] = $page_name;
                $this->load->view('template/header', $date_header);
                
                $this->load->view($content, $data);

                $view["assets_js"] = !is_null($assets_js) ? $this->js_asset($assets_js) : '';

                $this->load->view('template/foot');
                break;
            case 'blank_page':
                
                $this->load->view($content, $data);

                $view["assets_js"] = !is_null($assets_js) ? $this->js_asset($assets_js) : '';
                break;
        }
    }

    protected function check_permission($rankID, $roleID, $userID){

        if(is_null($rankID) || is_null($userID)){
            return false;
        }

        $result = $this->permission_model->check_permission($rankID, $roleID, $userID);

        return count($result) > 0 ? true : false;

    }

    public function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    protected function format_date_sql($date){
        $str = explode('/',$date);
        return $str[2]."-".$str[1]."-".$str[0];
    }

    protected function format_date($date){
        if(!is_null($date)){
            $str = explode('-',$date);
            return $str[2]."/".$str[1]."/".$str[0];
        }else{
            return '';
        }
    }

    function js_asset($asset_name) {
        return '<script src="' . ASSETS_JS . $asset_name . '"></script>';
    }

    function url_index($index){
        $str_url = uri_string();
        $str_url = explode('/', $str_url);
        return $str_url[$index];
        
    }

}