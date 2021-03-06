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

$tpl -> define_dynamic('page', $cfg['ADMIN_TEMPLATE_PATH'].'/view_ticket.tpl');

$tpl -> define_dynamic('page_message', 'page');

$tpl -> define_dynamic('tickets_list', 'page');

$tpl -> define_dynamic('tickets_item', 'tickets_list');

//
// page functions.
//

function gen_tickets_list(&$tpl, &$sql, &$ticket_id)
{

$query = <<<SQL_QUERY
        select
            ticket_id,
            ticket_status,
            ticket_reply,
            ticket_urgency,
            ticket_date,
            ticket_subject,
            ticket_message
        from
            tickets
        where
            ticket_id = ?
SQL_QUERY;

    $rs = exec_query($sql, $query, array($ticket_id));

		if ($rs -> RecordCount() == 0) {

        $tpl -> assign('TICKETS_LIST', '');

        set_page_message(tr('Ticket not found!'));

    } else {



		$ticket_urgency = $rs -> fields['ticket_urgency'];
		$ticket_subject = $rs -> fields['ticket_subject'];
		$ticket_status = $rs -> fields['ticket_status'];

		if ($ticket_status == 0){
				$tr_action = tr("Open ticket");
				$action = "open";
			}
			else {
				$tr_action = tr("Close ticket");
				$action = "close";
			}

		if ($ticket_urgency == 1){
				$tpl -> assign(
                            array(
                                    'URGENCY' => tr("Low"),
									'URGENCY_ID' => tr("1"),
                                 )
                          );

			}
			elseif ($ticket_urgency == 2){
					$tpl -> assign(
                            array(
                                    'URGENCY' => tr("Medium"),
									'URGENCY_ID' => tr("2"),
                                 )
                          );
			}
			elseif ($ticket_urgency == 3){
					$tpl -> assign(
                            array(
                                    'URGENCY' => tr("High"),
									'URGENCY_ID' => tr("3"),
                                 )
                          );
			}
			elseif ($ticket_urgency == 4){
					$tpl -> assign(
                            array(
                                    'URGENCY' => tr("Very high"),
									'URGENCY_ID' => tr("4"),
                                 )
                          );
			}

			get_ticket_from($tpl, $sql, $ticket_id);
			global $cfg;
			$date_formt = $cfg['DATE_FORMAT'];
            $tpl -> assign(
                            array(
									'TR_ACTION' => $tr_action,
									'ACTION' => $action,
                                    'DATE' => date($date_formt, $rs -> fields['ticket_date']),
                                    'SUBJECT' => $rs -> fields['ticket_subject'],
									'TICKET_CONTENT' => $rs -> fields['ticket_message'],
									'ID' => $rs -> fields['ticket_id'],

								)
                          );

			$tpl -> parse('TICKETS_ITEM', '.tickets_item');
			get_tickets_replys($tpl, $sql, $ticket_id);
		}

}
function get_tickets_replys(&$tpl, &$sql, &$ticket_id)
{

$query = <<<SQL_QUERY
      select
          ticket_id,
          ticket_status,
          ticket_reply,
          ticket_urgency,
          ticket_date,
          ticket_subject,
          ticket_message

      from
          tickets
      where
          ticket_reply = ?
      order by
          ticket_id ASC
SQL_QUERY;

		$rs = exec_query($sql, $query, array($ticket_id));

		while (!$rs -> EOF) {


			$ticket_id = $rs -> fields['ticket_id'];
			$ticket_subject = $rs -> fields['ticket_subject'];
            $ticket_date = $rs -> fields['ticket_date'];
            $ticket_message = $rs -> fields['ticket_message'];


			global $cfg;
			$date_formt = $cfg['DATE_FORMAT'];
			$tpl -> assign(
                            array(
                                    'DATE' => date($date_formt, $rs -> fields['ticket_date']),
									'TICKET_CONTENT' => $rs -> fields['ticket_message'],
									//'ID' => $rs -> fields['ticket_reply'],
                                 )
                          );
			get_ticket_from($tpl, $sql, $ticket_id);
			$tpl -> parse('TICKETS_ITEM', '.tickets_item');
			$rs -> MoveNext();

			}

}

function get_ticket_from(&$tpl, &$sql, &$ticket_id)
{
  $query = <<<SQL_QUERY
      select
          ticket_from,
          ticket_to,
          ticket_status,
          ticket_reply
      from
          tickets
      where
          ticket_id = ?
SQL_QUERY;

		$rs = exec_query($sql, $query, array($ticket_id));

		$ticket_from = $rs -> fields['ticket_from'];
		$ticket_to = $rs -> fields['ticket_to'];
		$ticket_status = $rs -> fields['ticket_status'];
		$ticket_reply = $rs -> fields['ticket_reply'];

    $query = <<<SQL_QUERY
          select
              admin_name,
              fname,
              lname
          from
              admin
          where
              admin_id = ?
SQL_QUERY;

	$rs = exec_query($sql, $query, array($ticket_from));
	$from_user_name = $rs -> fields['admin_name'];
	$from_first_name = $rs -> fields['fname'];
	$from_last_name = $rs -> fields['lname'];

	$from_name = $from_first_name." ".$from_last_name." (".$from_user_name.")";

			$tpl -> assign(
                            array(
                                    'FROM' => $from_name

                                 )
                          );


}




//
// common page data.
//

global $cfg;
$theme_color = $cfg['USER_INITIAL_THEME'];

$tpl -> assign(
                array(
                        'TR_CLIENT_VIEW_TICKET_PAGE_TITLE' => tr('VHCS - Client : Support System: View Tickets'),
                        'THEME_COLOR_PATH' => "../themes/$theme_color",
                        'THEME_CHARSET' => tr('encoding'),
						'ISP_LOGO' => get_logo($_SESSION['user_id']),
                        'VHCS_LICENSE' => $cfg['VHCS_LICENSE']

                     )
              );


