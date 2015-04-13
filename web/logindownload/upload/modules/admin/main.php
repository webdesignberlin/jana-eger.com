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
		
if ($uam->permitted('acp_view'))
{		
	// Template
	$main = $uim->fetch_template('admin/main');
	
	// Count active files
	$count_result = $dbim->query('SELECT COUNT(*) AS files
									FROM '.DB_PREFIX.'files
									WHERE (status = 1)');	
	$count = $dbim->fetch_array($count_result);
	$main->assign_var('total_files', $count['files']);
	
	// Count inactive files
	$count_result = $dbim->query('SELECT COUNT(*) AS files
									FROM '.DB_PREFIX.'files
									WHERE (status = 0)');	
	$count = $dbim->fetch_array($count_result);
	$main->assign_var('total_inactive_files', $count['files']);
	
	// Count downloads
	$count_result = $dbim->query('SELECT COUNT(*) AS downloads
									FROM '.DB_PREFIX.'stats');	
	$count = $dbim->fetch_array($count_result);
	$main->assign_var('total_downloads', $count['downloads']);
	
	// Count pending comments
	$count_result = $dbim->query('SELECT COUNT(*) AS comments
									FROM '.DB_PREFIX.'comments');	
	$count = $dbim->fetch_array($count_result);
	$main->assign_var('total_comments', $count['comments']);
	
	// Count pending comments
	$count_result = $dbim->query('SELECT COUNT(*) AS comments
									FROM '.DB_PREFIX.'comments
									WHERE (status = 0)');	
	$count = $dbim->fetch_array($count_result);
	$main->assign_var('pending_comments', $count['comments']);
	
	// Count users
	$count_result = $dbim->query('SELECT COUNT(*) AS users
									FROM '.DB_PREFIX.'users');	
	$count = $dbim->fetch_array($count_result);
	$main->assign_var('total_users', $count['users']);
	
	// http class - checks for updates
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
		
		if ($version_upgrade[1] == $site_config['version'] || empty($version_upgrade[1]))
		{
			// Running latest version
			$main->assign_var('up_to_date', true);
		}
		else
		{
			$main->assign_var('latest_version' , $version_upgrade[1] );
			$main->assign_var('vcheck_style_end' , '</span></td></tr></table></div>' );
			switch( $version_upgrade[0] )
			{
				case '1':
					// Running latest version
					$main->assign_var('up_to_date', true);
				break;

				case '2':
					// There is a security update ( Very minor version, bugfixes, etc. )
					$main->assign_var('check' , 1 );
					$main->assign_var('vcheck_style_top', '<div valign=\'top\' style=\'border: 1px solid #ff0000;padding: 0px; margin: 0px; background-color: #ffbcbc;\'><table> <tr><td><img src="templates/olate/images/update_c.png" /></td><td> <span style=\'color:#606060;\'>');
				break;
				
				case '3':
					// Just a minor update available
					$main->assign_var('check' , 2 );
					$main->assign_var('vcheck_style_top', '<div valign=\'top\' style=\'border: 1px solid #fecd4b;padding: 0px; margin: 0px; background-color: #fdffcb;\'><table> <tr><td><img src="templates/olate/images/update.png" /></td><td> <span style=\'color:#606060;\'>');
				break;
				
				case '4':
					// Major update, but no security.
					$main->assign_var('check' , 3 );
					$main->assign_var('vcheck_style_top', '<div valign=\'top\' style=\'border: 1px solid #fecd4b;padding: 0px; margin: 0px; background-color: #fdffcb;\'><table> <tr><td><img src="templates/olate/images/update.png" /></td><td> <span style=\'color:#606060;\'>');
				break;
				
				case '5':
					// Minor update, but security included
					$main->assign_var('check' , 4 );
					$main->assign_var('vcheck_style_top', '<div valign=\'top\' style=\'border: 1px solid #ff0000;padding: 0px; margin: 0px; background-color: #ffbcbc;\'><table> <tr><td><img src="templates/olate/images/update.png" /></td><td> <span style=\'color:#606060;\'>');
				break;
				
				case '6':
					// Minor update, but security included
					$main->assign_var('check' , 5 );
					$main->assign_var('vcheck_style_top', '<div valign=\'top\' style=\'border: 1px solid #ff0000;padding: 0px; margin: 0px; background-color: #ffbcbc;\'><table> <tr><td><img src="templates/olate/images/update_c.png" /></td><td> <span style=\'color:#606060;\'>');
				break;
			}
		}
	}
	else
	{
		// We never do a version check, so we're always up to date G-UNIT
		$main->assign_var('up_to_date', true);
	}
	
	$main->show();
}
else
{
	// User is not permitted
	$no_permission = $uim->fetch_template('admin/no_permission');
	$no_permission->show();
}
		
$end = $uim->fetch_template('global/end');
$end->show();
		
$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'index'), false);
?>