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

$tpl -> define_dynamic('page', $cfg['ADMIN_TEMPLATE_PATH'].'/reseller_user_statistics.tpl');

$tpl -> define_dynamic('page_message', 'page');

$tpl -> define_dynamic('page_message', 'page');

$tpl -> define_dynamic('month_list', 'page');

$tpl -> define_dynamic('year_list', 'page');

$tpl -> define_dynamic('no_domains', 'page');

$tpl -> define_dynamic('domain_list', 'page');

$tpl -> define_dynamic('domain_entry', 'domain_list');

$tpl -> define_dynamic('scroll_prev_gray', 'page');

$tpl -> define_dynamic('scroll_prev', 'page');

$tpl -> define_dynamic('scroll_next_gray', 'page');

$tpl -> define_dynamic('scroll_next', 'page');


global $cfg;
$theme_color = $cfg['USER_INITIAL_THEME'];


if (isset($_POST['rid']) && isset($_POST['name'])){

    $rid= $_POST['rid'];

    $name = $_POST['name'];
}
else if (isset($_GET['rid']) && isset($_GET['name'])){

    $rid= $_GET['rid'];

    $name = $_GET['name'];
}

$year= 0;
$month = 0;


if (isset($_POST['month']) && isset($_POST['year'])){

    $year= $_POST['year'];

    $month = $_POST['month'];
}
else if (isset($_GET['month']) && isset($_GET['year'])){

    $month= $_GET['month'];

    $year= $_GET['year'];
}

if (!is_numeric($rid) || !is_numeric($month) || !is_numeric($year)) {

    header("Location: reseller_statistics.php");

    die();

}

$tpl -> assign(
                array(
                        'TR_ADMIN_USER_STATISTICS_PAGE_TITLE' => tr('VHCS - Admin/Reseller User Statistics'),
                        'THEME_COLOR_PATH' => "../themes/$theme_color",
                        'THEME_CHARSET' => tr('encoding'),
						'ISP_LOGO' => get_logo($_SESSION['user_id']),
                        'VHCS_LICENSE' => $cfg['VHCS_LICENSE']
                     )
              );



function generate_page (&$tpl, $reseller_id, $reseller_name) {

    global $sql, $cfg, $rid;

	$start_index = 0;

	$rows_per_page = $cfg['DOMAIN_ROWS_PER_PAGE'];

	if (isset($_GET['psi'])) {
		$start_index = $_GET['psi'];

	} else if (isset($_POST['psi'])) {
		$start_index = $_POST['psi'];
	}

	$tpl -> assign(
					array(
							'POST_PREV_PSI' => $start_index
						)
					);


// count query
	$count_query = <<<SQL_QUERY
                select
                    count(admin_id) as cnt
                from
                    admin
                where
                    admin_type = 'user'
                and
                    created_by = ?
SQL_QUERY;

  $query = <<<SQL_QUERY
        select
            admin_id
        from
            admin
        where
            admin_type = 'user'
          and
            created_by = ?
        order by
            admin_id desc
        limit
            $start_index, $rows_per_page
SQL_QUERY;


	$rs = exec_query($sql, $count_query, array($reseller_id));
	$records_count = $rs -> fields['cnt'];

	$rs =  exec_query($sql, $query, array($reseller_id));

    $tpl -> assign(
                    array(
                            'RESELLER_NAME' => $reseller_name,
                            'RESELLER_ID' => $reseller_id
                         )
                  );


    if ($rs -> RowCount() == 0) {

		$tpl -> assign(
							array(
									'DOMAIN_LIST' => '',
									'SCROLL_PREV' => '',
									'SCROLL_NEXT' => '',

								 )
						  );


    } else {

		$prev_si = $start_index - $rows_per_page;

		if ($start_index == 0) {

				$tpl -> assign('SCROLL_PREV', '');

		} else {

				$tpl -> assign(
								array(
										'SCROLL_PREV_GRAY' => '',
										'PREV_PSI' => $prev_si,
										'RID' => $rid
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
										'NEXT_PSI' => $next_si,
										'RID' => $rid
									 )
							  );

		}




        $tpl -> assign(
            array(
                'PAGE_MESSAGE' => ''
                )
            );


        $tpl -> assign('NO_DOMAINS', '');

        $row= 1;

        while (!$rs -> EOF) {

            $admin_id = $rs -> fields['admin_id'];

            $query = <<<SQL_QUERY
                select
                    domain_id
                from
                    domain
                where
                    domain_admin_id = ?
SQL_QUERY;

            $dres =  exec_query ($sql, $query, array($admin_id));

            generate_domain_entry($tpl, $dres ->fields['domain_id'], $row++);

            $tpl -> parse('DOMAIN_ENTRY', '.domain_entry');

            $rs->MoveNext();

        }

    }


}



