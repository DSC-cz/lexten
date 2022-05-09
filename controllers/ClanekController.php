<?php

    final class ClanekController extends BaseController{
        public $params;
        protected $page;
        protected $clanek;
        protected $model;

        public function __construct($params){
            $this->model = new Clanek();
            $this->params = $params;

            if(empty($params[0])) throw new Exception("Stránka nenalezena");
            elseif($params[0] == "archiv"){
                $this->head = array("title"=>"Archiv článků");
                $this->view = "Clanky/ClanekArchiv";
                if(!empty($params[1])) $this->page = $params[1];
            }
            elseif($params[0] == "add"){
                if(!User::isModerator([1,2])) throw new Exception("Nemáš dostatečné oprávnění k této akci");
                $this->view = "Clanky/ClanekAdd";
                $this->head = array('title'=>'Přidání článku');
            }
            else{
                $this->clanek = $params[0];
                if(isset($params[1]) && $params[1] == 'edit' && User::isModerator([1,2])) $this->view = "Clanky/ClanekEdit";
                else $this->view = "Clanky/Clanek";
                $this->head = array('title'=>$this->selectArticle(false)['nazev'].' - Článek', 'desc'=>mb_substr(strip_tags($this->selectArticle(false)['sub']), 0, 150, 'utf-8').'...');

                $links = glob('css/shortcodes/*.css');
                foreach($links as $link)
                    $this->links[] = '/'.$link;

                $shortcodes = glob('js/shortcodes/*.js');
                foreach($shortcodes as $script)
                    $this->scripts[] = '/'.$script;
            }
        }

        public function selectNews($limit){
            $news = $this->model->selectNews($limit, ($this->page > 0 ? $this->page-1 : 0));

            return $news;
        }

        public function selectArticle($count_views = true){
            try{
                $article = $this->model->selectArticle($this->clanek, $count_views);
            } catch (Exception $e){
                $this->flash($e->getMessage(), "flash_error");
            }
            if(isset($this->params[1]) && $this->params[1] == "edit") return $article;

            preg_match_all('/\[([^[]*):([^[]*)\]/i', $article['text'], $matches);

            for($i = 0; $i < count($matches[1]); $i++){
                try{
                    $model_name = ucfirst($matches[1][$i]).'Shortcode';
                    if(!class_exists($model_name)) throw new Exception("Neplatný Class pro shortcode [".$matches[1][$i].":".$matches[2][$i]."].");
                    $model = new $model_name;
                    $article['text'] = str_replace("[".$matches[1][$i].":".$matches[2][$i]."]", $model->render($matches[2][$i]), $article['text']);
                } catch (Exception $e){
                    $article['text'] = str_replace("[".$matches[1][$i].":".$matches[2][$i]."]", "<div class='error'>".$e->getMessage()."</div>", $article['text']);
                }
            }

            return $article;
        }

        public function addArticle($title, $sub, $content, $image = null){
            $query = $this->model->addArticle($title, $sub, $content, ($image ? $this->upload('images/',$image, uniqid()) : null));

            return $query['alias'];
        }

        public function editArticle($title, $sub, $content, $image = null, $edit_alias){
            $query = $this->model->editArticle($this->clanek, $title, $sub, $content, (file_exists($image['tmp_name']) ? $this->upload('images/',$image, uniqid()) : null), $edit_alias);
            if(!$query['up_query']) throw new Exception('Nastala chyba při editaci: '.$query['up_error']->error.'<br/>'.$query['query']);

            return $query['alias'];
        }

        public function publicArticle(){
            $public = $this->model->publicArticle($this->clanek);
            if($public) return $this->redirect('clanek/'.$this->clanek);
            else throw new Exception("Nepodařilo se publikovat článek, zkuste to prosím později.");
        }

        public function deleteArticle(){
            $delete = $this->model->deleteArticle($this->clanek);
            if($delete) return $this->redirect('');
            else throw new Exception("Nepodařilo se smazat článek, zkuste to prosím později.");
        }

    }

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $clanek = new ClanekController(Router::select($_SERVER['REQUEST_URI'])["params"]);
        if(isset($_POST['article_add'])){
            try{
                if(!User::isModerator([1,2])) throw new Exception("Nemáš oprávnění pro tuto akci.");

                if(empty($_POST['title'])) throw new Exception("Nebyl vyplněn titulek.");

                if(strlen(trim(html_entity_decode($_POST['content']))) < 30) throw new Exception("Obsah článku musí mít minimálně 30 znaků.");

                $alias = $clanek->addArticle($_POST['title'], $_POST['sub'], $_POST['content'], $_FILES['fotka']);
                if(!$alias) throw new Exception("Nepodařilo se přidat článek.");

                $clanek->redirect("clanek/".$alias);
            } catch (Exception $e){
                $_SESSION['error_message'] = $e->getMessage();
                return;
            }
        }

        if(isset($_POST['article_edit'])){
            try{
                if(!User::isModerator([1,2])) throw new Exception("Nemáš oprávnění pro tuto akci.");

                if(empty($_POST['title'])) throw new Exception("Nebyl vyplněn titulek.");

                if(strlen(trim(html_entity_decode($_POST['content']))) < 30) throw new Exception("Obsah článku musí mít minimálně 30 znaků.");

                $alias = $clanek->editArticle($_POST['title'], $_POST['sub'], $_POST['content'], $_FILES['fotka'], isset($_POST['edit_alias']));
                if(!$alias) throw new Exception("Nepodařilo se upravit článek.");
                $clanek->redirect("clanek/".$alias);
            } catch (Exception $e){
                $_SESSION['error_message'] = $e->getMessage();
                return;
            }
        }

        if(isset($_POST['public'])){
            try{
                if(!User::isModerator([1])) throw new Exception("Nemáš oprávnění pro tuto akci.");
                if(!$clanek->publicArticle()) throw new Exception("Nepodařilo se publikovat článek.");
            } catch (Exception $e){
                $clanek->flash($e->getMessage(), "flash_error");
            }
        }

        if(isset($_POST['delete'])){
            try{
                if(!User::isModerator([1])) throw new Exception("Nemáš oprávnění pro tuto akci.");
                if(!$clanek->deleteArticle()) throw new Exception("Nepodařilo se odebrat článek");
            } catch (Exception $e){
                $clanek->flash($e->getMessage(), "flash_error");
            }
        }
    }
