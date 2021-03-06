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

$tpl -> define_dynamic('page', $cfg['CLIENT_TEMPLATE_PATH'].'/protect_it.tpl');

$tpl -> define_dynamic('page_message', 'page');

$tpl -> define_dynamic('logged_from', 'page');

$tpl -> define_dynamic('group_item', 'page');

$tpl -> define_dynamic('user_item', 'page');

$tpl -> define_dynamic('unprotect_it', 'page');

$tpl -> define_dynamic('custom_buttons', 'page');





global $cfg;
$theme_color = $cfg['USER_INITIAL_THEME'];




$tpl -> assign(
                array(
                        'TR_CLIENT_WEBTOOLS_PAGE_TITLE' => tr('VHCS - Client/Webtools'),
                        'THEME_COLOR_PATH' => "../themes/$theme_color",
                        'THEME_CHARSET' => tr('encoding'),
						'TID' => $_SESSION['layout_id'],
                        'VHCS_LICENSE' => $cfg['VHCS_LICENSE'],
						'ISP_LOGO' => get_logo($_SESSION['user_id'])
                     )
              );


function protect_area(&$tpl, &$sql, &$dmn_id)
{
	global $cfg;
	if (isset($_POST['uaction']) && $_POST['uaction'] === 'protect_it')  {

		if (!isset($_POST['users']) && !isset($_POST['groups'])){
			
			set_page_message(tr('Please choose user or group'));
			return;
		
		} else if(!isset($_POST['paname']) || $_POST['paname'] == '') {
		
			set_page_message(tr('Please enter area name'));
			return;
		} else if(!isset($_POST['other_dir']) || $_POST['other_dir'] == '') {
		
			set_page_message(tr('Please enter area path'));
			return;
		} else if (!is_dir($cfg['FTP_HOMEDIR']."/".$_SESSION['user_logged'].$_POST['other_dir'])) {
			  set_page_message($_POST['other_dir'].tr(' do not exist'));
			  return;
		}
	
		$ptype = $_POST['ptype'];
		
		if(isset($_POST['users']))
			$users = $_POST['users'];
		
		if(isset($_POST['groups']))
			$groups = $_POST['groups'];
		
		$path = $_POST['other_dir'];
		$area_name = $_POST['paname'];
		
		
		
		
		$user_id = '';
		$group_id = '';
		if ($ptype == 'user') {
			
			
			for($i = 0;$i< count($users);$i++){
				if (count($users) == 1 || count($users) == $i+1){
					$user_id .= $users[$i];
					if ($user_id == '-1' ||$user_id == '') {
						set_page_message(tr('You can not protect area without selected usre(s)'));
					return;
					}
				} else {
					$user_id .= $users[$i].',';
				}
			}
			$group_id = 0;
			
		} else {
					
			for($i = 0;$i< count($groups);$i++){
				
				if (count($groups) == 1 || count($groups) == $i+1){
					$group_id .= $groups[$i];
					if ($group_id == '-1' || $group_id == '')  {
						set_page_message(tr('You can not protect area without selected group(s)'));
					return;
					}
				} else {
					$group_id .= $groups[$i].',';
				}
			}
			$user_id = 0;
		}

// lets check if we have to update or to make new enrie
	$alt_path = $path."/";
	 $query = <<<SQL_QUERY
        select
            id
        from
            htaccess
        where
             dmn_id = ?
		and
			(path = ?
				or
			path = ?)
SQL_QUERY;

    $rs = exec_query($sql, $query, array($dmn_id, $path, $alt_path));
	$basic = 'Basic';
	$toadd_status = $cfg['ITEM_ADD_STATUS'];
	$tochange_statsu = $cfg['ITEM_CHANGE_STATUS'];
	
	if ($rs -> RecordCount() !== 0) {
	$update_id = $rs -> fields['id'];
	$query = <<<SQL_QUERY
        update htaccess
		set
			user_id = ?,
			group_id = ?,
			auth_name = ?,
			path = ?,
			status = ?
        where 
			id = '$update_id'
SQL_QUERY;
	
	check_for_lock_file();
	send_request();
	$rs = exec_query($sql, $query, array($user_id, $group_id, $area_name, $path, $tochange_statsu));
	set_page_message( tr('Protected area updated successfully!'));
	
	}	else {

	$query = <<<SQL_QUERY
        insert into htaccess
            (dmn_id, user_id, group_id, auth_type, auth_name, path, status)
        values
            (?, ?, ?, ?, ?, ?, ?)
SQL_QUERY;

	check_for_lock_file();
	send_request();
    $rs = exec_query($sql, $query, array($dmn_id, $user_id, $group_id, $basic ,$area_name, $path, $toadd_status));
	set_page_message( tr('Protected area created successfully!'));
	}
	
	header( "Location: protected_areas.php");
	die();

	} 
	else {
		return;
	}

}

