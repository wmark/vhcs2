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

$tpl -> define_dynamic('page', $cfg['PURCHASE_TEMPLATE_PATH'].'/index.tpl');

$tpl -> define_dynamic('purchase_list', 'page');

$tpl -> define_dynamic('purchase_message', 'page');

$tpl -> define_dynamic('purchase_header', 'page');

$tpl -> define_dynamic('purchase_footer', 'page');


/*
* Functions start
*/
function gen_packages_list(&$tpl, &$sql, $user_id)
{
	
	$query = <<<SQL_QUERY
			select
				*
			from
				hosting_plans
			where
				reseller_id = ?
			  and
				status = '1'
SQL_QUERY;

  $rs = exec_query($sql, $query, array($user_id));

  if ($rs -> RecordCount() == 0) {

    system_message(tr('No available hosting packages'));
  } else {
  
  	while (!$rs -> EOF) {
			$description = $rs -> fields['description'];
			if ($description == ''){
				$description = '';
			}
			$price = $rs -> fields['price'];
			if ($price == 0 || $price == '') {
				$price = "/ ".tr('free of charge');
			} else {
				$price = "/ ".$price." ".$rs -> fields['value']." ".$rs -> fields['payment'];
			}
			
			
			
            $tpl -> assign(
                            array(
                                    'PACK_NAME' => $rs -> fields['name'],
                                    'PACK_ID' => $rs -> fields['id'],
                                    'USER_ID' => $user_id,
                                    'PURCHASE' => tr('Purchase'),
                                    'PACK_INFO' => $description,
									'PRICE' => $price,
                                 )
                          );
			
			$tpl -> parse('PURCHASE_LIST', '.purchase_list');

            $rs -> MoveNext();	
	}

  }

}


/*
* Functions end
*/






/*
*
* static page messages.
*
*/

if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])){
	
	$user_id = $_GET['user_id'];
	$_SESSION['user_id'] = $user_id;
	
} else if (isset($_SESSION['user_id'])){

	$user_id = $_SESSION['user_id'];

} else {
	system_message(tr('You do not have permission to access this interface!'));
}
if (isset($_SESSION['plan_id']))
	unset($_SESSION['plan_id']);

gen_purchase_haf($tpl, $sql, $user_id);
gen_packages_list($tpl, $sql, $user_id);

gen_page_message($tpl);

$tpl -> parse('PAGE', 'page');

$tpl -> prnt();

if (isset($cfg['DUMP_GUI_DEBUG'])) dump_gui_debug();

unset_messages();
?>