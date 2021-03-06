<?php
//   -------------------------------------------------------------------------------
//  |             VHCS(tm) - Virtual Hosting Control System                         |
//  |              Copyright (c) 2001-2005 by moleSoftware  |
//  |     http://vhcs.net | http://www.molesoftware.com                 |
//  |                                                                               |
//  | This program is free software; you can redistribute it and/or                 |
//  | modify it under the terms of the MPL General Public License                   |
//  | as published by the Free Software Foundation; either version 1.1              |
//  | of the License, or (at your option) any later version.                        |
//  |                                                                               |
//  | You should have received a copy of the MPL Mozilla Public License             |
//  | along with this program; if not, write to the Open Source Initiative (OSI)    |
//  | http://opensource.org | osi@opensource.org                    |
//  |                                                                               |
//   -------------------------------------------------------------------------------


include '../include/vhcs-lib.php';

check_login();

$tpl = new pTemplate();

$tpl -> define_dynamic('page', $cfg['CLIENT_TEMPLATE_PATH'].'/add_sql_database.tpl');
$tpl -> define_dynamic('page_message', 'page');
$tpl -> define_dynamic('logged_from', 'page');
$tpl -> define_dynamic('custom_buttons', 'page');
$tpl -> define_dynamic('mysql_prefix_no', 'page');
$tpl -> define_dynamic('mysql_prefix_yes', 'page');
$tpl -> define_dynamic('mysql_prefix_infront', 'page');
$tpl -> define_dynamic('mysql_prefix_behind', 'page');
$tpl -> define_dynamic('mysql_prefix_all', 'page');

//
// page functions.
//
function gen_page_post_data(&$tpl)
{
  global $cfg;
  if ($cfg['MYSQL_PREFIX'] === 'no') {
    $tpl -> assign('MYSQL_PREFIX_YES', '');
  } else {
    $tpl -> assign('MYSQL_PREFIX_NO', '');
  }
    if ($cfg['MYSQL_PREFIX_TYPE'] === 'infront'){
      $tpl -> parse('MYSQL_PREFIX_INFRONT', 'mysql_prefix_infront');
      $tpl -> assign('MYSQL_PREFIX_BEHIND', '');
      $tpl -> assign('MYSQL_PREFIX_ALL', '');

    } else if ($cfg['MYSQL_PREFIX_TYPE'] === 'behind'){
      $tpl -> assign('MYSQL_PREFIX_INFRONT', '');
      $tpl -> parse('MYSQL_PREFIX_BEHIND', 'mysql_prefix_behind');
      $tpl -> assign('MYSQL_PREFIX_ALL', '');
    } else {
      $tpl -> assign('MYSQL_PREFIX_INFRONT', '');
      $tpl -> assign('MYSQL_PREFIX_BEHIND', '');
      $tpl -> parse('MYSQL_PREFIX_ALL', 'mysql_prefix_all');
    }

    if (isset($_POST['uaction']) && $_POST['uaction'] === 'add_db') {
      $tpl -> assign(array('DB_NAME' => $_POST['db_name'],
                           'USE_DMN_ID' => (isset($_POST['use_dmn_id']) && $_POST['use_dmn_id'] === 'on') ? 'checked' : '',
                           'START_ID_POS_CHECKED' => (isset($_POST['id_pos']) && $_POST['id_pos'] !== 'end') ? 'checked' : '',
                           'END_ID_POS_CHECKED' => (isset($_POST['id_pos']) && $_POST['id_pos'] === 'end') ? 'checked' : ''));
    } else {
      $tpl -> assign(array('DB_NAME' => '',
                           'USE_DMN_ID' => '',
                           'START_ID_POS_CHECKED' => 'checked',
                           'END_ID_POS_CHECKED' => ''));
    }
}

function check_db_name(&$sql, $db_name)
{
  $query = <<<SQL_QUERY
        show databases
SQL_QUERY;

    $rs = exec_query($sql, $query, array());

    while (!$rs -> EOF) {
      if ($db_name === $rs -> fields[0]) return 1;
      $rs -> MoveNext();
    }

    return 0;
}

