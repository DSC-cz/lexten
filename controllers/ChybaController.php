<?php

    final class ChybaController extends BaseController{
        public function __construct(){
            $this->head = array("title"=>"Stránka nenalezena");
            $this->view = "Chyba";
        }
    }

?>