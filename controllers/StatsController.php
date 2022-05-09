<?php

final class StatsController extends BaseController{
    protected $user;
    public $params;

    public function __construct($params){
        if(isset($params[0]) && $params[0] == "top"){
            $this->view = "Stats/Top";
            $this->head = array("title"=>"TOP statistiky");
        }  
        else if(isset($params[0]) && $params[0] == "search"){
            if(!isset($params[1])) return false;
            else $this->findUsers($params[1]);    
        }
        else if(isset($params[0]) && !empty($params[0])) {
            $this->user = $params[0];
            $this->view = "Stats/User";
            $this->head = array("title"=>"Statistiky");
        } else throw new Exception("Uživatel nebyl nalezen.");

        $this->model = new Stats;
        $this->page = (isset($params[1]) && is_numeric($params[1])) ? $params[1] : 1;
    }

    public function findUsers($name){
        echo json_encode(User::getUserByName(1, $name));
        exit();
    }

    public function getStats($user = null){
        if($user == null) $user = $this->user;

        $find = $this->model->findUser($user);

        return $find;
    }

    public function getUserNicknameHistory($user = null){
        if($user == null) $user = $this->user;

        $lookfor = 0;

        if(is_numeric($user)) $lookfor = User::steamid64_to_accountid($user);
        else $lookfor = User::steamid64_to_accountid(User::getUserById($user)['steamid']);

        return $this->model->nicknameHistory($lookfor);
    }

    public function getUserJoinHistory($user = null){
        if($user == null) $user = $this->user;

        $lookfor = 0;

        if(is_numeric($user)) $lookfor = User::steamid64_to_accountid($user);
        else $lookfor = User::steamid64_to_accountid(User::getUserById($user)['steamid']);

        return $this->model->joinHistory($lookfor);
    }

    public function getTopStatsCount(){
        return $this->model->topStatsCount();
    }

    public function getTopStats($limit = 50, $search = null, $vip = "off", $order = "score", $t = "DESC"){
        $page = $this->page;
        if(isset($_GET['order'])) $order = $_GET['order'];
        if(isset($_GET['desc'])) $t = $_GET['desc'] == "false" ? "asc" : "desc"; 

        $query = $this->model->topStats($limit, $search, $vip, $order, $t, $page);

        $users = array();
        $steamids = array();

        if($query["status"]) foreach($query["results"] as $row){
            $communityid = User::toCommunityID($row['steamid']);
            $users[$communityid] = $row;
            $steamids[] = $communityid;
        }

        $steamapi = new Steam;
        $getdata = $steamapi->getData($steamids);
        
        for($i = 0; $i < count($getdata); $i++){
            $users[$getdata[$i]->steamid]['avatar'] = $getdata[$i]->avatar;
        }

        return $users;
    }


}

?>