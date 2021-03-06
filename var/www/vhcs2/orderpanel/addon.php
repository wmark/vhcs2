<?php
//   -------------------------------------------------------------------------------
//  |             VHCS(tm) - Virtual Hosting Control System                         |
//  |              Copyright (c) 2001-2005 be moleSoftware		            		|
//  |			http://vhcs.net | http://www.molesoftware.com		           		|
//  |                                                                               |
//  | This program is free software; you can redistribute it and/or                 |
//  | modify it under the terms of the MPL General Public License                   |
//  | as published by the Free Software Foundation; either version 1.1              |
//  | of the License, or (at your option) any later version.                        |
//  |                                                                               |
//  | You should have received a copy of the MPL Mozilla Public License             |
//  | along with this program; if not, write to the Free Software                   |
//  | Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA     |
//  |                                                                               |
//   -------------------------------------------------------------------------------



include '../include/vhcs-lib.php';

$tpl = new pTemplate();

$tpl -> define_dynamic('page', $cfg['PURCHASE_TEMPLATE_PATH'].'/addon.tpl');

$tpl -> define_dynamic('page_message', 'page');

$tpl -> define_dynamic('purchase_header', 'page');

$tpl -> define_dynamic('purchase_footer', 'page');


/*
* Functions start
*/

function addon_domain(&$tpl, $dmn_name)
{
	$dmn_name = strtolower($dmn_name);
	$dmn_name = get_punny($dmn_name);
	
	if (!vhcs_domain_check($dmn_name)) {

        set_page_message(tr('Wrong domain name syntax!'));
		return;

    } else if (vhcs_domain_exists($dmn_name)) {

        set_page_message(tr('Domain with that name already exists on the system!'));
		return;

    }
	
	$_SESSION['domainname'] = $dmn_name;
	header( "Location: address.php" );
	die();

}

/*
* Functions end
*/






/*
*
* static page messages.
*
*/

if (isset($_SESSION['user_id'])){
	$user_id = $_SESSION['user_id'];
	
	if (isset($_SESSION['plan_id'])){
		$plan_id = $_SESSION['plan_id'];
	} else if(isset($_GET['id'])){
		$plan_id = $_GET['id'];
		$_SESSION['plan_id'] = $plan_id;
	} else {
		system_message(tr('You do not have permission to access this interface!'));
	}
} else {
	system_message(tr('You do not have permission to access this interface!'));
}

if (isset($_SESSION['domainname'])){
	header( "Location: address.php" );
	die();
}

if (isset($_POST['domainname']) && $_POST['domainname'] != ''){
	addon_domain($tpl, $_POST['domainname']);
}

gen_purchase_haf($tpl, $sql, $user_id);

gen_page_message($tpl);

	$tpl -> assign(
                array(
                       	'DOMAIN_ADDON' => tr('Add On A Domain'),
						'TR_DOMAIN_NAME' => tr('Domain name'),
						'TR_CONTINUE' => tr('Continue'),
						'TR_EXAMPLE' => tr('(Ex. domain-of-your-choice.com)'),


					)
			);

$tpl -> parse('PAGE', 'page');

$tpl -> prnt();

if (isset($cfg['DUMP_GUI_DEBUG'])) dump_gui_debug();

unset_messages();
?>