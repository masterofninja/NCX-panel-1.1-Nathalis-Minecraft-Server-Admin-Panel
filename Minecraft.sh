#!/bin/sh

### BEGIN INIT INFO# Provides: Minecraft server
# Required-Start:
# Required-Stop:
# Should-Start:
# Should-Stop:
# Default-Start: 2 3 4 5
# Default-Stop: 0 1 6
# Short-Description: Start Minecraft server
# Description: Minecraft server Script
### END INIT INFO

cd /var/www2
sleep 1
chmod +x /var/www2/command.php
sudo -u www-data php command.php "screen -S Minecraft -t MinecraftWindow -A -d -m bash -c 'cd /var/server/; java -jar server.jar nogui;'"
chmod -R 777 /var/server/

exit 0
