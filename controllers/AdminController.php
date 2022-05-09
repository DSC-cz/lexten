<?php

final class AdminController extends BaseController{

    public function __construct($params){
        if(!User::isLoggedIn()) $this->redirect('?login');
        else if(!User::isModerator([1]) && $params[0] !== "finduser"){
            $this->flash("Nemáš oprávnění vstoupit do této sekce", "flash_error");
            $this->redirect('');
        }

        $this->head = ["title"=>"Administrace"];
        $this->model = new Admin();
        $this->scripts = ["/js/Admin/admin.js?v=".strtotime("now")];

        if(isset($params[0]) && !empty($params[0])){
            if($params[0] == "finduser"){
                if(strlen($params[1]) < 3) die("Zadej alespoň 3 znaky");

                try{
                    $users = $this->findUser($params[1]);
                } catch (Exception $e){
                    echo $e->getMessage();
                    exit();
                }

                foreach($users as $user){
                    echo '<li data-steamid="'.$user["steamid"].'" class="result_action">'.$user["name"].'</li>';
                }
                echo '<script src="/js/Admin/search.js?v='.strtotime("now").'"></script>';
                exit();
            }
            $this->view = "Admin/".$params[0];
        } else $this->view = "Admin/Main";

    }

    public function findUser($name){
        $query = $this->model->findUser($name);

        $users = [];

        if($query["status"]) return $query["results"];
        else throw new Exception($query["errors"]);

        return $users;
    }

    public function setVIP($steamid, $expire){
        return $this->model->setVIP($steamid, $expire);
    }

    public function getRoles(){
        $q = $this->model->getRoles();

        $roles = [];

        if($q["status"]) return $q["results"];

        return $roles;
    }

    public function setRole($steamid, $communityid, $name, $group){
        return $this->model->setRole($steamid, $communityid, $name, $group);
    }
}

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $c = new AdminController(Router::select($_SERVER['REQUEST_URI'])["params"]);

    if(isset($_POST['setvip'])){
        try{
            if(empty($_POST["steamid"])) throw new Exception("Nelze přidělit VIP bez zadané SteamID");
            $set = $c->setVIP($_POST['steamid'], strtotime($_POST['expire']));

            if($set) $c->flash("VIP bylo úspěšně přiděleno hráči ".$_POST['name'].' do '.date("d.m.Y H:i:s", strtotime($_POST['expire'])), 'success');
            else throw new Exception("Nelze přidělit VIP bez zadané SteamID");
        } catch (Exception $e){
            $c->flash($e->getMessage(), "flash_error");
        }
    }

    if(isset($_POST['setgroup'])){
        try{
            if(empty($_POST["steamid"])) throw new Exception("Nelze přidělit VIP bez zadané SteamID");
            $set = $c->setRole($_POST['steamid'], User::toCommunityID($_POST['steamid']), $_POST['name'], $_POST['group']);

            if($set) $c->flash("Role byla úspěšně přidělena hráči ".$_POST['name'].' (ID role: '.$_POST['group'].')', 'success');
            else throw new Exception("Nepodařila se přidělit role hráči");
        } catch (Exception $e){
            $c->flash($e->getMessage(), "flash_error");
        }
    }
}

?>
