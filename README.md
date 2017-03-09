# PHP-deamon-supervisor
This script supervise one or multiples php deamons.

You have to call it via PHP CLI (Command Line Interface)

Like that : 

To check deamon is running and relaunch it if not
  /usr/bin/php /path/to/script/checkDeamons.php check myDeamon.php

To start deamon
  /usr/bin/php /path/to/script/checkDeamons.php check myDeamon.php

To Stop deamon
  /usr/bin/php /path/to/script/checkDeamons.php check myDeamon.php

Note : It has been wrotten to be executed in a Linux/Debian environnement

If you want, you can add it to your crontab to check every minutes if the supervised deamon(s) is(are) running and relaunched it(them) if not.
