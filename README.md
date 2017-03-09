# PHP-deamon-supervisor
This script supervise one or multiples php deamons.

You have to call it via PHP CLI (Command Line Interface)

Like that : 

**To check deamon is running and relaunch it if not :**
```
/usr/bin/php /path/to/script/checkDeamon.php check myDeamon.php
```
**To start deamon :**
```
/usr/bin/php /path/to/script/checkDeamon.php start myDeamon.php
```
**To Stop deamon :**
```
/usr/bin/php /path/to/script/checkDeamon.php stop myDeamon.php
```

first argument is the action  (check/start/stop), second argument is the relative path of the deamon to supervise (relative to checkDeamon.php)

Note : It has been wrotten to be executed in a Linux/Debian environnement

If you want, you can add it to your crontab to check every minutes if the supervised deamon(s) is(are) running and relaunched it(them) if not.
