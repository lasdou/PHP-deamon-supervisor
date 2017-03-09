<?php
$action = isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : '';

$deamons = array('deamon.php');

$action = (ereg("^start$|^stop$", $action)) ? $action : 'check';

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

function stop_deamon($deamon) {
	$pid = get_pid($deamon);

	if ($pid > 0) {
		system("kill -15 $pid");
	}

	return true;
}

function start_deamon($deamon) {
	stop_deamon($deamon);

	$script = dirname(__FILE__) . '/'. $deamon;

	if (!file_exists($script))
		trigger_error("Script $script does not exists.", E_USER_ERROR);
	
	system('/usr/bin/php ' . $script . ' > /dev/null &');

	return true;
}

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
			$get_pid = get_pid($deamon);
			if ($get_pid > 0) {
				echo ("$deamon is running.\n");
				
				$script = dirname(__FILE__) . '/'. $deamon;
				//si le script à été modifié et qu'il est en train de tourner on le relance
				$script_time = dirname(__FILE__).'/time_' . $deamon.'.txt';
				if (!file_exists($script_time) or filemtime($script) > file_get_contents($script_time))
				{
					file_put_contents($script_time, filemtime($script));
					
					if(stop_deamon($deamon) === true)
					{
						start_deamon($deamon);
						echo ("$deamon modified relaunched.\n");
					}
				}
				
			} else {
				start_deamon($deamon);
				echo ("$deamon not running relaunched.\n");
			}
			break;

		default :
			trigger_error('Wrong Parameters.', E_USER_ERROR);
			break;
	}
}

exit("<success>\n");