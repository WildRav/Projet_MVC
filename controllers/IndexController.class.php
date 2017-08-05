<?php

class IndexController{

    public function indexAction($params){

        $user = new User();
        $user = $user->populate(['id' => 1]);

        echo '<pre>';
        print_r($user);
        echo '</pre>';

        $pseudo = 'prof';
        $view = new View();
        $view->assign('pseudo', $pseudo);
        $view->assign('form', $user->getForm());
    }
}