function send_user_message(&$sql, $user_id, $reseller_id, $ticket_id)
{
  if (!isset($_POST['uaction'])) return;

	// close ticket
	elseif ($_POST['uaction'] == "close"){
		close_ticket($sql, $ticket_id);
		return;
	}
	// open ticket
	elseif ($_POST['uaction'] == "open"){
		open_ticket($sql, $ticket_id);
		return;
	}
	// no message check->error
	elseif ($_POST['user_message'] === '') {

        set_page_message(tr('Please type your message!'));

        return;

    }

    $ticket_date = time();

    $subj = $_POST['subject'];

    $user_message = preg_replace("/\n/", "<br>", $_POST["user_message"]);

	$ticket_status = 1;

	$ticket_reply = $_GET['ticket_id'];

	$urgency = $_POST['urgency'];

$query = <<<SQL_QUERY
            select
                ticket_level,
                ticket_from,
                ticket_to,
                ticket_status,
                ticket_reply,
                ticket_urgency,
                ticket_date,
                ticket_subject,
                ticket_message
            from
                tickets
            where
                ticket_id = ?
SQL_QUERY;

		$rs = exec_query($sql, $query, array($ticket_reply));

			$ticket_to = $rs -> fields['ticket_from'];

			$ticket_from = $rs -> fields['ticket_to'];



    $query = <<<SQL_QUERY
        insert into tickets
            (ticket_from,
             ticket_to,
             ticket_status,
             ticket_reply,
             ticket_urgency,
             ticket_date,
             ticket_subject,
             ticket_message)
        values
            (?, ?, ?, ?, ?, ?, ?, ?)
SQL_QUERY;

    $rs = exec_query($sql, $query, array($ticket_from,
                                         $ticket_to,
                                         $ticket_status,
                                         $ticket_reply,
                                         $urgency,
                                         $ticket_date,
                                         $subj,
                                         $user_message));

	set_page_message(tr('Message was send!'));


// Update all Replays -> Status 1

	$query = <<<SQL_QUERY
        update
            tickets
        set
            ticket_status = '1'
        where
            ticket_id = ?
          or
            ticket_reply = ?
SQL_QUERY;

    $rs = exec_query($sql, $query, array($ticket_reply, $ticket_reply));

		while (!$rs -> EOF)
		{
		$rs -> MoveNext();
		}
}

function change_ticket_status($sql, $ticket_id)
{

  $query = <<<SQL_QUERY
        select
            ticket_status
        from
            tickets
        where
            ticket_id = ?
SQL_QUERY;

		$rs = exec_query($sql, $query, array($ticket_id));
		$ch_ticket_status = $rs -> fields['ticket_status'];


	if ($ch_ticket_status == 0){
		$ticket_status = 0;
	}
	else if (!isset($_POST['uaction'])){
		$ticket_status = 3;
	}
	else {
		$ticket_status = 4;
	}

$query = <<<SQL_QUERY
        update
            tickets
        set
            ticket_status = ?
        where
            ticket_id = ?
SQL_QUERY;

    	$rs = exec_query($sql, $query, array($ticket_status, $ticket_id));
// end of set status 3

}



function close_ticket($sql, $ticket_id)
{
$query = <<<SQL_QUERY
      update
          tickets
      set
          ticket_status = '0'
      where
          ticket_id = ?
SQL_QUERY;

		$rs = exec_query($sql, $query, array($ticket_id));

		set_page_message(tr('Ticket was closed!'));

}

function open_ticket($sql, $ticket_id)
{

		$ticket_status = 3;

$query = <<<SQL_QUERY
        update
            tickets
        set
            ticket_status = ?
        where
            ticket_id = ?
SQL_QUERY;

		$rs = exec_query($sql, $query, array($ticket_status, $ticket_id));

		//set_page_message(tr('Ticket was closed!'));

}



//
// dynamic page data.
//
	global $cfg;
	$support_system = $cfg['VHCS_SUPPORT_SYSTEM'];

	if ($support_system !== 'yes'){
		header( "Location: index.php" );
        die();

	}

$reseller_id = $_SESSION['user_created_by'];

$reseller_id = $_SESSION['user_created_by'];

if (isset($_GET['ticket_id'])) {

	$ticket_id = $_GET['ticket_id'];

	send_user_message($sql, $_SESSION['user_id'], $reseller_id, $ticket_id);

	change_ticket_status($sql, $ticket_id);

	gen_tickets_list($tpl, $sql, $ticket_id);

}
else
{
    set_page_message(tr('Ticket not found!'));

	Header("Location: support_system.php");
	die();

}




//
// static page messages.
//

gen_admin_menu($tpl);

$tpl -> assign(
                array(
                        'TR_VEIW_SUPPORT_TICKET' => tr('View support ticket'),
						'TR_TICKET_URGENCY' => tr('Priority'),
						'TR_TICKET_SUBJECT' => tr('Subject'),
						'TR_TICKET_DATE' => tr('Date'),
						'TR_DELETE' => tr('Delete'),
						'TR_NEW_TICKET_REPLY' => tr('Send message reply'),
						'TR_REPLY' => tr('Send reply'),
						'TR_TICKET_FROM' => tr('From'),
						'TR_OPEN_TICKETS' => tr('Open tickets'),
						'TR_CLOSED_TICKETS' => tr('Closed tickets'),
                     )
              );

gen_page_message($tpl);

$tpl -> parse('PAGE', 'page');

$tpl -> prnt();

if (isset($cfg['DUMP_GUI_DEBUG'])) dump_gui_debug();

?>
