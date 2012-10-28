<?php

// Automatic Jobs

require_once('func.inc.php');

Timer::tStart();

define('BASEPATH', './cron');
require_once('../../config/database.php');
$db = $db['default'];

$mysqli = new mysqli(
	$db['hostname'],
	$db['username'],
	$db['password'],
	$db['database']
);

// Check connection
if (mysqli_connect_errno()) {
    printf("Connection error: %s\n", mysqli_connect_error());
    exit();
}

// Set collation
$mysqli->set_charset($db['char_set']);

// Get GMT datetime
$cDate = gmdate('Y m d w H i s');
$cDate = explode(' ', $cDate);

// Set job type
$type = '';
// Daily Re-Count
if ($cDate[4] == 18 && $cDate[3] != 6 && $cDate[3] != 0) $type = 'first';
// Weekly Re-Count
if ($cDate[4] == 19 && $cDate[3] == 5) $type = 'second';
// 25th
if ($cDate[4] == 20 && $cDate[2] == 25) $type = 'third';
// Database Backup
if ($cDate[4] == 22) $type = 'dbbackup';

// Working...
switch ($type)
{
	// First job
	case 'first':
        // DO FIRST JOB
		break;

	// Second job
	case 'weekly':
        // DO SECOND JOB
		break;

	// Third job
	case '25th':
        // DO THIRD JOB
		break;

	// Database backup
	case 'dbbackup':
		DBBackup::backup_tables($mysqli);
		break;

	// Default actions
	default:
		printf("Nothing to do now.\n");
		break;
}

// Close connection
$mysqli->close();

Timer::tEnd();

printf("Script generated in: %s sec.\n", Timer::tTime());

/* End of file jobs.php */
/* Location: ./application/admin/cron/jobs.php */