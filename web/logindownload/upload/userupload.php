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

// Initialisation
require('./includes/init.php');

// Show categories
$fcm->show_cats();

validate_types($input, array('name' => 'STR', 'description_small' => 'STR',
								 'description_big' => 'STR', 'category' => 'INT',
								 'downloads' => 'INT', 'size' => 'INT', 
								 'agreement' => 'INT', 'password' => 'STR', 'upload' => 'INT',
								 'convert_newlines' => 'INT', 'filesize_format' => 'STR'));

if ($site_config['enable_useruploads'] == 1)
{
	// Upload file
	if (isset($_FILES['uploadfile']))
	{		
		$ext = strrchr($_FILES['uploadfile']['name'], '.');
		$allowed_ext = explode(',', $site_config['uploads_allowed_ext']);
			
		// It's like finding a needle in a haystack...
		if (in_array($ext, $allowed_ext))
		{
			$time = time();
			
			if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], './uploads/upload-'.$time.'-'.basename($_FILES['uploadfile']['name']))) 
			{
				// Get various variables
				if (!empty($input['password']))
				{
					$password = md5($input['password']);
				}
				else
				{
					$password = '';
				}
				
				if (!empty($input['convert_newlines']) && $input['convert_newlines'] === 1)
				{
					$convert_newlines = $input['convert_newlines'];
				}
				else
				{
					$convert_newlines = 0;
				}
				
				// Gigabyte?
				if ($input['filesize_format'] == 'gb')
				{
					$filesize = $input['size'] * pow(1024,3);
				}
				// Megabyte?
				elseif ($input['filesize_format'] == 'mb')
				{
					$filesize = $input['size'] * pow(1024,2);
				}
				// Kilobyte?
				elseif ($input['filesize_format'] == 'kb')
				{
					$filesize = $input['size'] * 1024;
				}
				// Byte, or otherwise
				else
				{
					$filesize = $input['size'];
				}
				
				$dbim->query('INSERT INTO '.DB_PREFIX.'files
								SET category_id = "'.$input['category'].'", 
									name = "'.$input['name'].'", 
									description_small = "'.$input['description_small'].'", 
									description_big = "'.$input['description_big'].'", 
									downloads = "'.$input['downloads'].'", 
									size = "'.$input['size'].'", 
									date = "'.$time.'",
									agreement_id = "'.$input['agreement'].'",
									password = "'.$password.'",
									status = 0,
									convert_newlines = '.$convert_newlines);
				
				$file_id = $dbim->insert_id();
				
				// File was uploaded successfully - add as a mirror
				$dbim->query('INSERT INTO '.DB_PREFIX.'mirrors
								SET file_id = '.$file_id.', 
									name = "Mirror 1", 
									location = "Earth", 
									url = "'.$site_config['url'].'uploads/upload-'.$time.'-'.basename($_FILES['uploadfile']['name']).'"');			
				
				// Get filesize in bytes
				$filesize = filesize('./uploads/upload-'.$time.'-'.basename($_FILES['uploadfile']['name']));
				
				// Update file size
				$dbim->query('UPDATE '.DB_PREFIX.'files
								SET size = '.$filesize.'
								WHERE id = '.$file_id);
				
				// Template
				$add_file = $uim->fetch_template('files/userupload_add');
				
				if ($site_config['userupload_always_approve'])
				{
					// Set file as active
					$dbim->query('UPDATE '.DB_PREFIX.'files
									SET status = 1
									WHERE id = '.$file_id);
					
					$success = true; // For redirect EOF
					$add_file->assign_var('id', $file_id);
					$add_file->assign_var('success', true);
					
				}
				else
				{
					$error = $lm->language('admin', 'upload_error_0');
				}
				
				// E-mail admin to let them know
				$message = "Hello,\n\nA user (".$_SERVER['REMOTE_ADDR'].") has just added a file to your site.";
				
				if ($site_config['userupload_always_approve'] == 0)
				{
					 $message .= " For security reasons, it is not yet viewable - you must first enable it by editing the file at \n\n".$site_config['url']."admin.php?cmd=files_edit_file&action=file_select&file_id=".$file_id;
				}
				else
				{
					$message .= " You can see it at \n\n".$site_config['url']."details.php?file=".$file_id;
					
					$dbim->query('UPDATE '.DB_PREFIX.'files
									SET status = 1
									WHERE id = '.$file_id);
				}
				
				// Send
				mail($site_config['admin_email'], 'New File Added', $message, 'From: '.$site_config['admin_email']);
			}
			else 
			{
				switch ($_FILES['uploadfile']['error'])
				{
					case 1:
						$error = $lm->language('admin', 'upload_error_1');
						break;
					case 3:
						$error = $lm->language('admin', 'upload_error_3');
						break;
					case 4:
						$error = $lm->language('admin', 'upload_error_4');
						break;
				}
			}
		}
		else
		{
			$error = $lm->language('admin', 'upload_error_ext');
		}
		
		if (!isset($add_file))
		{
			// Template
			$add_file = $uim->fetch_template('files/userupload_upload');
			
			// Any error?
			if (!empty($error))
			{
				$add_file->assign_var('error', $error);
				$add_file->assign_var('success', false);
			}
			
		}
	}
	
	// Has the form been submitted?
	if (isset($input['submit']) && !empty($input['name']) && !isset($_FILES['uploadfile']))
	{
		if (isset($input['upload']))
		{	
			// Use this in place of an ID for the time being... (no pun)			
			$time = time();
			
			// Template
			$add_file = $uim->fetch_template('files/userupload_upload');
			
			// These are the fields we need to pass on to the upload section
			$wanted_elements = array('name', 'description_small', 'description_big', 
										'convert_newlines', 'category', 'size',
										'filesize_format');
			
			foreach ($input as $key => $value)
			{
				if (in_array($key, $wanted_elements) || (strpos($key, 'custom_field') !== false))
				{
					$add_file->assign_var('key', $key);
					$add_file->assign_var('value', $value);
					$add_file->use_block('submitted_data');
				}
			}
			
			// Display the max filesize that can be uploaded
			$max_upload_size = ini_get('upload_max_filesize');
			$add_file->assign_var('max_upload_size', $max_upload_size);
		}
		else
		{
			for ($i = 1; $i <= $site_config['mirrors']; $i++)
			{
				validate_types($input, array('mirror'.$i.'_name' => 'STR', 'mirror'.$i.'_location' => 'STR', 'mirror'.$i.'_url' => 'STR'));
					
				if (!empty($input['mirror'.$i.'_name']) && !empty($input['mirror'.$i.'_location'])&& !empty($input['mirror'.$i.'_url']))
				{
					// Have we inserted into the database yet?
					if (empty($inserted))
					{
						// Get various variables
						if (!empty($input['password']))
						{
							$password = md5($input['password']);
						}
						else
						{
							$password = '';
						}
						
						if (!empty($input['convert_newlines']) && $input['convert_newlines'] === 1)
						{
							$convert_newlines = $input['convert_newlines'];
						}
						else
						{
							$convert_newlines = 0;
						}
						
						// Gigabyte
						if ($input['filesize_format'] == 'gb')
						{
							$filesize = floatval($input['size'] * pow(1024,3));
						}
						// Megabyte
						elseif ($input['filesize_format'] == 'mb')
						{
							$filesize = floatval($input['size'] * pow(1024,2));
						}
						// Kilobyte
						elseif ($input['filesize_format'] == 'kb')
						{
							$filesize = floatval($input['size'] * 1024);
						}
						// Byte, or otherwise
						else
						{
							$filesize = floatval($input['size']);
						}
						
						$dbim->query('INSERT INTO '.DB_PREFIX.'files
										SET category_id = "'.$input['category'].'", 
											name = "'.$input['name'].'", 
											description_small = "'.$input['description_small'].'", 
											description_big = "'.$input['description_big'].'", 
											downloads = "'.$input['downloads'].'", 
											size = "'.$filesize.'", 
											date = "'.time().'",
											agreement_id = "'.$input['agreement'].'",
											password = "'.$password.'",
											status = 0,
											convert_newlines = '.$convert_newlines);
						
						$file_id = $dbim->insert_id();
						
						// We have now, and don't want to do it again
						$inserted = true;
					}
					
					$dbim->query('INSERT INTO '.DB_PREFIX.'mirrors
									SET file_id = '.$file_id.', 
										name = "'.$input['mirror'.$i.'_name'].'", 
										location = "'.$input['mirror'.$i.'_location'].'", 
										url = "'.$input['mirror'.$i.'_url'].'"');
					$success = true;
				}
			}	
			
			// Don't do anything unless row has been inserted
			if (!empty($inserted))
			{
				// Add the new custom field value
				for ($i = 1; $i <= $input['custom_field_total']; $i++)
				{			 
					if (!empty($input['custom_field_'.$i.'_value']))
					{
						validate_types($input, array('custom_field_'.$i.'_value' => 'STR'));
						
						// Add
						$dbim->query('INSERT INTO '.DB_PREFIX.'customfields_data
										SET field_id = '.$input['custom_field_'.$i.'_field_id'].',
										file_id = '.$file_id.',
										value = "'.$input['custom_field_'.$i.'_value'].'"');
					}
				}
			
				// E-mail admin to let them know
				$message = "Hello,\n\nA user (".$_SERVER['REMOTE_ADDR'].") has just added a file to your site.";
				
				if ($site_config['userupload_always_approve'] == 0)
				{
					 $message .= " For security reasons, it is not yet viewable - you must first enable it by editing the file at \n\n".$site_config['url']."admin.php?cmd=files_edit_file&action=file_select&file_id=".$file_id;
				}
				else
				{
					$message .= " You can see it at \n\n".$site_config['url']."details.php?file=".$file_id;
					
					$dbim->query('UPDATE '.DB_PREFIX.'files
									SET status = 1
									WHERE id = '.$file_id);
				}
				
				// Send
				mail($site_config['admin_email'], 'New File Added', $message, 'From: '.$site_config['admin_email']);
			}
			else
			{
				$success = false;
			}
			
			// Template
			$add_file = $uim->fetch_template('files/userupload_add');
			
			#$success = true; // For redirect EOF
			$add_file->assign_var('id', $file_id);
			$add_file->assign_var('success', $success);
		}
	}
	
	if (!isset($add_file))
	{
		// Template
		$add_file = $uim->fetch_template('files/userupload_add');
	}
	
	$fcm->generate_category_list($add_file, 'category', 'cats');
	
	// Get the agreements
	$agreements_result = $dbim->query('SELECT id, name, contents
										FROM '.DB_PREFIX.'agreements');
										
	while ($agreement = $dbim->fetch_array($agreements_result))
	{
		$add_file->assign_var('agreement', $agreement);
		$add_file->use_block('agreements');
	}
	
	// Custom fields
	$custom_query = $dbim->query('SELECT id, label, value
									FROM '.DB_PREFIX.'customfields');
	
	$rows = $dbim->num_rows($custom_query);  
	$add_file->assign_var('custom_field_total', $rows);  
	$id = 1;
	
	while ($custom_fields = $dbim->fetch_array($custom_query))
	{
		$custom_fields['uid'] = $id;
		$add_file->assign_var('custom_field', $custom_fields);
		$add_file->use_block('custom_fields');
		$id++;
	}
	
	// Show mirror entry forms
	$mirror_amount = $site_config['mirrors'];
	$current_mirror = 1;
	
	while ($current_mirror <= $mirror_amount)
	{
		$add_file->assign_var('mirror', $current_mirror);
		$add_file->use_block('mirror');
		
		$current_mirror++;
	}
	
	// Use FCKeditor or not?
	if (use_fckeditor())
	{
		$add_file->assign_var('use_fckeditor', true);
		
		// Module
		include_once ('FCKeditor/fckeditor.php');
		
		// Small description
		$fck_desc_small = new FCKeditor('description_small');
		$fck_desc_small->BasePath = $site_config['url'].'FCKeditor/';
		$fck_desc_small->ToolbarSet = 'od';
		$fck_desc_small->Width = '90%';
		$fck_desc_small->Height = '200';
		$desc_small_html = $fck_desc_small->CreateHtml();
		$add_file->assign_var('desc_small_html', $desc_small_html);
		
		// Big description
		$fck_desc_big = new FCKeditor('description_big');
		$fck_desc_big->BasePath = $site_config['url'].'FCKeditor/';
		$fck_desc_big->ToolbarSet = 'od';
		$fck_desc_big->Width = '90%';
		$fck_desc_big->Height = '200';
		$desc_big_html = $fck_desc_big->CreateHtml();
		$add_file->assign_var('desc_big_html', $desc_big_html);
	}
	else
	{
		$add_file->assign_var('use_fckeditor', false);
	}
	
	$add_file->show();
}
else
{
	$add_file = $uim->fetch_template('files/userupload_disabled');
	$add_file->show();
}

// End table
$end = $uim->fetch_template('global/end');
$end->show();

// Show everything
$uim->generate(TITLE_PREFIX.$lm->language('admin', 'add_file'));

?>