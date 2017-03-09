<?php
include('./deamon_functions.php');

//get action passed by argument via CLI
$action = isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : '';

//list of deamons to supervise, path relative
$deamons = array('deamon.php');

//determine action to execute
$action = (preg_match("/^start$|^stop$/", $action)) ? $action : 'check';

foreach($deamons as $deamon)
{
    switch ($action) {
        case 'start' :
            start_deamon($deamon);
            exit ("Deamon $deamon launched.\n");
            break;

		case 'stop' :
		  stop_deamon($deamon);
		  exit ("Deamon $deamon stopped.\n");
		  break;

        case 'check' :
            if (get_pid($deamon) > 0) {
                echo ("$deamon is running.\n");
                
                $script = dirname(__FILE__) . '/'. $deamon;

                //get last edit time of deamon
                $script_time = dirname(__FILE__).'/time_' . $deamon.'.txt';

                //check if deamon was modified if true we relaunch him
                if (!file_exists($script_time) or filemtime($script) > file_get_contents($script_time))
                {
                    //save last edit time of deamon
                    file_put_contents($script_time, filemtime($script));

                    //restart deamon
                    if(stop_deamon($deamon) === true)
                    {
                        start_deamon($deamon);
                        echo ("$deamon modified relaunched.\n");
                    }
                }
                
            } else {
                start_deamon($deamon);
                echo ("$deamon not running launched.\n");
            }
            break;

        default :
            trigger_error('Wrong Parameters.', E_USER_ERROR);
            break;
    }
}

exit("<success>\n");
