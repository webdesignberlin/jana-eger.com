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
	
	$template = $uim->fetch_template('admin/files_mass_move');
	
	if (isset($input['submit']))
	{
		if (!isset($input['source_id']) || !isset($input['dest_id']))
		{
			$template->assign_var('error', $lm->language('admin', 'must_specify_source_dest'));
		}
		elseif (!$fcm->get_cat(intval($input['source_id'])))
		{
			$template->assign_var('error', $lm->language('admin', 'invalid_category'));
		}
		elseif (!$fcm->get_cat(intval($input['dest_id'])))
		{
			$template->assign_var('error', $lm->language('admin', 'invalid_category'));
		}
		else
		{
			// All is good so let's try and move the files
			$dbim->query('UPDATE '.DB_PREFIX.'files 
							SET category_id = '.intval($input['dest_id']).'
							WHERE category_id = '.intval($input['source_id']));
			
			$success = true;
			$template->assign_var('success', true);
			$template->assign_var('message', $lm->language('admin', 'files_moved'));
		}
	}
	
	if (empty($error) || $error !== false)
	{
		$fcm->generate_category_list($template, 'category', 'source_id');
		$fcm->generate_category_list($template, 'category', 'dest_id');
	}
	
	// Can we get the source category?
	if (!empty($input['source_id']) && $input['source_id'] != '--' 
		&& $source = $fcm->get_cat(intval($input['source_id'])))
	{
		$source['name'] = '- '.$source['name'];
		$template->assign_var('source', $source);
	}
	else 
	{
		$source = array('id' => '--', 'name' => $lm->language('admin', 'categories_select'));
		$template->assign_var('source', $source);
	}
	
	// Can we get the destination category?
	if (!empty($input['dest_id']) && $input['dest_id'] != '--' 
		&& $dest = $fcm->get_cat(intval($input['dest_id'])))
	{
		$dest['name'] = '- '.$dest['name'];
		$template->assign_var('dest', $dest);
	}
	else 
	{
		$dest = array('id' => '--', 'name' => $lm->language('admin', 'categories_select'));
		$template->assign_var('dest', $dest);
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
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'files').' - '.$lm->language('admin', 'file_mass_move'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'files').' - '.$lm->language('admin', 'file_mass_move'), 'admin.php?cmd=files_mass_move');
}
?>