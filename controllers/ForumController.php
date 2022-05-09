<?php

final class ForumController extends BaseController{
    public $params;
    protected $id;
    protected $page;
    public $error;

    public function __construct($params){
        $this->id = (isset($params[1]) ? $params[1] : 0);
        $this->model = new Forum();
        $this->head = array("title"=>"Forum");

        if(empty($params) || $params[0] == "") $this->view = "Forum/ForumMain";
        elseif($params[0] == "sekce"){
            if(isset($params[2]) && $params[2] == "add"){
                 $this->view = "Forum/TopicAdd";
                 $this->head = array("title"=>"Přidávání přispěvku - Forum");
            }
            else{
                $this->view = "Forum/ForumSection";
                $this->head = array("title"=>$this->getSectionInfo()[0]." - Forum");
            }
        }elseif($params[0] == 'topic'){
            if(isset($params[2]) && isset($params[3]) && isset($params[4]) && $params[2] == 'react' && is_numeric($params[3]) && is_numeric($params[4])){
                $this->react($params[3], $params[4]);
                exit();
            }
            else $this->view = "Forum/ForumTopic";
            $this->head = array("title"=>$this->getTopicInfo()["nazev"]." - Forum", "desc"=>mb_substr(strip_tags(html_entity_decode($this->getTopicInfo()["text"])), 0, 150, 'utf-8').'...');
        }
        else $this->view = "Chyba";

        $this->page = ((isset($params[2]) && is_numeric($params[2])) ? $params[2] : 0);
        if(isset($_SESSION['error_message'])) $this->error = $_SESSION['error_message'];
    }

    public function getSection($typeid = -1, $topics_per_page = 10){
        $query = ($typeid == -1 ? $this->model->getSections() : $this->model->getTopics($this->id, ($this->page > 0 ? $this->page-1 : 0), $topics_per_page));

        $section = array();
        if($query["status"])
            $section = $query["results"];
        else throw new Exception("Sekce nebyla nalezena.");

        return $section;
    }

    public function getTopics($section, $page, $topics_per_page, $limit = null, $order = null, $podrazeno = null){
        $query = $this->model->getTopics($section, $page, $topics_per_page, $limit, $order, $podrazeno);
        
        $topics = array();
        if($query["status"]) $topics = $query["results"];

        return $topics;
    }

    public function getLastComment($section = -1, $topic = -1){
        if($section == -1 && $topic == -1) return "x";

        $query = $this->model->lastComment($section, $topic);

        if($query["status"])
            return $query["result"];
        else return "x";
    }

    public function getSectionInfo($id = -1){
        if($id == -1) $id = $this->id;

        $query = $this->model->sectionInfo($id);

        if($query["status"])
            return $query["result"];
    }

    public function getTopicInfo($id = -1){
        if($id == -1) $id = $this->id;

        $query = $this->model->topicInfo($id);

        if($query["status"]){
            return $query["result"];
        }else throw new Exception("Nepodařily se načíst informace o tématu.");
    }

    public function getTopic($posts_per_page = 10){
        $query = $this->model->topicView($this->id, ($this->page > 0 ? $this->page-1 : 0), $posts_per_page);

        $topics = array();
        if($query["status"]){
                $topics = $query["results"];
            return $topics;
        }else throw new Exception("Téma nebylo nalezeno.");
    }

    public function getReactions($id){
        try{
            $reactions = $this->model->reactions($id);
            return $reactions;
        } catch (Exception $e){
            echo $e->getMessage();
            return;
        }
    }

    public function addComment($text){
        try{
            $user = User::getUser();
            if(!$user) throw new Exception("Nejste přihlášený.");

            $topic_info = $this->getTopicInfo();

            if(json_decode($topic_info["reply"])[0] != 0 && !in_array(User::getUser()["group"], json_decode($topic_info["reply"]))){
                throw new Exception("Nemáš oprávnění komentovat v této sekci.");
            } else $this->flash("Příspěvek přidán.", "success");

            if($topic_info["lock"] == 1) throw new Exception("Téma je zamknuté, nelze na něj již odpovídat.");

            $query = $this->model->addComment($this->id, $user["steam_steamid"], $topic_info["section"],'Re: '.$topic_info["nazev"], $text);
        } catch (Exception $e){
            $this->flash($e->getMessage(), "flash_error");
            return;
        }
    }

