<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset={THEME_CHARSET}">
<title>{TR_ADMIN_CUSTOM_MENUS_PAGE_TITLE}</title>
<link href="{THEME_COLOR_PATH}/css/vhcs.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="{THEME_COLOR_PATH}/css/vhcs.js"></script>
<script language="JavaScript" type="text/JavaScript">
<!--

function action_delete(url) {
	if (!confirm("{TR_MESSAGE_DELETE}"))
		return false;

	location = url;
}
//-->
</script>
<style type="text/css">
<!--
.style1 {font-weight: bold}
-->
</style>
</head>

<body onLoad="MM_preloadImages('{THEME_COLOR_PATH}/images/icons/database_a.gif','{THEME_COLOR_PATH}/images/icons/hosting_plans_a.gif','{THEME_COLOR_PATH}/images/icons/domains_a.gif','{THEME_COLOR_PATH}/images/icons/general_a.gif','{THEME_COLOR_PATH}/images/icons/logout_a.gif','{THEME_COLOR_PATH}/images/icons/manage_users_a.gif','{THEME_COLOR_PATH}/images/icons/webtools_a.gif','{THEME_COLOR_PATH}/images/icons/statistics_a.gif','{THEME_COLOR_PATH}/images/icons/support_a.gif')">
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%">
  <tr>
    <td height="80" align="left" valign="top">
    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="17"><img src="{THEME_COLOR_PATH}/images/top/left.jpg" width="17" height="80"></td>
          <td width="198" align="center" background="{THEME_COLOR_PATH}/images/top/logo_background.jpg"><img src="{ISP_LOGO}"></td>
          <td background="{THEME_COLOR_PATH}/images/top/left_fill.jpg"><img src="{THEME_COLOR_PATH}/images/top/left_fill.jpg" width="2" height="80"></td>
          <td width="766"><img src="{THEME_COLOR_PATH}/images/top/middle_background.jpg" width="766" height="80"></td>
          <td background="{THEME_COLOR_PATH}/images/top/right_fill.jpg"><img src="{THEME_COLOR_PATH}/images/top/right_fill.jpg" width="3" height="80"></td>
          <td width="9"><img src="{THEME_COLOR_PATH}/images/top/right.jpg" width="9" height="80"></td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td valign="top"><table height="100%" width="100%"  border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="215" valign="top" bgcolor="#F5F5F5">
		<table width="211" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="60" background="{THEME_COLOR_PATH}/images/menu/menu_top_left_bckgr.jpg"><img src="{THEME_COLOR_PATH}/images/icons/general_big.gif" width="60" height="62"></td>
            <td width="151" background="{THEME_COLOR_PATH}/images/menu/menu_top_bckgr.jpg" class="title">{TR_MENU_GENERAL_INFORMATION}</td>
          </tr>
        </table>
		<table width="205" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5">
          <tr background="{THEME_COLOR_PATH}/images/line.jpg">
            <td colspan="3" background="{THEME_COLOR_PATH}/images/line.jpg"><img src="{THEME_COLOR_PATH}/images/line.jpg" width="2" height="7"><img src="{THEME_COLOR_PATH}/images/line.jpg" width="2" height="7"></td>
            </tr>
          <tr>
            <td colspan="3"><img src="{THEME_COLOR_PATH}/images/trans.gif" width="30" height="4"></td>
          </tr>
          <tr>
            <td width="28" background="{THEME_COLOR_PATH}/images/menu/button_background.jpg" class="menu"><a href="#" onMouseOver="MM_swapImage('general','','{THEME_COLOR_PATH}/images/icons/general_a.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="{THEME_COLOR_PATH}/images/menu/open_pointer.jpg" width="28" height="36" border="0"></a></td>
            <td background="{THEME_COLOR_PATH}/images/menu/open_background.gif" class="menu"><a href="index.php" class="menu_active" onMouseOver="MM_swapImage('general','','{THEME_COLOR_PATH}/images/icons/general_a.gif',1)" onMouseOut="MM_swapImgRestore()">{TR_MENU_GENERAL_INFORMATION}</a></td>
            <td width="36" align="right" background="{THEME_COLOR_PATH}/images/menu/open_icon_bcgr.jpg" class="menu"><a href="#" onMouseOver="MM_swapImage('general','','{THEME_COLOR_PATH}/images/icons/general_a.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="{THEME_COLOR_PATH}/images/icons/general_a.gif" name="general" width="36" height="36" border="0" id="general"></a></td>
          </tr>
          <tr background="{THEME_COLOR_PATH}/images/menu/open_background.jpg">
            <td colspan="3" class="menu" background="{THEME_COLOR_PATH}/images/menu/open_background.jpg"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="5" rowspan="12" background="{THEME_COLOR_PATH}/images/menu/open_background_left.gif"><img src="{THEME_COLOR_PATH}/images/trans.gif" width="12" height="1"></td>
                <td><img src="{THEME_COLOR_PATH}/images/icons/document.gif" width="12" height="15"></td>
                <td><a href="index.php" class="submenu">{TR_MENU_OVERVIEW}</a></td>
                <td width="5" rowspan="12" background="{THEME_COLOR_PATH}/images/menu/open_background_right.gif"><img src="{THEME_COLOR_PATH}/images/trans.gif" width="5" height="1"></td>
              </tr>
              <tr>
                <td colspan="2"><img src="{THEME_COLOR_PATH}/images/trans.gif" width="30" height="4"></td>
                </tr>
              <tr>
                <td><img src="{THEME_COLOR_PATH}/images/icons/document.gif" width="12" height="15"></td>
                <td><a href="change_password.php" class="submenu">{TR_MENU_CHANGE_PASSWORD}</a></td>
                </tr>
              <tr>
                <td colspan="2"><img src="{THEME_COLOR_PATH}/images/trans.gif" width="30" height="4"></td>
              </tr>
              <tr>
                <td width="15"><img src="{THEME_COLOR_PATH}/images/icons/document.gif" width="12" height="15"></td>
                <td><a href="change_personal.php" class="submenu">{TR_MENU_CHANGE_PERSONAL_DATA}</a></td>
              </tr>
              <tr>
                <td colspan="2"><img src="{THEME_COLOR_PATH}/images/trans.gif" width="30" height="4"></td>
              </tr>
              <tr>
                <td><img src="{THEME_COLOR_PATH}/images/icons/document.gif" width="12" height="15"></td>
                <td><a href="multilanguage.php" class="submenu">{TR_MENU_I18N}</a></td>
              </tr>
              <tr>
                <td colspan="2"><img src="{THEME_COLOR_PATH}/images/trans.gif" width="30" height="4"></td>
                </tr>
              <tr>
                <td><img src="{THEME_COLOR_PATH}/images/icons/document.gif" width="12" height="15"></td>
                <td><a href="layout.php" class="submenu">{TR_MENU_LAYOUT_TEMPLATES}</a></td>
              </tr>
              <tr>
                <td colspan="2"><img src="{THEME_COLOR_PATH}/images/trans.gif" width="30" height="4"></td>
                </tr>
              <tr>
                <td><img src="{THEME_COLOR_PATH}/images/icons/document.gif" width="12" height="15"></td>
                <td><a href="custom_menus.php" class="submenu">{TR_CUSTOM_MENUS}</a></td>
              </tr>
              <tr>
                <td colspan="2"><img src="{THEME_COLOR_PATH}/images/trans.gif" width="30" height="4"></td>
              </tr>
            </table>
            </td>
          </tr>
          <tr>
            <td class="menu"><img src="{THEME_COLOR_PATH}/images/menu/open_down_left.gif" width="28" height="7"></td>
            <td background="{THEME_COLOR_PATH}/images/menu/open_down.gif" class="menu"><img src="{THEME_COLOR_PATH}/images/menu/open_down.gif" width="4" height="7"></td>
            <td align="right" class="menu"><img src="{THEME_COLOR_PATH}/images/menu/open_down_right.gif" width="36" height="7"></td>
          </tr>
          <tr>
            <td colspan="3"><img src="{THEME_COLOR_PATH}/images/trans.gif" width="30" height="4"></td>
            </tr>
			 <tr>
            <td background="{THEME_COLOR_PATH}/images/menu/button_background.jpg" class="menu"><a href="manage_users.php" onMouseOver="MM_swapImage('domains','','{THEME_COLOR_PATH}/images/icons/manage_users_a.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="{THEME_COLOR_PATH}/images/menu/pointer.jpg" name="Image1" width="28" height="36" border="0" id="Image1"></a></td>
            <td background="{THEME_COLOR_PATH}/images/menu/button_background.jpg" class="menu"><a href="manage_users.php" class="menu"  onMouseOver="MM_swapImage('domains','','{THEME_COLOR_PATH}/images/icons/manage_users_a.gif',1)" onMouseOut="MM_swapImgRestore()">{TR_MENU_MANAGE_USERS}</a></td>
            <td align="right" background="{THEME_COLOR_PATH}/images/icons/icon_bcgr.gif" class="menu"><a href="manage_users.php" onMouseOver="MM_swapImage('domains','','{THEME_COLOR_PATH}/images/icons/manage_users_a.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="{THEME_COLOR_PATH}/images/icons/manage_users.gif" name="domains" width="36" height="36" border="0" id="domains"></a></td>
          </tr>
		   <tr>
            <td colspan="3"><img src="{THEME_COLOR_PATH}/images/trans.gif" width="30" height="4"></td>
            </tr>
		   <tr>
            <td background="{THEME_COLOR_PATH}/images/menu/button_background.jpg" class="menu"><a href="sysinfo.php" onMouseOver="MM_swapImage('webtools','','{THEME_COLOR_PATH}/images/icons/webtools_a.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="{THEME_COLOR_PATH}/images/menu/pointer.jpg" width="28" height="36" border="0"></a></td>
            <td background="{THEME_COLOR_PATH}/images/menu/button_background.jpg" class="menu"><a href="sysinfo.php" class="menu"  onMouseOver="MM_swapImage('webtools','','{THEME_COLOR_PATH}/images/icons/webtools_a.gif',1)" onMouseOut="MM_swapImgRestore()">{TR_MENU_SYSTEM_TOOLS}</a></td>
            <td align="right" background="{THEME_COLOR_PATH}/images/icons/icon_bcgr.gif" class="menu"><a href="sysinfo.php" onMouseOver="MM_swapImage('webtools','','{THEME_COLOR_PATH}/images/icons/webtools_a.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="{THEME_COLOR_PATH}/images/icons/webtools.gif" name="webtools" width="36" height="36" border="0" id="webtools"></a></td>
          </tr>
          <tr>
            <td colspan="3"><img src="{THEME_COLOR_PATH}/images/trans.gif" width="30" height="4"></td>
            </tr>
		   <tr>
            <td background="{THEME_COLOR_PATH}/images/menu/button_background.jpg" class="menu"><a href="server_statistic.php" onMouseOver="MM_swapImage('statistics','','{THEME_COLOR_PATH}/images/icons/statistics_a.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="{THEME_COLOR_PATH}/images/menu/pointer.jpg" width="28" height="36" border="0"></a></td>
            <td background="{THEME_COLOR_PATH}/images/menu/button_background.jpg" class="menu"><a href="server_statistic.php" class="menu" onMouseOver="MM_swapImage('statistics','','{THEME_COLOR_PATH}/images/icons/statistics_a.gif',1)" onMouseOut="MM_swapImgRestore()">{TR_MENU_STATISTICS}</a></td>
            <td align="right" background="{THEME_COLOR_PATH}/images/icons/icon_bcgr.gif" class="menu"><a href="server_statistic.php" onMouseOver="MM_swapImage('statistics','','{THEME_COLOR_PATH}/images/icons/statistics_a.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="{THEME_COLOR_PATH}/images/icons/statistics.gif" name="statistics" width="36" height="36" border="0" id="statistics"></a></td>
          </tr>
		  <!-- BDP: support_system -->
		  <tr>
            <td colspan="3"><img src="{THEME_COLOR_PATH}/images/trans.gif" width="30" height="4"></td>
            </tr>
		   <tr>
            <td background="{THEME_COLOR_PATH}/images/menu/button_background.jpg" class="menu"><a href="{SUPPORT_SYSTEM_PATH}" target="{SUPPORT_SYSTEM_TARGET}"  onMouseOver="MM_swapImage('support','','{THEME_COLOR_PATH}/images/icons/support_a.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="{THEME_COLOR_PATH}/images/menu/pointer.jpg" width="28" height="36" border="0"></a></td>
            <td background="{THEME_COLOR_PATH}/images/menu/button_background.jpg" class="menu"><a href="{SUPPORT_SYSTEM_PATH}" target="{SUPPORT_SYSTEM_TARGET}"  class="menu" onMouseOver="MM_swapImage('support','','{THEME_COLOR_PATH}/images/icons/support_a.gif',1)" onMouseOut="MM_swapImgRestore()">{TR_MENU_SUPPORT_SYSTEM}</a></td>
            <td align="right" background="{THEME_COLOR_PATH}/images/icons/icon_bcgr.gif" class="menu"><a href="{SUPPORT_SYSTEM_PATH}" target="{SUPPORT_SYSTEM_TARGET}"  onMouseOver="MM_swapImage('support','','{THEME_COLOR_PATH}/images/icons/support_a.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="{THEME_COLOR_PATH}/images/icons/support.gif" name="support" width="36" height="36" border="0" id="support"></a></td>
          </tr>
		  <!-- EDP: support_system -->
		   <tr>
            <td colspan="3"><img src="{THEME_COLOR_PATH}/images/trans.gif" width="30" height="4"></td>
            </tr>
		   <tr>
            <td background="{THEME_COLOR_PATH}/images/menu/button_background.jpg" class="menu"><a href="../index.php" onMouseOver="MM_swapImage('logout','','{THEME_COLOR_PATH}/images/icons/logout_a.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="{THEME_COLOR_PATH}/images/menu/pointer.jpg" width="28" height="36" border="0"></a></td>
            <td background="{THEME_COLOR_PATH}/images/menu/button_background.jpg" class="menu"><a href="../index.php" class="menu" onMouseOver="MM_swapImage('logout','','{THEME_COLOR_PATH}/images/icons/logout_a.gif',1)" onMouseOut="MM_swapImgRestore()">{TR_MENU_LOGOUT}</a></td>
            <td align="right" background="{THEME_COLOR_PATH}/images/icons/icon_bcgr.gif" class="menu"><a href="../index.php" onMouseOver="MM_swapImage('logout','','{THEME_COLOR_PATH}/images/icons/logout_a.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="{THEME_COLOR_PATH}/images/icons/logout.gif" name="logout" width="36" height="36" border="0" id="logout"></a></td>
          </tr>
        </table></td>
        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="62" align="left" background="{THEME_COLOR_PATH}/images/content/table_background.jpg" class="title"><img src="{THEME_COLOR_PATH}/images/content/table_icon_flash.jpg" width="85" height="62" align="absmiddle">{TR_TITLE_CUSTOM_MENUS}</td>
            <td width="27" align="right" background="{THEME_COLOR_PATH}/images/content/table_background.jpg"><img src="{THEME_COLOR_PATH}/images/content/table_icon_close.jpg" width="27" height="62"></td>
          </tr>
          <tr>
            <td>
			 <table width="100%" cellpadding="5" cellspacing="5">
			 <!-- BDP: page_message -->
              <tr>
                <td width="20">&nbsp;</td> 
                <td colspan="4" class="title"><font color="#FF0000">{MESSAGE}</font></td>
                </tr>
              <!-- EDP: page_message -->
              
			  <tr>
			    <td width="20">&nbsp;</td>
                <td class="content3"><b>{TR_MENU_NAME}</b></td>
                <td class="content3" align="center"><b>{TR_LEVEL}</b></td>
                <td colspan="2" align="center" class="content3"><b>{TR_ACTON}</b></td>
              </tr>
			 
			  <!-- BDP: button_list -->
              <tr>
                <td>&nbsp;</td> 
                <td class="{CONTENT}"><a href="{LINK}" class="link" target="_blank"><strong>{MENU_NAME}</strong></a><br>
                  {LINK}</td>
                <td class="{CONTENT}" align="center">{LEVEL}</td>
                <td width="100" class="{CONTENT}" align="center"><img src="{THEME_COLOR_PATH}/images/icons/edit.gif" width="16" height="16" border="0" align="absmiddle"> <a href="custom_menus.php?edit_id={BUTONN_ID}"  class="link">{TR_EDIT}</a></td>
                <td width="100" class="{CONTENT}" align="center"><img src="{THEME_COLOR_PATH}/images/icons/delete.gif" width="16" height="16" border="0" align="absmiddle"> <a href="#"  onClick="action_delete('custom_menus.php?delete_id={BUTONN_ID}')" class="link">{TR_DELETE}</a></td>
              </tr>
			  <!-- EDP: button_list -->
            </table>
            <br>
			<form name="add_new_button_frm" method="post" action="custom_menus.php">
			<!-- BDP: add_button -->
            <table width="100%" cellpadding="5" cellspacing="5">
              <tr>
                <td width="20">&nbsp;</td>
                <td colspan="2" class="content3"><b>{TR_ADD_NEW_BUTTON}</b></td>
                </tr>
              <tr>
                <td width="20">&nbsp;</td>
                <td width="200" class="content2">{TR_BUTTON_NAME}</td>
                <td class="content"><input name="bname" type="text" class="textinput" id="bname" style="width:210px"></td>
              </tr>
              <tr>
                <td width="20" >&nbsp;</td>
                <td width="200" class="content2">{TR_BUTTON_LINK}</td>
                <td class="content"><input name="blink" type="text" class="textinput" id="blink" style="width:210px"></td>
              </tr>
              <tr>
                <td width="20">&nbsp;</td>
                <td width="200" class="content2">{TR_BUTTON_TARGET}</td>
                <td class="content"><input name="btarget" type="text" class="textinput" id="btarget" style="width:210px"></td>
              </tr>
              <tr>
                <td width="20">&nbsp;</td>
                <td width="200" class="content2">{TR_VIEW_FROM}</td>
                <td  class="content"><select name="bview" id="bview">
                  <option value="reseller">{RESELLER}</option>
                  <option value="user">{USER}</option>
                  <option value="all">{RESSELER_AND_USER}</option>
                                                </select></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td colspan="2"><input name="Button" type="button" class="button" value="  {TR_SAVE}  " onClick="return sbmt(document.forms[0],'new_button');"></td>
                </tr>
            </table>            
              <!-- EDP: add_button -->
              <!-- BDP: edit_button -->
