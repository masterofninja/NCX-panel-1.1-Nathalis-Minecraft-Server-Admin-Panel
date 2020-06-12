<?php

if ($_POST['logstatus']) {
    $status = $_POST['logstatus'];
    require("config/config.php");
    if ($status == "check")	{
        $check = exec("ls -l " . SERVER_LOG_DIR);
        echo strtok($check, " ");
	  } elseif ($status == "update") {
		    //exec("chmod 666 " . SERVER_LOG_DIR. " ");
        chmod(SERVER_LOG_DIR,0666);       
		    $check = exec("ls -l " . SERVER_LOG_DIR);
        echo strtok($check, " ");
	  }
}

?>
