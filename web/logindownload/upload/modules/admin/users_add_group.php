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

if ($uam->permitted('acp_users_add_group'))
{		
	validate_types($input, array('name' => 'STR'));
	
	// Has the form been submitted?
	if (isset($input['submit']))
	{
		if (empty($input['name']))
		{
			// Template
			$error = $uim->fetch_template('global/error');
			$error->assign_var('error_message', $lm->language('admin', 'enter_group_name'));
			$error->show();
			
			// Show the form
			$group_add = $uim->fetch_template('admin/users_add_group');
			$group_add->show();
		}
		else
		{
			$dbim->query('INSERT INTO '.DB_PREFIX.'usergroups 
							SET name = "'.$input['name'].'"');
			
			// Insert permissions
			if (!empty($_POST['permissions']))
			{
				$group_id = $dbim->insert_id();

				// A list of permissions
				$permissions = $uam->list_permissions();
				
				// Get the specified permissions
				$user_permissions = $_POST['permissions'];
				
				foreach($permissions as $permission => $permission_id)
				{
					$setting = (isset($user_permissions["$permission"])) ? 1 : 0;
					
					// Insert it - This is pretty damn query heavy :(
					$dbim->query('INSERT INTO '.DB_PREFIX.'userpermissions 
									SET permission_id = "'.$permission_id.'", 
										type = "user_group",
										type_value = "'.$group_id.'", 
										setting = "'.$setting.'"');
				}	
			}
			
			// Template
			$message = $uim->fetch_template('admin/users_add_group');
			$success = true; // For redirect EOF
			$message->assign_var('success', true);
			$message->show();
		}
	}
	else
	{
		// Show the form
		$group_add = $uim->fetch_template('admin/users_add_group');
		$group_add->show();
	}
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
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'users_groups').' - '.$lm->language('admin', 'groups_add'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'users_groups').' - '.$lm->language('admin', 'groups_add'), 'admin.php?cmd=users_add_group');
}
?>