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

$tpl -> define_dynamic('page', $cfg['RESELLER_TEMPLATE_PATH'].'/edit_domain.tpl');

$tpl -> define_dynamic('page_message', 'page');

$tpl -> define_dynamic('ip_entry', 'page');

$tpl -> define_dynamic('custom_buttons', 'page');

$tpl -> define_dynamic('logged_from', 'page');

global $cfg;
$theme_color = $cfg['USER_INITIAL_THEME'];


$tpl -> assign(
                array(
                        'TR_EDIT_DOMAIN_PAGE_TITLE' => tr('VHCS - Domain/Edit'),
                        'THEME_COLOR_PATH' => "../themes/$theme_color",
                        'THEME_CHARSET' => tr('encoding'),
                        'VHCS_LICENSE' => $cfg['VHCS_LICENSE'],
						'ISP_LOGO' => get_logo($_SESSION['user_id']),
                     )
              );

/*
 *
 * static page messages.
 *
 */
	$tpl -> assign(
					array(
							'TR_EDIT_DOMAIN' => tr('Edit Domain'),
							'TR_DOMAIN_PROPERTIES' => tr('Domain properties'),
							'TR_DOMAIN_NAME' => tr('Domain name'),
							'TR_DOMAIN_IP' => tr('Domain IP'),
							'TR_PHP_SUPP' => tr('PHP support'),
							'TR_CGI_SUPP' => tr('CGI support'),
							'TR_SUBDOMAINS' => tr('Subdomain(s)<br><i>(-1 disabled, 0 unlimited)'),
							'TR_ALIAS' => tr('Alias(es)<br><i>(-1 disabled, 0 unlimited)'),
							'TR_MAIL_ACCOUNT' => tr('Mail account(s)<br><i>(0 unlimited)'),
							'TR_FTP_ACCOUNTS' => tr('FTP account(s)<br><i>(0 unlimited)'),
							'TR_SQL_DB' => tr('SQL database(s)<br><i>(-1 disabled, 0 unlimited)'),
							'TR_SQL_USERS' => tr('SQL user(s)<br><i>(-1 disabled, 0 unlimited)'),
							'TR_TRAFFIC' => tr('Traffic [MB]<br><i>(0 unlimited)'),
							'TR_DISK' => tr('Disk [MB]<br><i>(0 unlimited)'),
							'TR_USER_NAME' => tr('Username'),
							'TR_UPDATE_DATA' => tr('Submit changes'),
							'TR_CANCEL' => tr('Cancel'),
							'TR_YES' => tr('Yes'),
							'TR_NO' => tr('No'),
						)
				);

gen_reseller_menu($tpl);

gen_logged_from($tpl);

gen_page_message($tpl);

if (isset($_POST['uaction']) && ('sub_data' === $_POST['uaction'])) {
// Process data
        //var_dump($_SESSION);
	if(isset($_SESSION['edit_id']))	{
		$editid = $_SESSION['edit_id'];
        }
	else{
		unset($_SESSION['edit_id']);
		$_SESSION['edit'] = '_no_';

		Header('Location: users.php');
		die();
	}

	if(check_user_data($tpl, $_SESSION['user_id'], $editid))
	{// Save data to db
		$_SESSION['dedit'] = "_yes_";
		Header("Location: users.php");
		die();

	}
	load_additional_data($_SESSION['user_id'], $editid);

}else{
	// Get user id that come for edit
	if(isset($_GET['edit_id'])){
		$editid = $_GET['edit_id'];
	}

	load_user_data($_SESSION['user_id'], $editid);
	//$_SESSION['edit_ID'] = $editid;
	$_SESSION['edit_id'] = $editid;
	$tpl -> assign('MESSAGE', "");
}
gen_editdomain_page($tpl);

$tpl -> parse('PAGE', 'page');

$tpl -> prnt();

if (isset($cfg['DUMP_GUI_DEBUG'])) dump_gui_debug();

