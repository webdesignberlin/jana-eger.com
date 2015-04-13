<?php

/**********************************
* Olate Download 3.4.1
* http://www.olate.co.uk/od3
**********************************
* Copyright Olate Ltd 2005
*
* @author $Author: dsalisbury $ (Olate Ltd)
* @version $Revision: 262 $
* @package od
*
* Updated: $Date: 2006-10-10 20:51:48 +0100 (Tue, 10 Oct 2006) $
*/


// Initialisation
require('./includes/init.php');

// Meow
$fcm->show_cats();

// Start sessions
session_start();
$_SESSION['valid_user'] = true;

if (isset($input['file']))
{
	validate_types($input, array('file' => 'INT', 'cmd' => 'STR', 'password' => 'STR', 'name' => 'STR', 'email' => 'STR', 'comment' => 'STR', 'rating' => 'INT', 'page' => 'INT'));
	
	// Get file details
	$details = $fldm->get_details($input['file'], false);
	
	// Meta data
	if (!empty($details['description_small']))
	{
		$uim->add_meta_data('description', strip_tags($details['description_small']));
	}
	if (!empty($details['keywords']))
	{
		$uim->add_meta_data('keywords', $details['keywords']);
	}
	
	// *ding dong* let me in please
	if (isset($input['password']))
	{	
		if ($details['password'] == md5($input['password']))
		{
			$_SESSION[$input['file'].'_auth'] = true;
		}
	}
	
	if (!empty($details) && !empty($input['file']) && intval($details['activate_at']) <= time())
	{
		// Increment file view count
		$details['views'] = $details['views'] + 1;	
		$dbim->query('UPDATE '.DB_PREFIX.'files
						SET views = '.$details['views'].'
						WHERE (id = '.$input['file'].')');
		
		if (empty($details['password']) || isset($_SESSION[$input['file'].'_auth']))
		{
			// Get template
			$details_files = $uim->fetch_template('files/file');
					
			// Add comment
			if ($input['cmd'] == 'addcomment')
			{
				// Check all fields are filled out
				if (!empty($input['name']) && !empty($input['comment']))
				{					
					$status = $site_config['approve_comments'] ? 0 : 1;
					
					$dbim->query('INSERT INTO '.DB_PREFIX.'comments
									SET file_id = '.$input['file'].', 
										timestamp = '.time().', 
										name = "'.$input['name'].'", 
										email = "'.$input['email'].'", 
										comment = "'.$input['comment'].'", 
										status = '.$status.'');
					
					// Get success message ready
					$success = 'Comments';
					
					// What response shall I display?
					$success_template = $uim->fetch_template('files/comments_success');
					
					if ($site_config['approve_comments'] == 1)
					{
						$success_template->assign_var('response', $lm->language('frontend', 'comment_approval'));
					}
					else
					{
						$success_template->assign_var('response', $lm->language('frontend', 'comment_added'));
					}
				}
				else
				{
					// Get error ready
					$error = 'Comments';
					$error_message = $lm->language('frontend', 'fill_out_fields');
				}
			}
			
			// Rate file
			if ($input['cmd'] == 'rate')
			{
				if (!empty($input['rating']) && !isset($_SESSION['file_rating_'.$input['file']]))
				{						
					$new_count = ($details['rating_votes'] + 1);
					
					// Calculate new rating
					$file_rating = ($details['rating_value'] * $details['rating_votes']);
					$new_rating = (($input['rating'] + $file_rating) / ($new_count));
					$new_rating_formatted = number_format($new_rating, 2, '.', '');
					
					// Update
					$dbim->query('UPDATE '.DB_PREFIX.'files
									SET rating_votes = '.$new_count.', 
										rating_value = "'.$new_rating_formatted.'"
									WHERE (id = '.$input['file'].')');
								
					$_SESSION['file_rating_'.$input['file']] = true;
					
					// Get the success message ready
					$success = 'Rating';
					$success_template = $uim->fetch_template('files/rate_success');
					
					// Update the $file var so the changes are immediately apparent
					$details['rating_votes'] = $new_count;
					$details['rating_value'] = $new_rating_formatted;
				}
				else
				{
					if (empty($input['rating']))
					{
						$error_message = $lm->language('frontend', 'rating_enter');
					}
					elseif (isset($_SESSION['file_rating_'.$input['file']]))
					{
						$error_message = $lm->language('frontend', 'rating_once');
					}
					
					// Get the error ready
					$error = 'Rating';
				}
			}
			
			// Formatting
			$details['date'] = format_date($details['date']);
			
			// Are we converting new lines to <br />?
			if (intval($details['convert_newlines']) === 1)
			{
				$details['description_small'] = nl2br($details['description_small']);
				$details['description_big'] = nl2br($details['description_big']);
			}
			
			// Assigning template variables
			$details_files->assign_var('description_big', nl2br($details['description_big']));
			
			$filesize = $fldm->format_size($details['size']);
			
			$details['size'] = $filesize['size'];
			
			$details_files->assign_var('filesize_format', $filesize['unit']);			
			$details_files->assign_var('file', $details);
			$details_files->use_block('file');
			
			// Custom fields
			$custom_query = $dbim->query('SELECT cf.label AS label, cfd.value AS value
											FROM '.DB_PREFIX.'customfields_data AS cfd,
												'.DB_PREFIX.'customfields AS cf
											WHERE (cfd.file_id = '.$input['file'].') 
												AND	(cfd.field_id = cf.id)');
			
			$i = 0;
			while ($custom_fields_data = $dbim->fetch_array($custom_query))
			{
				$custom_fields[$i.'_label'] = $custom_fields_data['label'];
				$custom_fields[$i.'_value'] = $custom_fields_data['value'];
				$i++;
				
				// Assigning template variables
				$details_files->assign_var('custom_field_label', $custom_fields_data['label']);
				$details_files->assign_var('custom_field_value', $custom_fields_data['value']);
				$details_files->use_block('custom_fields');
			}
			
			$details_files->assign_var('custom_fields', $custom_fields);		
			
			// Meta data?
			
			
			// Show template
			$details_files->show();
			
			// Success message if they've just rated a file
			if (isset($success) && $success == 'Rating')
			{
				$success_template->show();
			}
			
			// Show the error message if it's a rating error
			if (isset($error) && $error == 'Rating')
			{
				$rating_error = $uim->fetch_template('global/error');
				$rating_error->assign_var('error_message', $error_message);
				$rating_error->show();
			}
				
			// Success message
			if (isset($success) && $success == 'Comments')
			{
				$success_template->show();
			}
			
			// Check for valid page
			if ($input['page'] < 1)
			{
				$input['page'] = 1;
			}
			
			// If there was an error when adding a comment
			// display the currently filled out fields
			if (isset($error) && $error == 'Comments')
			{
				// Show the error
				$comments_error = $uim->fetch_template('global/error');
				$comments_error->assign_var('error_message', $error_message);
				$comments_error->show();
				
				// Show toolbox with submitted data
				$fldm->display_toolbox($input['file'], $input, $input['page']);
			}
			else
			{
				// Show toolbox
				$fldm->display_toolbox($input['file'], false, $input['page']);
			}
		}
		else
		{
			// Get template
			$details_files = $uim->fetch_template('files/protected');
			$details_files->assign_var('file_id', $input['file']);
			
			// Show template
			$details_files->show();	
		}
	}
	elseif (!empty($details) && !empty($input['file']) && $details['activate_at'] > time())
	{
		// Get template
		$details_files = $uim->fetch_template('files/file');
		
		$details_files->assign_var('empty', $lm->language('frontend', 'file_not_active'));
		$details['name'] = $lm->language('frontend', 'none');
		
		// Show template
		$details_files->show();
	}
	else
	{
		// Get template
		$details_files = $uim->fetch_template('files/file');
		
		$details_files->assign_var('empty', $lm->language('frontend', 'no_files'));
		
		// Show template
		$details_files->show();
	}	
}
else
{
	// Get template
	$details_files = $uim->fetch_template('files/file');
	
	$details_files->assign_var('empty', $lm->language('frontend', 'no_files'));
	$details['name'] = $lm->language('frontend', 'none');
	
	// Show template
	$details_files->show();
}

// End table
$end = $uim->fetch_template('global/end');
$end->show();

// Show everything
$uim->generate(TITLE_PREFIX.$lm->language('frontend', 'viewing').' '.$details['name']);
?>