function generate_domain_entry (&$tpl, $user_id, $row) {

    global $crnt_month, $crnt_year;

    list (
           $domain_name,
           $domain_id,
           $web,
           $ftp,
           $smtp,
           $pop3,
           $utraff_current,
           $udisk_current,
           $i,
           $j
         ) = generate_user_traffic($user_id);

    list (
           $usub_current, $usub_max,
           $uals_current, $uals_max,
           $umail_current, $umail_max,
           $uftp_current, $uftp_max,
           $usql_db_current, $usql_db_max,
           $usql_user_current, $usql_user_max,
           $utraff_max, $udisk_max
         ) = generate_user_props($user_id);

    $utraff_max = $utraff_max * 1024 * 1024;

    $udisk_max = $udisk_max * 1024 * 1024;

    list ($traff_percent, $traff_red, $traff_green) = make_usage_vals($utraff_current, $utraff_max);

    list ($disk_percent, $disk_red, $disk_green) = make_usage_vals($udisk_current, $udisk_max);

	$traff_show_percent = $traff_percent;

	$disk_show_percent = $disk_percent;

	if ($traff_percent > 100)
	{
		$traff_percent = 100;
	}

	if ($disk_percent > 100)
	{
		$disk_percent = 100;
	}


    if ($row % 2 == 0) {
        $tpl -> assign(
                array(
                    'ITEM_CLASS' => 'content',
                    )
                );
    }
    else{
        $tpl -> assign(
                array(
                    'ITEM_CLASS' => 'content2',
                    )
                );
    }


	$domain_name = decode_idna($domain_name);

    $tpl -> assign(
            array(
                    'DOMAIN_NAME' => $domain_name,

                    'MONTH' => $crnt_month,
                    'YEAR' => $crnt_year,
                    'DOMAIN_ID' => $domain_id,

                    'TRAFF_SHOW_PERCENT' => $traff_show_percent,
					'TRAFF_PERCENT' => $traff_percent,
                    'TRAFF_RED' => $traff_red,
                    'TRAFF_GREEN' => $traff_green,
                    'TRAFF_USED' => make_hr($utraff_current),
                    'TRAFF_MAX' => ($utraff_max) ? make_hr($utraff_max) : tr('unlimited'),

                    'DISK_SHOW_PERCENT' => $disk_show_percent,
					'DISK_PERCENT' => $disk_percent,
                    'DISK_RED' => $disk_red,
                    'DISK_GREEN' => $disk_green,
                    'DISK_USED' => make_hr($udisk_current),
                    'DISK_MAX' => ($udisk_max) ? make_hr($udisk_max) : tr('unlimited'),

                    'WEB' => make_hr($web),
                    'FTP' => make_hr($ftp),
                    'SMTP' => make_hr($smtp),
                    'POP3' => make_hr($pop3),

                    'SUB_USED' => $usub_current,
                    'SUB_MAX' => ($usub_max) ? (($usub_max > 0) ? $usub_max : tr('disabled')) : tr('unlimited'),

                    'ALS_USED' => $uals_current,
                    'ALS_MAX' => ($uals_max) ? (($uals_max > 0) ? $uals_max : tr('disabled')) : tr('unlimited'),

                    'MAIL_USED' => $umail_current,
                    'MAIL_MAX' => ($umail_max) ? $umail_max : tr('unlimited'),

                    'FTP_USED' => $uftp_current,
                    'FTP_MAX' => ($uftp_max) ? $uftp_max : tr('unlimited'),

                    'SQL_DB_USED' => $usql_db_current,
                    'SQL_DB_MAX' => ($usql_db_max) ? (($usql_db_max > 0) ? $usql_db_max : tr('disabled')) : tr('unlimited'),

                    'SQL_USER_USED' => $usql_user_current,
                    'SQL_USER_MAX' => ($usql_user_max) ? (($usql_user_max > 0) ? $usql_user_max : tr('disabled')) : tr('unlimited'),

                    )
            );

}


/*
 *
 * static page messages.
 *
 */

gen_admin_menu($tpl);

$tpl -> assign(
                array(
							'TR_RESELLER_USER_STATISTICS' => tr('Reseller users table'),
							'TR_MONTH' => tr('Month'),
                            'TR_YEAR' => tr('Year'),
                            'TR_SHOW' => tr('Show'),
                            'TR_NO_DOMAINS' => tr('This reseller has no domains.'),
                            'TR_DOMAIN_NAME' => tr('Domain'),
                            'TR_TRAFF' => tr('Traffic<br>usage'),
                            'TR_DISK' => tr('Disk<br>usage'),
                            'TR_WEB' => tr('Web<br>traffic'),
                            'TR_FTP_TRAFF' => tr('FTP<br>traffic'),
                            'TR_SMTP' => tr('SMTP<br>traffic'),
                            'TR_POP3' => tr('POP3/IMAP<br>traffic'),
                            'TR_SUBDOMAIN' => tr('Subdomain'),
                            'TR_ALIAS' => tr('Alias'),
                            'TR_MAIL' => tr('Mail'),
                            'TR_FTP' => tr('FTP'),
                            'TR_SQL_DB' => tr('SQL<br>database'),
                            'TR_SQL_USER' => tr('SQL<br>user'),
                            'TR_OF' => tr('of'),

                            'VALUE_NAME' => $name,
                            'VALUE_RID' => $rid
                     )
              );

gen_select_lists($tpl, $month, $year);

generate_page($tpl, $rid, $name);

gen_page_message($tpl);

$tpl -> parse('PAGE', 'page');

$tpl -> prnt();

if (isset($cfg['DUMP_GUI_DEBUG'])) dump_gui_debug();

unset_messages();
?>