//unset_messages();

//
// Begin function block
//

// Load data from sql
function load_user_data($user_id, $domain_id)
{

	global $sql;

	global $domain_name, $domain_ip, $php_sup;
	global $cgi_supp ,$sub, $als;
	global $mail, $ftp, $sql_db;
	global $sql_user, $traff, $disk;
	global $username;

$query = <<<SQL_QUERY
        select
            domain_id
        from
            domain
        where
            domain_id = ?
          and
            domain_created_id = ?
SQL_QUERY;

	$rs = exec_query($sql, $query, array($domain_id, $user_id));

		if ($rs -> RecordCount() == 0) {

			set_page_message(tr('User does not exist or you do not have permission to access this interface!'));

			header('Location: users.php');
			die();
		}





	list (
           $a, $sub,
           $b, $als,
           $c, $mail,
           $d, $ftp,
           $e, $sql_db,
           $f, $sql_user,
		   $traff, $disk
         ) = generate_user_props($domain_id);;

	load_additional_data($user_id, $domain_id);

}//End of load_user_data()


// Load additional data
function load_additional_data($user_id, $domain_id)
{
	global $sql;
	global $domain_name, $domain_ip, $php_sup;
	global $cgi_supp, $username;

	// Get domain data
  $query = <<<SQL_QUERY
        select
            domain_name, domain_ip_id, domain_php, domain_cgi, domain_admin_id
        from
            domain
        where
            domain_id = ?
SQL_QUERY;

	$res = exec_query($sql, $query, array($domain_id));
    $data = $res -> FetchRow();

    $domain_name = $data['domain_name'];
    $domain_ip_id = $data['domain_ip_id'];
    $php_sup = $data['domain_php'];
    $cgi_supp = $data['domain_cgi'];
    $domain_admin_id = $data['domain_admin_id'];

	// Get IP of domain
	$query = <<<SQL_QUERY
        select
            ip_number, ip_domain
        from
            server_ips
        where
            ip_id = ?
SQL_QUERY;

    $res = exec_query($sql, $query, array($domain_ip_id));
    $data = $res -> FetchRow();

    $domain_ip = $data['ip_number'].'&nbsp;('.$data['ip_domain'].')';

	// Get username of domain
	$query = <<<SQL_QUERY
        select
            admin_name
        from
            admin
        where
            admin_id = ?
          and
            admin_type = 'user'
          and
            created_by = ?
SQL_QUERY;

    $res = exec_query($sql, $query, array($domain_admin_id, $user_id));
    $data = $res -> FetchRow();

    $username = $data['admin_name'];

}//End of load_additional_data()

// Show user data
function gen_editdomain_page(&$tpl)
{
	global $domain_name, $domain_ip, $php_sup;
	global $cgi_supp ,$sub, $als;
	global $mail, $ftp, $sql_db;
	global $sql_user, $traff, $disk;
	global $username;

	// Fill in the fileds

	$domain_name = decode_idna($domain_name);

	$username = decode_idna($username);

	generate_ip_list($tpl, $_SESSION['user_id']);

	if ($php_sup === 'yes') {
		$tpl -> assign(
					array(
							'PHP_YES' => 'selected',
							'PHP_NO' => '',
						)
				);
	} else {
		$tpl -> assign(
					array(
							'PHP_YES' => '',
							'PHP_NO' => 'selected',
						)
				);

	}
	if ($cgi_supp === 'yes') {
		$tpl -> assign(
					array(
							'CGI_YES' => 'selected',
							'CGI_NO' => '',
						)
				);
	} else {
		$tpl -> assign(
					array(
							'CGI_YES' => '',
							'CGI_NO' => 'selected',
						)
				);

	}

	$tpl -> assign(
                array(
                       	'VL_DOMAIN_NAME' => $domain_name,
						'VL_DOMAIN_IP' => $domain_ip,
						'VL_DOM_SUB' => $sub,
						'VL_DOM_ALIAS' => $als,
						'VL_DOM_MAIL_ACCOUNT' => $mail,
						'VL_FTP_ACCOUNTS' => $ftp,
						'VL_SQL_DB' => $sql_db,
						'VL_SQL_USERS' => $sql_user,
						'VL_TRAFFIC' => $traff,
						'VL_DOM_DISK' => $disk,
						'VL_USER_NAME' => $username
					)
			);

}// End of gen_editdomain_page()


