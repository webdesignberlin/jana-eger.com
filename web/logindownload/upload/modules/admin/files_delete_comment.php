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

if ($uam->permitted('acp_files_delete_comment'))
{
	validate_types($input, array('id' => 'INT'));
	
	// Template
	$delete = $uim->fetch_template('admin/files_delete_comment');
	
	if (!empty($input['id']))
	{	
		$dbim->query('DELETE FROM '.DB_PREFIX.'comments
						WHERE (id = '.$input['id'].')
						LIMIT 1');
						
		$success = true; // For redirect EOF
		$delete->assign_var('success', true);
		
		// Redirect?
		if (!empty($input['redir']))
		{
			if ($input['redir'] == 'files_edit_file' && !empty($input['file_id']))
			{
				header('Location: admin.php?cmd=files_edit_file&action=file_select&file_id='.intval($input['file_id']));
			}
		}
	}
	
	$delete->show();
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
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'files').' - '.$lm->language('admin', 'comments_delete_existing'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'files').' - '.$lm->language('admin', 'comments_delete_existing'), 'admin.php?cmd=files_approve_comments');
}
?>