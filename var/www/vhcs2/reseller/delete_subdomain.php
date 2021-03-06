<?php
//   -------------------------------------------------------------------------------
//  |             VHCS(tm) - Virtual Hosting Control System                         |
//  |              Copyright (c) 2001-2005 by moleSoftware		            		|
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

global $cfg;
$theme_color = $cfg['USER_INITIAL_THEME'];

if(isset($_GET['del_id']))
	$del_id = $_GET['del_id'];
else{
	$_SESSION['dadel'] = '_no_';
	Header("Location: subdomains.php");
	die();
}

$reseller_id = $_SESSION['user_id'];

$query = <<<SQL_QUERY
	select
		t1.subdomain_id, t1.domain_id, t2.domain_id, t2.domain_created_id
	from
		subdomain as t1,
		domain as t2
	where
			t1.subdomain_id = ?
		and
			t1.domain_id = t2.domain_id
		and
			t2.domain_created_id = ?
SQL_QUERY;

	$rs = exec_query($sql, $query, array($del_id, $reseller_id));

		if ($rs -> RecordCount() == 0) {

			header('Location: subdomains.php');
			die();
		}



/* check for mail acc in SUB domain ( MT_SUBDOM_MAIL ) */
$res = exec_query($sql,
                  "select count(mail_id) as mailnum from mail_users where sub_id=? and mail_type='".MT_SUBDOM_MAIL."'",
                  array($del_id));
$data = $res->FetchRow();
if ($data['mailnum'] > 0 ) {
    /* ERR - we have mail acc in this domain */
    $_SESSION['sdhavemail'] = '_yes_';
    header( "Location: subdomains.php" );
    die();
}

/* check for mail acc in SUB domain ( MT_SUBDOM_FORWARD ) */
$res = exec_query($sql,
                  "select count(mail_id) as mailnum from mail_users where sub_id=? and mail_type='".MT_SUBDOM_FORWARD."'",
                  array($del_id));
$data = $res->FetchRow();
if ($data['mailnum'] > 0 ) {
    /* ERR - we have mail acc in this domain */
	$_SESSION['sdhavemail'] = '_yes_';
    header( "Location: subdomains.php" );
    die();
}

/* check for ftp acc in SUB domain */
$res = exec_query($sql,
                  "select count(fg.gid) as ftpnum from ftp_group fg,subdomain d where d.subdomain_id=? and fg.groupname=d.subdomain_name",
                  array($del_id));
$data = $res->FetchRow();
if ($data['ftpnum'] > 0) {
    /* ERR - we have ftp acc in this domain */
    $_SESSION['sdhaveftp'] = '_yes_';
    header( "Location: manage_subdomain.php");
    die();
}

$res = exec_query($sql, "select subdomain_name from subdomain where subdomain_id=?", array($del_id));
$dat = $res->FetchRow();

exec_query($sql, "update subdomain set subdomain_status='".STATUS_TODELETE."' where subdomain_id=?", array($del_id));
send_request();
$admin_login = $_SESSION['user_logged'];
write_log("$admin_login: delete subdomain : $dat[subdomain_name]");

$_SESSION['dadel'] = '_yes_';
header( "Location: subdomains.php" );
die();

?>
