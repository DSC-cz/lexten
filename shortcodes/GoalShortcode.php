<?php

class GoalShortcode {
    public $db;

    public function __construct(){
        global $databases;
        $this->db = &$databases;
    }

    public function render($id){
        $data = $this->db[0]->get_row("SELECT * FROM `goals` WHERE `id`=:id", [":id"=>$id]);
        
        if($data["status"]){
                return '<h3 class="mt-5 text-center">'.$data["result"]["nazev"].'</h3>
                <p class="text-center">'.$data["result"]["sub"].'</p>
                <div id="1" class="my-auto goal-content">
                    <div class="goal-bar">
                        <strong id="actual">'.$data["result"]["actual"].'</strong>
                    </div>
                    <div class="space">
                        <div class="panel-space">
                            <div class="goal" style="width: '.round($data["result"]["actual"]/$data["result"]["max"]*100,1).'%;">
                                <div class="panel">
                                </div>
                            </div>
                        </div>
                        <div class="counter-space"><p>'.round($data["result"]["actual"]/$data["result"]["max"]*100,1).'%</p></div>
                    </div>
                    <div class="goal-bar">
                        <strong id="max">'.$data["result"]["max"].'</strong>
                    </div>
                </div>';
        }
        return "<div class=\"error\">Goal s ID $id nebyl nalezen.</div>";
    }

}

?>