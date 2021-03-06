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

$tpl = new pTemplate();

$tpl -> define_dynamic('page', $cfg['ADMIN_TEMPLATE_PATH'].'/ss_closed.tpl');

$tpl -> define_dynamic('page_message', 'page');

$tpl -> define_dynamic('tickets_list', 'page');

$tpl -> define_dynamic('tickets_item', 'tickets_list');

$tpl -> define_dynamic('scroll_prev_gray', 'page');

$tpl -> define_dynamic('scroll_prev', 'page');

$tpl -> define_dynamic('scroll_next_gray', 'page');

$tpl -> define_dynamic('scroll_next', 'page');

//
// page functions.
//
function get_last_date(&$tpl, &$sql, &$ticket_id)
{
$query = <<<SQL_QUERY
        select
            ticket_date
        from
            tickets
        where
            ticket_id = ?
          or
            ticket_reply = ?
        order by
            ticket_id DESC
SQL_QUERY;

    $rs = exec_query($sql, $query, array($ticket_id, $ticket_id));

    global $cfg;
    $date_formt = $cfg['DATE_FORMAT'];
    $last_date = date($date_formt, $rs -> fields['ticket_date']);
    $tpl -> assign(
                            array(
								   'LAST_DATE' => $last_date

                                 )
                          );


}



function gen_tickets_list(&$tpl, &$sql,$user_id)
{
	$start_index = 0;

	$rows_per_page = 8;

	if (isset($_GET['psi'])) $start_index = $_GET['psi'];

$count_query = <<<SQL_QUERY
                  select
                      count(ticket_id) as cnt
                  from
                      tickets
                   where
                      ticket_to = ?
                    and
                      ticket_status = 0
                    and
                      ticket_reply  = 0
SQL_QUERY;

	$rs = exec_query($sql, $count_query, array($user_id));
	$records_count = $rs -> fields['cnt'];

  $query = <<<SQL_QUERY
        select
            ticket_id,
            ticket_status,
            ticket_urgency,
            ticket_date,
            ticket_subject,
            ticket_message
        from
            tickets
        where
            ticket_to = ?
          and
            ticket_status = 0
          and
            ticket_reply  = 0
        order by
            ticket_id DESC
        limit
            $start_index, $rows_per_page
SQL_QUERY;

    $rs = exec_query($sql, $query, array($user_id));

    if ($rs -> RecordCount() == 0) {

		$tpl -> assign(
							array(
									'TICKETS_LIST' => '',
									'SCROLL_PREV' => '',
									'SCROLL_NEXT' => ''
								 )
						  );

        set_page_message(tr('You have no support tickets.'));

    } else {

		$prev_si = $start_index - $rows_per_page;

		if ($start_index == 0) {

				$tpl -> assign('SCROLL_PREV', '');

		} else {

				$tpl -> assign(
								array(
										'SCROLL_PREV_GRAY' => '',
										'PREV_PSI' => $prev_si
									 )
							  );

		}

		$next_si = $start_index + $rows_per_page;

		if ($next_si + 1 > $records_count) {

				$tpl -> assign('SCROLL_NEXT', '');

		} else {

				$tpl -> assign(
								array(
										'SCROLL_NEXT_GRAY' => '',
										'NEXT_PSI' => $next_si
									 )
							  );

		}


	global $i ;

		while (!$rs -> EOF) {
			$ticket_id  = $rs -> fields['ticket_id'];
			get_last_date($tpl, $sql, $ticket_id);
			$ticket_urgency = $rs -> fields['ticket_urgency'];
			$ticket_status = $rs -> fields['ticket_status'];

			if ($ticket_urgency == 1){
				$tpl -> assign(
                            array(
                                    'URGENCY' => tr("Low"),
									'NEW' => tr("&nbsp;")
                                 )
                          );

			}
			elseif ($ticket_urgency == 2){
					$tpl -> assign(
                            array(
                                    'URGENCY' => tr("Medium"),
									'NEW' => tr("&nbsp;")
                                 )
                          );
			}
			elseif ($ticket_urgency == 3){
					$tpl -> assign(
                            array(
                                    'URGENCY' => tr("High"),
									'NEW' => tr("&nbsp;")
                                 )
                          );
			}
			elseif ($ticket_urgency == 4){
					$tpl -> assign(
                            array(
                                    'URGENCY' => tr("Very high"),
									'NEW' => tr("&nbsp;")
                                 )
                          );
			}

            $tpl -> assign(
                            array(
                                    'SUBJECT' => $rs -> fields['ticket_subject'],
									'MESSAGE' => $rs -> fields['ticket_message'],
									'ID' => $ticket_id,
									'CONTENT' => ($i % 2 == 0) ? 'content' : 'content2'

                                 )
                          );

            $tpl -> parse('TICKETS_ITEM', '.tickets_item');
			$rs -> MoveNext(); $i++;

        }

    }
}





//
// common page data.
//

global $cfg;
$theme_color = $cfg['USER_INITIAL_THEME'];

$tpl -> assign(
                array(
                        'TR_CLIENT_QUESTION_PAGE_TITLE' => tr('VHCS - Client/Questions & Comments'),
                        'THEME_COLOR_PATH' => "../themes/$theme_color",
                        'THEME_CHARSET' => tr('encoding'),
						'ISP_LOGO' => get_logo($_SESSION['user_id']),
                        'VHCS_LICENSE' => $cfg['VHCS_LICENSE']

                     )
              );

//
// dynamic page data.
//
	global $cfg;
	$support_system = $cfg['VHCS_SUPPORT_SYSTEM'];

	if ($support_system !== 'yes'){
		header( "Location: index.php" );
        die();

	}

gen_tickets_list($tpl, $sql, $_SESSION['user_id']);

//
// static page messages.
//

gen_admin_menu($tpl);

$tpl -> assign(
                array(
                        'TR_SUPPORT_SYSTEM' => tr('Support system'),
                        'TR_SUPPORT_TICKETS' => tr('Support tickets'),
                        'TR_NEW' => tr('&nbsp;'),
						'TR_ACTION' => tr('Action'),
                        'TR_URGENCY' => tr('Priority'),
                        'TR_SUBJECT' => tr('Subject'),
						'TR_LAST_DATA' => tr('Last reply'),
						'TR_DELETE_ALL' => tr('Delete all'),
						'TR_OPEN_TICKETS' => tr('Open tickets'),
						'TR_CLOSED_TICKETS' => tr('Closed tickets'),
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
