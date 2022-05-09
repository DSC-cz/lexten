<?php

class InputShortcode{

    public function render($type){
        if($type == "vip"){
            return '<select id="months"><option value="0">1 měsíc</option><option value="1">3 měsíce</option><option value="2">6 měsíců</option><option value="3">1 rok</option></select>';
        }
    }

}

?>