function gen_protect_it(&$tpl, &$sql, &$dmn_id)
{
global $cfg;
if (!isset($_GET['id'])){
	$edit = 'no';
	$type = 'user';
	$user_id = 0;
	$group_id = 0;
	$tpl -> assign(
                array(
						'PATH' => '',
						'AREA_NAME' => '',
						'UNPROTECT_IT' => '',
					  )
				);		
	
} else {

	$edit = 'yes';
	$ht_id  = $_GET['id'];
	
	$tpl -> assign('CDIR', $ht_id);
	$tpl -> parse('UNPROTECT_IT', 'unprotect_it');
	

	 $query = <<<SQL_QUERY
        select
            *
        from
            htaccess
        where
             dmn_id = ?
		and
			id = ?
SQL_QUERY;

    $rs = exec_query($sql, $query, array($dmn_id, $ht_id));
	
	if ($rs -> RecordCount() == 0) {
		header( 'Location: protect_it.php' );
		die();
	}	
	
	$user_id = $rs -> fields['user_id'];
	$group_id = $rs -> fields['group_id'];
	$status = $rs -> fields['status'];
	$path = $rs -> fields['path'];	
	$auth_name = $rs -> fields['auth_name'];
	$ok_status = $cfg['ITEM_OK_STATUS'];
	if ($status !== $ok_status) {
		set_page_message(tr('Protected area status should be OK if you wannt to edit it!'));
		header( "Location: protected_areas.php");
		die();
	}

	
	$tpl -> assign(
                array(
						'PATH' => $path,
						'AREA_NAME' => $auth_name,
					  )
				);		

	
	 // lets get the htaccess management type
		 if ($user_id !== 0 and $group_id == 0){
		 	// we habe only user htaccess
		 	$type = 'user';
		 } else if ($group_id !== 0 and $user_id == 0) {
		 	// we habe only groups htaccess
		 	$type = 'group';
		 } else if ($group_id == 0 and $user_id == 0) {
		 	// we habe unsr and groups htaccess
		 	$type = 'both';
		 }
}	
	// this area is not secured by htaccess
	if ($edit = 'no' || $rs -> RecordCount() == 0 || $type == 'user') {
	
		$tpl -> assign(
                array(
						'USER_CHECKED' => " checked ",
						'GROUP_CHECKED' => "",
						'USER_FORM_ELEMENS' => "false",
						'GROUP_FORM_ELEMENS' => "true",
					  )
				);		
	}	
	
	if ($type == 'group') {
		$tpl -> assign(
                array(
						'USER_CHECKED' => "",
						'GROUP_CHECKED' => " checked ",
						'USER_FORM_ELEMENS' => "true",
						'GROUP_FORM_ELEMENS' => "false",
					  )
				);	
	}

	$query = <<<SQL_QUERY
        select
            *
        from
            htaccess_users
        where
             dmn_id = ?

SQL_QUERY;

    $rs = exec_query($sql, $query, array($dmn_id));
	
			if ($rs -> RecordCount() == 0) {
				
				
				 $tpl -> assign(
						array(
								'USER_VALUE' => "-1",
								'USER_LEBEL' => tr('You have no users !')
							  )
						);	
					$tpl -> parse('USER_ITEM', 'user_item');
					
			} else {
				
					while (!$rs -> EOF) {
					
					$usr_id = split(',', $user_id);
					for ($i = 0; $i < count($usr_id); $i++) {
					
									
						if ($edit == 'yes' && $usr_id[$i] == $rs -> fields['id']) {
							$i = count($usr_id)+1;
							$usr_selected = " selected ";
						} else {
							$usr_selected = "";
						}
					}
					

								$tpl -> assign(
								array(
										'USER_VALUE' => $rs -> fields['id'],
										'USER_LEBEL' => $rs -> fields['uname'],
										'USER_SELECTED' => $usr_selected,
									  )
								);	
						
						
						$tpl -> parse('USER_ITEM', '.user_item'); 

						$rs -> MoveNext(); 
					
					
					}
			}

	$query = <<<SQL_QUERY
        select
            *
        from
            htaccess_groups
        where
             dmn_id = ?

SQL_QUERY;

    $rs = exec_query($sql, $query, array($dmn_id));
	
			if ($rs -> RecordCount() == 0) {
				$tpl -> assign(
						array(
								'GROUP_VALUE' => "-1",
								'GROUP_LEBEL' => tr('You have no groups !')
							  )
						);	
				$tpl -> parse('GROUP_ITEM', 'group_item');
			
			} else {
				
				while (!$rs -> EOF) {
				
				$grp_id = split(',', $group_id);
				for ($i = 0; $i < count($grp_id); $i++) {
				
								
					if ($edit == 'yes' && $grp_id[$i] == $rs -> fields['id']) {
						$i = count($grp_id)+1;
						$grp_selected = "selected";
					} else {
						$grp_selected = "";
					}
				}
					
					$tpl -> assign(
						array(
								'GROUP_VALUE' => $rs -> fields['id'],
								'GROUP_LEBEL' => $rs -> fields['ugroup'],
								'GROUP_SELECTED' => $grp_selected,
							  )
						);					
				   $tpl -> parse('GROUP_ITEM', '.group_item'); 
				   $rs -> MoveNext();   
				 }
			
			}

				
}