// Function to update changes into db
function update_data_in_db($hpid)
{
	global $domain_name, $domain_ip, $php_sup;
	global $cgi_supp ,$sub, $als;
	global $mail, $ftp, $sql_db;
	global $sql_user, $traff, $disk;
	global $username, $domain_php, $domain_cgi;

	$user_props  = "$usub_current;$usub_max;";
	$user_props .= "$uals_current;$uals_max;";
	$user_props .= "$umail_current;$umail_max;";
	$user_props .= "$uftp_current;$uftp_max;";
	$user_props .= "$usql_db_current;$usql_db_max;";
	$user_props .= "$usql_user_current;$usql_user_max;";
	$user_props .= "$utraff_max;";
	$user_props .= "$udisk_max;";
	//$user_props .= "$domain_ip;";
	$user_props .= "$domain_php;";
	$user_props .= "$domain_cgi;";

	update_user_props($user_id, $user_props);

	$reseller_props  = "$rdmn_current;$rdmn_max;";
	$reseller_props .= "$rsub_current;$rsub_max;";
	$reseller_props .= "$rals_current;$rals_max;";
	$reseller_props .= "$rmail_current;$rmail_max;";
	$reseller_props .= "$rftp_current;$rftp_max;";
	$reseller_props .= "$rsql_db_current;$rsql_db_max;";
	$reseller_props .= "$rsql_user_current;$rsql_user_max;";
	$reseller_props .= "$rtraff_current;$rtraff_max;";
	$reseller_props .= "$rdisk_current;$rdisk_max;";

	update_reseller_props($reseller_id, $reseller_props);

	//$tpl -> assign('MESSAGE', tr('Domain properties updated successfully!'));

	unset($_SESSION['edit_id']);
	set_page_message(tr('Domain properties updated successfully!'));
	Header("Location: users.php");
	die();
}// End of update_data_in_db()


