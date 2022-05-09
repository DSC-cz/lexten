<?php

class UserShortcode{

    public function render($value){
        if($value == "LoggedUserAccountID"){
            if(User::isLoggedIn()) return User::steamid64_to_accountid(User::getUser()["steamid"]);
            else return "<span class=\"red bold\">ACCOUNTID</span>";
        }
    }

}

?>