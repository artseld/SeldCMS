<?php

// Mailing Details Ajax Process
$access_key = 'cU8rekujeseT';

// Check if POST data exists
if (empty($_POST) || !isset($_POST['access_key']) || $_POST['access_key'] !== $access_key) {
    print("File access is forbidden.");
    exit();
}

// Prepare POST data
$mailingResource = intval($_POST['mailing_resource']);
$eventId = intval($_POST['event_id']);

define('BASEPATH', './ajax');
require_once('../../config/database.php');
$db = $db['default'];

$mysqli = new mysqli(
    $db['hostname'],
    $db['username'],
    $db['password'],
    $db['database']
);

// Check connection
if ($mysqli->connect_errno) {
    printf("Connection error: %s\n", $mysqli->connect_error);
    exit();
}

// Set collation
$mysqli->set_charset($db['char_set']);

// Get accounts
$accounts = $mysqli->query('
    select
        message
    from
        ' . $db['dbprefix'] . 'mailing_events me
    where
        me.id=' . $eventId . ' and
        me.id_resource=' . $mailingResource . '
    limit 1
');
if ($mysqli->errno)
{
    printf("Get details error: " . $mysqli->errno . " " . $mysqli->error . "\n");
    exit();
}
elseif (!$accounts->num_rows)
{
    printf("Empty data\n");
    exit();
}

// Close connection
$mysqli->close();

// Return result
echo stripslashes($accounts->fetch_object()->message);

/* End of file process.mailing.details.php */
/* Location: ./application/admin/ajax/process.mailing.details.php */