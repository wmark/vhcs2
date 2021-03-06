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

$tpl -> define_dynamic('page', $cfg['ADMIN_TEMPLATE_PATH'].'/manage_users.tpl');

$tpl -> define_dynamic('page_message', 'page');


$tpl -> define_dynamic('admin_message', 'page');

$tpl -> define_dynamic('admin_list', 'page');

$tpl -> define_dynamic('admin_item', 'admin_list');

$tpl -> define_dynamic('admin_delete_show', 'admin_item');

$tpl -> define_dynamic('admin_delete_link', 'admin_item');

$tpl -> define_dynamic('rsl_message', 'page');

$tpl -> define_dynamic('rsl_list', 'page');

$tpl -> define_dynamic('rsl_item', 'rsl_list');

$tpl -> define_dynamic('rsl_delete_show', 'rsl_item');

$tpl -> define_dynamic('rsl_delete_link', 'rsl_item');

$tpl -> define_dynamic('usr_message', 'page');

$tpl -> define_dynamic('usr_list', 'page');

$tpl -> define_dynamic('usr_item', 'usr_list');

$tpl -> define_dynamic('user_details', 'usr_list');

$tpl -> define_dynamic('usr_delete_show', 'usr_item');

$tpl -> define_dynamic('usr_delete_link', 'usr_item');

$tpl -> define_dynamic('icon', 'usr_item');

$tpl -> define_dynamic('scroll_prev_gray', 'page');

$tpl -> define_dynamic('scroll_prev', 'page');

$tpl -> define_dynamic('scroll_next_gray', 'page');

$tpl -> define_dynamic('scroll_next', 'page');


global $cfg;
$theme_color = $cfg['USER_INITIAL_THEME'];

$tpl -> assign(
                array(
                        'TR_ADMIN_MANAGE_USERS_PAGE_TITLE' => tr('VHCS - Admin/Manage Users'),
                        'THEME_COLOR_PATH' => "../themes/$theme_color",
                        'THEME_CHARSET' => tr('encoding'),
						'ISP_LOGO' => get_logo($_SESSION['user_id']),
                        'VHCS_LICENSE' => $cfg['VHCS_LICENSE']
                     )
              );

		
		
		if (isset($_POST['details']) && $_POST['details'] !== ''){		
		
			$_SESSION['details'] = $_POST['details'];
			
		} else {
			if (!isset($_SESSION['details']))
			{
				$_SESSION['details'] = "hide";
			}
		}



if (isset($_SESSION['user_added'])){
    
        unset($_SESSION['user_added']);
	
	set_page_message(tr('User added'));
	
}
else if (isset($_SESSION['reseller_added'])){
    
        unset($_SESSION['reseller_added']);
	
	set_page_message(tr('Reseller added'));
	
}
else if (isset($_SESSION['user_updated'])){
    
        unset($_SESSION['user_updated']);
	
	set_page_message(tr('User updated'));
	
}
else if (isset($_SESSION['user_deleted'])){
    
        unset($_SESSION['user_deleted']);
	
	set_page_message(tr('User deleted'));
	
}
else if (isset($_SESSION['email_updated'])){
    
        unset($_SESSION['email_updated']);
	
	set_page_message(tr('Email Updated'));
	
}
else if (isset($_SESSION['hdomain'])){
    
        unset($_SESSION['hdomain']);
	
	set_page_message(tr('This user have domain !<br>To delete user  - first delete domain!'));
	
}
else if (isset($_SESSION['user_disabled'])){
    
        unset($_SESSION['user_disabled']);
	
	set_page_message(tr('User was disabled'));
	
}

/*
 *
 * static page messages.
 *
 */

gen_admin_menu($tpl);

get_admin_manage_users($tpl, $sql);

gen_page_message($tpl);

$tpl -> parse('PAGE', 'page');

$tpl -> prnt();

if (isset($cfg['DUMP_GUI_DEBUG'])) dump_gui_debug();

unset_messages();
?>
