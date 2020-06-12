<?php 

if (isset($_POST["cmd"])) $cmd=$_POST["cmd"];  
else $cmd="";

function get_server_memory_usage(){

    $free = shell_exec('free');
    $free = (string)trim($free);
    $free_arr = explode("\n", $free);
    $mem = explode(" ", $free_arr[1]);
    $mem = array_filter($mem);
    $mem = array_merge($mem);
    $memory_usage = $mem[2]/$mem[1]*100;

    $memtotal=$mem[1];

    return $memory_usage;
}

function get_server_memory_size(){

    $free = shell_exec('free');
    $free = (string)trim($free);
    $free_arr = explode("\n", $free);
    $mem = explode(" ", $free_arr[1]);
    $mem = array_filter($mem);
    $mem = array_merge($mem);
    //$memory_usage = $mem[2]/$mem[1]*100;

    return $mem[1]/1000;
}

function get_server_cpu_usage(){
    $load = sys_getloadavg();
    return $load[0];
}

$out="";

if ($cmd=="getramusage") $out=get_server_memory_usage();
else if ($cmd=="getramusize") $out=get_server_memory_size(); 
else if ($cmd=="getcpuusage") $out=get_server_cpu_usage(); 

echo($out);

?>