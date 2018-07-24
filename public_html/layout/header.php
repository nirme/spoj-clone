<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="pl" xmlns="http://www.w3.org/1999/xhtml">
<head>
		<meta http-equiv="content-type" content="text/html; charset=<?php echo $encoding ?>">
		<link rel="stylesheet" type="text/css" href="layout/styles/style_table.css" media="screen">
		<link rel="stylesheet" type="text/css" href="layout/styles/<?php echo $_SESSION['style']; ?>" media="screen">
		<script src="layout/scripts/script.js" type="text/javascript"></script>
		<script src="layout/scripts/form_filter.js" type="text/javascript"></script>
		<title><?php echo $title." | spoj-clone (dominatrix2000)"; ?></title>
</head>
<body>
<div id="box">
	<div id="header">
	<div id="header-login">
	<?php if (!isset($_SESSION['userIndeks'])) { ?>
	<table id="log_table">
	<form action="index.php?category=<?php echo $category; ?>" method="post">
	<tr><td><p>Login: </p></td><td><input id="Text1" type="text" maxlength="64" size="16" name="login" /></td>
	<td rowspan="2"><input id="Submit1" type="submit" name="authenticate" value=" Zaloguj " /></td></tr>
	<tr><td><p>Hasło: </p></td><td><input id="Password1" type="password" maxlength="64" size="16" name="password" /></td></tr>
	</p></form>
	</table>
	<?php } else { ?>
	<table id="log_table"><tr><td><p>Zalogowany jako:</p></td>
	<td rowspan="2"><a id="logout" href="?category=<?php echo $category ?>&action=logout">Wyloguj</a></td></tr>
	<tr><td><a href="?category=account"><?php if ($_SESSION['userLogin']) { echo $_SESSION['userLogin']; } else { echo $_SESSION['userIndeks']; } ?> </a></td></tr>
	</table> <?php } ?>
	</div>
	</div>
	
<div id="cont-box">
