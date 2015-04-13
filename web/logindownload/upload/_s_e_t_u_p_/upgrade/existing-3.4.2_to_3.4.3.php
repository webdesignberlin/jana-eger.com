<?php
/**********************************
* Olate Download 3.4.3
* http://www.olate.co.uk/od3
**********************************
* Copyright Olate Ltd 2005
*
* @author $Author: gburnes $ (Olate Ltd)
* @version $Revision: 1 $
* @package od
*
* Updated: $Date: 2005-10-15 22:16:22 +0100 (Sat, 15 Oct 2005) $
*/

// Initialisation
require('../../includes/config.php');

// Core modules
require('../../modules/core/dbim.php');
require('../../modules/core/ehm.php');
require('../../modules/core/lm.php');

// Define any constants
// Error types
define('FATAL', E_USER_ERROR);
define('ERROR', E_USER_WARNING);
define('WARNING', E_USER_NOTICE);

// Initialise modules (order is important)

// EHM
$ehm = new ehm(1); // Debug level 1 recommended for live environments

// DBIM
$dbim = new dbim();
$dbim->connect($config['database']['username'], $config['database']['password'], $config['database']['server'], $config['database']['name'], $config['database']['persistant']);

// Alter database version
$dbim->query('UPDATE '.DB_PREFIX.'config
				SET version = "3.4.3"');
				

if( @file_exists('../../environment.php') )
{
	$result = @unlink('../../environment.php');
	if( ! $result )
	{
		$cant_do_it = 1;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Olate Download 3 - Upgrade</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<link href="../setup.css" rel="stylesheet" type="text/css" />
</head>
<body>

<table cellspacing="0" cellpadding="0" id="main">
<tr id="logo"><td colspan="2"><img src="../images/logo.gif" width="190" height="68" alt="Logo"/></td></tr>
<tr id="top">
	<td id="left">Install Progress</td>
	<td id="right">Olate Download 3 Setup</td>
</tr>

<tr>
	<td id="progress">
		<ul>
		<li class="first"><span>Start</span></li>
			<ul class="second">
				<li>Action Selection</li>
		<li class="complete">Complete!</li>
		</ul>	
	</td>
	<td id="content">
		<h1>Upgrade Complete</h1>
		<p id="intro">The upgrade from 3.4.2 to 3.4.3 has been completed<a href="index.php"></a> successfully. However, before you can login again, you must take the following actions: </p>
		<br />
        <div id="options">
          <h3>Actions</h3>
          <p>&#8226; Please ensure /setup and all sub directories are removed</p>
		  <?php if( isset($cant_do_it)) echo '<p>&#8226; <span style="color:red;"><b>You must delete <i>environment.php</i> to ensure the security of your server. I tried to delete it for you but I do not have the correct permissions. Delete this file before you continue.'?>
          <h3>What's New?</h3>
          <p>Introduced a new update notification sysem that differentiates the severity of an update.<br />
			 Introduced a new Input validation system to reduce XSS attacks dramatically.<br />
			 Addressed security issues within the software.<br />
			 Removed the .DS_Store files from packages.</p>
        </div>
	</td>
</tr>
</table>

</body>
</html>