<table width="100%" cellpadding="5" cellspacing="5">
              <tr>
                <td width="20">&nbsp;</td>
                <td colspan="2" class="content3"><b>{TR_EDIT_BUTTON}</b></td>
                </tr>
              <tr>
                <td width="20">&nbsp;</td>
                <td width="200" class="content2">{TR_BUTTON_NAME}</td>
                <td class="content"><input name="bname" type="text" class="textinput" id="bname" style="width:210px" value="{BUTON_NAME}"></td>
              </tr>
              <tr>
                <td width="20">&nbsp;</td>
                <td width="200" class="content2">{TR_BUTTON_LINK}</td>
                <td class="content"><input name="blink" type="text" class="textinput" id="blink" style="width:210px" value="{BUTON_LINK}"></td>
              </tr>
              <tr>
                <td width="20">&nbsp;</td>
                <td width="200" class="content2">{TR_BUTTON_TARGET}</td>
                <td class="content"><input name="btarget" type="text" class="textinput" id="btarget" style="width:210px" value="{BUTON_TARGET}"></td>
              </tr>
              <tr>
                <td width="20">&nbsp;</td>
                <td width="200" class="content2">{TR_VIEW_FROM}</td>
                <td  class="content"><select name="bview" id="bview">
                  <option value="reseller" {RESELLER_VIEW}>{RESELLER}</option>
                  <option value="user" {USER_VIEW}>{USER}</option>
                  <option value="all" {ALL_VIEW}>{RESSELER_AND_USER}</option>
                                                </select></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td colspan="2"><input name="Button" type="button" class="button" value="  {TR_SAVE}  " onClick="return sbmt(document.forms[0],'edit_button');"></td>
                </tr>
            </table>            
              <input type="hidden" name="eid" value="{EID}">
			<!-- EDP: edit_button -->

			 <input type="hidden" name="uaction" value="">
            </form>
    

              <!-- end of content -->
			
			
			
			</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
          </td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="71"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr><td width="17"><img src="{THEME_COLOR_PATH}/images/top/down_left.jpg" width="17" height="71"></td><td width="198" valign="top" background="{THEME_COLOR_PATH}/images/top/downlogo_background.jpg"><table width="100%" border="0" cellpadding="0" cellspacing="0" >
          <tr>
            <td width="55"><a href="http://www.vhcs.net" target="_blank"><img src="{THEME_COLOR_PATH}/images/vhcs.gif" alt="" width="51" height="71" border="0"></a></td>
            <td class="bottom">{VHCS_LICENSE}</td>
          </tr>
        </table>          </td>
          <td background="{THEME_COLOR_PATH}/images/top/down_left_fill.jpg"><img src="{THEME_COLOR_PATH}/images/top/down_left_fill.jpg" width="2" height="71"></td><td width="766" background="{THEME_COLOR_PATH}/images/top/middle_background.jpg"><img src="{THEME_COLOR_PATH}/images/top/down_middle_background.jpg" width="766" height="71"></td>
          <td background="{THEME_COLOR_PATH}/images/top/down_right_fill.jpg"><img src="{THEME_COLOR_PATH}/images/top/down_right_fill.jpg" width="3" height="71"></td>
          <td width="9"><img src="{THEME_COLOR_PATH}/images/top/down_right.jpg" width="9" height="71"></td></tr>
    </table></td>
  </tr>
</table>
</body>
</html>