    public function editComment($id, $text, $edited_by){
        $edit = $this->model->editComment($id, $text, ["edited_by"=>$edited_by, "time"=>strtotime("now")]);

        if($edit){
            header("refresh:0");
            $this->flash("Příspěvek upraven", "flash_success");
        } else throw new Exception("Nepodařilo se upravit příspěvek.");
    }

    public function deleteComment($id){
        $query = $this->model->deleteComment($id);
        echo '<script>alert("Příspěvek smazán");</script>';
        $this->redirect('forum/');
    }

    public function lockTopic($id, $lock){
        $this->model->lockTopic($id, $lock);
    }

    public function addTopic($title, $text){
        $add = $this->model->addTopic($title, $text, $this->id);

        if($add["query"]){
            return $this->redirect("forum/topic/".$add["con"]->insert_id);
        }else throw new Exception("Při přidávání příspěvku došlo k chybě.");
    }

    public function react($reaction_id = 0, $post){
        $react = $this->model->sendReaction($reaction_id, $post);

        if($react) return true;
        else return false;
    }

}

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $forum = new ForumController(Router::select($_SERVER['REQUEST_URI'])["params"]);
    
    if(isset($_POST['topicadd'])){
        if(strlen(trim(html_entity_decode($_POST['title']))) < 10){
            $_SESSION['error_message'] = "Nadpis musí obsahovat minimálně 10 znaků";
            return;
        }else if(strlen(trim(html_entity_decode($_POST['text']))) < 30){
            $_SESSION['error_message'] = "Obsah tématu musí obsahovat minimálně 30 znaků";
            return;
        }else{
            $add = $forum->addTopic($_POST['title'], $_POST['text']); 
        }
    }

    if(isset($_POST['commentadd'])){
        if(strlen(trim(html_entity_decode($_POST['text']))) < 30){
            $_SESSION['error_message'] = 'Zpráva musí obsahovat nejméně 30 znaků.';
            return;
        }

        $add = $forum->addComment($_POST['text']);
    }

    if(isset($_POST['commentedit'])){
        try{
            if(strlen(trim(html_entity_decode($_POST['text']))) < 30) throw new Exception('Zpráva musí obsahovat nejméně 30 znaků.');
            
            $topic_info = $forum->getTopicInfo($_POST['commentedit']);

            if(User::isLoggedIn() && (User::isModerator(explode(",",$topic_info['moderator'])) || User::getUser()['steam_steamid'] == $topic_info['author'])){
                $edit = $forum->editComment($_POST['commentedit'], $_POST['text'], User::getUser()["steam_steamid"]);
            } else throw new Exception("Nepodařilo se upravit tento komentář.");
        } catch (Exception $e){
            $_SESSION['error_message'] = $e->getMessage();
            return;
        }
    }

    if(isset($_POST['edit'])){
        $forum->flash("Nelze upravit příspěvek v zamknutém tématu.", "flash_error");
    }

    if(isset($_POST['delete'])){
        try{
            $topic_info = $forum->getTopicInfo($_POST['delete']);

            if(User::isLoggedIn() && (User::isModerator(explode(",",$topic_info['moderator'])) || User::getUser()['steam_steamid'] == $topic_info['author'])){
                $delete = $forum->deleteComment($_POST['delete']);
            }
            else throw new Exception("Nemáte dostatečné oprávnění ke smazání tohoto příspěvku.");
        } catch (Exception $e){
            $_SESSION['error_message'] = $e->getMessage();
            return;
        }

    }

    if(isset($_POST['lock'])){
        try{
            $topic_info = $forum->getTopicInfo($_POST['lock']);

            if(User::isLoggedIn() && User::isModerator(explode(",",$topic_info['moderator']))){
                $lock = $forum->lockTopic($_POST['lock'], $topic_info['lock']);
            }else if(User::isLoggedIn() && User::getUser()['steam_steamid'] == $topic_info['author'] && $topic_info['lock'] == 0){
                $lock = $forum->lockTopic($_POST['lock'], $topic_info['lock']);
            }
            else throw new Exception("Nemáte dostatečné oprávnění k zamykání / odemykání tohoto příspěvku.");
        } catch (Exception $e){
            $forum->flash($e->getMessage(), "flash_error");
        }
    }
}

?>