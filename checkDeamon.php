<?php
include('./deamon_functions.php');

//get action passed by argument via CLI
$action = isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : '';

//get PHP deamon filename (relative to this script) passed by argument via CLI
$deamon = isset($_SERVER['argv'][2]) ? $_SERVER['argv'][2] : '';

if($deamon == '')
{
  trigger_error('No deamon specified.', E_USER_ERROR);
}

if (!file_exists(dirname(__FILE__) .'/'. $deamon))
{
  trigger_error("Script $deamon does not exists.", E_USER_ERROR);
}

//determine action to execute
$action = (preg_match("/^start$|^stop$/", $action)) ? $action : 'check';

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

      //check if deamon was modified if true we relaunch him
      if (is_deamon_modified($deamon))
      {
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
exit("<success>\n");
