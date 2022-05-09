<?php

final class ChatlogController extends BaseController{
    public $page;

    public function __construct($params){
        $this->view = "Chatlog/Main";
        $this->head = array('title'=>"Chatlog");
        $this->model = new Chatlog;

        if(isset($params[0]) && $params[0] == "messages"){
            if(isset($params[1]) && is_numeric($params[1])) $this->page = $params[1];
            else $this->page = 1;

            echo json_encode($this->getMessages(isset($params[2]) ? $params[2] : 20));
            exit();
        }else if(isset($params[0]) && is_numeric($params[0])) $this->page = $params[0];
        else $this->page = 1;
    }

    public function getMessages($limit = 20){
        return $this->model->getMessages($limit, $this->page-1);
    }

}