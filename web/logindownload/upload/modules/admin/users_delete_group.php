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

if ($uam->permitted('acp_users_add_user'))
{		
	// Template
	$delete_group = $uim->fetch_template('admin/users_delete_group');
	
	validate_types($input, array('id' => 'INT'));
	
	// Have they specified a user id?
	if (!empty($input['id']))
	{
		$check_result = $dbim->query('SELECT group_id
										FROM '.DB_PREFIX.'users
										WHERE (group_id = '.$input['id'].')');
		
		if ($dbim->num_rows($check_result) == 0)
		{
			if (empty($input['confirm_yes']) && empty($input['confirm_no']))
			{
				// Get user info
				$result = $dbim->query('SELECT name
										FROM '.DB_PREFIX.'usergroups 
										WHERE id = '.$input['id']);
				
				$row = $dbim->fetch_array($result);
				
				// Load template
				$delete_group = $uim->fetch_template('admin/generic_yes_no');
				
				// Variables
				$delete_group->assign_var('title', $lm->language('admin', 'groups_delete'));
				$delete_group->assign_var('desc', $lm->language('admin', 'are_you_sure_list'));
				$delete_group->assign_var('action', 'admin.php?cmd=users_delete_group&id='.$input['id']);
				
				// Add user to items list
				$text = str_replace('_NAME_', $row['name'], $lm->language('admin', 'groups_delete_list_desc'));
				
				$delete_group->assign_var('text', $text);
				$delete_group->use_block('items');
			}
			elseif (!empty($input['confirm_yes']))
			{
				// Delete usergroup
				$dbim->query('DELETE FROM '.DB_PREFIX.'usergroups
								WHERE (id = '.$input['id'].')
								LIMIT 1');
								
				$success = true; // For redirect EOF
				$delete_group->assign_var('result', 1);
			}
			else
			{
				$success = true; // For redirect EOF
				$delete_group->assign_var('result', 3);
			}
		}
		else
		{	
			// Can't delete if there are users in the group
			$delete_group->assign_var('result', 2);
		}
	}
	else
	{
		// Display a list of users
		$result = $dbim->query('SELECT id, name 
								FROM '.DB_PREFIX.'usergroups 
								ORDER BY name');
		
		while ($group = $dbim->fetch_array($result))
		{
			$delete_group->assign_var('group', $group);
			$delete_group->use_block('group');
		}
	}
	
	$delete_group->show();
}
else
{
	// User is not permitted
	$no_permission = $uim->fetch_template('admin/no_permission');
	$no_permission->show();
}
	
// End the page
$end = $uim->fetch_template('global/end');
$end->show();

if (!isset($success) || !$success)
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'users_groups').' - '.$lm->language('admin', 'groups_delete'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'users_groups').' - '.$lm->language('admin', 'groups_delete'), 'admin.php?cmd=users_delete_group');
}
?>