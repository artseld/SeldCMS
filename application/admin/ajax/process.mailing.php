<?php

// Mailing Ajax Process
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
$accountsFrom = intval($_POST['accounts_from']);
$accountsNumInStream = intval($_POST['accounts_num_in_stream']);

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

// Selection of mailing data
$mailingData = $mysqli->query('
    select
        *
    from
        ' . $db['dbprefix'] . 'mailing m
    where
        m.id_resource=' . $mailingResource . '
    limit 1
');
if ($mysqli->errno)
{
    printf("Get current mailing data error: " . $mysqli->errno . " " . $mysqli->error . "\n");
    exit();
}
$mailingData = $mailingData->fetch_object();

// Selection of recipients
switch ($mailingData->subscribers_category) {
    // All active (no blocked)
    default :
        $whereRecipients = 'u.flag_access=1';
        break;
}

// Get accounts
$accounts = $mysqli->query('
    select
        u.first_name,
        u.last_name,
        u.email
    from
        ' . $db['dbprefix'] . 'users u
    where
        ' . $whereRecipients . '
    limit
        ' . $accountsFrom . ', ' . $accountsNumInStream . '
');
if ($mysqli->errno)
{
    printf("Get accounts error: " . $mysqli->errno . " " . $mysqli->error . "\n");
    exit();
}
else
{
    // Send messages
    while ($row = $accounts->fetch_object())
    {
        $mailHeaders = 'MIME-Version: 1.0' . "\r\n" .
            'Content-type: text/html; charset=UTF-8' . "\r\n" .
            'From: robot@' . $_SERVER['SERVER_NAME'] . "\r\n" .
            'Reply-To: robot@' . $_SERVER['SERVER_NAME'] . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        @mail('"' . $row->first_name . ' ' . $row->last_name . '" <' . $row->email . '>',
            '=?UTF-8?B?' . base64_encode(stripslashes($mailingSubject)) . '?=',
            '<html>
            <head>
            <title>
            ' . stripslashes($mailingSubject) . '
            </title>
            </head>
            <body>
            ' . (!empty($mailingTitle) ? '<p style="font-size: 18px; font-weight: bold; margin: 8px 0 28px ; padding: 0;">' . stripslashes($mailingTitle) . '</p>' : '') . '
            ' . stripslashes($mailingMessage) . '
            ' . stripslashes($mailingData->signature) . '
            </body>
            </html>',
            $mailHeaders);
    }
}

// Close connection
$mysqli->close();

/* End of file process.mailing.php */
/* Location: ./application/ajax/process.mailing.php */