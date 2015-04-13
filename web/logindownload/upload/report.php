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

// Initialisation
require('./includes/init.php');

// Start sessions
session_start();
$_SESSION['valid_user'] = true;

// Show categories
$fcm->show_cats();

validate_types($input, array('file' => 'INT', 'email' => 'STR'));

if (empty($input['email']) || empty($input['description']) || empty($input['file']))
{
	// Show report form
	$report = $uim->fetch_template('files/report');
	$report->assign_var('file_id', $input['file']);
}
else
{
	if (!isset($_SESSION['report_timestamp']) || ((time() - $site_config['flood_interval']) > $_SESSION['report_timestamp']))
	{
		$input['description'] = str_replace('\r\n', '\n', $input['description']);
		
		// You've got mail (no need to translate this text)
		$message = "Hello,\n\nA user (".$input['email'].") has reported the following problem on file #".$input['file']." at ".$site_config['url']."details.php?file=".$input['file']." :\n\n----------\n"
				. $input['description']
				. "\n----------";
		
		mail($site_config['admin_email'], 'Reported File', $message, 'From: '.$site_config['admin_email']);
		
		// Set a session variable with the time - flood prevention
		$_SESSION['report_timestamp'] = time();
		
		$report = $uim->fetch_template('files/report');
		$report->assign_var('result', 1);
	}
	else
	{
		$report = $uim->fetch_template('files/report');
		$report->assign_var('result', 2);
	}
}

$report->show();

// End table
$end = $uim->fetch_template('global/end');
$end->show();

// Show everything
$uim->generate(TITLE_PREFIX.$lm->language('frontend', 'report_problem'));
?>