<?php  
   
   include("config/config.php");

   $batchdata="#!/bin/sh\n\n"."### BEGIN INIT INFO".
   "# Provides: Minecraft server\n"."# Required-Start:\n"."# Required-Stop:\n"."# Should-Start:\n"."# Should-Stop:\n"."# Default-Start: 2 3 4 5\n"."# Default-Stop: 0 1 6\n"."# Short-Description: Start Minecraft server\n"."# Description: Minecraft server Script\n"."### END INIT INFO\n".
   "\ncd ".__DIR__."\n".
   "sleep 1"."\n".
   "chmod +x ".__DIR__."/command.php"."\n".
   "sudo -u www-data php command.php \"screen -S Minecraft -t MinecraftWindow -A -d -m bash -c 'cd ".SERVER_ROOT_DIR."; java -jar server.jar nogui;'\""."\n".
   "chmod -R 777 ".SERVER_ROOT_DIR."\n".
   "\nexit 0\n";

   
   file_put_contents("Minecraft.sh", $batchdata);
   
   //file_put_contents("/etc/init.d/Minecraft.sh", $batchdata);
   //chmod("Minecraft.sh",0777);
     
   //$status=exec("sudo -u root cp /var/www2/Minecraft.sh /etc/init.d/Minecraft.sh ");
   //echo($status);

   echo("<pre>".$batchdata."</pre>");

?>