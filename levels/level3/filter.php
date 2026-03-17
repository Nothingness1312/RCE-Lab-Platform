<?php

function filter_cmd($cmd){

    if(preg_match('/cat|ls|bash|sh|nc|curl|wget|python|perl/i', $cmd)){
        return "Blocked command!";
    }

    if(preg_match('/[\\s\\/;&|`]/', $cmd)){
        return "Blocked characters!";
    }

    return false;
}