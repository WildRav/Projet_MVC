<?php

class Routing
{

    private $uriExploded;

    private $controller;
    private $controllerName;

    private $action;
    private $actionName;

    private $params;

    public function __construct()
    {
        $uri = $_SERVER["REQUEST_URI"];
        $uri = preg_replace("#" . BASE_PATH_PATTERN . "#i", "", $uri, 1);
        $uri = trim($uri, "/");

        $this->uriExploded = explode("/", $uri);

        $this->setController();
        $this->setAction();
        $this->setParams();

        $this->runRoute();
    }

    public function setController()
    {
        $requested = $this->uriExploded[0];

        $this->controller = (empty($requested)) ? "Index" : ucfirst($requested);
        $this->controllerName = $this->controller . "Controller";

        unset($this->uriExploded[0]);
    }

    public function setAction()
    {
        if (isset($this->uriExploded[1])) {
            $requested = $this->uriExploded[1];
        }

        $this->action = (empty($requested)) ? "index" : $requested;
        $this->actionName = $this->action . "Action";

        unset($this->uriExploded[1]);
    }

    public function setParams()
    {
        $this->params = array_merge(array_values($this->uriExploded), $_POST);
    }

    public function checkRoute()
    {
        $controllerPath = "controllers/" . $this->controllerName . ".class.php";
        if (!file_exists($controllerPath)) {
            return false;
        }
        include $controllerPath;

        if (!class_exists($this->controllerName)) {
            return false;
        }

        if (!method_exists($this->controllerName, $this->actionName)) {
            return false;
        }

        return true;
    }

    public function runRoute()
    {
        if ($this->checkRoute()) {
            $controller = new $this->controllerName();
            $controller->{$this->actionName}($this->params);
        } else {
            $this->page404();
        }
    }

    public function page404()
    {
        die("Error 404");
        // TODO
    }

}

// TODO : Fix url when uppercase
