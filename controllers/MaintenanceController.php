<?php


final class MaintenanceController extends BaseController{
    public $reason;

    public function __construct($params, $reason){
        $this->head = array("title"=>"Omezený režím");
        $this->view = "Maintenance";
        $this->reason = $reason;
    }
}