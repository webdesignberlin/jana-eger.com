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

if ($uam->permitted('acp_categories_add'))
{		
	// Template
	$categories_add = $uim->fetch_template('admin/categories_add');
	
	// Make any changes
	if (isset($input['submit']))
	{
		validate_types($input, array('name' => 'STR', 'description' => 'STR', 'parent_id' => 'INT', 'order' => 'INT', 'keywords' => 'STR'));
		
		$dbim->query('INSERT INTO '.DB_PREFIX.'categories
						SET name = "'.$input['name'].'", 
							description = "'.$input['description'].'", 
							parent_id = "'.$input['parent_id'].'",
							sort = "'.$input['sort'].'",
							keywords = "'.$input['keywords'].'"');
	
		$success = true;
		$categories_add->assign_var('success', $success);
	}
	
	// List categories
	$fcm->generate_category_list($categories_add, 'category', 'cats');
	
	$categories_add->show();
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
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'categories').' - '.$lm->language('admin', 'categories_add'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'files').' - '.$lm->language('admin', 'categories_add'), 'admin.php?cmd=categories_add');
}
?>