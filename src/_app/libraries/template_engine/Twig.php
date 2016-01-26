<?php
class Twig extends \Slim\View
{
    public function render($template, $data = null, $return = false)
    {

      $app = \Slim\Slim::getInstance();

      if(defined('IS_ADMIN'))
        $loader = new Twig_Loader_Filesystem(BASE_PATH.'/_app/templates/admin');
      else
        $loader = new Twig_Loader_Filesystem(BASE_PATH.'/_app/templates');

      $twig = new Twig_Environment($loader);

      $bundles = directory_map(BASE_PATH . '/_app/bundles');

      foreach ($bundles as $f) {

           $pi = mb_pathinfo($f);
           $type_name = explode('.', $pi['filename']);

           include_once BASE_PATH . '/_app/bundles/'.$f;


           if($type_name[0] == 'filter'){
             $twig->addFilter(new Twig_SimpleFilter($type_name[1], $type_name[1]));
           }

           if($type_name[0] == 'func'){
             $twig->addFunction(new Twig_SimpleFunction($type_name[1], $type_name[1]));
           }

           if($type_name[0] == 'test'){
             $twig->addTest(new Twig_SimpleTest($type_name[1], $type_name[1]));
           }
      }

      $req = $app->request;

      $data['request'] = $req;

      if(isset($_SESSION['phone']))
        $data['phone'] = $app->model->get_user($_SESSION['phone']);

      $data['config'] = $app->config;
      $data['user']   = $app->model->get_user();

      if(isset($_SESSION['slim.flash']))
        $data['flash'] = $_SESSION['slim.flash'];

      $data = array_merge($this->data->all(), (array) $data);

      if($return) return $twig->render($template, $data); else echo $twig->render($template, $data);
    }

    public function fetch($template, $data = null){
        return $this->render($template, $data, true);
    }

}
