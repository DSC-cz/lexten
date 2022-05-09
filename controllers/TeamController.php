<?php

final class TeamController extends BaseController{
    public function __construct($params){
        $this->head = array('title'=>'Team');
        $this->view = "Team/Main";
    }

    public function getState($id){
        $states = array(
            '<p class="status-offline">Offline</p>',
            '<p class="status-online">Online</p>', 
            '<p class="status-online">Away</p>', 
            '<p class="status-online">Busy</p>', 
            '<p class="status-online">Snooze</p>', 
            '<p class="status-online">Looking to trade</p>', 
            '<p class="status-online">Looking to play</p>'
        );
        return $states[$id];
    }
}