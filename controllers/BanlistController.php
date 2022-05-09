<?php

final class BanlistController extends BaseController {
    private $type;
    private $id;
    protected $page;
    protected $user;
    public $params;

    public function __construct($params){
        $this->params = $params;
        
        if(isset($params[0]) && $params[0] == 'detail'){
            if(isset($params[1]) && isset($params[2])){
                $this->type = $params[1];
                $this->id = $params[2];
            }
            else throw new Exception("Neplatný typ blokace."); 
            $this->view = "Banlist/Detail";
             $this->head = array("title"=>"Detail banu - Banlist");
        }
        elseif(isset($params[0]) && $params[0] == "add" && User::isModerator([1, 2])){
            $this->view = "Banlist/Add";
            $this->head = array("title"=>"Přidat ban");
            $this->scripts = ["/js/Admin/admin.js?v=".strtotime("now")];
        }
        elseif(isset($params[0]) && $params[0] == 'ctbans'){
            if(isset($params[1]) && is_numeric($params[1])){
                $this->page = $params[1];
            }elseif(isset($params[1]) && $params[1] == "user"){
                if(isset($params[2]) && is_numeric($params[2])) $this->user = $params[2];
                else $this->redirect('banlist/ctbans');

                if(isset($params[3]) && is_numeric($params[3])) $this->page = $params[3];
                else $this->page = 1;
            }
            else $this->page = 1;

            $this->head = array('title'=>'Banlist - CT bany');
            $this->view = "Banlist/CTBans";
        }
        elseif(isset($params[0]) && $params[0] == 'bans'){
            if(isset($params[1]) && is_numeric($params[1])){
                $this->page = $params[1];
            }elseif(isset($params[1]) && $params[1] == "user"){
                if(isset($params[2]) && is_numeric($params[2])) $this->user = $params[2];
                else $this->redirect('banlist/bans');

                if(isset($params[3]) && is_numeric($params[3])) $this->page = $params[3];
                else $this->page = 1;
            }
            else $this->page = 1;

            $this->head = array('title'=>'Banlist - Bany');
            $this->view = "Banlist/Bans";
        }
        elseif(isset($params[0]) && $params[0] == 'comms'){
            if(isset($params[1]) && is_numeric($params[1])){
                $this->page = $params[1];
            }elseif(isset($params[1]) && $params[1] == "user"){
                if(isset($params[2]) && is_numeric($params[2])) $this->user = $params[2];
                else $this->redirect('banlist/comms');

                if(isset($params[3]) && is_numeric($params[3])) $this->page = $params[3];
                else $this->page = 1;
            }
            else $this->page = 1;

            $this->head = array('title'=>'Banlist - Umlčení');
            $this->view = "Banlist/Comms";
        }     
        else{
            $this->view = "Banlist/Main";
            $this->head = array("title"=>"Banlist - Hlavní stránka");
            $this->page = 1;
        }
        $this->model = new Banlist;
    }

    public function getCTBans($limit = 10){
        try{
            $ctbans = $this->model->getCTBans($limit, $this->page, null, [], !empty($this->user) ? $this->user : null);

            return $ctbans;
        } catch (Exception $e){
            echo "<div class=\"error\">".$e->getMessage()."</div>";
            return [];
        }
    }

    public function getBans($limit = 10){
        try{
            $bans = $this->model->getBans($limit, $this->page, "WHERE `unban` = 0", [], !empty($this->user) ? $this->user : null);

            return $bans;
        } catch (Exception $e){
            echo "<div class=\"error\">".$e->getMessage()."</div>";
            return [];
        }
    }

    public function getComms($limit = 10){
        try{
            $comms = $this->model->getComms($limit, $this->page, "WHERE `removed` = 0", [], !empty($this->user) ? $this->user : null);

            return $comms;
        } catch (Exception $e){
            echo "<div class=\"error\">".$e->getMessage()."</div>";
            return [];
        }
    }

    public function getDetail($id = -1){
        try{
            if($id == -1) $id = $this->id;

            $types = array('ctban'=>'sm_ctlocks', 'ban'=>'sm_bans', 'comm'=>'sm_comms');

            $detail = $this->model->getDetail($id, $types[$this->type]);

            return $detail;
        } catch (Exception $e){
            $this->flash($e->getMessage(), "flash_error");
            header("Location: /");
            exit();
        }
    }

    public function edit($rounds_actual, $rounds_given, $expire, $reason){
        $types = array('ctban'=>'sm_ctlocks', 'ban'=>'sm_bans', 'comm'=>'sm_comms');

        return $this->model->editBan($this->id, $rounds_actual, $rounds_given, $expire, $reason, $types[$this->type]);
    }

    public function delete(){
        $types = array('ctban'=>'sm_ctlocks', 'ban'=>'sm_bans', 'comm'=>'sm_comms');

        return $this->model->deleteBan($this->id, $types[$this->type]);
    }

    public function add($accountid, $reason, $type, $comm_type = null, $rounds = null, $expire = null, $admin){
        return $this->model->addBan($accountid, $reason, $type, $comm_type, $rounds, $expire, $admin);
    }

}

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $banlist = new BanlistController(Router::select($_SERVER['REQUEST_URI'])["params"]);

    if(isset($_POST['edit'])){
        if(!User::isModerator([1,2])) throw new Exception("Nemáš oprávnění pro tuto akci.");
        try{
            $edit = $banlist->edit(
                (isset($_POST['rounds_actual']) ? $_POST['rounds_actual'] : null), 
                (isset($_POST['rounds_given']) ? $_POST['rounds_given'] : null),
                (isset($_POST['expire']) ? strtotime($_POST['expire']) : null),
                $_POST['reason']
            );
            if($edit) $banlist->flash("Ban upraven", "flash_success");
            else throw new Exception("Nepodařilo se upravit ban.");
        } catch (Exception $e){
            $banlist->flash($e->getMessage(), "flash_error");
        }
    }

    if(isset($_POST['delete'])){
        try{
            if(!User::isModerator([1,2])) throw new Exception("Nemáš oprávnění pro tuto akci.");

            if($banlist->delete()) $banlist->redirect('banlist');
            else throw new Exception("Nepodařilo se odebrat ban.");
        } catch (Exception $e){
            $banlist->flash($e->getMessage(), "flash_error");
        }

    }

    if(isset($_POST["ban_add"])){
        try{
            if(!User::isModerator([1,2])) throw new Exception("Nemáš oprávnění pro tuto akci.");
            $ban = $banlist->add(User::steamid64_to_accountid(User::toCommunityId($_POST["steamid"])), $_POST["reason"], $_POST["type"], $_POST["comm_type"], $_POST["rounds"], $_POST["expire"], User::steamid64_to_accountid($_SESSION["steamid"]));
        
            if($ban) $banlist->flash("Ban přidán.", "flash_success");
        } catch (Exception $e){
            $banlist->flash($e->getMessage(), "flash_error");
        }
    }
}

?>