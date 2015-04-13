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

if ($uam->permitted('acp_files_delete_file'))
{
	validate_types($input, array('action' => 'STR', 'file' => 'INT'));
	
	// Show all files
	$delete_file = $uim->fetch_template('admin/files_delete_file');
	
	if ($input['action'] == 'delete' && !empty($input['file']))
	{	
		if (empty($input['confirm_yes']) && empty($input['confirm_no']))
		{
			// Load template
			$delete_file = $uim->fetch_template('admin/generic_yes_no');
			
			// Variables
			$delete_file->assign_var('title', $lm->language('admin', 'file_delete'));
			$delete_file->assign_var('desc', $lm->language('admin', 'are_you_sure_list'));
			$delete_file->assign_var('action', 'admin.php?cmd=files_delete_file&action=delete&file='.$input['file']);
			
			// Add file to items list
			$file = $fldm->get_details($input['file']);
			$text = str_replace('_FILE_', $file['name'], $lm->language('admin', 'file_delete_list_desc'));
			
			$delete_file->assign_var('text', $text);
			$delete_file->use_block('items');
		}
		elseif (!empty($input['confirm_yes']))
		{
			// Delete file
			$dbim->query('DELETE FROM '.DB_PREFIX.'files
							WHERE (id = '.$input['file'].')
							LIMIT 1');
							
			// Is this file located on the server? If so, we'll delete it
			$mirror_query = $dbim->query('SELECT id, url FROM '.DB_PREFIX.'mirrors WHERE (file_id = '.$input['file'].')');
			
			while ($mirror = $dbim->fetch_array($mirror_query))
			{
				$url = explode('/', $mirror['url']);
				$file = array_pop($url);
				
				if (file_exists('uploads/'.$file))
				{
					unlink('uploads/'.$file);
					$delete_file->assign_var('file_deleted', true);
				}
			}
			
			// Delete mirror(s)
			$dbim->query('DELETE FROM '.DB_PREFIX.'mirrors
							WHERE (file_id = '.$input['file'].')');
							
			// Delete comment(s)
			$dbim->query('DELETE FROM '.DB_PREFIX.'comments
							WHERE (file_id = '.$input['file'].')');
							
			$success = true; // For redirect EOF
			$delete_file->assign_var('success', true);
		}
		else
		{
			$success = true; // For redirect EOF
			$delete_file->assign_var('success', 'nothing');
		}
	}
	else
	{
		// Include module
		require_once('modules/core/listings.php');
		$listing = new listing();
		
		// Categories to expand?
		if (!empty($input['files_for']) || $input['files_for'] == '0')
		{
			$files_for = $input['files_for'];
			// Tidy it up
			$listing->trim_files_for($files_for);
			
			// Explode... boom!
			$files_for = explode(',', $files_for);
		}
		else
		{
			$files_for = false;
		}
		
		// Link for files
		$file_link = array(
			'link' => 'admin.php',
			'query' => 'cmd=files_delete_file&amp;action=delete&amp;file=#file_id#'
			);
		
		// Query string
		$self_query = 'cmd='.$input['cmd'];
		
		// Filter category list leaving only categories that have files
		$category_list = $listing->filter_cats();
		
		// Build listing
		$delete_file = $listing->list_cat_file_div($self_query, false, $file_link, $category_list, $files_for);
		
		// Header and text...
		$text = $lm->language('admin', 'file_delete_desc').'. '.$lm->language('admin', 'list_expand_collapse');
		$delete_file->assign_var('title', $lm->language('admin', 'file_delete'));
		$delete_file->assign_var('text', $text);
		
		if ($fcm->count_files(0) > 0)
		{
			// Private/hidden files
			$cat_level = array(0);
			$private_files = $listing->list_cat_file_div($self_query, false, $file_link, $cat_level, $files_for);
			$private_category = $private_files->show(true);
			
			// Assign it
			$delete_file->assign_var('private_category', $private_category);
		}
	}
	
	$delete_file->show();
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
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'files').' - '.$lm->language('admin', 'file_delete'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'files').' - '.$lm->language('admin', 'file_delete'), 'admin.php?cmd=files_delete_file');
}
?>