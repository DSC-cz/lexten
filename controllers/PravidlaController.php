<?php

final class PravidlaController extends BaseController {
    public function __construct($params){
        if(isset($params[0]) && $params[0] == "edit" && User::isLoggedIn() && User::isModerator(arraY(1))) $this->view = "Pravidla/Edit";
        else $this->view = "Pravidla/Main";
        $this->head = array("title"=>"Pravidla");
        $this->scripts = ["js/pravidla.js?v=".strtotime("now")];
        $this->tinymce_code = true;
    }
}

if($_SERVER['REQUEST_METHOD'] == "POST"){
    if(isset($_POST['edit_rules'])){

        $file = fopen("views/Pravidla/pravidla.html", "w");

        if (fwrite($file, $_POST['content']) === FALSE) {
            throw new Exception("Nepodařilo se uložit soubor.");
            exit;
        }

        fclose($file);

        header("Location: /pravidla");
    }
}

?>