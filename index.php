<?php
/******************************************************************************/
/*                                                                            */
/*  NCX panel 1.0 - Nathalis Minecraft Server Admin Panel                     */ 
/*                                                                            */
/******************************************************************************/
   
   session_start(); 

   include 'config/config.php';


   if (!defined('USERNAME')) {define("USERNAME", "admin");}
   if (!defined('PASSWORD')) {define("PASSWORD", password_hash("admin", PASSWORD_DEFAULT));}
   if (!defined('SERVER_NAME')) {define("SERVER_NAME", "Minecraft Server");}
   if (!defined('SERVER_IP')) {define("SERVER_IP", "127.0.0.1");}
   if (!defined('SERVER_PORT')) {define("SERVER_PORT", "25565");}
   if (!defined('SERVER_ROOT_DIR')) {define("SERVER_ROOT_DIR", "server/");}
   if (!defined('SERVER_LOG_DIR')) {define("SERVER_LOG_DIR", SERVER_ROOT_DIR . "logs/latest.log");}

   if (isset($_SESSION['username'])) { // logged in
   } else { // not logged in
      header("Location:login.php"); 
   }

   if (isset($_POST["savecfg"])) { 
      if (isset($_POST["username"]) && trim($_POST["username"]) != "") {  $username=trim($_POST["username"]); }
      if (isset($_POST["password"]) && trim($_POST["password"]) != "") {  
         $password=trim($_POST["password"]);
         $hash=md5($password); 
      } else {$hash=''.PASSWORD.''; }
      if (isset($_POST["servername"]) && trim($_POST["servername"]) != "") {  $servername=trim($_POST["servername"]); }
      if (isset($_POST["serverip"]) && trim($_POST["serverip"]) != "") {  $serverip=trim($_POST["serverip"]); }
      if (isset($_POST["serverport"]) && trim($_POST["serverport"]) != "") {  $serverport=trim($_POST["serverport"]); }
      if (isset($_POST["serverrootdir"]) && trim($_POST["serverrootdir"]) != "") {  $serverrootdir=trim($_POST["serverrootdir"]); }
      if (isset($_POST["serverlogdir"]) && trim($_POST["serverlogdir"]) != "") {  $serverlogdir=trim($_POST["serverlogdir"]); }    
      file_put_contents("config/config.php", "<"."?"."php\ndefine(\"USERNAME\", \"".$username."\");\ndefine(\"PASSWORD\", '".$hash."');\n\ndefine(\"SERVER_NAME\", \"".$servername."\");\ndefine(\"SERVER_IP\", \"".$serverip."\");\ndefine(\"SERVER_PORT\", \"".$serverport."\");\n\ndefine(\"SERVER_ROOT_DIR\", \"".$serverrootdir."\");\ndefine(\"SERVER_LOG_DIR\", SERVER_ROOT_DIR.\"".$serverlogdir."\");\n?".">");
  
      header("Refresh:0");   
   }   

   if (isset($_POST["saveprops"])) { 
      if (isset($_POST["propscode"]) && trim($_POST["propscode"]) != "") {  $propscode=$_POST["propscode"]; }
      if ($propscode!="") file_put_contents(SERVER_ROOT_DIR."server.properties", $propscode);      

      header("Refresh:0");   
   }   

 

//******************************************************************************
//**   Minecraft Query
//******************************************************************************
   use xPaw\MinecraftQuery;
   use xPaw\MinecraftQueryException;

   define( 'MQ_SERVER_ADDR', SERVER_IP );
   define( 'MQ_SERVER_PORT', SERVER_PORT );
   define( 'MQ_TIMEOUT', 1 );

   // Display everything in browser, because some people can't look in logs for errors
   Error_Reporting( E_ALL | E_STRICT );
   Ini_Set( 'display_errors', true );

   require __DIR__ . '/class/MinecraftQuery.php';
   require __DIR__ . '/class/MinecraftQueryException.php';

   $Timer = MicroTime( true );
   $Query = new MinecraftQuery( );
   try {
      $Query->Connect( MQ_SERVER_ADDR, MQ_SERVER_PORT, MQ_TIMEOUT );
   }	catch( MinecraftQueryException $e )	{
      $Exception = $e;
   }
   $Timer = Number_Format( MicroTime( true ) - $Timer, 4, '.', '' );
