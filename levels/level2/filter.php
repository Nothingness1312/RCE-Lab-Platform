<?php

if(isset($_GET['cmd'])){

$cmd=$_GET['cmd'];

if(preg_match("/cat/i",$cmd)){
die("Gaboleh abang :D");
}

}