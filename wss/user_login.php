<?php require_once('config/tank_config.php'); ?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['textfield'])) {
  $loginUsername=$_POST['textfield'];
  $password=$_POST['textfield2'];
  $tk_password = md5(crypt($password,substr($password,0,2)));
  $MM_fldUserAuthorization = "tk_user_status";
  $MM_redirectLoginSuccess = "index.php";
  $MM_redirectLoginFailed = "user_error2.php";
  $MM_redirecttoReferrer = false;
  mysqli_select_db($tankdb,$database_tankdb);
  	
  $LoginRS__query=sprintf("SELECT tk_user_login, tk_user_pass, tk_display_name, uid, tk_user_status, tk_user_rank, tk_user_message, tk_user_lastuse FROM tk_user WHERE binary tk_user_login=%s AND (tk_user_pass=%s OR tk_user_pass=%s)",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($tk_password, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysqli_query($tankdb,$LoginRS__query) or die(mysqli_error());
  $loginFoundUser = mysqli_num_rows($LoginRS);

  if ($loginFoundUser) {

    $loginStrGroup  = mysqli_result($LoginRS,0,'tk_user_status');
	$loginStrDisplayname  = mysqli_result($LoginRS,0,'tk_display_name');
	$loginStrpid  = mysqli_result($LoginRS,0,'uid');
	$loginStrrank  = mysqli_result($LoginRS,0,'tk_user_rank');
	$loginStrlogin  = mysqli_result($LoginRS,0,'tk_user_login');
	$loginStrmsg  = mysqli_result($LoginRS,0,'tk_user_message');
	$loginStrlast  = mysqli_result($LoginRS,0,'tk_user_lastuse');

	//check_message( $loginStrpid );
    
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;
	$_SESSION['MM_Displayname'] = $loginStrDisplayname;	
	$_SESSION['MM_uid'] = $loginStrpid;	
	$_SESSION['MM_rank'] = $loginStrrank;	
	$_SESSION['MM_msg'] = $loginStrmsg;	
	$_SESSION['MM_last'] = $loginStrlast;
	
   //判断是否是老用户
  if ($loginStrGroup == $multilingual_dd_role_admin) {
  $userrank = "5";
  } else if ($loginStrGroup == $multilingual_dd_role_general){
  $userrank = "3";
  } else if ($loginStrGroup == $multilingual_dd_role_disabled){
  $userrank = "0";
  }
   
  if ($loginStrrank == null) {
  $updateSQL = sprintf("UPDATE tk_user SET tk_user_rank=%s WHERE tk_user_login=%s", 
                       GetSQLValueString($userrank, "text"),                      
                       GetSQLValueString($loginStrlogin, "text"));
  mysqli_select_db($tankdb,$database_tankdb);
  $Result2 = mysqli_query($tankdb,$updateSQL) or die(mysqli_error());
  $_SESSION['MM_rank'] = $userrank;
  }

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WSS - <?php echo $multilingual_userlogin_title; ?></title>
<link href="bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
<link href="skin/themes/base/tk_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="srcipt/jquery.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>
</head>

<body>
<?php require('head_sub.php'); ?>
<table width="70%" border="0" cellspacing="0" cellpadding="0" height="450px;" align="center">
    <tr>
      <td >
      <div class="ping_logo"></div>
      </td>
	  
	  <td >
      <form id="form1" name="form1" method="POST" action="<?php echo $loginFormAction; ?>">
	  
	   <div class="form-group">
    <label for="textfield"><?php echo $multilingual_userlogin_username; ?></label>
    <input type="text" class="form-control" id="textfield" name="textfield" placeholder="User name">
  </div>
  
  <div class="form-group">
    <label for="textfield2"><?php echo $multilingual_userlogin_password; ?></label>
    <input type="password" class="form-control" name="textfield2" id="textfield2" placeholder="Password">
  </div>
  
  <button type="submit" class="btn btn-default" style="width:120px;"><?php echo $multilingual_userlogin_login; ?></button>
  <div class="pull-right">
  <?php echo $multilingual_global_version; ?>: <?php echo $version; ?>
  </div>
	  </form>
      </td>
	  
    </tr>

  </table>

<div style="background:#F6F6F6; padding:15px; width:100%;" >
<table width="480px" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td width="100px">
<img src="skin/themes/base/images/getqrcode.jpg" width="82" height="82" />
</td>
<td valign="top">
<span class="gray2 glink" style="line-height:150%;"><?php echo $multilingual_getqrcode; ?></span>
</td>
</tr>
</table>
</div>

<iframe id="frame_content" name="main_frame" frameborder="0" height="1px" width="1px" src="http://www.wssys.net/analytics<?php if ($language == "en") { echo "_en";}?>.html" scrolling="no"></iframe>
<?php require('foot.php'); ?>
</body>
</html>