//Check input data
function check_user_data ( &$tpl, $reseller_id, $user_id) {

    global $sub, $als, $mail, $ftp, $sql_db, $sql_user, $traff, $disk, $sql, $domain_ip, $domain_php, $domain_cgi;

	$sub	= $_POST['dom_sub'];
	$als	= $_POST['dom_alias'];
	$mail	= $_POST['dom_mail_acCount'];
	$ftp	= $_POST['dom_ftp_acCounts'];
	$sql_db	= $_POST['dom_sqldb'];
	$sql_user= $_POST['dom_sql_users'];
	$traff	= $_POST['dom_traffic'];
	$disk	= $_POST['dom_disk'];
	//$domain_ip = $_POST['domain_ip'];
	$domain_php = $_POST['domain_php'];
	$domain_cgi = $_POST['domain_cgi'];

    $ed_error = '_off_';

    if (!vhcs_limit_check($sub, 999)) {

        $ed_error = tr('Incorrect subdomain range or syntax!');

    } else if (!vhcs_limit_check($als, 999)) {

        $ed_error = tr('Incorrect alias range or syntax!');

    } else if (!vhcs_limit_check($mail, 999)) {

        $ed_error = tr('Incorrect mail account range or syntax!');

    } else if (!vhcs_limit_check($ftp, 999) || $ftp == -1) {

        $ed_error = tr('Incorrect FTP account range or syntax!');

    } else if (!vhcs_limit_check($sql_db, 999)) {

        $ed_error = tr('Incorrect SQL user range or syntax!');

    } else if (!vhcs_limit_check($sql_user, 999)) {

        $ed_error = tr('Incorrect SQL database range or syntax!');

    } else if (!vhcs_limit_check($traff, 1024*1024) || $traff == -1) {

        $ed_error = tr('Incorrect traffic range or syntax!');

    } else if (!vhcs_limit_check($disk, 1024*1024) || $disk == -1) {

        $ed_error = tr('Incorrect disk range or syntax!');

    }

    //$user_props = generate_user_props($user_id);

    //$reseller_props = generate_reseller_props($reseller_id);

    list (
           $usub_current, $usub_max,
           $uals_current, $uals_max,
           $umail_current, $umail_max,
           $uftp_current, $uftp_max,
           $usql_db_current, $usql_db_max,
           $usql_user_current, $usql_user_max,
           $utraff_max, $udisk_max) = generate_user_props($user_id);

    list (
           $rdmn_current, $rdmn_max,
           $rsub_current, $rsub_max,
           $rals_current, $rals_max,
           $rmail_current, $rmail_max,
           $rftp_current, $rftp_max,
           $rsql_db_current, $rsql_db_max,
           $rsql_user_current, $rsql_user_max,
           $rtraff_current, $rtraff_max,
           $rdisk_current, $rdisk_max
         ) = get_reseller_default_props($sql, $reseller_id); //generate_reseller_props($reseller_id);


    list ($a, $b, $c, $d, $e, $f, $utraff_current, $udisk_current, $i, $h) = generate_user_traffic($user_id);

    if ($ed_error == '_off_') {

        calculate_user_dvals($sub, $usub_current, $usub_max, $rsub_current, $rsub_max, $ed_error, tr('Subdomain'));

    }

    if ($ed_error == '_off_') {

        calculate_user_dvals($als, $uals_current, $uals_max, $rals_current, $rals_max, $ed_error, tr('Alias'));

    }

    if ($ed_error == '_off_') {

        calculate_user_vals($mail, $umail_current, $umail_max, $rmail_current, $rmail_max, $ed_error, tr('Mail'));

    }

    if ($ed_error == '_off_') {

        calculate_user_vals($ftp, $uftp_current, $uftp_max, $rftp_current, $rftp_max, $ed_error, tr('FTP'));

    }

    if ($ed_error == '_off_') {

        calculate_user_dvals($sql_db, $usql_db_current, $usql_db_max, $rsql_db_current, $rsql_db_max, $ed_error, tr('SQL Database'));

    }

    if ($ed_error == '_off_') {

        calculate_user_dvals($sql_user, $usql_user_current, $usql_user_max, $rsql_user_current, $rsql_user_max, $ed_error, tr('SQL User'));

    }

    if ($ed_error == '_off_') {

        calculate_user_vals($traff, $utraff_current / 1024 / 1024 , $utraff_max, $rtraff_current, $rtraff_max, $ed_error, tr('Traffic'));

    }

    if ($ed_error == '_off_') {

        calculate_user_vals($disk, $udisk_current / 1024 / 1024, $udisk_max, $rdisk_current, $rdisk_max, $ed_error, tr('Disk'));

    }

    if ($ed_error == '_off_') {

        $user_props  = "$usub_current;$usub_max;";
        $user_props .= "$uals_current;$uals_max;";
        $user_props .= "$umail_current;$umail_max;";
        $user_props .= "$uftp_current;$uftp_max;";
        $user_props .= "$usql_db_current;$usql_db_max;";
        $user_props .= "$usql_user_current;$usql_user_max;";
        $user_props .= "$utraff_max;";
        $user_props .= "$udisk_max;";
		//$user_props .= "$domain_ip;";
		$user_props .= "$domain_php;";
		$user_props .= "$domain_cgi";
        update_user_props($user_id, $user_props);


        $reseller_props  = "$rdmn_current;$rdmn_max;";
        $reseller_props .= "$rsub_current;$rsub_max;";
        $reseller_props .= "$rals_current;$rals_max;";
        $reseller_props .= "$rmail_current;$rmail_max;";
        $reseller_props .= "$rftp_current;$rftp_max;";
        $reseller_props .= "$rsql_db_current;$rsql_db_max;";
        $reseller_props .= "$rsql_user_current;$rsql_user_max;";
        $reseller_props .= "$rtraff_current;$rtraff_max;";
        $reseller_props .= "$rdisk_current;$rdisk_max";

		update_reseller_props($reseller_id, $reseller_props);

        // update the sql quotas too
        $query = "select domain_name from domain where domain_id=?";
        $rs = exec_query($sql, $query, array($user_id));
        $temp_dmn_name = $rs->fields['domain_name'];

        $query = "SELECT count(name) as cnt from quotalimits where name=?";
        $rs = exec_query($sql, $query, array($temp_dmn_name));
        if ($rs -> fields['cnt'] > 0 ) {
          // we need to update it
          if($disk == 0 ) {
              $dlim = 0;
          }
          else {
              $dlim = $disk*1024*1024;
          }

          $query = "UPDATE quotalimits SET bytes_in_avail=? WHERE name=?";
          $rs = exec_query($sql, $query, array($dlim, $temp_dmn_name));

        }

        set_page_message(tr('Domain properties updated successfully!'));

        return true;

    } else {


		$tpl -> assign('MESSAGE', $ed_error);
		$tpl -> parse('PAGE_MESSAGE', 'page_message');

        return false;

    }

}//End of check_user_data()


