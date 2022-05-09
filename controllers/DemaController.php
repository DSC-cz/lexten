<?php

final class DemaController extends BaseController{
    public function __construct($params){
        $this->view = "Dema/Main";
        $this->head = array("title"=>"Dema");
        $this->links = ["/css/dems.css?v=".strtotime("now")];
    }
}


?>