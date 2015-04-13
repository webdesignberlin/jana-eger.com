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

if ($uam->permitted('acp_categories_edit'))
{
	validate_types($input, array('action' => 'STR', 'id' => 'INT', 'name' => 'STR', 'description' => 'STR', 'parent_id' => 'INT', 'sort' => 'INT'));
	
	if ($input['action'] == 'select' && isset($input['id']))
	{
		// Template
		$categories_edit = $uim->fetch_template('admin/categories_edit');

		$category_result = $dbim->query('SELECT id, parent_id, name, description, sort, keywords
											FROM '.DB_PREFIX.'categories
											WHERE (id = '.$input['id'].')');										
		$category_row = $dbim->fetch_array($category_result);
		$categories_edit->assign_var('current_category', $category_row);
		
		// Include listings module
		require_once('modules/core/listings.php');
		$listing = new listing();
		
		// Get heierachy of children excluding this category and any children it has
		$listing->where_cat = 'id <> '.$input['id'];
		$cats_filtered = $listing->get_cats();
		
		// Call function to add values to combo box
		$listing->list_cat_combo_box($categories_edit, 'category', 'cats', $cats_filtered);
		
		// Get the name and ID of the parent category
		$category_result = $dbim->query('SELECT id, name
											FROM '.DB_PREFIX.'categories
											WHERE (id = '.$category_row['parent_id'].')');		
		$category_row = $dbim->fetch_array($category_result);
	
		// Assign name/ID
		if (empty($category_row))
		{
			$categories_edit->assign_var('category_name', $lm->language('admin', 'na_parent'));
		}
		else
		{
			$categories_edit->assign_var('category_name', $category_row['name']);
			$categories_edit->assign_var('parent_id', $category_row['id']);
		}
		
	}
	elseif ($input['action'] == 'edit' && isset($input['id']))
	{
		// Template
		$categories_edit = $uim->fetch_template('admin/categories_edit');

		$dbim->query('UPDATE '.DB_PREFIX.'categories
						SET name = "'.$input['name'].'", 
							description = "'.$input['description'].'", 
							parent_id = "'.$input['parent_id'].'",
							sort = "'.$input['sort'].'",
							keywords = "'.$input['keywords'].'"
						WHERE (id = '.$input['id'].')');
						
		$success = true;
		$categories_edit->assign_var('success', $success);
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
			'query' => 'cmd=categories_edit&action=select&id=#cat_id#'
			);
		
		// Build listing
		$categories_edit = $listing->list_cat_file_div('cmd=categories_edit_file', $cat_link, false, false, -1, false);
		
		// Header and text...
		$categories_edit->assign_var('title', $lm->language('admin', 'categories_edit'));
		$categories_edit->assign_var('text', $lm->language('admin', 'categories_edit_select'));
	}
	
	$categories_edit->show();
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
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'categories').' - '.$lm->language('admin', 'categories_edit'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'categories').' - '.$lm->language('admin', 'categories_edit'), 'admin.php?cmd=categories_edit');
}
?>