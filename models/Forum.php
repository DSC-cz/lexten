<?php

    class Forum {
        public $db;

        public function __construct(){
            global $databases;
            $this->db = &$databases;
        }

        public function getSections(){
            
            return $this->db[0]->get_results("SELECT a.*, 
            (SELECT COUNT(*) FROM `forum_topics` WHERE `section`=a.id AND `podrazeno`=0 AND `show`=1) as `topics`, 
            (SELECT COUNT(*) FROM `forum_topics` WHERE `section`=a.id AND `podrazeno`!=0 AND `show`=1) as `replies` 
            FROM `forum_categories` a", []);
        }

        public function getTopics($id, $page, $topics_per_page, $order = "a.up DESC, lastcomment DESC", $podrazeno = false){
            global $databases;
            return $databases[0]->get_results("SELECT a.id, a.nazev, a.datum, a.lock, a.podrazeno, a.up, a.author, b.nick, b.avatarsmall, c.barva, (SELECT `datum` FROM `forum_topics` WHERE `id` = a.id OR `podrazeno` = a.id ORDER BY `datum` DESC LIMIT 1) as lastcomment 
            FROM `forum_topics` a 
            LEFT JOIN `web_users` b ON a.author = b.steamid 
            LEFT JOIN `user_groups` c ON c.id = b.group 
            WHERE ".($id != -1 ? "a.section = :id AND" : "")." a.show=1 ".($podrazeno == false ? "AND a.podrazeno=0" : "")." ORDER BY $order LIMIT $topics_per_page OFFSET ".($page*$topics_per_page), ($id != -1 ? [":id"=>$id] : []));
        }

        public function lastComment($section, $topic){
            
            return $this->db[0]->get_row("SELECT a.author, a.datum, a.id, a.podrazeno, b.nick, b.avatarsmall, c.barva 
            FROM `forum_topics` a 
            LEFT JOIN `web_users` b ON a.author = b.steamid 
            LEFT JOIN `user_groups` c ON b.group = c.id
            WHERE (a.section=:section OR a.id=:topic OR a.podrazeno=:podrazeno) AND a.show=1 
            ORDER BY a.datum DESC LIMIT 1", [":section"=>$section, ":topic"=>$topic, ":podrazeno"=>$topic]);
        }

        public function sectionInfo($id){
            
            return $this->db[0]->get_row("SELECT `nazev` FROM `forum_categories` WHERE `id`=:id", [":id"=>$id]);
        }

        public function topicInfo($id){
            
            return $this->db[0]->get_row("SELECT a.nazev, a.text, a.id as `topic_id`, a.author, a.lock, a.section, b.nazev as `section_name`, b.create, b.reply, b.moderator FROM `forum_topics` a LEFT JOIN `forum_categories` b ON b.id = a.section WHERE a.id=:id", [":id"=>$id]);
        }

        public function topicView($id, $page, $posts_per_page){
            
            return $this->db[0]->get_results( 
            "SELECT a.*, b.nick, b.avatarmedium, b.firstjoin, b.lastjoin, b.discord, c.barva, (SELECT COUNT(*) FROM `forum_topics` WHERE `author` = a.author AND `show`=1) as `posts`, d.moderator FROM `forum_topics` a 
            LEFT JOIN `web_users` b ON a.author = b.steamid 
            LEFT JOIN `user_groups` c ON c.id = b.group 
            LEFT JOIN `forum_categories` d ON d.id = a.section
            WHERE a.id=:id OR a.podrazeno=:podrazeno and a.show=1 ORDER BY a.datum LIMIT $posts_per_page OFFSET ".($posts_per_page*$page), [":id"=>$id, ":podrazeno"=>$id]);
        }

        public function lockTopic($id, $lock){
            
            return $this->db[0]->update("forum_topics", "`lock`=:lock, `lockedby`=:lockedby", [":lock"=>($lock == 0 ? 1 : 0), ":lockedby"=>User::getUser()["steam_steamid"], ":id"=>$id], "`id`=:id");
        }

        public function addComment($parent, $author, $section, $title, $text){
            
            $marks = $this->db[0]->get_results("SELECT b.discord_id, MAX(a.datum) as `datum` FROM `forum_topics` a LEFT JOIN `web_users` b ON a.author=b.steamid WHERE a.id=:parent_id OR a.podrazeno=:parent_podrazeno AND a.show=1 AND b.discord_id IS NOT NULL GROUP BY `discord_id`", [":parent_id"=>$parent, ":parent_podrazeno"=>$parent]);
            $mark = [];
            if($marks["status"]) foreach($marks["results"] as $row){
                if(!empty($row['discord_id'])) $mark[] = "<@".$row['discord_id'].">";
                if(strtotime("now") - strtotime($row['datum']) < 300) throw new Exception("Zpráva byla odfiltrována jako spam. Další zprávu do tohoto tématu lze odeslat až za ".(300-(strtotime("now") - strtotime($row['datum']))).' sekund');
            }

            $query = $this->db[0]->insert("forum_topics", ["`podrazeno`", "`author`", "`section`", "`nazev`", "`text`", "`datum`"], [$parent, $author, $section, $title, $text, date("Y-m-d H:i:s", strtotime("now"))]);
            
            if($query){
                discord("$title",
                "Označení: <@&813158588322676768>, ".implode(", ", $mark),
                mb_substr(html_entity_decode(strip_tags($text)), 0, 100).'...',
                "https://lexten.cz/forum/topic/".$parent.'/',
                "026AA6",
                "".User::getUser()["steam_personaname"]." odpověděl na téma ",
                "https://lexten.cz/stats/".User::getUser()["steamid"],
                User::getUser()["steam_avatar"],
                "https://discord.com/api/webhooks/702180525074546738/tfkvuv9sB9fGMQjaxMqpTcV3I1qwKrBF9WKb3ty9TDI2DnZXjrp3tOW1IUVbltwZqhth"
                );
            }

            return $query;
        }
        
        public function editComment($id, $text, $edit_info){
            
            return $this->db[0]->update("forum_topics", "`text`=:text, `edited`=:edited", [":text"=>$text, "edited"=>json_encode($edit_info), ":id"=>$id], "`id`=:id");
        }

        public function deleteComment($id){
            
            return $this->db[0]->update("forum_topics", "`show`=0, `admin`=:admin", [":admin"=>User::getUser()["steam_steamid"], ":id"=>$id], "`id`=:id");
        }

        public function sendReaction($reaction_id, $topic){
            
            if(!User::isLoggedIn()) return;

            if($reaction_id != 0){
                    $this->db[0]->delete("forum_reactions", "`author`=:author AND `topic`=:topic", [":author"=>User::getUser()["steam_steamid"], ":topic"=>$topic]);
                    return $this->db[0]->insert("forum_reactions", ["`author`", "`reactionid`", "`topic`"], [User::getUser()["steam_steamid"], $reaction_id, $topic]);
            }
            return $this->db[0]->delete("forum_reactions", "`author`=:author AND `topic`=:topic", [":author"=>User::getUser()["steam_steamid"], ":topic"=>$topic]);
        }

        public function reactions($id){
            $query = $this->db[0]->get_results("SELECT a.*, b.nick, b.avatarsmall FROM `forum_reactions` a 
            LEFT JOIN `web_users` b ON a.author = b.steamid 
            WHERE `topic`=:id", [":id"=>$id]);

            $reactions = array();

            if($query["status"]){
                foreach($query["results"] as $row){
                    if(!isset($reactions[$row['reactionid']])) $reactions[$row['reactionid']] = array(
                        array(
                            'nick'=>$row['nick'],
                            'steamid'=>$row['author'],
                            'avatar'=>$row['avatarsmall']
                        )
                    );
                    else $reactions[$row['reactionid']][] = array(
                        'nick'=>$row['nick'],
                        'steamid'=>$row['author'],
                        'avatar'=>$row['avatarsmall']
                    );
                }
            } //else throw new Exception("Nepodařilo se získat reakce u příspěvku #".$id);
            return $reactions;
        }

        public function addTopic($title, $text, $section){
            
            $query = $this->db[0]->insert("forum_topics", ["`nazev`", "`text`", "`section`", "`podrazeno`", "`datum`", "`author`"], [$title, $text, $section, 0, date("Y-m-d H:i:s", strtotime("now")), User::getUser()['steam_steamid']]);

            if($query){
                discord("$title",
                "Označení: <@&813158587044069461> ",
                mb_substr(html_entity_decode(strip_tags($text)), 0, 100).'...',
                "https://lexten.cz/forum/topic/".$query.'/',
                "31a5f7",
                "".User::getUser()["steam_personaname"]." založil nové téma",
                "https://lexten.cz/stats/".User::getUser()["steamid"],
                User::getUser()["steam_avatar"],
                "https://discord.com/api/webhooks/702180525074546738/tfkvuv9sB9fGMQjaxMqpTcV3I1qwKrBF9WKb3ty9TDI2DnZXjrp3tOW1IUVbltwZqhth"
                );
            }

            return $query;
        }
    }

?>