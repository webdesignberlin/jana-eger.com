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

if ($uam->permitted('acp_categories_delete_multiple'))
{	
	validate_types($input, array('move' => 'INT'));
	
	$delete_multiple = $uim->fetch_template('admin/categories_delete_multiple');
	
	// Listings module
	require_once('modules/core/listings.php');
	$listing = new listing;
	
	$category_list_tpl = $listing->list_cat_check();
	$category_list = $category_list_tpl->show(true);
	
	$delete_multiple->assign_var('category_list', $category_list);
	
	// Make the combo box
	$listing->list_cat_combo_box($delete_multiple, 'category', 'category_list');
	
	// Anything submitted?
	if (isset($_POST['submit']) || !empty($input['confirm_yes']) || !empty($input['confirm_no']))
	{
		// Firstly, convert all the 'on's into 1s	
		if (!empty($_POST['categories']))	
		{
			foreach ($_POST['categories'] as $category => $value)
			{
				$_POST['categories']["$category"] = ($value == 'on') ? 1 : 0;
			}
		}
		
		if (!empty($input['confirm_no']))
		{
			$delete_multiple->assign_var('error', 3);
		}
		// Don't kill the new parent!
		elseif (array_key_exists($_POST['move'], $_POST['categories']))
		{
			$delete_multiple->assign_var('error','1');
		}
		elseif (empty($_POST['categories']))
		{
			$delete_multiple->assign_var('error','2');
		}
		else 
		{
			if (empty($input['confirm_yes']) && empty($input['confirm_no']))
			{
				// Template for confirmation
				$delete_multiple = $uim->fetch_template('admin/generic_yes_no');
				
				// Variables
				$delete_multiple->assign_var('title', $lm->language('admin', 'categories_delete_multi'));
				$delete_multiple->assign_var('desc', $lm->language('admin', 'are_you_sure_list'));
				$delete_multiple->assign_var('action', 'admin.php?cmd=categories_delete_multiple');
				
				foreach ($_POST['categories'] as $category_id => $value)
				{
					$category_id = intval($category_id);
					
					// Cat info
					$category = $fcm->get_cat($category_id);
					
					// Stuff being deleted
					$text = str_replace('_CAT_NAME_', $category['name'], $lm->language('admin', 'category_delete_list_desc'));
					$delete_multiple->assign_var('text', $text);
					$delete_multiple->use_block('items');
					
					// Hidden fields
					$delete_multiple->assign_var('field_name', 'categories['.$category_id.']');
					$delete_multiple->assign_var('value', 1);
					$delete_multiple->use_block('hidden_fields');
				}
				
				// Text about moving
				if ($input['move'] == 0)
				{
					$text = $lm->language('admin', 'cat_multi_move_root');
				}
				else
				{
					$dest_category = $fcm->get_cat($input['move']);
					$text = str_replace('_CAT_NAME_', $dest_category['name'], $lm->language('admin', 'category_move_list_desc'));
				}
				
				$delete_multiple->assign_var('text', $text);
				$delete_multiple->use_block('items');
				
				// Category ID to move things to
				$delete_multiple->assign_var('field_name', 'move');
				$delete_multiple->assign_var('value', $input['move']);
				$delete_multiple->use_block('hidden_fields');
			}
			elseif (!empty($input['confirm_yes']))
			{
				foreach ($_POST['categories'] as $category => $value)
				{
					// Start by moving anything
					$dbim->query('UPDATE '.DB_PREFIX.'files SET category_id="'.$input['move'].'" WHERE category_id="'.$category.'"');
					
					// Now, the children
					$dbim->query('UPDATE '.DB_PREFIX.'categories SET parent_id="'.$input['move'].'" WHERE parent_id="'.$category.'"');
					
					// And finally, delete the category
					$dbim->query('DELETE FROM '.DB_PREFIX.'categories WHERE id="'.$category.'"');
				}
				
				// And show the message
				$success = true;
				$delete_multiple->assign_var('success', $success);
			}
			else
			{
				$delete_multiple->assign_var('error', 3);
			}
		}
	}
	
	$delete_multiple->show();
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
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'categories').' - '.$lm->language('admin', 'categories_delete_multi'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'categories').' - '.$lm->language('admin', 'categories_delete_multi'), 'admin.php?cmd=categories_delete_multiple');
}
?>