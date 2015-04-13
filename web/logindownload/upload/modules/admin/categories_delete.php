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

if ($uam->permitted('acp_categories_delete'))
{
	// Template
	$categories_delete = $uim->fetch_template('admin/categories_delete');
	
	if ($input['action'] == 'select' && isset($input['id']))
	{
		validate_types($input, array('id' => 'INT'));
		
		$check_result = $dbim->query('SELECT count(id) as count
										FROM '.DB_PREFIX.'files
										WHERE (category_id = '.$input['id'].')');
		
		$check_row = $dbim->fetch_array($check_result);
		
		if ($check_row['count'] == 0)
		{
			if (empty($input['confirm_yes']) && empty($input['confirm_no']))
			{
				// Get category info
				$category = $fcm->get_cat($input['id']);
				
				// Template for confirmation
				$categories_delete = $uim->fetch_template('admin/generic_yes_no');
				
				// Variables
				$categories_delete->assign_var('title', $lm->language('admin', 'categories_delete'));
				$categories_delete->assign_var('desc', $lm->language('admin', 'delete_items_confirm'));
				$categories_delete->assign_var('action', 'admin.php?cmd=categories_delete&action=select&id='.$input['id']);
				
				// Block for what's being deleted
				$categories_delete->assign_var('text', $category['name']);
				$categories_delete->use_block('items');
			}
			elseif (!empty($input['confirm_yes']))
			{
				// There are no files in the category
				$dbim->query('DELETE FROM '.DB_PREFIX.'categories
								WHERE (id = '.$input['id'].')
								LIMIT 1');
				
				// And show the message
				$categories_delete->assign_var('result', 1);
				$success = true;
			}
			else
			{
				$categories_delete->assign_var('result', 3);
				$success = true;
			}
		}
		else
		{	
			// The category has files in it, show the move form
			
			// First get the categories
			$categories = $fcm->get_all_cats();
			
			// Include module
			require_once('modules/core/listings.php');
			$listing = new listing();
			
			// Filter category listings
			$listing->where_cat = 'id <> '.$input['id'];
			$cats_filtered = $listing->get_cats();
			
			// Call function
			$listing->list_cat_combo_box($categories_delete, 'category', 'move_categories', $cats_filtered);
			
			// Give it the current categoryid
			$categories_delete->assign_var('current', $input['id']);
			$categories_delete->assign_var('result', 2);
		}
	}
	// Move children & files
	elseif (isset($input['move']))
	{
		validate_types($input, array('current' => 'INT', 'move' => 'INT'));
		
		// Confirmation
		if (empty($input['confirm_yes']) && empty($input['confirm_yes']))
		{
			$categories_delete = $uim->fetch_template('admin/generic_yes_no');
			
			$category = $fcm->get_cat($input['current']);
			
			// Variables
			$categories_delete->assign_var('title', $lm->language('admin', 'categories_delete'));
			$categories_delete->assign_var('desc', $lm->language('admin', 'are_you_sure_list'));
			$categories_delete->assign_var('action', 'admin.php?cmd=categories_delete&action=move&current='.$input['current'].'&move='.$input['move']);
			
			// Block for what's being deleted
			$text = str_replace('_CAT_NAME_', $category['name'], $lm->language('admin', 'category_delete_list_desc'));
			$categories_delete->assign_var('text', $text);
			$categories_delete->use_block('items');
			
			// Destination category details
			$dest_category = $fcm->get_cat($input['move']);
			$text = str_replace('_CAT_NAME_', $dest_category['name'], $lm->language('admin', 'category_move_list_desc'));
			
			$categories_delete->assign_var('text', $text);
			$categories_delete->use_block('items');
			
		}
		elseif (!empty($input['confirm_yes']))
		{
			// Firstly, move all files
			$dbim->query('UPDATE '.DB_PREFIX.'files SET category_id="'.$input['move'].'" WHERE category_id="'.$input['current'].'"');
			
			// Now, the children
			$dbim->query('UPDATE '.DB_PREFIX.'categories SET parent_id="'.$input['move'].'" WHERE parent_id="'.$input['current'].'"');
			
			// And finally, delete the category
			$dbim->query('DELETE FROM '.DB_PREFIX.'categories WHERE id="'.$input['current'].'"');
			
			// And show the message
			$categories_delete->assign_var('result', 1);
			
			// End redirect
			$success = true;
		}
		else
		{
			$categories_delete->assign_var('result', 3);
			
			// End redirect
			$success = true;
		}
	}
	// Default template - select category
	else
	{
		// Include module
		require_once('modules/core/listings.php');
		$listing = new listing();
		
		// Link for categories
		$cat_link = array(
			'link' => 'admin.php',
			'query' => 'cmd=categories_delete&action=select&id=#cat_id#'
			);
		
		// Build listing
		$categories_delete = $listing->list_cat_file_div('cmd=categories_delete_file', $cat_link, false, false, -1, false);
		
		// Header and text...
		$categories_delete->assign_var('title', $lm->language('admin', 'categories_delete'));
		$categories_delete->assign_var('text', $lm->language('admin', 'categories_delete_select'));
	}
	
	$categories_delete->show();
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
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'categories').' - '.$lm->language('admin', 'categories_delete'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'categories').' - '.$lm->language('admin', 'categories_delete'), 'admin.php?cmd=categories_delete');
}
?>