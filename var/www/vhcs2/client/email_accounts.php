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

$tpl -> define_dynamic('page', $cfg['CLIENT_TEMPLATE_PATH'].'/email_accounts.tpl');

$tpl -> define_dynamic('page_message', 'page');

$tpl -> define_dynamic('logged_from', 'page');


$tpl -> define_dynamic('mail_message', 'page');

$tpl -> define_dynamic('mail_item', 'page');

$tpl -> define_dynamic('mail_auto_respond', 'mail_item');

$tpl -> define_dynamic('mails_total', 'page');

$tpl -> define_dynamic('no_mails', 'page');

$tpl -> define_dynamic('custom_buttons', 'page');



//
// page functions.
//

function gen_user_mail_action($mail_id, $mail_status)
{

    global $cfg;



    if ($mail_status === $cfg['ITEM_OK_STATUS']) {

         return array(tr('Delete'), "delete_mail_acc.php?id=$mail_id", "edit_mail_acc.php?id=$mail_id");

    } else {

        return array(tr('N/A'), '#', '#');

    }
}

function gen_user_mail_auto_respond(&$tpl, $mail_id, $mail_type, $mail_status, $mail_auto_respond)
{

    global $cfg;

    if (preg_match('/_mail$/', $mail_type) == 1) {

        if ($mail_status === $cfg['ITEM_OK_STATUS']) {

            if ($mail_auto_respond === '_no_') {

                $tpl -> assign(
                                array(
                                        'AUTO_RESPOND_ACTION' => tr('Enable'),
                                        'AUTO_RESPOND_ACTION_SCRIPT' => "enable_mail_arsp.php?id=$mail_id"
                                     )
                              );

            } else {

                $tpl -> assign(
                                array(
                                        'AUTO_RESPOND_ACTION' => tr('Disable'),
                                        'AUTO_RESPOND_ACTION_SCRIPT' => "disable_mail_arsp.php?id=$mail_id"
                                     )
                              );

            }

            $tpl -> parse('MAIL_AUTO_RESPOND', 'mail_auto_respond');

        } else {

            $tpl -> assign('MAIL_AUTO_RESPOND', '');

        }

    } else {

        $tpl -> assign('MAIL_AUTO_RESPOND', '');

    }

}

function gen_page_dmn_mail_list(&$tpl, &$sql, $dmn_id, $dmn_name)
{
  $dmn_query = <<<SQL_QUERY
        select
            mail_id, mail_acc, mail_type, status, mail_auto_respond
        from
            mail_users
        where
            domain_id = ?
          and
            sub_id = 0
          and
            (mail_type  = 'normal_mail' or mail_type  = 'normal_forward')
        order by
            mail_type desc,
            mail_id
SQL_QUERY;

  $rs = exec_query($sql, $dmn_query, array($dmn_id));
  if ($rs -> RecordCount() == 0) {
    return 0;
  } else {
    global $counter;
    while (!$rs -> EOF) {
      if ($counter % 2 == 0) {

               			 $tpl -> assign('ITEM_CLASS', 'content');

          		  } else {

		                $tpl -> assign('ITEM_CLASS', 'content2');
           		  }

            list($mail_action, $mail_action_script, $mail_edit_script) = gen_user_mail_action($rs -> fields['mail_id'], $rs -> fields['status']);

			$mail_acc = decode_idna($rs -> fields['mail_acc']);

			$show_dmn_name = decode_idna($dmn_name);


            $tpl -> assign(
                            array(
                                    'MAIL_ACC' => $mail_acc."@".$show_dmn_name,
                                    'MAIL_TYPE' => user_trans_mail_type($rs -> fields['mail_type']),
                                    'MAIL_STATUS' => translate_dmn_status($rs -> fields['status']),
                                    'MAIL_ACTION' => $mail_action,
                                    'MAIL_ACTION_SCRIPT' => $mail_action_script,
									'MAIL_EDIT_SCRIPT' => $mail_edit_script
                                 )
                          );

            gen_user_mail_auto_respond($tpl,
                                       $rs -> fields['mail_id'],
                                       $rs -> fields['mail_type'],
                                       $rs -> fields['status'],
                                       $rs -> fields['mail_auto_respond']);

            $tpl -> parse('MAIL_ITEM', '.mail_item');

            $rs -> MoveNext(); $counter ++;

        }

        return $rs -> RecordCount();

    }

}

