<?php
/**********************************
* Olate Download 3.4.1
* http://www.olate.co.uk/od3
**********************************
* Copyright Olate Ltd 2005
*
* @author $Author: dsalisbury $ (Olate Ltd)
* @version $Revision: 259 $
* @package od
*
* Updated: $Date: 2006-10-10 20:27:12 +0100 (Tue, 10 Oct 2006) $
*/

$uam->user_logout();

// Start admin cp
$start = $uim->fetch_template('admin/start');
$start->show();
		
// Show message
$message = $uim->fetch_template('admin/logged_out');
$message->show();
		
$end = $uim->fetch_template('global/end');
$end->show();
		
$uim->generate($lm->language('admin', 'logged_out'));
?>