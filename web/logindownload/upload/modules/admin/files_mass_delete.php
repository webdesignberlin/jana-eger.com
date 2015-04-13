<?php
/**********************************
* Olate Download 3.4.1
* http://www.olate.co.uk/od3
**********************************
* Copyright Olate Ltd 2005
*
* @author $Author: dsalisbury $ (Olate Ltd)
* @version $Revision: 141 $
* @package od
*
* Updated: $Date: 2005-10-27 19:00:15 +0100 (Thu, 27 Oct 2005) $
*/

// Start admin cp
$start = $uim->fetch_template('admin/start');
$start->show();

if ($uam->permitted('acp_files_mass_move'))
{
	validate_types($input, array('source_id' => 'INT', 'dest_id' => 'INT'));
	
	$template = $uim->fetch_template('admin/files_mass_delete');
	
	if (isset($input['submit']))
	{
		if (!isset($input['cat_id']) || $input['cat_id'] == '--')
		{
			$template->assign_var('error', $lm->language('admin', 'must_specify_source_dest'));
		}
		elseif (!$fcm->get_cat(intval($input['cat_id'])))
		{
			$template->assign_var('error', $lm->language('admin', 'invalid_category'));
		}
		elseif (empty($input['confirm_yes']) && empty($input['confirm_no']))
		{
			// Load confirmation template
			$template = $uim->fetch_template('admin/generic_yes_no');
			
			// Variables
			$template->assign_var('title', $lm->language('admin', 'file_mass_delete'));
			$template->assign_var('desc', $lm->language('admin', 'are_you_sure_list'));
			$template->assign_var('action', 'admin.php?cmd=files_mass_delete&cat_id='.$input['cat_id']);
			
			// Add category to items list
			$category = $fcm->get_cat(intval($input['cat_id']));
			$text = str_replace('_NAME_', $category['name'], $lm->language('admin', 'file_mass_delete_list'));
			
			$template->assign_var('text', $text);
			$template->use_block('items');
		}
	}
	elseif (!empty($input['confirm_yes']))
	{
		// All is good so let's try and move the files
		$dbim->query('DELETE FROM '.DB_PREFIX.'files 
						WHERE category_id = '.intval($input['cat_id']));
		
		$success = true;
		$template->assign_var('success', true);
		$template->assign_var('message', $lm->language('admin', 'file_mass_deleted'));
	}
	elseif (!empty($input['confirm_no']))
	{
		$success = true; // For redirect EOF
		$template->assign_var('success', true);
		$template->assign_var('message', $lm->language('admin', 'file_mass_not_deleted'));
	}
	
	if (empty($error) || $error !== false)
	{
		$fcm->generate_category_list($template, 'category', 'cats');
	}
	
	// Can we get the destination category?
	if (!empty($input['cat_id']) && $input['cat_id'] != '--' 
		&& $cat = $fcm->get_cat(intval($input['cat_id'])))
	{
		$cat['name'] = '- '.$dest['name'];
		$template->assign_var('cat', $cat);
	}
	else 
	{
		$cat = array('id' => '--', 'name' => $lm->language('admin', 'categories_select'));
		$template->assign_var('cat', $cat);
	}
	
	$template->show();
}
else
{
	// User is not permitted
	$no_permission = $uim->fetch_template('admin/no_permission');
	$no_permission->show();
}

$end = $uim->fetch_template('global/end');
$end->show();
		
if (!isset($success) || !$success)
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'files').' - '.$lm->language('admin', 'file_mass_delete'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'files').' - '.$lm->language('admin', 'file_mass_delete'), 'admin.php?cmd=files_mass_delete');
}
?>