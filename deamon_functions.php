<?php

/*
 * function to get process id of a deamon (in linux environnement)
 * */
function get_pid($deamon) {
  $script = dirname(__FILE__) .'/'. $deamon;
  if (!file_exists($script))
    trigger_error("Script $script does not exists.", E_USER_ERROR);

  $content = array();
  exec('ps aux | grep php', $content);

  $pid = false;
  foreach ($content as $line) {

    if (preg_match('#([0-9]{1,5}).*/usr/bin/php ' . $script.'#', $line, $regs)) {
      $pid = $regs[1];
      break;
    }
  }

  return $pid;
}

/*
 * function to stop deamon
 */
function stop_deamon($deamon) {
  //first : get process id of deamon
  $pid = get_pid($deamon);

  //if pid exists kill it
  if ($pid > 0) {
    system("kill -15 $pid");
  }

  return true;
}

/*
 * function to start deamon
 */
function start_deamon($deamon) {
  //stop deamon before start a new, to avoid multiple execution of same deamon
  stop_deamon($deamon);

  $script = dirname(__FILE__) . '/'. $deamon;

  //check existence of script
  if (!file_exists($script))
    trigger_error("Script $script does not exists.", E_USER_ERROR);

  //file path to store last edit time of deamon
  $script_time = dirname(__FILE__).'/time_' . $deamon.'.txt';

  //save edit time of deamon
  file_put_contents($script_time, filemtime($script));

  //run deamon in background mode
  system('/usr/bin/php ' . $script . ' > /dev/null &');

  return true;
}

/*
 * function to check if deamon has been modified
 */
function is_deamon_modified($deamon){
  $script = dirname(__FILE__) . '/'. $deamon;

  //check existence of script
  if (!file_exists($script))
    trigger_error("Script $script does not exists.", E_USER_ERROR);

  //file path to store last edit time of deamon
  $script_time = dirname(__FILE__).'/time_' . $deamon.'.txt';

  return !file_exists($script_time) or filemtime($script) > file_get_contents($script_time);
}