function calculate_user_dvals ( $data, $u, &$umax, &$r, $rmax, &$err, $obj ) {

    if ($rmax == 0 && $umax == -1) {

        if ($data == -1) {

            return;

        } else if ($data == 0) {

            $umax = $data;

            return;

        } else if ($data > 0) {

            $umax = $data;

            $r += $umax;

            return;

        }

    } else if ($rmax == 0 && $umax == 0) {

        if ($data == -1) {

            if ($u > 0) {

                $err = '<b>'.$obj.tr('</b> Service can not be disabled !<br>There is <b>').$obj.tr('</b> records on the system!');

            } else {

                $umax = $data;

            }

            return;

        } else if ($data == 0) {

            return;

        } else if ($data > 0) {

            if ($u > $data) {

                $err = '<b>'.$obj.tr('</b> Service can not be limited !<br>Specified number is smaller then <b>').$obj.tr('</b> records, present on the system!');

            } else {

                $umax = $data;

                $r += $umax;

            }

            return;

        }

    } else if ($rmax == 0 && $umax > 0) {

        if ($data == -1) {

            if ($u > 0) {

                $err = '<b>'.$obj.tr('</b> Service can not be disabled !<br>There is <b>').$obj.tr('</b> records on the system!');

            } else {

                $r -= $umax;

                $umax = $data;

            }

            return;

        } else if ($data == 0) {

            $r -= $umax;

            $umax = $data;

            return;

        } else if ($data > 0) {

            if ($u > $data) {

                $err = '<b>'.$obj.tr('</b> Service can not be limited !<br>Specified number is smaller then <b>').$obj.tr('</b> records, present on the system!');

            } else {

                if ($umax > $data) {

                    $data_dec = $umax - $data;

                    $r -= $data_dec;

                } else {

                    $data_inc = $data - $umax;

                    $r += $data_inc;

                }

                $umax = $data;

            }

            return;

        }

    } else if ($rmax > 0 && $umax == -1) {

        if ($data == -1) {

            return;

        } else if ($data == 0) {

            $err = '<b>'.$obj.tr('</b> Service can not be unlimited !<br>There is reseller limits for the <b>').$obj.tr('</b> service!');

            return;

        } else if ($data > 0) {

            if ($r + $data > $rmax) {

                $err = '<b>'.$obj.tr('</b> Service can not be limited !<br>You are exceeding reseller limits for the <b>').$obj.tr('</b> service!');

            } else {

                $r += $data;

                $umax = $data;

            }

            return;

        }

    } else if ($rmax > 0 && $umax == 0) {

        //
        // We Can't Get Here! This clone is present only for
        //
        // sample purposes;
        //

        if ($data == -1) {

        } else if ($data == 0) {

        } else if ($data > 0) {

        }

    } else if ($rmax > 0 && $umax > 0) {

        if ($data == -1) {

            if ($u > 0) {

                $err = '<b>'.$obj.tr('</b> Service can not be disabled !<br>There is <b>').$obj.tr('</b> records on the system!');

            } else {

                $r -= $umax;

                $umax = $data;

            }

            return;

        } else if ($data == 0) {

            $err = '<b>'.$obj.tr('</b> Service can not be unlimited !<br>There is reseller limits for the <b>').$obj.tr('</b> service!');

            return;

        } else if ($data > 0) {

            if ($u > $data) {

                $err = '<b>'.$obj.tr('</b> Service can not be limited !<br>Specified number is smaller then <b>').$obj.tr('</b> records, present on the system!');

            } else {

                if ($umax > $data) {

                    $data_dec = $umax - $data;

                    $r -= $data_dec;

                } else {

                    $data_inc = $data - $umax;

                    if ($r + $data_inc > $rmax) {

                       $err = '<b>'.$obj.tr('</b> Service can not be limited !<br>You are exceeding reseller limits for the <b>').$obj.tr('</b> service!');

                       return;

                    }

                    $r += $data_inc;

                }

                $umax = $data;

            }

            return;

        }

    }

}// End of calculate_user_dvals()



