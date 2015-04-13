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

// Begin template
$template = $uim->fetch_template('admin/languages');

if (!empty($input['deactivate_languages']))
{
	if (is_array($input['deactivate']) && sizeof($input['deactivate']) > 0)
	{
		$count_active_languages = sizeof($lm->list_languages(true));
		
		if (sizeof($input['deactivate']) < $count_active_languages)
		{
			foreach ($input['deactivate'] as $lang_id => $value)
			{
				$lang_id = intval($lang_id);
				
				$dbim->query('DELETE FROM '.DB_PREFIX.'languages
								WHERE id = '.$lang_id);
			}
		}
		else 
		{
			$message = $lm->language('admin', 'one_active_lang');
		}
	}
}
elseif (!empty($input['activate_languages']))
{
	// Various checks on incoming request
	if (is_array($input['activate']) && sizeof($input['activate']) > 0)
	{
		foreach ($input['activate'] as $file => $value)
		{
			// Used many times, defined once
			$file_path = 'languages/'.$file;
			
			// Does the file exist?
			if (file_exists($file_path) && !is_dir($file_path) && strpos($file, '.php') !== false)
			{
				// Check file isn't already in DB
				$search = $dbim->query('SELECT COUNT(id) AS count 
										FROM '.DB_PREFIX.'languages
										WHERE filename = "'.$file.'"');
				
				$search_row = $dbim->fetch_array($search);
				
				if ($search_row['count'] == 0)
				{
					// Load file
					require($file_path);
					
					// Info needed for DB query
					$language_info = array(
						'name' => $language['config']['full_name'],
						'filename' => $file,
						'version' => explode('.', $language['config']['version'])
					);
					
					// Not needed any more, so save a bit of memory
					unset($language);
					
					// Insert into database
					$dbim->query('INSERT INTO '.DB_PREFIX.'languages
									SET name = "'.$language_info['name'].'",
										filename = "'.$language_info['filename'].'",
										version_major = "'.$language_info['version'][0].'",
										version_minor = "'.$language_info['version'][1].'"');
				}
				else 
				{
					$message = $lm->language('admin', 'lang_exists');
				}
			}
		}
		
		if (empty($message))
		{
			$message = $lm->language('admin', '');
		}
	}
}
elseif (!empty($input['make_default']))
{
	if (sizeof($input['make_default']) > 0)
	{
		// Get language
		$lang_id = array_shift(array_keys($input['make_default']));
		$lang_id = intval($lang_id);
		
		// Check it actually exists
		$search = $dbim->query('SELECT COUNT(id) AS count 
								FROM '.DB_PREFIX.'languages
								WHERE id = '.$lang_id);
		
		$search_row = $dbim->fetch_array($search);
		
		if (intval($search_row['count']) > 0)
		{
			// Set no defaults
			$dbim->query('UPDATE '.DB_PREFIX.'languages
							SET site_default = 0');
			
			// Set new language as default
			$dbim->query('UPDATE '.DB_PREFIX.'languages
							SET site_default = 1
							WHERE id = '.$lang_id);
			
			$message = $lm->language('admin', 'make_default_done');
			$success = true;
		}
	}
}

// Any message to display?
if (!empty($message))
{
	$template->assign_var('message', $message);
}

// Success?
if (!empty($success))
{
	$template->assign_var('success', $success);
}

if (empty($success) || $success !== true)
{
	// Get languages from database
	$active_languages = $lm->list_languages(true);
	
	// Initialise various variables
	$active_filenames = array();
	$inactive_count = 0;
	$old_count = 0;
	
	foreach ($active_languages as $language)
	{
		// Add to list of active filenames so that they can be ignored later on
		$active_filenames[] = $language['filename'];
		
		// Pass to template and use block
		$template->assign_var('active_lang', $language);
		$template->use_block('active_lang');
	}
	
	// Get languages from languages/ folder
	$dir_handle = dir('languages/');
	
	// Read directory
	while (false !== ($file = $dir_handle->read()))
	{
		// File path variable - saves duplicating code
		$file_path = 'languages/'.$file;
		
		if (!is_dir($file_path) && strpos($file, '.php') !== false && !in_array($file, $active_filenames))
		{
			// Load language file
			require($file_path);
			
			// Build array to pass to template
			$language_info = array(
				'language' => $language['config']['full_name'],
				'filename' => $file,
				'version' => $language['config']['version']
			);
			
			$lang_version = explode('.', $language['config']['version']);
			
			// Unload language
			unset($language);
			
			// Check version major/minor against site version
			$site_version = explode('.', $site_config['version']);
			
			if ($site_version[0] == $lang_version[0] && $site_version[1] == $lang_version[1])
			{
				// Use inactive template block
				$template->assign_var('inactive_lang', $language_info);
				$template->use_block('inactive_lang');
				
				// Increment
				$inactive_count++;
			}
			else 
			{
				// Use old language block
				$template->assign_var('old_lang', $language_info);
				$template->use_block('old_lang');
				
				// Increment
				$old_count++;
			}
		}
	}
	
	$template->assign_var('inactive_count', $inactive_count);
	$template->assign_var('old_count', $old_count);
}

$template->show();

$end = $uim->fetch_template('global/end');
$end->show();

if (!isset($success) || !$success)
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'main').' - '.$lm->language('admin', 'language_settings'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'main').' - '.$lm->language('admin', 'language_settings'), 'admin.php?cmd=languages');
}

?>