//******************************************************************************
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


  $memorypercentage=get_server_memory_usage();
  $memorysizeMB=get_server_memory_size();
//******************************************************************************
function get_server_cpu_usage(){
    $load = sys_getloadavg();
    return $load[0];
}
  
  $cpupercentage=get_server_cpu_usage();
//******************************************************************************
  
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title><?php echo(SERVER_NAME); ?> | Minecraft Server Web Console</title>
      <link rel="stylesheet" media="screen, projection" href="css/core.css" />
      <link rel="stylesheet" media="screen, projection" href="css/font-awesome.css" />
      <link rel="stylesheet" media="screen, projection" href="css/notification.css" />
      <script src="js/jQuery.min.js"></script>
      <script language="javascript" src="js/jquery.timers-1.0.0.js"></script>
      <script language="javascript" src="js/notification.js"></script>

      <link href="css/jquery-ui.css" rel="stylesheet" type="text/css"/>
      <script src="js/jquery-ui.min.js"></script>
      
<style type="text/Css">
   @font-face {
      font-family: 'Digital7'; /*a name to be used later*/
      src: url('fonts/digital-7.ttf'); /*URL to font*/
   }
   
   #header {width:100%;height:10vh;border-bottom:0.1vhpx solid #ffffff;display: table;line-height:4.5vh;background: rgb(2,0,36);background: linear-gradient(90deg, rgba(2,0,36,1) 0%, rgba(76,122,32,1) 35%, rgba(11,184,185,1) 100%);}

   #serverlogo {float:left;width:8vh;height:8vh;background-image:url('mcserver.png');background-size:cover;margin:0.3vh;}
   #servername {overflow:hidden;word-break: break-word;width:30vh;height:8vh;font-family:Tahoma;padding: 0.4vh;display: table;font-size: 2.5vh;color: #c0c0c0;border:0px solid #c0c0c0;margin:4px 0px 0px 12px;float: left;line-height: 4vh;}

   #servercommands {} 

   #startcmd {float:left;width:8vh;height:8vh;margin:1vh;border:0px solid red;background-image:url('img/play.png');background-size: contain;} 
   #stopcmd {float:left;width:8vh;height:8vh;margin:1vh;border:0px solid red;background-image:url('img/stop.png');background-size: contain;}
   #startcmd:hover {background-image:url('img/play1.png') !important;}
   #stopcmd:hover {background-image:url('img/stop1.png') !important;}
   #startcmd.disabled {background-image:url('img/play2.png') !important;}
   #stopcmd.disabled {background-image:url('img/stop2.png') !important;}

   #serverplayers {background-color: rgba(0,0,0,0.5);height:8vh;width:14vh;font-family:Digital7;padding: 0.6vh;display: table;font-size: 3.3vh;color: #c0c0c0;border:0.1vh solid #9a9a02;margin:0.4vh 0px 0px 0.6px;float: left;}
   #servermemory {background-color: rgba(0,0,0,0.5);height:8vh;width:14vh;overflow:hidden;word-break: break-word;width:14vh;font-family:Digital7;padding: 0.6vh;display: table;font-size: 3.3vh;color: #c0c0c0;border:0.1vh solid #008000;margin:0.4vh 0px 0px 0.6px;float: left;}
   #servercpu {background-color: rgba(0,0,0,0.5);height:8vh;width:14vh;overflow:hidden;word-break: break-word;width:14vh;font-family:Digital7;padding: 0.6vh;display: table;font-size: 3.3vh;color: #c0c0c0;border:0.1vh solid #008000;margin:0.4vh 0px 0px 0.6px;float: left;}

   .ui-tabs .ui-tabs-panel {padding:1vh;}

   .update{font-family: tahoma;font-size:2vh;height: 60vh; margin: 0;}
   .time {width: 8vh;}
   .info {padding-left: 0.5vh;border-left: 0.5vh solid #34d000;}
   .log {word-break: break-word;}
   .log i {padding: 0 0.5vh;width:auto;height:auto;}
   .log div {padding:0 1.0vh !important;word-break: keep-all;padding: 0px 1vh 0px 0px;}
   .block {padding: 0 1.5vh;font-size: 3vh;}
   .block-info {color: #34d000;}
         
   .server-status {background-size: contain;background-repeat:no-repeat; font-size:2vh;border-width:0.1vh; margin: 1vh 1vh 0 0; padding: 0.5vh 1vh 0.5vh 6vh !important;}
   .server-status-online {background-image:url('img/online.png');}
   .server-status-offline {background-image:url('img/offline.png');}

   #tabs {width:98vw;}   
   #tabs ul li a {font-size:2vh;}
   .ui-tabs .ui-tabs-nav {padding: 0.2vh;}
   .ui-state-active, .ui-widget-content .ui-state-active, .ui-widget-header .ui-state-active {border: 1px solid #20ff00;background: #269800;}
   #servercmdshow {width:8vh;height:2vh;border:0.1vh solid #00ff00;font-size:1.8vh;color:#ffffff;margin: 1vh 1vh 1vh 1vh ; padding: 1vh 1vh 1vh 1vh;    background-color: #269800;}
   .cmd-type {    line-height: 3vh;    padding: 1vh 1vh;    font-size: 2.6vh;}
 
   #logout {border: 0.1vh solid #ff0000;display: table;float: right;padding: 1.2vh;background-color: #800000;font-size: 2vh;}
   #logout:hover {border: 0.1vh solid #ff4040;background-color: #a00000;}

   .notification {background-color: rgba(0,0,0,0.5) !important;}
   .notification-board {font-family: tahoma;font-size:1.6vh;}
         
   #cmd-input {font-size: 1.6vh;padding:1vh;height: 3vh;font-family:Tahoma;}

   .notification-board {width:40vh;}
   .notification-board > .notification {padding:1vh 1.6vh;}
   .notification-board > .notification i {font-size: 4vh;}   
    
/******************************************************************************/         
   @media (orientation : portrait) { /* Mobile Devices CSS */
      .update {font-family: tahoma;font-size:0.8vh;}
      .server-status {line-height:2vh;padding: 0.5vh 1vh 0.5vh 3vh !important;} 
        
      #serverlogo {width:4vh;height:4vh;margin:0.15vh;line-height:2vh;}
      #servername {width:15vh;height:4vh;font-size: 1.25vh;line-height:2vh;} 
      #startcmd, #stopcmd {width:4vh;height:4vh;margin:0.5vh;line-height:2vh;}
        
      #serverinfoblocks {display: table;float: left;width: inherit;}
        
      #serverplayers, #servermemory, #servercpu  {width:7vh;height:4vh;padding: 0.3vh;font-size: 1.6vh;line-height:2vh;}   
        
      .update{font-family: tahoma;font-size:1vh;}
      .log  {width: 100%;white-space: nowrap;word-break: keep-all;display: contents !important;font-size:0.6vh;}
      .log * {font-size:0.6vh;display: contents;}
      .log i {padding: 0 0.25vh;width:auto;height:auto;}
      .log div {padding:0 1.0vh !important;    display: table;font-size:1vh;}
      .info {padding-left: 0.25vh;border-left: 0.25vh solid #34d000;}
      .block {padding: 0 0.75vh;font-size: 1.5vh;}
      .block-info {color: #34d000;}
      .time {width: 4vh;}
        
      #tabs ul li a {font-size:1vh;}

      #logout {border: 0.1vh solid #ff0000;display: table;float: right;padding: 0.6vh;background-color: #800000;font-size: 1vh;}
   }
/******************************************************************************/        
   table.table_status { font-family: Tahoma, Geneva, sans-serif;border: 1px solid #20FF00;background-color: #505050;width: 95%;min-width: 400px;height: 458px; text-align: left;border-collapse: collapse;margin-right:12px;}
   table.table_status td, table.table_status th {border: 1px solid #15AA00;padding: 10px 10px;}
   table.table_status tbody td {width:200px;font-size: 16px;font-weight: bold;color: #FFFFFF;vertical-align: top;}
   table.table_status tr:nth-child(even) {background: #404040;}
   table.table_status thead {background: #269800;border-bottom: 3px solid #000000;}
   table.table_status thead th {font-size: 15px;font-weight: bold;color: #E6E6E6;text-align: center;border-left: 2px solid #4A4A4A;}
   table.table_status thead th:first-child {border-left: none;}
   table.table_status tfoot {font-size: 12px;font-weight: bold;color: #E6E6E6;background: #000000;background: -moz-linear-gradient(top, #404040 0%, #191919 66%, #000000 100%);background: -webkit-linear-gradient(top, #404040 0%, #191919 66%, #000000 100%); background: linear-gradient(to bottom, #404040 0%, #191919 66%, #000000 100%); border-top: 1px solid #4A4A4A;}
   table.table_status tfoot td {font-size: 12px;}
/******************************************************************************/
   table.table_settings {font-family: Tahoma, Geneva, sans-serif;border: 1px solid #20FF00;background-color: #505050;width: 95%;min-width: 400px;height: 280px;text-align: left;border-collapse: collapse;margin-right:12px;}
   table.table_settings td, table.table_settings th {border: 1px solid #15AA00;padding: 10px 10px;}
   table.table_settings tbody td {width:200px;font-size: 16px;font-weight: bold;color: #FFFFFF;vertical-align: top;}
   table.table_settings tr:nth-child(even) {background: #404040;}
   table.table_settings thead {background: #269800;border-bottom: 3px solid #000000;}
   table.table_settings thead th {font-size: 15px;font-weight: bold;color: #E6E6E6;text-align: center;border-left: 2px solid #4A4A4A;}
   table.table_settings thead th:first-child {border-left: none;}
   table.table_settings tfoot {font-size: 12px;font-weight: bold;color: #E6E6E6;background: #000000;background: -moz-linear-gradient(top, #404040 0%, #191919 66%, #000000 100%);background: -webkit-linear-gradient(top, #404040 0%, #191919 66%, #000000 100%);background: linear-gradient(to bottom, #404040 0%, #191919 66%, #000000 100%);border-top: 1px solid #4A4A4A;}
   table.table_settings tfoot td {font-size: 12px;}
/******************************************************************************/          
   #scriptcode {width:90%;min-width:640px;height:auto;display:table;border:1px solid #a0a0a0;color:#ffffff;padding:12px;background-color:#404040;margin:8px 0px 8px 0px;}
   #propscode {width:90%;min-width:640px;height:100%;display:table;border:1px solid #a0a0a0;color:#ffffff;padding:12px;background-color:#404040;margin:8px 0px 8px 0px;}
</style>
<script type="text/javascript">
   $(document).ready(function() {
      var status = "";
      var lld = "";
      var togglecmdi = 1;

      notify("<i class='fa fa-info-circle'></i> <div class='notification-content'> <div class='notification-header'>Tip</div> Clicking on the server status box (top right corner) refreshes the status.</div>");
      notify("<i class='fa fa-info-circle'></i> <div class='notification-content'> <div class='notification-header'>Tip</div> The command box pops up right after you start typing the command.</div>");
      
      gh();
      
      function gh() {
         checklogs();
         lld = "";
         status = "gh";
         $.post("reqman.php", {status: status, lld: lld}, function(clgh) {
            $("#console").html(clgh);
            notify("<i class='fa fa-check-circle notification-success'></i> <div class='notification-content'> <div class='notification-header notification-success'>Success</div> Successfully grabbed the log data.</div>");
            sd();
            status = "lld";
            $.post("reqman.php", {status: status, lld: lld}, function(cllld) {
               lld = cllld;
            });
            status = "ilu";
            $.post("reqman.php", {status: status, lld: lld}, function(clilu) {
               $("#infologs").html(clilu);
            });
         });

         setInterval(update, 1000);
         setInterval(ss, 5000);
      }
      
      function checklogs() {
         logstatus = "check";
         $.post("lpc.php", {logstatus: logstatus}, function(lsd) {
            if (lsd !== "-rw-rw-rw-") {
               notify("<i class='fa fa-times-circle notification-error'></i> <div class='notification-content'> <div class='notification-header notification-error'>Error</div> Error, No log data was received. Requesting permission change of 'latest.log' to '666'.</div>");
               logstatus = "update";
               $.post("lpc.php", {logstatus: logstatus}, function(lpcd) {
                  if (lpcd == "-rw-rw-rw-") {
              		   notify("<i class='fa fa-check-circle notification-success'></i> <div class='notification-content'> <div class='notification-header notification-success'>Success</div> Successfully changed permission of 'latest.log' to '666'. Refreshing logs...</div>");
              		   gh();
              		} else {
              		   notify("<i class='fa fa-times-circle notification-error'></i> <div class='notification-content'> <div class='notification-header notification-error'>Error</div> Error, Failed to change permission of 'latest.log' to '666'.</div>");
              		}
               });
            }
         });
      }

      function update() {
         status = "lld";
         $.post("reqman.php", {status: status, lld: lld}, function(lldrdata) {
            console.log(lldrdata)
            if (lldrdata == "error.logPermissionInvalid") {
               checklogs();
               return false;
            }
            if (lldrdata !== lld) {
               status = "clu";
               $.post("reqman.php", {status: status, lld: lld}, function(clurdata) {
                  $("#console").append(clurdata);
                  sd();
                  status = "ilu";
            	 	  $.post("reqman.php", {status: status, lld: lld}, function(clilu) { 
                     $("#infologs").html(clilu);
                  });
            	 });
            	lld = lldrdata;
            }
         });
      }

      function sd() {
         $('html, body').animate({ scrollTop: $('#sd').offset().top }, 'slow');
      }

      function command(e) {
         var active = $( "#tabs" ).tabs( "option", "active" );
         if (active==0) {
          
         var key = e.keyCode || e.charCode;
         if (e.keyCode==undefined || e.keyCode==null) key=191;
         if (key == 13) { // ENTER
            var cmd = $("#cmd-input").val();
            cmdtype = $(".cmd-type").html();
            cmdtype = cmdtype.replace("/", "");

            cmd = cmdtype + cmd;
            $.post("exec.php", {cmd: cmd}, function(cmdrd) {
               $(".cmd-input").addClass("hidden");
               $("#cmd-input").val("");
               $(".cmd-type").html("");
               togglecmdi = 1;
               notify(cmdrd);
            });
         } else if(key == 27) {	// ESC
         		$(".cmd-input").addClass("hidden");
         	  $("#cmd-input").val("");
         		$(".cmd-type").html("");
        		togglecmdi = 1;
      	 } else if(key == 8) { // BACKSPACE
            if (document.getElementById('cmd-input').value.length == 0) {
               $(".cmd-input").addClass("hidden");
               $("#cmd-input").val("");
               $(".cmd-type").html("");
               togglecmdi = 1;
            }
         } else {
            $(".cmd-input").removeClass("hidden");
            $("#cmd-input").focus();
         	  if(key == 191) { //  /<command>
               e.preventDefault();
               if (togglecmdi == 1) {
                  $(".cmd-type").html("/");
                  togglecmdi = 0;
                  return false;
               }
            } else if (togglecmdi == 1) {  //  /say <message>
               $(".cmd-type").html("/say ");
            }
            togglecmdi = 0;
         }
         }
      }    

      $(document).on('keydown', command);
      $("#servercmdshow").click(command); // for phone/tablet
      
      $(".server-status").click(function() {
         ss();
      });
      ss();

      function ss() {
         $.post("ss.php", function(ssd) {
            $(".server-status").html("Server is " + ssd).addClass("server-status-" + ssd);
     		    if (ssd == "online") {
    			     $(".server-status").removeClass("server-status-offline");
               $("#stopcmd").removeClass("disabled");
               $("#startcmd").addClass("disabled");
     		    } else {
    			     $(".server-status").removeClass("server-status-online");
               $("#stopcmd").addClass("disabled");
               $("#startcmd").removeClass("disabled");
     		    }
         });
      }

      function notify(content) {
         $.createNotification({
            content: content,
               duration: 10000
            });
      }
            
      $("#startcmd").click(function() {
         $("#startcmd").addClass("disabled");
         setTimeout(function(){ if ( $("#stopcmd").hasClass("disabled")) {} else {$("#startcmd").removeClass("disabled");} }, 10000); 
      });
            
      $('#console').bind('DOMSubtreeModified', function(){
         $("#console").scrollTop($("#console")[0].scrollHeight);
      });
//------------------------------------------------------------------------------            
      $("#tabs").tabs({
         activate: function(event, ui) {
            window.location.hash = ui.newPanel.attr('id');
         }
      });
            
      $("#tabs>div a[href^='#']").click(function() {
         var index = $($(this).attr("href")).index() - 1;
         $("#tabs").tabs("option", "active", index);
      });
      //------------------------------------------------------------------------------            
      $("#startcmd").click(function() {
         $.ajax({         
            method: "POST",
            async: false,
            data: { cmd: "start" },
            url: "exec.php", 
            success: function(response){    }
         }); 
      });     
      $("#stopcmd").click(function() {
         $.ajax({         
            method: "POST",
            async: false,
            data: { cmd: "stop" },
            url: "exec.php", 
            success: function(response){    }
         }); 
      });     
      /* $("#stopcmd").click(function() {
         $.ajax({         
            method: "POST",
            async: false,
            data: { cmd: "killall -9 java" },
            url: "command.php", 
            success: function(response){    }
         }); 
      });*/
//******************************************************************************
      setInterval(function() {
         $.ajax({         
            method: "POST",
            async: false,
            data: { cmd: "" },
            url: "players.php", 
            success: function(response){ 
               $("#playerscount").html(response+"&nbsp;");
            }
         }); 
         $.ajax({         
            method: "POST",
            async: false,
            data: { cmd: "getramusage" },
            url: "cpu_ram.php", 
            success: function(response){ 
               $("#ramusage").html("<span style='color: #01d201;'>RAM:</span> "+Math.round(response)+"%");
            }
         }); 
         $.ajax({         
            method: "POST",
            async: false,
            data: { cmd: "getcpuusage" },
            url: "cpu_ram.php", 
            success: function(response){ 
               $("#cpuusage").html("<span style='color: #01d201;'>CPU:</span> "+Math.round(response*100)+"%");
            }
         });
         
      }, 3000);
//******************************************************************************
/*$('#propscode').css({'display':'block','visibility':'hidden'});

         $("textarea:visible").each(function(textarea) {
            $(this).height( $(this)[0].scrollHeight );
         }); 

/*
$('#propscode').load("TEST", function() {


  //$('#textarea').load("TEST");
  
  
});         */

$("#tabs ul li a").eq(4).click(function() {
$("textarea").each(function(textarea) {
    $(this).height( $(this)[0].scrollHeight );
});
});




$("textarea").each(function(textarea) {
    $(this).height( $(this)[0].scrollHeight );
});




   });
</script>
</head>
<body>
   <div class="header" id="header" style="">
      <a href="index.php"><div id="serverlogo" style=""></div></a>
      <div id="servername" style="">
         <div style="color:#ffbf00;">SERVER NAME:</div>
         <div style="position: absolute;max-width: 30vh;"><?php echo(SERVER_NAME); ?></div>
      </div>   

      <div id="servercommands">
         <div id="startcmd" class="" alt="start server" style=""></div>
         <div id="stopcmd" class="" alt="stop server" style=""></div>
      </div>   
      
      <div id="serverinfoblocks">                 
         <div id="serverplayers" style="">
            <div id="playerslabel" style='color: #ffbf00;'>Players:</div>       
            <div id="playerscount"><?php if (  ($Info = $Query->GetInfo())  !== false ) echo($Info["Players"]."/".$Info["MaxPlayers"]); ?>&nbsp;</div>
         </div>   
                            
         <div id="servermemory" style="">
            <?php
               echo ("<div id='ramusage'><span style='color: #01d201;'>RAM:</span> ".round($memorypercentage,0)."% </div>"); 
               echo ("<div id='ramsize'>".round($memorysizeMB,0)." <span style='color: #01d201;'>MB</span> </div>");
            ?>
         </div>
         <div id="servercpu" style="">
            <?php
               echo ("<div id='cpuusage'><span style='color: #01d201;'>CPU:</span> ".round($cpupercentage*100,0)."% </div>");
               echo ("<div>&nbsp;</div>");
            ?>
         </div>
         <div class="server-status" style="right: 4vh;top: 1vh !important; padding: 1.0vh 4vh;position:static;float:right;"></div>
      </div>
   </div>
<!--end of header-------------------------------------------------------------->

   <div class="notification-board right top"></div>
      <div id="tabs" style="">
         <ul>
            <li><a href="#tabs-1">CONSOLE</a></li>
            <li><a href="#tabs-2">STATUS</a></li>
            <li><a href="#tabs-3">SETTINGS</a></li>
            <li><a href="#tabs-4">AUTOSTART</a></li>
            <li><a href="#tabs-5">PROPERTIES</a></li>
            <a href="logout.php"><div id="logout" style="">LOGOUT</div></a>
         </ul>
<!----------------------------------------------------------------------------->
         <div id="tabs-1">
            <div class="update" id="console" style=""></div>
            <div id="sd"></div>
            <div id="servercmdshow">Command</div>
            <div id="infologs"></div>
            <div class="cmd-input hidden">
               <div class="cmd-wrapper">
                  <div class="cmd-type"></div>
                  <input type="text" id="cmd-input" placeholder="Enter a command... Press enter to execute." />
    	         </div>
            </div>
         </div>
<!----------------------------------------------------------------------------->
         <div id="tabs-2">
            <div class="container"><?php if( isset( $Exception ) ): ?>
		           <div class="panel panel-primary">
			            <div class="panel-heading"><?php echo htmlspecialchars( $Exception->getMessage( ) ); ?></div>
			            <div class="panel-body"><?php echo nl2br( $e->getTraceAsString(), false ); ?></div>
		           </div><?php else: ?>
		           <div class="row">
			            <div class="col-sm-6">
				             <table class="table_status table-bordered table-striped">
					              <thead><tr style="height: 4vh;"><th colspan="2">Server Info <em>(queried in <?php echo $Timer; ?>s)</em></th></tr></thead>
					              <tbody>
                           <?php if( ( $Info = $Query->GetInfo( ) ) !== false ): ?>
                           <?php foreach( $Info as $InfoKey => $InfoValue ): ?>
						               <tr>
							                <td><?php echo htmlspecialchars( $InfoKey ); ?></td>
							                <td><?php
	                               if( Is_Array( $InfoValue )) {
		                                echo "<pre>";
		                                print_r( $InfoValue );
		                                echo "</pre>";
	                               } else {
		                                echo htmlspecialchars( $InfoValue );
	                               }
                              ?></td>
                           </tr>
                           <?php endforeach; ?>
                           <?php else: ?>
						               <tr><td colspan="2">No information received</td></tr>
                           <?php endif; ?>
					              </tbody>
				             </table>
			            </div>
			            <div class="col-sm-6">
   				           <table class="table_status table-bordered table-striped">
					              <thead><tr style="height: 4vh;"><th>Players</th></tr></thead>
					              <tbody>
                           <?php if( ( $Players = $Query->GetPlayers( ) ) !== false ): ?>
                           <?php foreach( $Players as $Player ): ?>
						               <tr>
   							              <td><?php echo htmlspecialchars( $Player ); ?></td>
	   					             </tr>
                           <?php endforeach; ?>
                           <?php else: ?>
						               <tr><td>No players in da house</td></tr>
                           <?php endif; ?>
					              </tbody>
				             </table>
			            </div>
		           </div>
               <?php endif; ?>
	          </div>
         </div>
<!----------------------------------------------------------------------------->
         <div id="tabs-3">
            <div id="settings" style="width:640px;">
               <form style="width:640px;" action="index.php#tabs-3" method="post">
                  <table class="table_settings table-bordered table-striped">                               
                     <tr><td><div>USERNAME: </td><td><input name="username" type="text" value="<?php echo(USERNAME); ?>" /></div></td></tr>
                     <tr><td><div>PASSWORD: </td><td><input name="password" type="password" value="<?php echo(""); ?>" /></div></td></tr>
                     <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                                      
                     <tr><td><div>SERVER NAME: </td><td><input name="servername" type="text" value="<?php echo(SERVER_NAME); ?>" /></div></td></tr>
                     <tr><td><div>SERVER IP: </td><td><input name="serverip" type="text" value="<?php echo(SERVER_IP); ?>" /></div></td></tr>
                     <tr><td><div>SERVER_NAME: </td><td><input name="serverport" type="text" value="<?php echo(SERVER_PORT); ?>" /></div></td></tr>
                     <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                     <tr><td><div>SERVER ROOT DIR: </td><td><input name="serverrootdir" type="text" value="<?php echo(SERVER_ROOT_DIR); ?>" /></div></td></tr>
                     <tr><td><div>SERVER LOG DIR: </td><td><?php echo(SERVER_ROOT_DIR); ?><input name="serverlogdir" type"text" value="<?php echo(str_replace(SERVER_ROOT_DIR, "",SERVER_LOG_DIR)); ?>" /></div></td></tr>
                  </table>
                  <br />
                  <input type="submit" name="savecfg" value="Save Settings" />
               </form><br />
            </div>
         </div>
<!----------------------------------------------------------------------------->
         <div id="tabs-4">   
            <div id="scriptcode" style=""></div>
            <script type="text/Javascript">
               $.ajax({         
                  method: "POST",
                  async: false,
                  data: { cmd: "install" },
                  url: "installer.php", 
                  success: function(response){ 
                     $("#scriptcode").html(response);
                     //console.log(response);                               
                  }
               }); 
            </script>
            <br /><br />
            Save file <a style="color:#ff0000;" target="_blank" href="Minecraft.sh">Minecraft.sh</a> to /etc/ini.d/Minecraft.sh by command:
            <div id="" style="width:90%;min-width:640px;height:auto;display:table;border:1px solid #a0a0a0;color:#ffffff;padding:12px;background-color:#404040;margin:8px 0px 8px 0px;">
<pre>
cp Minecraft.sh /etc/init.d/Minecraft.sh
sudo chmod 755 /etc/init.d/Minecraft.sh
sudo update-rc.d Minecraft.sh defaults
reboot
</pre>
            </div>
            <br /><br />And process is installed. <br />To disable autostart Minecraft:
            <div id="" style="width:90%;min-width:640px;height:auto;display:table;border:1px solid #a0a0a0;color:#ffffff;padding:12px;background-color:#404040;margin:8px 0px 8px 0px;">
<pre>
sudo update-rc.d Minecraft disable
sudo update-rc.d -f Minecraft remove 
</pre>
            </div>
         </div>
<!----------------------------------------------------------------------------->
         <div id="tabs-5">   
            <form style="width:640px;" action="index.php#tabs-5" method="post">
            <textarea name="propscode" id="propscode" style="" oninput='this.style.height = "";this.style.height = this.scrollHeight + "px"'>
<?php 
$fprops = fopen(SERVER_ROOT_DIR.'server.properties','r');
while ($line = fgets($fprops)) {
  echo($line);                        
}
fclose($fprops);
?>            
            </textarea>            
            <input type="submit" name="saveprops" value="Save Settings" />
             </form><br />
         </div>
<!----------------------------------------------------------------------------->
      </div>
      <div style="bottom:0;right:0;font-size:1.6vh;position:fixed;">NCX panel 1.0 - Nathalis Minecraft Server Admin Panel &nbsp;</div>
   </body>
</html>
