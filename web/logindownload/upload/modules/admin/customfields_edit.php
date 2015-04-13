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

if ($uam->permitted('acp_customfields_edit'))
{	
	if ($input['action'] == 'select' && isset($input['id']))
	{
		validate_types($input, array('id' => 'INT'));
		
		// Template
		$customfields_edit = $uim->fetch_template('admin/customfields_edit');
		
		$customfields_query = $dbim->query('SELECT id, label, value
											FROM '.DB_PREFIX.'customfields
											WHERE (id = '.$input['id'].')');
		$customfields = $dbim->fetch_array($customfields_query);
		$customfields_edit->assign_var('customfield', $customfields);
	}
	elseif ($input['action'] == 'edit' && isset($input['id']))
	{
		// Template
		$customfields_edit = $uim->fetch_template('admin/customfields_edit');
		
		validate_types($input, array('label' => 'STR', 'value' => 'STR_HTML'));
		
		$dbim->query('UPDATE '.DB_PREFIX.'customfields
						SET label = "'.$input['label'].'", 
							value = "'.$input['value'].'"
						WHERE (id = '.$input['id'].')');
	
		$success = true;
		$customfields_edit->assign_var('success', $success);
	}
	else
	{
		// Template
		$customfields_edit = $uim->fetch_template('admin/customfields_edit_select');
		
		$customfields_query = $dbim->query('SELECT id, label
											FROM '.DB_PREFIX.'customfields');
		
		while ($customfields = $dbim->fetch_array($customfields_query))
		{		
			$customfields_edit->assign_var('customfield', $customfields);
			$customfields_edit->use_block('customfields');
		}
	}
		
	$customfields_edit->show();
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
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'custom_fields').' - '.$lm->language('admin', 'custom_fields_edit'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'custom_fields').' - '.$lm->language('admin', 'custom_fields_edit'), 'admin.php?cmd=customfields_edit');
}
?>