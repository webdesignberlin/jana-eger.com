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

// Start admin cp
$start = $uim->fetch_template('admin/start');
$start->show();
		
// Template
$updates_template = $uim->fetch_template('admin/od_updates');

if( $site_config['version_check'] == 1 )
{
	require('./includes/http.php');
	$http = new http();

	// Construct fields
	$http->add_field('product', '1' );
	$http->add_field('_v' , $site_config['version'] );

	// Make request
	$http->post_page('http://olatedownload.sourceforge.net/upgrade_check.php');
	$version_upgrade = $http->get_content();
			
	$version_upgrade = explode( ':' , $version_upgrade );
	$updates_template->assign_var('latest_version' , $version_upgrade[1] );

	if ($version_upgrade[1] == $site_config['version'] || empty($version_upgrade[1]))
	{
		// Running latest version
		$updates_template->assign_var('up_to_date', true);
		$updates_template->assign_var('latest_version' , $version_upgrade[1] );
	}
	else
	{
		$updates_template->assign_var('vcheck_style_end' , '</span></td></tr></table></div>' );
		switch( $version_upgrade[0] )
		{
			case '1':
				// Running latest version
				$updates_template->assign_var('up_to_date', true);
			break;

			case '2':
				// There is a security update ( Very minor version, bugfixes, etc. )
				$updates_template->assign_var('check' , 1 );
				$updates_template->assign_var('vcheck_style_top', '<div valign=\'top\' style=\'border: 1px solid #ff0000;padding: 0px; margin: 0px; background-color: #ffbcbc;\'><table> <tr><td><img src="templates/olate/images/update_c.png" /></td><td> <span style=\'color:#606060;\'>');
			break;
			
			case '3':
				// Just a minor update available
				$updates_template->assign_var('check' , 2 );
				$updates_template->assign_var('vcheck_style_top', '<div valign=\'top\' style=\'border: 1px solid #fecd4b;padding: 0px; margin: 0px; background-color: #fdffcb;\'><table> <tr><td><img src="templates/olate/images/update.png" /></td><td> <span style=\'color:#606060;\'>');
			break;
			
			case '4':
				// Major update, but no security.
				$updates_template->assign_var('check' , 3 );
				$updates_template->assign_var('vcheck_style_top', '<div valign=\'top\' style=\'border: 1px solid #fecd4b;padding: 0px; margin: 0px; background-color: #fdffcb;\'><table> <tr><td><img src="templates/olate/images/update.png" /></td><td> <span style=\'color:#606060;\'>');
			break;
			
			case '5':
				// Minor update, but security included
				$updates_template->assign_var('check' , 4 );
				$updates_template->assign_var('vcheck_style_top', '<div valign=\'top\' style=\'border: 1px solid #ff0000;padding: 0px; margin: 0px; background-color: #ffbcbc;\'><table> <tr><td><img src="templates/olate/images/update.png" /></td><td> <span style=\'color:#606060;\'>');
			break;
			
			case '6':
				// Minor update, but security included
				$updates_template->assign_var('check' , 5 );
				$updates_template->assign_var('vcheck_style_top', '<div valign=\'top\' style=\'border: 1px solid #ff0000;padding: 0px; margin: 0px; background-color: #ffbcbc;\'><table> <tr><td><img src="templates/olate/images/update_c.png" /></td><td> <span style=\'color:#606060;\'>');
			break;
		}
	}
}
else
{
	// We never do a version check... Then we just so ut oh, can't work...
	$updates_template->assign_var('error', $lm->language('admin', 'updates_unavailable'));
}
$updates_template->show();
		
$end = $uim->fetch_template('global/end');
$end->show();
		
$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('general', 'title').' - '.$lm->language('admin', 'updates'), false);
?>