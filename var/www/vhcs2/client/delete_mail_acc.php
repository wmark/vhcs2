<?php
//   -------------------------------------------------------------------------------
//  |             VHCS(tm) - Virtual Hosting Control System                         |
//  |              Copyright (c) 2001-2005 by moleSoftware	|
//  |			http://vhcs.net | http://www.molesoftware.com		           		|
//  |                                                                               |
//  | This program is free software; you can redistribute it and/or                 |
//  | modify it under the terms of the MPL General Public License                   |
//  | as published by the Free Software Foundation; either version 1.1              |
//  | of the License, or (at your option) any later version.                        |
//  |                                                                               |
//  | You should have received a copy of the MPL Mozilla Public License             |
//  | along with this program; if not, write to the Open Source Initiative (OSI)    |
//  | http://opensource.org | osi@opensource.org								    |
//  |                                                                               |
//   -------------------------------------------------------------------------------


include '../include/vhcs-lib.php';

check_login();

if (isset($_GET['id']) && $_GET['id'] !== '') {
  global $delete_id;
  $delete_id = $_GET['id'];
} else {
  user_goto('email_accounts.php');
}

/* do we have a proper delete_id ? */
if (!isset($delete_id)) {
  header( "Location: email_accounts.php" );
  die();
}

if (!is_numeric($delete_id)) {
  header( "Location: email_accounts.php" );
  die();
}

$dmn_name = $_SESSION['user_logged'];

$query = <<<SQL_QUERY
        select
             t1.mail_id, t2.domain_id, t2.domain_name
        from
            mail_users as t1,
            domain as t2
        where
            t1.mail_id = ?
          and
            t1.domain_id  = t2.domain_id
          and
            t2.domain_name = ?
SQL_QUERY;

$rs = exec_query($sql, $query, array($delete_id, $dmn_name));
if ($rs -> RecordCount() == 0) {
  user_goto('email_accounts.php');
}

/* check for catchall assigment !! */
$query = "select mail_acc,domain_id,sub_id,mail_type from mail_users where mail_id=?";
$res = exec_query($sql, $query, array($delete_id));
$data = $res->FetchRow();

if ($data['mail_type'] == MT_NORMAL_MAIL || $data['mail_type'] == MT_NORMAL_FORWARD) {
  /* mail to normal domain */
  //global $domain_name;
  $mail_name = $data['mail_acc'].'@'.$_SESSION['user_logged'];//$domain_name;
} else if ($data['mail_type'] == MT_ALIAS_MAIL || $data['mail_type'] == MT_ALIAS_FORWARD) {
  /* mail to domain alias*/
  $res_tmp = exec_query($sql, "select alias_name from domain_aliasses where alias_id=?", array($data[sub_id]));
  $dat_tmp = $res_tmp->FetchRow();
  $mail_name = $data['mail_acc'].'@'.$dat_tmp['alias_name'];
} else if ($data['mail_type'] == MT_SUBDOM_MAIL || $data['mail_type'] == MT_SUBDOM_FORWARD) {
  /* mail to subdomain*/
  $res_tmp = exec_query($sql, "select subdomain_name from subdomain where subdomain_id=?", array($data[sub_id]));
  $dat_tmp = $res_tmp->FetchRow();
  $mail_name = $data['mail_acc'].'@'.$dat_tmp['subdomain_name'];
}

$query = "select * from mail_users where mail_acc=?";
$res_tmp = exec_query($sql, $query, array($mail_name));
$num = $res_tmp->RowCount();
if ($num > 0) {
  $catchall_assigned = 1;
  set_page_message(tr('Please delete first CatchAll account for this email!'));
  session_register("catchall_assigned");
  header( "Location: email_accounts.php" );
  die();
}

/* if we are locket wait to unlock */
check_for_lock_file();

$query = "update mail_users set status='" . STATUS_TODELETE . "' where mail_id=?";
exec_query($sql, $query, array($delete_id));

send_request();
$admin_login = decode_idna($_SESSION['user_logged']);
write_log("$admin_login: delete mail account -> $data[mail_acc]/$delete_id");
$maildel=1;
session_register("maildel");
header( "Location: email_accounts.php" );
die();

?>
