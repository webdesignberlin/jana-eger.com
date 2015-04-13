<?php
/*****************************************
* Olate Download
* http://www.centrixonline.com/products/od
******************************************
* Copyright Centrix Information Systems 2007
*
* @author $Author: dsalisbury $ (Olate Ltd)
* @version $Revision: 259 $
* @package od
*
* Updated: $Date: 2006-10-10 20:27:12 +0100 (Tue, 10 Oct 2006) $
*/

// Language Module
class lm
{
	// Declare variables
	var $languagename;
	var $language;
	var $language_row;
	
	// Constructor - get current language from database
	function lm()
	{
		// Need to use DBIM & $site_config
		global $dbim, $site_config;
		
		$this->load_language();
	}
	
	// Return current language
	function current_language()
	{
		return $this->languagename;
	}
	
	// Return language data
	function get_config($name)
	{
		// Return array
		return $this->language['config'][$name];
	}
	
	// Return text for $language['text']['section']['$name']
	function language($section, $name)
	{
		return $this->language['text'][$section][$name];
	}
	
	// Fetches array of available languages
	function list_languages($version_check = false)
	{
		global $site_config, $dbim;
		
		// Split up OD version
		$site_version = explode('.', $site_config['version']);
		
		// Are we only returning up-to-date languages for this version of OD?
		if ($version_check === true)
		{
			$version_check_sql = 'WHERE version_major = "'.$site_version[0].'" AND version_minor = "'.$site_version[1].'"';
		}
		else 
		{
			$version_check_sql = '';
		}
		
		// Query database
		$language_sql = 'SELECT * 
							FROM '.DB_PREFIX.'languages
							'.$version_check_sql.'
							ORDER BY site_default DESC, name ASC';
		
		$language_result = $dbim->query($language_sql);
		
		// Initialise variables
		$languages = array();
		$deleted_default = false;
		
		while ($language = $dbim->fetch_array($language_result))
		{
			// No point returning a language which doesn't actually exist
			if (file_exists('languages/'.$language['filename']))
			{
				// Add language to array of language
				$languages[] = $language;
			}
			else
			{
				$dbim->query('DELETE FROM '.DB_PREFIX.'languages
								WHERE id = '.$language['id']);
				
				if ((bool)$language['site_default'])
				{
					$deleted_default = true;
				}
			}
		}
		
		if ($deleted_default)
		{
			// Get first element of $languages array
			reset($languages);
			$current = current($languages);
			$key = key($languages);
			
			// Update database to set this language to be default
			$dbim->query('UPDATE '.DB_PREFIX.'languages
							SET site_default = 1
							WHERE id = '.$current['id']);
			
			$languages[$key]['site_default'] = 1;
		}
		
		return $languages;
	}
	
	function load_language()
	{
		global $dbim, $site_config;
		
		$site_version = explode('.', $site_config['version']);
		
		$use_default = true;
		
		// Get current language
		if (!empty($input['OD3_language']))
		{
			validate_types($input, array('OD3_language', 'INT'));
			
			// Check cookie language is valid
			$result = $dbim->query('SELECT *
									FROM '.DB_PREFIX.'languages
									WHERE (id = '.$input['OD3_language'].') 
											AND (version_major = "'.$site_version[0].'") 
											AND (version_minor = "'.$site_version[1].'")');
			
			if ($dbim->num_rows($result) > 0)
			{
				// It is, so fetch row
				$row = $dbim->fetch_array($result);
				
				// Language file exists?
				if (file_exists('languages/'.$row['filename']))
				{
					// Load it in
					include('languages/'.$row['filename']);
					$this->languagename = $language['config']['full_name'];
					$this->language = $language;
					$this->language_row = $language_row;
					
					$use_default = false;
				}
				else 
				{
					// No file, so fall back on default
					$use_default = true;
				}
			}
			else 
			{
				// No such language in DB, so fall back on default
				$use_default = true;
			}
		}
		
		// Default language
		if ($use_default !== false)
		{
			$language_res = $dbim->query('SELECT *
											FROM '.DB_PREFIX.'languages
											WHERE 
												(site_default = 1) 
												AND (version_major = "'.$site_version[0].'") 
												AND (version_minor = "'.$site_version[1].'")
											LIMIT 1');
			
			if ($dbim->num_rows($language_res) > 0)
			{
				// Language exists
				$language_row = $dbim->fetch_array($language_res);
			}
			else 
			{
				// No default language, fall back on any up-to-date language
				$language_res = $dbim->query('SELECT *
												FROM '.DB_PREFIX.'languages
												WHERE
													(version_major = "'.$site_version[0].'") 
													AND (version_minor = "'.$site_version[1].'")
												LIMIT 1');
				
				if ($dbim->num_rows($language_res) > 0)
				{
					// Success, we have a language
					$language_row = $dbim->fetch_array($language_res);
				}
				else 
				{
					// No languages at all for this version!!
					trigger_error('[LM] No up to date languages are available to be used', FATAL);
				}
			}
			
			if (file_exists('languages/'.$language_row['filename']))
			{
				include('languages/'.$language_row['filename']);
				$this->languagename = $language['config']['full_name'];
				$this->language = $language;
				$this->language_row = $language_row;
			}
			else 
			{
				trigger_error('[LM] There are no languages available to be used', FATAL);
			}
		}
		
		unset($language);
	}
}
?>