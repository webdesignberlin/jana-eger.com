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

if ($uam->permitted('acp_main_settings'))
{		
	// Template
	$settings = $uim->fetch_template('admin/main_settings');
	
	// Check existance of FCKeditor
	if (file_exists('FCKeditor/fckeditor.php'))
	{
		$settings->assign_var('wysiwyg_disabled', true);
	}
	else
	{
		$settings->assign_var('wysiwyg_disabled', false);
	}
	
	// Make any changes
	if (isset($input['submit']))
	{
		validate_types($input, array('site_name' => 'STR_HTML', 'url' => 'STR', 'admin_email' => 'STR', 'flood_interval' => 'INT', 'language' => 'STR', 
										'template' => 'STR', 'date_format' => 'STR', 'filesize_format' => 'STR', 'mirrors' => 'INT', 
										'page_amount' => 'INT', 'latest_files' => 'INT', 'enable_topfiles' => 'INT', 'top_files' => 'INT', 'enable_allfiles' => 'INT', 
										'enable_comments' => 'INT', 'approve_comments' => 'INT', 'enable_search' => 'INT', 'enable_ratings' => 'INT', 'enable_stats' => 'INT', 
										'enable_rss' => 'INT', 'enable_count' => 'INT', 'enable_useruploads' => 'INT', 'enable_leech_protection' => 'INT', 'uploads_allowed_ext' => 'STR',
										'filter_cats' => 'INT', 'enable_recommend_friend' => 'INT', 'enable_recommend_confirm' => 'INT', 'acp_check_extensions' => 'INT', 'allow_user_lang' => 'INT', 'secure_key' => 'STR'));
		// Checkboxes are soooo annoying
		$input['enable_topfiles'] = (!isset($input['enable_topfiles'])) ? 0 : 1;
		$input['enable_allfiles'] = (!isset($input['enable_allfiles'])) ? 0 : 1;
		$input['enable_comments'] = (!isset($input['enable_comments'])) ? 0 : 1;
		$input['approve_comments'] = (!isset($input['approve_comments'])) ? 0 : 1;
		$input['enable_search'] = (!isset($input['enable_search'])) ? 0 : 1;
		$input['enable_stats'] = (!isset($input['enable_stats'])) ? 0 : 1;
		$input['enable_rss'] = (!isset($input['enable_rss'])) ? 0 : 1;
		$input['enable_count'] = (!isset($input['enable_count'])) ? 0 : 1;
		$input['enable_useruploads'] = (!isset($input['enable_useruploads'])) ? 0 : 1;
		$input['enable_actual_upload'] = (!isset($input['enable_actual_upload'])) ? 0 : 1;
		$input['enable_mirrors'] = (!isset($input['enable_mirrors'])) ? 0 : 1;
		$input['enable_leech_protection'] = (!isset($input['enable_leech_protection'])) ? 0 : 1;
		$input['userupload_always_approve'] = (!isset($input['userupload_always_approve'])) ? 0 : 1;
		$input['filter_cats'] = (!isset($input['filter_cats'])) ? 0 : 1;
		$input['enable_recommend_friend'] = (!isset($input['enable_recommend_friend'])) ? 0 : 1;
		$input['enable_recommend_confirm'] = (!isset($input['enable_recommend_confirm'])) ? 0 : 1;
		$input['acp_check_extensions'] = (!isset($input['acp_check_extensions'])) ? 0 : 1;
		$input['use_fckeditor'] = (!isset($input['use_fckeditor'])) ? 0 : 1;
		$input['allow_user_lang'] = (!isset($input['allow_user_lang'])) ? 0 : 1;
		$secure_key = 'secure_key  = "'.$input['secure_key'].'"';
		
		// And finally, the SQL
		$dbim->query('UPDATE '.DB_PREFIX.'config 
						SET site_name = "'.htmlspecialchars($input['site_name']).'", 
							url = "'.$input['url'].'",
							flood_interval = "'.$input['flood_interval'].'", 
							admin_email = "'.$input['admin_email'].'", 
							language = "'.$input['language'].'", 
							template = "'.$input['template'].'", 
							date_format = "'.$input['date_format'].'", 
							filesize_format = "'.$input['filesize_format'].'", 
							mirrors = "'.$input['mirrors'].'", 
							page_amount = "'.$input['page_amount'].'", 
							latest_files = "'.$input['latest_files'].'",
							enable_topfiles = "'.$input['enable_topfiles'].'",  
							top_files = "'.$input['top_files'].'", 
							enable_allfiles = "'.$input['enable_allfiles'].'", 
							enable_comments = "'.$input['enable_comments'].'", 
							approve_comments = "'.$input['approve_comments'].'", 
							enable_search = "'.$input['enable_search'].'", 
							enable_ratings = "'.$input['enable_ratings'].'",
							enable_stats = "'.$input['enable_stats'].'", 
							enable_rss = "'.$input['enable_rss'].'",
							enable_count = "'.$input['enable_count'].'",
							enable_useruploads  = "'.$input['enable_useruploads'].'",
							enable_actual_upload  = "'.$input['enable_actual_upload'].'",
							enable_mirrors  = "'.$input['enable_mirrors'].'",
							enable_leech_protection  = "'.$input['enable_leech_protection'].'",
							uploads_allowed_ext  = "'.$input['uploads_allowed_ext'].'",
							userupload_always_approve  = "'.$input['userupload_always_approve'].'",
							filter_cats  = "'.$input['filter_cats'].'",
							enable_recommend_friend  = "'.$input['enable_recommend_friend'].'",
							enable_recommend_confirm  = "'.$input['enable_recommend_confirm'].'",
							acp_check_extensions  = "'.$input['acp_check_extensions'].'",
							use_fckeditor  = "'.$input['use_fckeditor'].'",
							allow_user_lang  = "'.$input['allow_user_lang'].'",
							'.$secure_key.'
							
						LIMIT 1');
							
		$success = true; // For redirect EOF
		$settings->assign_var('success', true);
		
		// Get the new values							
		foreach ($input as $key => $value)
		{
			$settings->assign_var($key, $value);
		}
	}
	elseif (isset($input['stats_reset']) && $input['stats_reset'] == 1)
	{
		// Reset statistics
		$dbim->query('TRUNCATE TABLE '.DB_PREFIX.'stats');
		$dbim->query('UPDATE '.DB_PREFIX.'files
						SET downloads = 0,
							views = 0');
		
		$success = true; // For redirect EOF
		$settings->assign_var('reset', true);
	}
	else
	{
		// Get the values obtained in init.php							
		foreach ($site_config as $key => $value)
		{
			if( $key != 'secure_key')
			{
				$settings->assign_var($key, $value);
			}
			else
			{
				if( $uam->permitted('acp_edit_secure_key') )
				{
					$settings->assign_var($key, $value);
				}
			}
			
		}
		
		// Get path
		$path = 'templates/';  
		
		// Using the opendir function
		$dir_handle = opendir($path); 
		
		// Running the while loop
		while ($file = readdir($dir_handle)) 
		{
			// . and .. are displayed so remove them
			if (($file != '.') && ($file != '..') && ($file != 'CVS') && ($file != '.svn'))
			{
				// Get the language config.php file so data can be displayed
				$settings->assign_var('template_name', $file);
				$settings->use_block('templates');
			}
		} 
		
		// Close directory
		closedir($dir_handle);
	}
	
	$settings->show();
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
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'main').' - '.$lm->language('admin', 'general_settings'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'main').' - '.$lm->language('admin', 'general_settings'), 'admin.php?cmd=main_settings');
}
?>