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

$tpl = new pTemplate();

$tpl -> define_dynamic('page', $cfg['CLIENT_TEMPLATE_PATH'].'/ftp_accounts.tpl');

$tpl -> define_dynamic('page_message', 'page');

$tpl -> define_dynamic('logged_from', 'page');


$tpl -> define_dynamic('mail_message', 'page');

$tpl -> define_dynamic('mail_item', 'page');

$tpl -> define_dynamic('mail_auto_respond', 'mail_item');

$tpl -> define_dynamic('mails_total', 'page');


$tpl -> define_dynamic('catchall_message', 'page');

$tpl -> define_dynamic('catchall_item', 'page');


$tpl -> define_dynamic('ftp_message', 'page');

$tpl -> define_dynamic('ftp_item', 'page');

$tpl -> define_dynamic('no_mails', 'page');

$tpl -> define_dynamic('custom_buttons', 'page');


//
// page functions.
//

function gen_page_ftp_list(&$tpl, &$sql, $dmn_id, $dmn_name)
{

  $query = <<<SQL_QUERY
        select gid, members from ftp_group where groupname = ?
SQL_QUERY;

  $rs = exec_query($sql, $query, array($dmn_name));

    if ($rs -> RecordCount() == 0) {

        $tpl -> assign(
                        array(
                                'FTP_MSG' => tr('FTP list is empty!'),
                                'FTP_ITEM' => '',
                                'FTPS_TOTAL' => ''
                             )
                      );

        $tpl -> parse('FTP_MESSAGE', 'ftp_message');

    } else {

        $tpl -> assign('FTP_MESSAGE', '');

        $ftp_accs = split(',', $rs -> fields['members']);

        for ($i = 0; $i < count($ftp_accs); $i++) {

		if ($i % 2 == 0) {

               			 $tpl -> assign('ITEM_CLASS', 'content');

          		  } else {

		                $tpl -> assign('ITEM_CLASS', 'content2');
           		  }


			$ftp_accs_encode[$i] = decode_idna($ftp_accs[$i]);

            $tpl -> assign(
                            array(
                                    'FTP_ACCOUNT' => $ftp_accs_encode[$i],
                                    'UID' => $ftp_accs[$i]
                                 )
                          );

            $tpl -> parse('FTP_ITEM', '.ftp_item');

        }

        $tpl -> assign('TOTAL_FTP_ACCOUNTS', count($ftp_accs));

    }


}

function gen_page_lists(&$tpl, &$sql, $user_id)
{

    list($dmn_id,
         $dmn_name,
         $dmn_gid,
         $dmn_uid,
         $dmn_created_id,
         $dmn_created,
         $dmn_last_modified,
         $dmn_mailacc_limit,
         $dmn_ftpacc_limit,
         $dmn_traff_limit,
         $dmn_sqld_limit,
         $dmn_sqlu_limit,
         $dmn_status,
         $dmn_als_limit,
         $dmn_subd_limit,
         $dmn_ip_id,
         $dmn_disk_limit,
         $dmn_disk_usage,
         $dmn_php,
         $dmn_cgi) = get_domain_default_props($sql, $user_id);


    gen_page_ftp_list($tpl, $sql, $dmn_id, $dmn_name);

    //return $total_mails;

}

//
// common page data.
//

global $cfg;
$theme_color = $cfg['USER_INITIAL_THEME'];

$tpl -> assign(
                array(
                        'TR_CLIENT_MANAGE_USERS_PAGE_TITLE' => tr('VHCS - Client/Manage Users'),
                        'THEME_COLOR_PATH' => "../themes/$theme_color",
                        'THEME_CHARSET' => tr('encoding'),
						'TID' => $_SESSION['layout_id'],
                        'VHCS_LICENSE' => $cfg['VHCS_LICENSE'],
						'ISP_LOGO' => get_logo($_SESSION['user_id'])
                     )
              );

//
// dynamic page data.
//

if (isset($_SESSION['email_support']) && $_SESSION['email_support'] == "no")
{
		$tpl -> assign('NO_MAILS', '');
}

gen_page_lists($tpl, $sql, $_SESSION['user_id']);

//
// static page messages.
//

gen_client_menu($tpl);

gen_logged_from($tpl);

check_permissions($tpl);


$tpl -> assign(
                array(
                        'TR_MANAGE_USERS' => tr('Manage users'),
                        'TR_MAIL_USERS' => tr('Mail users'),
                        'TR_MAIL' => tr('Mail'),
                        'TR_TYPE' => tr('Type'),
                        'TR_STATUS' => tr('Status'),
                        'TR_ACTION' => tr('Action'),
                        'TR_AUTORESPOND' => tr('Auto respond'),
                        'TR_DMN_MAILS' => tr('Domain mails'),
                        'TR_SUB_MAILS' => tr('Subdomain mails'),
                        'TR_ALS_MAILS' => tr('Alias mails'),
                        'TR_TOTAL_MAIL_ACCOUNTS' => tr('Mails total'),
                        'TR_TOTAL_FTP_ACCOUNTS' => tr('FTPs total'),
                        'TR_CATCHALL_MAIL_USERS' => tr('Catch all account'),
                        'TR_DOMAIN' => tr('Domain'),
                        'TR_CATCHALL' => tr('Catch all'),
                        'TR_FTP_USERS' => tr('FTP users'),
                        'TR_FTP_ACCOUNT' => tr('FTP account'),
                        'TR_FTP_ACTION' => tr('Action'),
                        'TR_EDIT' => tr('Edit'),
                        'TR_DELETE' => tr('Delete'),
						'TR_MESSAGE_DELETE' => tr('Are you sure you want to delete'),
                     )
              );

gen_page_message($tpl);

$tpl -> parse('PAGE', 'page');

$tpl -> prnt();

if (isset($cfg['DUMP_GUI_DEBUG'])) dump_gui_debug();

unset_messages();

?>
