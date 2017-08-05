<?php

class View
{

    protected $view;
    protected $template;
    protected $data = [];

    public function __construct($view = 'index', $template = 'frontend')
    {

        $this->setView($view);
        $this->setTemplate($template);
    }

    public function setView($view)
    {
        $path = 'views/' . $view . '.view.php';
        if (!file_exists($path)) {
            die('View not found');
        }

        $this->view = $view . '.view.php';
    }

    public function setTemplate($template)
    {
        $path = 'views/' . $template . '.temp.php';
        if (!file_exists($path)) {
            die('Template not found');
        }

        $this->template = $template . '.temp.php';
    }

    public function assign($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function includeModal($modal, $config)
    {
        if (!file_exists('views/modals/' . $modal . '.mod.php')) {
            die("Le modal n'existe pas");
        }
        include "views/modals/" . $modal . ".mod.php";
    }

    public function __destruct()
    { // = renderer
        extract($this->data);

        include 'views/' . $this->template;
    }

}
