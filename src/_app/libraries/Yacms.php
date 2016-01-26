<?php

use secondparty\Dipper\Dipper as Dipper;

class Yacms{
    
    public static function loadConfigs(){
        
        $settings = Yacms::getConfigFile('settings');
        
        return $settings;
    }
    
    public static function getConfigFile($name){
        
        if ( file_exists( BASE_PATH . '/_data/' . $name . '.yaml' ) ){
            return Dipper::parse(file_get_contents ( BASE_PATH . '/_data/' . $name . '.yaml' ));
        }
        
        return false;
        
    }
    
    public static function can_access($rules){
        
        $app = \Slim\Slim::getInstance();
        
        if(self::is_logged() == false) return false;
        
        $user = self::getUser();
        
        if($app->config['access'][$rules] <= $user['points']) return true;
        
        return false;
        
    }
    
    public static function login($data){
        $_SESSION['user'] = $data;
    }
    
    public static function register($data){
        
        $app = \Slim\Slim::getInstance();
        
        if($app->db->check_user_exists($data['identity']) == false){
            $app->db->register_user($data);
        }
        
        self::login($data);
    }
    
    public static function getUser(){
        
        $app = \Slim\Slim::getInstance();
        
        if(isset($_SESSION['user'])){
            return $app->db->get_user($_SESSION['user']['identity']);
        }
    }
    public static function is_logged(){
        
        if($_SESSION['user']){
            return true;
        }
        
    }
    
    
    
    
}