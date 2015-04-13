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

require('./includes/init.php');

// Start the session
session_start();

// Define a list of 'ok' modules
$allowed_modules = array('main',
						 'login',
						 'logout',
						 'main_settings',
						 'categories_add',
						 'categories_edit',
						 'categories_delete',
						 'categories_delete_multiple',
						 'categories_ordering',
						 'customfields_add',
						 'customfields_edit',
						 'customfields_delete',
						 'files_add_file',
						 'files_edit_file',
						 'files_delete_file',
						 'files_add_agreement',
						 'files_edit_agreement',
						 'files_delete_agreement',
						 'files_approve_comments',
						 'files_approve_files',
						 'files_add_comment',
						 'files_manage_comments',
						 'files_edit_comment',
						 'files_delete_comment',
						 'files_mass_move',
						 'files_mass_delete',
						 'ip_restrict_main',
						 'leech_settings',
						 'users_add_user',
						 'users_edit_user',
						 'users_delete_user',
						 'users_add_group',
						 'users_edit_group',
						 'users_delete_group',
						 'od_updates',
						 'od_license',
						 'od_bugs',
						 'languages'
						 );

// See if our cookie exists and is valid
if (isset($input['OD3_AutoLogin']))
{	
	$parts = explode('::', plain_decrypt($input['OD3_AutoLogin']));
	$hash = $parts[0];
	$data = $parts[1];
	
	$data = unserialize(stripslashes($data));
	
	// Give them nice names
	$user_id = $data[0];
	$username = $data[1];
	$group_id = $data[2];
	
	// Check the user exists
	$result = $dbim->query('SELECT id 
							FROM '.DB_PREFIX.'users 
							WHERE (id = "'.$user_id.'") 
								AND (username = "'.$username.'")
									AND (group_id="'.$group_id.'")');
	
	if ((md5($user_id.$username.$group_id) == $hash) && $dbim->num_rows($result) == 1)
	{
		$uam->user_login($user_id, $username, $group_id);
	}
		
	// Initialise permissions
	$uam->all_permissions($user_id);
}	
else
{				 
	// Make sure the user is either logged in or trying to						 
	if (!$uam->user_authed() && !isset($input['submit']))
	{
		validate_types($input, array('error' => 'STR', 'cmd' => 'STR'));
		
		// Start admin cp
		$start = $uim->fetch_template('admin/start');
		$start->show();
		
		// Set a redirect destination?
		if (empty($_SESSION['admin_redirect']) && (in_array($input['cmd'], $allowed_modules) /*|| empty($input['cmd'])*/) 
			&& $input['cmd'] != 'login' && $input['cmd'] != 'logout')
		{
			$_SESSION['admin_redirect'] = $_SERVER['REQUEST_URI'];
		}
		
		// Login template
		$login = $uim->fetch_template('admin/login');
		
		// If there was a login error, show it
		if (isset($input['error']))
		{
			$login->assign_var('error', urldecode($input['error']));
		}
		
		// Show login form
		$login->show();
		
		$end = $uim->fetch_template('global/end');
		$end->show();
		
		$uim->generate($lm->language('admin', 'please_login'));
		exit;
	}
	
	// Initialise permissions
	$uam->all_permissions();
}

if (!isset($input['cmd']) || empty($input['cmd']) || !in_array($input['cmd'], $allowed_modules))
{
	$input['cmd'] = 'main';
}

include('modules/admin/'.$input['cmd'].'.php');
?>