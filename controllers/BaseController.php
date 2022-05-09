<?php

class BaseController {
    protected $data = array();
    protected $name = "LEXTEN.cz";
    protected $head = array("title" => "", "desc" => "");
    protected $view;
    protected $model;
    protected $links = [];
    protected $scripts = [];
    public $tinymce_code = false;
    public $db;

    public function __construct(){
        global $databases;
        $this->db = &$databases;
    }

    public function getMainSettings($type){
        $query = $this->db[0]->get_row("SELECT `hodnota` FROM `main_settings` WHERE `nazev`=:nazev", [":nazev"=>$type]);

        if($query["status"]) return $query["result"]['hodnota'];
        else return "Unknown";
    }

    public function flash($message, $type = null){
        if(isset($type)) $_SESSION['flash_type'] = $type;

        $_SESSION['flash_message'] = $message;
    }

    public function links(){
        foreach($this->links as $link){
            echo '<link rel="stylesheet" type="text/css" href="'.$link.'">';
        }
    }

    public function scripts(){
        foreach($this->scripts as $script){
            echo '<script type="text/javascript" src="'.$script.'"></script>';
        }
    }

    public function returnView(){
        extract($this->data);
        require("views/" . $this->view . ".phtml");
    }

    public function returnInfo(){
        return array("name"=>$this->name, "head"=>$this->head);
    }

    public function redirect($path){
        header("Location: /$path");
        header("Connection: close");
        exit;
    }

    public function upload($folder, $file, $name){
        if(empty($file["tmp_name"])) return;
        $type = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $path = $folder.basename($name).'.'.$type;

        if(move_uploaded_file($file["tmp_name"], $path)) return $path;
        else throw new Exception("Nepodařilo se nahrát soubor ");
    }

    private function Menu_drop_items($id){
        global $databases;
        $query = $databases[0]->get_results("SELECT * FROM `menu` WHERE `show`=:id", [":id"=>$id]);

        $items = array();

        if($query["status"])
            foreach($query["results"] as $row){
                $replace = ["[communityid]"];
                $to = [(User::isLoggedIn() ? User::getUser()["steamid"] : 0)];
                $row['odkaz'] = str_replace($replace, $to, $row['odkaz']);
                $items[] = $row;
            }

        return $items;
    }

    public function Menu($limit = 10){
        global $databases;
        $query = $databases[0]->get_results("SELECT *, (SELECT COUNT(*) FROM `menu` n WHERE m.id = n.show) as `count` FROM `menu` m WHERE `show`=1 ORDER BY `id` LIMIT $limit", []);
        $menu = array();

        if($query["status"]){
            foreach($query["results"] as $row){
                $replace = ["[communityid]"];
                $to = [(User::isLoggedIn() ? User::getUser()["steamid"] : 0)];
                $row['odkaz'] = str_replace($replace, $to, $row['odkaz']);
                if($row['count'] == 0) $row['drop'] = false;
                else{
                    $row['drop'] = true;
                    $row['drop_items'] = $this->Menu_drop_items($row['id']);
                }
                $menu[] = $row;
            }
        }

        return $menu;
    }

    public function Footer_Menu($limit = 6){
        global $databases;
        $query = $databases[0]->get_results("SELECT * FROM `odkazy` WHERE `position`=2 LIMIT $limit", []);

        $menu = array();

        if($query["status"]) return $query["results"];

        return $menu;
    }

    public function Slider($limit = 10){
        global $databases;
        $query = $databases[0]->get_results("SELECT * FROM `banner` WHERE `bannerimg` LIKE 'http%' ORDER BY `id` LIMIT $limit", []);

        $items = array();

        if($query["status"]) return $query["results"];

        return $items;
    }

    public function getUser(){
        return User::getUser();
    }

    public function getLastConnectedUsers(){
        return User::lastConnected(5);
    }

    public function getURLSlash($slash_count){
        $rurl = array_filter(explode("/",$_SERVER['REQUEST_URI']));
        $link = '';
        for($i = 1; $i <= $slash_count; $i++) $link.="/".$rurl[$i];

        return $link;
    }

    public function pageCount($db, $table, $where, $where_params, $rows_op){
        try{
            global $databases;
            $query = $databases[$db]->get_row("SELECT COUNT(*) as `count` FROM `$table` WHERE $where", $where_params);
            $rows = 0;

            if($query["status"])
                $rows = $query["result"]['count'];

            return $rows == 0 ? 0 : ceil($rows / $rows_op);
        } catch (Exception $e){
            return 0;
        }
    }

    public function pagination($db, $table, $where, $where_params = [], $rows_op, $actual_page, $url_slash_count = -1, $custom_url = ""){
        if($url_slash_count == -1) $link = $_SERVER['REQUEST_URI'];
        else{
            $rurl = array_filter(explode("/",$_SERVER['REQUEST_URI']));
            $link = '';
            for($i = 1; $i <= $url_slash_count; $i++) $link.="/".explode("?", $rurl[$i])[0];
        }

        if(!empty($custom_url)) $link='/'.$custom_url;

        if(isset(explode("?", $_SERVER['REQUEST_URI'])[1])) $get = explode("?", $_SERVER['REQUEST_URI'])[1];
        else $get = null;

        $count = 1;
        if(!is_numeric($table)) $count = $this->pageCount($db, $table, $where, $where_params, $rows_op);
        else $count = ceil($table/$rows_op);

        if($count == 1) return;

        if($actual_page == 0) $actual_page++;

        if($actual_page > 4) echo '<a href="'.$link.'/1'.(isset($get) ? '?'.$get : '').'" class="btn btn-primary">1</a> ... ';

        for($i = $actual_page - 3; $i < $actual_page; $i++){
            if($i < 1) continue;
            echo '<a href="'.$link.'/'.$i.''.(isset($get) ? '?'.$get : '').'" class="btn btn-primary">'.$i.'</a> ';
        }

        for($i = $actual_page; $i < $actual_page + 4; $i++){
            if($i > $count) break;
            echo '<a href="'.$link.'/'.$i.''.(isset($get) ? '?'.$get : '').'" class="btn '.($i == $actual_page ? 'btn-dark' : 'btn-primary').'">'.$i.'</a> ';
        }
        if($count - $actual_page > 3) echo '... <a href="'.$link.'/'.$count.''.(isset($get) ? '?'.$get : '').'" class="btn btn-primary">'.$count.'</a>';
    }
}
