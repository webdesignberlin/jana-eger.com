<?php
/**********************************
* Olate Download 3.3.0
* http://www.olate.co.uk/od3
**********************************
* Copyright Olate Ltd 2005
*
* @author $Author: dsalisbury $ (Olate Ltd)
* @version $Revision: 125 $
* @package od
*
* Updated: $Date: 2005-10-15 22:16:22 +0100 (Sat, 15 Oct 2005) $
*/

// Initialisation
require('../../includes/config.php');

// Core modules
require('../../modules/core/dbim.php');
require('../../modules/core/ehm.php');

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
				SET version = "3.2.1"');
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
		<p id="intro">The upgrade from 3.2.0 to 3.2.1 has been completed<a href="index.php"></a> successfully. However, before you can login again, you must take the following actions: </p>
		<br />
        <div id="options">
          <h3>Actions</h3>
          <p>&#8226; Please ensure /setup and all sub directories are removed</p>
          <h3>What's New?</h3>
          <p>In this version, the following changes have been made:</p>
          <p>            - <a href="http://www.olate.co.uk/tracker/view.php?id=163" title="[resolved] Custom Field Value Won't Update">0000163</a>: <b>[Admin]</b> Custom Field Value Won't Update (David)<br />
            - <a href="http://www.olate.co.uk/tracker/view.php?id=164" title="[resolved] Agreements Parse Error">0000164</a>: <b>[Admin]</b> Agreements Parse Error (David)
            <br />
            - <a href="http://www.olate.co.uk/tracker/view.php?id=136" title="[resolved] &quot;Accept&quot; button label">0000136</a>: <b>[Core: LM (Languages)]</b> "Accept" button label (David)<br />
          - <a href="http://www.olate.co.uk/tracker/view.php?id=158" title="[resolved] File Count">0000158</a>: <b>[General]</b> File Count (David)<br />
- <a href="http://www.olate.co.uk/tracker/view.php?id=167" title="[resolved] Incorrect ID in admin notification email on user file upload">0000167</a>: <b>[General]</b> Incorrect ID in admin notification email on user file upload (David)<br />
- <a href="http://www.olate.co.uk/tracker/view.php?id=169" title="[resolved] Ability to display custom fields">0000169</a>: <b>[General]</b> Ability to display custom fields (David) <br />
- <a href="http://www.olate.co.uk/tracker/view.php?id=184" title="[resolved] Vulnerability with file uploads">0000184</a>: <b>[Security]</b> Vulnerability with file uploads (David)<br />
- <a href="http://www.olate.co.uk/tracker/view.php?id=165" title="[resolved] Agreement line breaks not being preserved">0000165</a>: <b>[Templates: olate]</b> Agreement line breaks not being preserved (David)<br />
- <a href="http://www.olate.co.uk/tracker/view.php?id=181" title="[resolved] Page does not render fully in IE">0000181</a>: <b>[Templates: olate]</b> Page does not render fully in IE (David)</p>
          <p>A full changelog can be viewed in the Changelog.txt file within the original download package and also in the <a href="http://www.olate.co.uk/tracker/changelog_page.php">live changelog</a>.</p>
        </div>
	</td>
</tr>
</table>

</body>
</html>