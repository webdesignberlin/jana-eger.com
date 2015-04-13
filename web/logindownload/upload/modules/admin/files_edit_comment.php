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

if ($uam->permitted('acp_files_edit_comment'))
{
	validate_types($input, array('id' => 'INT', 'submit' => 'INT', 'name' => 'STR', 'email' => 'STR', 'comment' => 'STR'));
	
	// Template
	$edit = $uim->fetch_template('admin/files_edit_comment');
	
	if (!empty($input['id']) && !isset($input['submit']))
	{	
		$comment_result = $dbim->query('SELECT id, name, email, comment
											FROM '.DB_PREFIX.'comments
											WHERE (id = '.$input['id'].')');
											
		$comment = $dbim->fetch_array($comment_result);
		$edit->assign_var('comment', $comment);
		
		if (!empty($input['redir']))
		{
			if ($input['redir'] == 'files_edit_file' && !empty($input['file_id']))
			{
				$edit->assign_var('redir', $input['redir']);
				$edit->assign_var('file_id', $input['file_id']);
			}
		}
	}
	elseif (!empty($input['id']) && isset($input['submit']))
	{
		$comment_result = $dbim->query('UPDATE '.DB_PREFIX.'comments
											SET name = "'.$input['name'].'",
												email = "'.$input['email'].'",
												comment = "'.$input['comment'].'"
											WHERE (id = '.$input['id'].')');
									
		$success = true; // For redirect EOF
		$edit->assign_var('success', true);
		
		// Do we need to redirect?
		if (!empty($input['redir']))
		{
			if ($input['redir'] == 'files_edit_file' && !empty($input['file_id']))
			{
				header('Location: admin.php?cmd=files_edit_file&action=file_select&file_id='.intval($input['file_id']));
				exit;
			}
		}
	}
	else
	{
		$edit->assign_var('empty', true);
	}
	
	$edit->show();
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
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'files').' - '.$lm->language('admin', 'comments_edit_existing'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'files').' - '.$lm->language('admin', 'comments_edit_existing'), 'admin.php?cmd=files_edit_comment&id='.$input['id']);
}
?>