function add_sql_database(&$sql, $user_id)
{
  global $cfg;

  if (!isset($_POST['uaction'])) return;

  $root_sql = &ADONewConnection('mysql');

  if (!@$root_sql -> Connect($cfg['DB_HOST'], $cfg['DB_USER'], $cfg['DB_PASS'])) {
    set_page_message(tr('Can not connect as MySQL administrator!'));
    return;
  }

  //
  // let's generate database name.
  //
  if ($_POST['db_name'] === '') {
    set_page_message(tr('Please type database name!'));
    return;
  }

  $dmn_id = get_user_domain_id($sql, $user_id);

  if (isset($_POST['use_dmn_id']) && $_POST['use_dmn_id'] === 'on') {
    //
    // we'll use domain_id in the name of the database;
    //
    if (isset($_POST['id_pos']) && $_POST['id_pos'] === 'start') {
      $db_name = $dmn_id."_".$_POST['db_name'];
    } else if (isset($_POST['id_pos']) && $_POST['id_pos'] === 'end') {
      $db_name = $_POST['db_name']."_".$dmn_id;
    }

  } else {
    $db_name = $_POST['db_name'];
  }

  if (strlen($db_name) > $cfg['MAX_SQL_DATABASE_LENGTH']) {
    set_page_message(tr('Too long database name!'));
    return;
  }

  //
  // have we such database in the system!?
  //
  if (check_db_name($root_sql, $db_name)) {
    set_page_message(tr('Specified database name already exists!'));
    return;
  }
  
   // are wildcards used?
  //
  if (ereg("\%|\?", $db_name)) {
    set_page_message(tr('Wildcards as % and ? are not allowed!'));
    return;
  }

  $query = 'create database ' . quoteIdentifier($db_name);
  $rs = exec_query($root_sql, $query, array());

  $query = <<<SQL_QUERY
        insert into sql_database
            (domain_id, sqld_name)
        values
            (?, ?)
SQL_QUERY;

  $rs = exec_query($sql, $query, array($dmn_id, $db_name));

  write_log($_SESSION['user_logged']." : add new SQL  database  -> ".$db_name);
  set_page_message(tr('SQL database created successfully!'));
  user_goto('manage_sql.php');
}

//
// common page data.
//

// check User sql permision
function check_sql_permissions($sql, $user_id)
{
  if (isset($_SESSION['sql_support']) && $_SESSION['sql_support'] == "no") {
    header("Location: index.php");
  }

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

  list($sqld_acc_cnt, $sqlu_acc_cnt) = get_domain_running_sql_acc_cnt($sql, $dmn_id);

  if ($dmn_sqld_limit != 0 &&  $sqld_acc_cnt >= $dmn_sqld_limit) {
    set_page_message(tr('SQL accounts limit expired!'));
    header("Location: manage_sql.php");
    die();
  }
}

global $cfg;
$theme_color = $cfg['USER_INITIAL_THEME'];

$tpl -> assign(array('TR_CLIENT_ADD_SQL_DATABASE_PAGE_TITLE' => tr('VHCS - Client/Add SQL Database'),
                     'THEME_COLOR_PATH' => "../themes/$theme_color",
                     'THEME_CHARSET' => tr('encoding'),
                     'TID' => $_SESSION['layout_id'],
                     'VHCS_LICENSE' => $cfg['VHCS_LICENSE'],
                     'ISP_LOGO' => get_logo($_SESSION['user_id'])));

//
// dynamic page data.
//
check_sql_permissions($sql, $_SESSION['user_id']);

gen_page_post_data($tpl);
add_sql_database($sql, $_SESSION['user_id']);
gen_client_menu($tpl);
gen_logged_from($tpl);
check_permissions($tpl);

$tpl -> assign(array('TR_ADD_DATABASE' => tr('Add SQL database'),
                     'TR_DB_NAME' => tr('Database name'),
                     'TR_USE_DMN_ID' => tr('Use numeric ID'),
                     'TR_START_ID_POS' => tr('In front the name'),
                     'TR_END_ID_POS' => tr('Behind the name'),
                     'TR_ADD' => tr('Add')));

gen_page_message($tpl);
$tpl -> parse('PAGE', 'page');
$tpl -> prnt();

if (isset($cfg['DUMP_GUI_DEBUG'])) dump_gui_debug();

?>
