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
  global $cfg;

  $mail_id = $_GET['id'];
  $item_change_status = $cfg['ITEM_CHANGE_STATUS'];
  check_for_lock_file();

  $query = <<<SQL_QUERY
        update
            mail_users
        set
            status = ?,
            mail_auto_respond = '_no_'
        where
            mail_id = ?
SQL_QUERY;

  $rs = exec_query($sql, $query, array($item_change_status, $mail_id));

  send_request();
  write_log($_SESSION['user_logged']." : change mail autoresponder");
  set_page_message(tr('Mail account scheduled for modification!'));
  header('Location: email_accounts.php');
  exit(0);

} else {

  header('Location: email_accounts.php');
  exit(0);

}

?>