function calculate_user_vals ( $data, $u, &$umax, &$r, $rmax, &$err, $obj ) {

    if ($rmax == 0 && $umax == 0) {

        if ($data == 0) {

            return;

        } else if ($data > 0) {

            if ($u > $data) {

                $err = '<b>'.$obj.tr('</b> Service can not be limited !<br>specified number is smaller then <b>').$obj.tr('</b> amount, present on the system!');

            } else {

                $umax = $data;

                $r += $umax;

            }

            return;

        }

    } else if ($rmax == 0 && $umax > 0) {

        if ($data == 0) {

            $r -= $umax;

            $umax = $data;

            return;

        } else if ($data > 0) {

            if ($u > $data) {

                $err = '<b>'.$obj.tr('</b> Service can not be limited !<br>Specified number is smaller then <b>').$obj.tr('</b> amount, present on the system!');

            } else {

                if ($umax > $data) {

                    $data_dec = $umax - $data;

                    $r -= $data_dec;

                } else {

                    $data_inc = $data - $umax;

                    $r += $data_inc;

                }

                $umax = $data;

            }

            return;

        }

    } else if ($rmax > 0 && $umax == 0) {

        //
        // We Can't Get Here! This clone is present only for
        //
        // sample purposes;
        //

        if ($data == 0) {

        } else if ($data > 0) {

        }

    } else if ($rmax > 0 && $umax > 0) {

        if ($data == 0) {

            $err = '<b>'.$obj.tr('</b> Service can not be unlimited !<br>There is reseller limits for the <b>').$obj.tr('</b> service!');

            return;

        } else if ($data > 0) {

            if ($u > $data) {

                $err = '<b>'.$obj.tr('</b> Service can not be limited !<br>Specified number is smaller then <b>').$obj.tr('</b> amount, present on the system!');

            } else {

                if ($umax > $data) {

                    $data_dec = $umax - $data;

                    $r -= $data_dec;

                } else {

                    $data_inc = $data - $umax;

                    if ($r + $data_inc > $rmax) {

                       $err = '<b>'.$obj.tr('</b> Service can not be limited !<br>You are exceeding reseller limits for the <b>').$obj.tr('</b> service!');

                       return;

                    }

                    $r += $data_inc;

                }

                $umax = $data;

            }

            return;

        }

    }

}// End of calculate_user_vals()
?>
