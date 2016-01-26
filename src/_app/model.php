<?php

class DB{

    private $db = null;

    function __construct(){

        $app = \Slim\Slim::getInstance();

        include_once BASE_PATH.'vendor/sparrow.php';

        $db = new Sparrow();
        $db->setDb(array(
            'type' => 'mysqli',
            'hostname' => $app->config['db']['hostname'],
            'database' => $app->config['db']['database'],
            'username' => $app->config['db']['username'],
            'password' => $app->config['db']['password']
        ));

        $db->sql('SET NAMES utf8')->execute();


        $this->db = $db;
        $app->db  = $db;
    }

    // создание аккаунта
    function create_account(){
        $app = \Slim\Slim::getInstance();
    }

    // авторизация
    function create_session(){
    }

    function get_user($user=false){

        
    }

    function user_id(){

        $account = $this->get_user();

        if($account)
            return $account['id'];

        return false;

    }
    function logout(){

    }

}