function gen_page_sub_mail_list(&$tpl, &$sql, $dmn_id, $dmn_name)
{
  $sub_query = <<<SQL_QUERY
        select
            t1.subdomain_id as sub_id,
            t1.subdomain_name as sub_name,
            t2.mail_id,
            t2.mail_acc,
            t2.mail_type,
            t2.status,
            t2.mail_auto_respond
        from
            subdomain as t1,
            mail_users as t2
        where
            t1.domain_id = ?
          and
            t2.domain_id = ?
          and
            (t2.mail_type = 'subdom_mail' or t2.mail_type = 'subdom_forward')
          and
            t1.subdomain_id = t2.sub_id
        order by
            t2.mail_type desc, t2.mail_id
SQL_QUERY;

  $rs = exec_query($sql, $sub_query, array($dmn_id, $dmn_id));

  if ($rs -> RecordCount() == 0) {
    return 0;
  } else {
    global $counter;
	    while (!$rs -> EOF) {

			if ($counter % 2 == 0) {

               			 $tpl -> assign('ITEM_CLASS', 'content');

          		  } else {

		                $tpl -> assign('ITEM_CLASS', 'content2');
           		  }

            list($mail_action, $mail_action_script, $mail_edit_script) = gen_user_mail_action($rs -> fields['mail_id'], $rs -> fields['status']);

			$mail_acc = decode_idna($rs -> fields['mail_acc']);

			$show_sub_name = decode_idna($rs -> fields['sub_name']);

			$show_dmn_name = decode_idna($dmn_name);

            $tpl -> assign(
                            array(
                                    'MAIL_ACC' => $mail_acc."@".$show_sub_name.".".$show_dmn_name,
                                    'MAIL_TYPE' => user_trans_mail_type($rs -> fields['mail_type']),
                                    'MAIL_STATUS' => user_trans_item_status($rs -> fields['status']),
                                    'MAIL_ACTION' => $mail_action,
                                    'MAIL_ACTION_SCRIPT' => $mail_action_script,
									'MAIL_EDIT_SCRIPT' => $mail_edit_script
                                 )
                          );

            gen_user_mail_auto_respond($tpl,
                                       $rs -> fields['mail_id'],
                                       $rs -> fields['mail_type'],
                                       $rs -> fields['status'],
                                       $rs -> fields['mail_auto_respond']);

            $tpl -> parse('MAIL_ITEM', '.mail_item');

            $rs -> MoveNext(); $counter ++;

        }

        return $rs -> RecordCount();

    }

}

function gen_page_als_mail_list(&$tpl, &$sql, $dmn_id, $dmn_name)
{
  $als_query = <<<SQL_QUERY
        select
            t1.alias_id as als_id,
            t1.alias_name as als_name,
            t2.mail_id,
            t2.mail_acc,
            t2.mail_type,
            t2.status,
            t2.mail_auto_respond
        from
            domain_aliasses as t1,
            mail_users as t2
        where
            t1.domain_id = ?
          and
            t2.domain_id = ?
          and
            t1.alias_id = t2.sub_id
          and
            (t2.mail_type = 'alias_mail' or t2.mail_type = 'alias_forward')
        order by
            t2.mail_type desc, t2.mail_id
SQL_QUERY;

  $rs = exec_query($sql, $als_query, array($dmn_id, $dmn_id));

  if ($rs -> RecordCount() == 0) {
    return 0;
  } else {
		global $counter;
        while (!$rs -> EOF) {

			if ($counter % 2 == 0) {

               			 $tpl -> assign('ITEM_CLASS', 'content');

          		  } else {

		                $tpl -> assign('ITEM_CLASS', 'content2');
           		  }
            list($mail_action, $mail_action_script, $mail_edit_script) = gen_user_mail_action($rs -> fields['mail_id'], $rs -> fields['status']);


			$mail_acc = decode_idna($rs -> fields['mail_acc']);

			$show_dmn_name = decode_idna($dmn_name);

			$show_als_name = decode_idna($rs -> fields['als_name']);

			$tpl -> assign(
                            array(
                                    'MAIL_ACC' => $mail_acc."@".$show_als_name,
                                    'MAIL_TYPE' => user_trans_mail_type($rs -> fields['mail_type']),
                                    'MAIL_STATUS' => user_trans_item_status($rs -> fields['status']),
                                    'MAIL_ACTION' => $mail_action,
                                    'MAIL_ACTION_SCRIPT' => $mail_action_script,
									'MAIL_EDIT_SCRIPT' => $mail_edit_script
                                 )
                          );

            gen_user_mail_auto_respond($tpl,
                                       $rs -> fields['mail_id'],
                                       $rs -> fields['mail_type'],
                                       $rs -> fields['status'],
                                       $rs -> fields['mail_auto_respond']);

            $tpl -> parse('MAIL_ITEM', '.mail_item');

            $rs -> MoveNext(); $counter ++;

        }

        return $rs -> RecordCount();

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

    $dmn_mails = gen_page_dmn_mail_list($tpl, $sql, $dmn_id, $dmn_name);

    $sub_mails = gen_page_sub_mail_list($tpl, $sql, $dmn_id, $dmn_name);

    $als_mails = gen_page_als_mail_list($tpl, $sql, $dmn_id, $dmn_name);

    $total_mails = $dmn_mails + $sub_mails + $als_mails;

    if ($total_mails > 0) {

        $tpl -> assign(
                        array(
                                'MAIL_MESSAGE' => '',
                                'DMN_TOTAL' => $dmn_mails,
                                'SUB_TOTAL' => $sub_mails,
                                'ALS_TOTAL' => $als_mails,
                                'TOTAL_MAIL_ACCOUNTS' => $total_mails
                             )
                      );

    } else {

        $tpl -> assign(
                        array(
                                'MAIL_MSG' => tr('Mail accounts list is empty!'),
                                'MAIL_ITEM' => '',
                                'MAILS_TOTAL' => ''
                             )
                      );

        $tpl -> parse('MAIL_MESSAGE', 'mail_message');

    }


    return $total_mails;

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
