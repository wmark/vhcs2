<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset={THEME_CHARSET}">
<title>{TR_DETAILS_DOMAIN_PAGE_TITLE}</title>
<link href="{THEME_COLOR_PATH}/css/vhcs.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="{THEME_COLOR_PATH}/css/vhcs.js"></script>
<script>
<!--
function action_status(url) {
	if (!confirm("{TR_MESSAGE_CHANGE_STATUS}"))
		return false;

	location = url;
}

function action_delete(url) {
	if (!confirm("{TR_MESSAGE_DELETE}"))
		return false;

	location = url;
}

function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
//-->
</script>

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
            <td width="60" background="{THEME_COLOR_PATH}/images/menu/menu_top_left_bckgr.jpg"><img src="{THEME_COLOR_PATH}/images/icons/manage_users_big.gif" width="60" height="62"></td>
            <td width="151" background="{THEME_COLOR_PATH}/images/menu/menu_top_bckgr.jpg" class="title">{TR_MENU_MANAGE_USERS}</td>
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
            <td background="{THEME_COLOR_PATH}/images/menu/button_background.jpg" class="menu"><a href="index.php" onMouseOver="MM_swapImage('domains','','{THEME_COLOR_PATH}/images/icons/general_a.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="{THEME_COLOR_PATH}/images/menu/pointer.jpg" name="Image1" width="28" height="36" border="0" id="Image1"></a></td>
            <td background="{THEME_COLOR_PATH}/images/menu/button_background.jpg" class="menu"><a href="index.php" class="menu"  onMouseOver="MM_swapImage('domains','','{THEME_COLOR_PATH}/images/icons/general_a.gif',1)" onMouseOut="MM_swapImgRestore()">{TR_MENU_GENERAL_INFORMATION}</a></td>
            <td align="right" background="{THEME_COLOR_PATH}/images/icons/icon_bcgr.gif" class="menu"><a href="index.php" onMouseOver="MM_swapImage('domains','','{THEME_COLOR_PATH}/images/icons/general_a.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="{THEME_COLOR_PATH}/images/icons/general.gif" name="domains" width="36" height="36" border="0" id="domains"></a></td>
          </tr>
		   <tr>
            <td colspan="3"><img src="{THEME_COLOR_PATH}/images/trans.gif" width="30" height="4"></td>
            </tr>
			 <tr>
            <td width="28" background="{THEME_COLOR_PATH}/images/menu/button_background.jpg" class="menu"><a href="manage_users.php" onMouseOver="MM_swapImage('general','','{THEME_COLOR_PATH}/images/icons/manage_users_a.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="{THEME_COLOR_PATH}/images/menu/open_pointer.jpg" width="28" height="36" border="0"></a></td>
            <td background="{THEME_COLOR_PATH}/images/menu/open_background.gif" class="menu"><a href="manage_users.php" class="menu_active" onMouseOver="MM_swapImage('general','','{THEME_COLOR_PATH}/images/icons/manage_users_a.gif',1)" onMouseOut="MM_swapImgRestore()">{TR_MENU_MANAGE_USERS}</a></td>
            <td width="36" align="right" background="{THEME_COLOR_PATH}/images/menu/open_icon_bcgr.jpg" class="menu"><a href="manage_users.php" onMouseOver="MM_swapImage('general','','{THEME_COLOR_PATH}/images/icons/manage_users_a.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="{THEME_COLOR_PATH}/images/icons/manage_users_a.gif" name="general" width="36" height="36" border="0" id="general"></a></td>
          </tr>
          <tr background="{THEME_COLOR_PATH}/images/menu/open_background.jpg">
            <td colspan="3" class="menu" background="{THEME_COLOR_PATH}/images/menu/open_background.jpg"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="5" rowspan="14" background="{THEME_COLOR_PATH}/images/menu/open_background_left.gif"><img src="{THEME_COLOR_PATH}/images/trans.gif" width="12" height="1"></td>
                <td><img src="{THEME_COLOR_PATH}/images/icons/document.gif" width="12" height="15"></td>
                <td><a href="manage_users.php" class="submenu">{TR_MENU_OVERVIEW}</a></td>
                <td width="5" rowspan="14" background="{THEME_COLOR_PATH}/images/menu/open_background_right.gif"><img src="{THEME_COLOR_PATH}/images/trans.gif" width="5" height="1"></td>
              </tr>
              <tr>
                <td colspan="2"><img src="{THEME_COLOR_PATH}/images/trans.gif" width="30" height="4"></td>
                </tr>
              <tr>
                <td><img src="{THEME_COLOR_PATH}/images/icons/document.gif" width="12" height="15"></td>
                <td><a href="add_user.php" class="submenu">{TR_MENU_ADD_ADMIN}</a></td>
                </tr>
              <tr>
                <td colspan="2"><img src="{THEME_COLOR_PATH}/images/trans.gif" width="30" height="4"></td>
              </tr>
              <tr>
                <td width="15"><img src="{THEME_COLOR_PATH}/images/icons/document.gif" width="12" height="15"></td>
                <td><a href="add_reseller.php" class="submenu">{TR_MENU_ADD_RESELLER}</a></td>
              </tr>
              <tr>
                <td colspan="2"><img src="{THEME_COLOR_PATH}/images/trans.gif" width="30" height="4"></td>
              </tr>
              <tr>
                <td><img src="{THEME_COLOR_PATH}/images/icons/document.gif" width="12" height="15"></td>
                <td><a href="manage_reseller_owners.php" class="submenu">{TR_MENU_RESELLER_ASIGNMENT}</a></td>
              </tr>
              <tr>
                <td colspan="2"><img src="{THEME_COLOR_PATH}/images/trans.gif" width="30" height="4"></td>
                </tr>
              <tr>
                <td><img src="{THEME_COLOR_PATH}/images/icons/document.gif" width="12" height="15"></td>
                <td><a href="manage_reseller_users.php" class="submenu">{TR_MENU_USER_ASIGNMENT}</a></td>
              </tr>
              <tr>
                <td colspan="2"><img src="{THEME_COLOR_PATH}/images/trans.gif" width="30" height="4"></td>
                </tr>
              <tr>
                <td><img src="{THEME_COLOR_PATH}/images/icons/document.gif" width="12" height="15"></td>
                <td><a href="email_setup.php" class="submenu">{TR_MENU_EMAIL_SETUP}</a></td>
              </tr>
              <tr>
                <td colspan="2"><img src="{THEME_COLOR_PATH}/images/trans.gif" width="30" height="4"></td>
                </tr>
              <tr>
                <td><img src="{THEME_COLOR_PATH}/images/icons/document.gif" width="12" height="15"></td>
                <td><a href="circular.php" class="submenu">{TR_MENU_CIRCULAR}</a></td>
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
            <td height="62" align="left" background="{THEME_COLOR_PATH}/images/content/table_background.jpg" class="title"><img src="{THEME_COLOR_PATH}/images/content/table_icon_domains.jpg" width="85" height="62" align="absmiddle">{TR_DOMAIN_DETAILS}</td>
            <td width="27" align="right" background="{THEME_COLOR_PATH}/images/content/table_background.jpg"><img src="{THEME_COLOR_PATH}/images/content/table_icon_close.jpg" width="27" height="62"></td>
          </tr>
          <tr>
            <td><table width="100%" cellpadding="5" cellspacing="5">
                <tr>
                  <td width="20">&nbsp;</td>
                  <td class="content2" width="193">{TR_DOMAIN_NAME}</td>
                  <td  class="content" colspan="2">{VL_DOMAIN_NAME}</td>
                </tr>
                <tr>
                  <td width="20">&nbsp;</td>
                  <td class="content2" width="193">{TR_DOMAIN_IP}</td>
                  <td  class="content" colspan="2">{VL_DOMAIN_IP}</td>
                </tr>
                <tr>
                  <td width="20">&nbsp;</td>
                  <td class="content2" width="193">{TR_STATUS}</td>
                  <td  class="content" colspan="2">{VL_STATUS}</td>
                </tr>
                <tr>
                  <td width="20">&nbsp;</td>
                  <td class="content2" width="193">{TR_PHP_SUPP} </td>
                  <td  class="content" colspan="2">{VL_PHP_SUPP}</td>
                </tr>
                <tr>
                  <td width="20">&nbsp;</td>
                  <td class="content2" width="193">{TR_CGI_SUPP}</td>
                  <td  class="content" colspan="2">{VL_CGI_SUPP}</td>
                </tr>
                <tr>
                  <td width="20">&nbsp;</td>
                  <td class="content2" width="193">{TR_MYSQL_SUPP}</td>
                  <td  class="content" colspan="2">{VL_MYSQL_SUPP}</td>
                </tr>
                <tr>
                  <td width="20">&nbsp;</td>
                  <td class="content2" width="193">{TR_TRAFFIC}</td>
                  <td  colspan="2" class="content"><table width="252" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="13"><img src="{THEME_COLOR_PATH}/images/stats_left_small.gif" width="13" height="20"></td>
                        <td background="{THEME_COLOR_PATH}/images/stats_background.gif"><table border="0" cellspacing="0" cellpadding="0" align="left">
                            <tr>
                              <td width="7"><img src="{THEME_COLOR_PATH}/images/bars/stats_left.gif" width="7" height="13"></td>
                              <td background="{THEME_COLOR_PATH}/images/bars/stats_background.gif"><img src="{THEME_COLOR_PATH}/images/trans.gif" width="{VL_TRAFFIC_PERCENT}" height="1"></td>
                              <td width="7"><img src="{THEME_COLOR_PATH}/images/bars/stats_right.gif" width="7" height="13"></td>
                            </tr>
                        </table></td>
                        <td width="13"><img src="{THEME_COLOR_PATH}/images/stats_right_small.gif" width="13" height="20"></td>
                      </tr>
                    </table>
                      <br>
                      {VL_TRAFFIC_USED} / {VL_TRAFFIC_LIMIT} </td>
                </tr>
                <tr>
                  <td width="20">&nbsp;</td>
                  <td class="content2" width="193">{TR_DISK}</td>
                  <td  colspan="2" class="content"><table width="252" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="13"><img src="{THEME_COLOR_PATH}/images/stats_left_small.gif" width="13" height="20"></td>
                        <td background="{THEME_COLOR_PATH}/images/stats_background.gif"><table border="0" cellspacing="0" cellpadding="0" align="left">
                            <tr>
                              <td width="7"><img src="{THEME_COLOR_PATH}/images/bars/stats_left.gif" width="7" height="13"></td>
                              <td background="{THEME_COLOR_PATH}/images/bars/stats_background.gif"><img src="{THEME_COLOR_PATH}/images/trans.gif" width="{VL_DISK_PERCENT}" height="1"></td>
                              <td width="7"><img src="{THEME_COLOR_PATH}/images/bars/stats_right.gif" width="7" height="13"></td>
                            </tr>
                        </table></td>
                        <td width="13"><img src="{THEME_COLOR_PATH}/images/stats_right_small.gif" width="13" height="20"></td>
                      </tr>
                    </table>
                      <br>
                      {VL_DISK_USED} / {VL_DISK_LIMIT} </td>
                <tr>
                  <td>&nbsp;</td>
                  <td class="content3"><strong>{TR_FEATURE}</strong></td>
                  <td width="200" class="content3"><strong>{TR_USED}</strong></td>
                  <td class="content3"><strong>{TR_LIMIT}</strong></td>
                </tr>
                <tr>
                  <td width="20">&nbsp;</td>
                  <td class="content2" width="193">{TR_MAIL_ACCOUNTS}</td>
                  <td  class="content">{VL_MAIL_ACCOUNTS_USED}</td>
                  <td  class="content">{VL_MAIL_ACCOUNTS_LIIT}</td>
                </tr>
                <tr>
                  <td width="20">&nbsp;</td>
                  <td class="content2" width="193">{TR_FTP_ACCOUNTS}</td>
                  <td  class="content">{VL_FTP_ACCOUNTS_USED}</td>
                  <td  class="content">{VL_FTP_ACCOUNTS_LIIT}</td>
                </tr>
                <tr>
                  <td width="20">&nbsp;</td>
                  <td class="content2" width="193">{TR_SQL_DB_ACCOUNTS}</td>
                  <td  class="content">{VL_SQL_DB_ACCOUNTS_USED}</td>
                  <td  class="content">{VL_SQL_DB_ACCOUNTS_LIIT}</td>
                </tr>
                <tr>
                  <td width="20">&nbsp;</td>
                  <td class="content2" width="193">{TR_SQL_USER_ACCOUNTS}</td>
                  <td  class="content">{VL_SQL_USER_ACCOUNTS_USED}</td>
                  <td  class="content">{VL_SQL_USER_ACCOUNTS_LIIT}</td>
                </tr>
                <tr>
                  <td width="20">&nbsp;</td>
                  <td class="content2" width="193">{TR_SUBDOM_ACCOUNTS}</td>
                  <td  class="content">{VL_SUBDOM_ACCOUNTS_USED}</td>
                  <td  class="content">{VL_SUBDOM_ACCOUNTS_LIIT}</td>
                </tr>
                <tr>
                  <td width="20">&nbsp;</td>
                  <td class="content2" width="193">{TR_DOMALIAS_ACCOUNTS}</td>
                  <td  class="content">{VL_DOMALIAS_ACCOUNTS_USED}</td>
                  <td  class="content">{VL_DOMALIAS_ACCOUNTS_LIIT}</td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td colspan="3"><form name="buttons" method="post" action="#">
                      <input name="Submit" type="submit" class="button" onClick="MM_goToURL('parent','manage_users.php');return document.MM_returnValue" value="  {TR_BACK}  ">
&nbsp;&nbsp;&nbsp;                                    </form></td>
                </tr>
            </table></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table></td>
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
