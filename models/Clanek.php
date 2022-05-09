<?php
    class Clanek {
        public $db;

        public function __construct(){
            global $databases;

            $this->db = &$databases;
        }

        public function selectNews($limit = 4, $page = 0){
            global $databases;
            $query = $databases[0]->get_results("SELECT a.*, b.nick, b.avatarsmall, c.barva,
            (SELECT COUNT(*) FROM `news_comments` WHERE `podrazeno` = a.id) as `comments`
            FROM `news` a
            LEFT JOIN `web_users` b ON a.author = b.steamid
            LEFT JOIN `user_groups` c ON b.group = c.id
            WHERE `public`=1 ORDER BY `datum` DESC LIMIT :limit OFFSET :offset", [":limit"=>$limit, ":offset"=>($limit * $page)]);

            $news = array();

            if($query["status"]){
                return $query["results"];
            }

            throw new Exception("Nepodařilo se nalézt žádné články.");
        }

        public function selectArticle($alias, $count_views){
            
            $add = ($count_views ? 1 : 0);
            $this->db[0]->update("news", "`views`=`views`+:add_views", [":add_views"=>$add, ":alias"=>$alias], "`alias`=:alias");
            $query = $this->db[0]->get_row("SELECT * FROM `news` WHERE `alias`=:alias LIMIT 1", [":alias"=>$alias]);
            if($query["status"]){
                if($query["result"]['public'] == 1 || User::isModerator([1,2])) return $query["result"];
                else throw new Exception("Nemáš oprávnění sledovat tento článek.");
            }
            
            throw new Exception("Zadaná stránka neexistuje");

        }

        public function addArticle($title, $sub, $content, $image){
            setlocale(LC_ALL, "en_US.utf8");
            $alias = preg_replace("/\s+/", '-', str_replace("'", '', iconv("utf-8", "ascii//TRANSLIT", $title)));
            
            if($this->db[0]->get_results("SELECT * FROM `news` WHERE `alias`=:alias", [":alias"=>$alias])["status"]) $alias = uniqid();

            return array('alias'=>$alias, 'ins_query'=>$this->db[0]->insert("news", ["`nazev`", "`sub`", "`text`", "`author`", "`alias`", "`image`"], [$title, $sub, $content, User::getUser()["steam_steamid"], $alias, $image]));
        }

        public function editArticle($id, $title, $sub, $content, $image, $edit_alias){
            setlocale(LC_ALL, "en_US.utf8");
            $alias = preg_replace("/\s+/", '-', str_replace("'", '', iconv("utf-8", "ascii//TRANSLIT", $title)));

            $already_exists = $this->db[0]->get_results("SELECT * FROM `news` WHERE `alias`=:alias", [":alias"=>$alias]);
            if($already_exists["status"]) $alias.='_'.uniqid();

            $update = $this->db[0]->update("`news`", "`nazev`=:title, `sub`=:sub, `text`=:content, `alias`=:alias, `image`=:article_image", [":title"=>$title, ":sub"=>$sub, ":content"=>$content, ":alias"=>($edit_alias == true ? $alias : "`alias`"), ":article_image"=>($image ? $image : "`image`"), ":id"=>$id, ":alias_where"=>$id], "`id`=:id OR `alias`=:alias_where");

            return array('alias'=>($edit_alias ? $alias : $id), 'up_query'=>$update);
        }

        public function publicArticle($id){
            $is_public = $this->db[0]->get_row("SELECT a.*, b.nick, b.avatarsmall FROM `news` a LEFT JOIN `web_users` b ON a.author = b.steamid WHERE `id`=:id OR `alias`=:alias AND a.public=0", [":id"=>$id, ":alias"=>$id]);

            if($is_public["status"]){
                if($is_public["result"]['publicated'] == 0 && function_exists('discord'))
                        discord($row['nazev'], "@everyone", html_entity_decode(strip_tags($is_public["result"]['sub'])), "https://lexten.cz/clanek/".$is_public["result"]['alias']."/", "ffc107", $is_public["result"]['nick']." přidal novinku", '', $is_public["result"]['avatarsmall'], "https://discord.com/api/webhooks/772874134408527932/L5rIlfXZhqBgavxycozk8_w7u5mTMwv4oktN2kKhmNS7xcfuMPdJDiZstBa7YTWfyMn1");
                return $this->db[0]->update("news", "`public`=1, `publicated`=1", [":id"=>$id, ":alias"=>$id], "WHERE `id`=:id OR `alias`=:alias");
            }
            else return $this->db[0]->update("`news`", "`public`=0", [":id"=>$id, ":alias"=>$id], "`id`=:id OR `alias`=:alias");
        }

        public function deleteArticle($id){
            return $this->db[0]->delete("news", "`id`=:id OR `alias`=:alias", [":id"=>$id, ":alias"=>$id]);
        }

    }

?>
