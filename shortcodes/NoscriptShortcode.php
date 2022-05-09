<?php

class NoscriptShortcode{

    public function render($text){
        return "<noscript><div class=\"error\">".$text."</div></noscript>";
    }

}

?>