/*
 *
 * static page messages.
 *
 */

gen_client_menu($tpl);

gen_logged_from($tpl);

check_permissions($tpl);

$dmn_id = get_user_domain_id($sql, $_SESSION['user_id']);

protect_area($tpl, $sql, $dmn_id);

gen_protect_it($tpl, $sql, $dmn_id);

$tpl -> assign(
                array(
						'TR_HTACCESS' => tr('Protected areas'),
						'TR_PROTECT_DIR' => tr('Protect this area'),
						'TR_PATH' => tr('Path'),
						'TR_USER' => tr('Users'),
						'TR_GROUPS' => tr('Groups'),						
						'TR_PROTECT_IT' => tr('Protect it'),
						'TR_USER_AUTH' => tr('User auth'),
						'TR_GROUP_AUTH' => tr('Group auth'),
						'TR_AREA_NAME' => tr('Area name'),
						'TR_PROTECT_IT' => tr('Protect it'),
						'TR_UNPROTECT_IT' => tr('Unprotect it'),
						'TR_AREA_NAME' => tr('Area name'),
						'TR_CANCEL' =>  tr('Cancel'),
						'TR_MANAGE_USRES' => tr('Manage users and groups'),
						'CHOOSE_DIR' => tr('Choose dir'),
					  )
				);

gen_page_message($tpl);

$tpl -> parse('PAGE', 'page');

$tpl -> prnt();

if (isset($cfg['DUMP_GUI_DEBUG'])) dump_gui_debug();

unset_messages();
?>
