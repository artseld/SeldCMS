<?php

// Mailing Log Ajax Process
$access_key = 'cU8rekujeseT';

// Check if POST data exists
if (empty($_POST) || !isset($_POST['access_key']) || $_POST['access_key'] !== $access_key) {
    print("File access is forbidden.");
    exit();
}

// Prepare POST data
$mailingResource = intval($_POST['mailing_resource']);
$mailingSubject = $_POST['mailing_subject'];
$mailingTitle = $_POST['mailing_title'];
$mailingMessage = $_POST['mailing_message'];
$accountsNum = intval($_POST['accounts_num']);

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
    insert into
        ' . $db['dbprefix'] . 'mailing_events
    select
        0,
        now(),
        ' . $mailingResource . ',
        ' . $accountsNum . ',
        msc.id,
        \'' . $mysqli->escape_string( $mailingSubject ) . '\',
        concat(\'' . (!empty($mailingTitle) ? '<p style="font-size: 18px; font-weight: bold; margin: 8px 0 28px ; padding: 0;">' . stripslashes($mailingTitle) . '</p>' : '')
            . $mysqli->escape_string( $mailingMessage ) . '\', m.signature)
    from
        ' . $db['dbprefix'] . 'mailing_subscribers_categories msc,
        ' . $db['dbprefix'] . 'mailing m
    where
        msc.id=m.subscribers_category and
        m.id_resource=' . $mailingResource . '
    limit 1
');
if ($mysqli->errno)
{
    printf("Save mailing log error: " . $mysqli->errno . " " . $mysqli->error . "\n");
    exit();
}

// Close connection
$mysqli->close();

/* End of file process.mailing.log.php */
/* Location: ./application/admin/ajax/process.mailing.log.php */