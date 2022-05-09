<?php

class VipcastkaShortcode{
    public function render($value){
        return '<span class="vip-price" value="'.$value.'">'.explode("|", $value)[0].'</span>';
    }
}

?>