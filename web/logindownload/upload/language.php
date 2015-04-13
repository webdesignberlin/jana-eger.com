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

// Inititalisation
include_once('includes/init.php');

// Categories/left bar
$fcm->show_cats();

validate_types($input, array('language' => 'STR'));

if ($site_config['allow_user_lang'])
{
	// Template
	$template = $uim->fetch_template('general/set_language');
	
	if (!empty($input['language']))
	{
		// Check language exists in database
		$search = $dbim->query('SELECT COUNT(*) AS count
								FROM '.DB_PREFIX.'languages
								WHERE id = '.intval($input['language']));
		
		$row = $dbim->fetch_array($search);
		
		if ($row['count'] > 0)
		{
			setcookie('OD3_language', intval($input['language']), time() + 60*60*24*365);
			$success = true;
			$template->assign_var('success', true);
		}
	}
	else
	{
		// Fetch language list
		$languages = $lm->list_languages();
		
		foreach ($languages as $language)
		{
			$template->assign_var('language', $language);
			$template->use_block('languages');
		}
		
		// Get current language
		$current = array(
			'name' => $lm->language['config']['full_name'],
			'id' => $lm->language_row['id']
		);
		
		$template->assign_var('current', $current);
	}
	
	// Show template
	$template->show();
}
else 
{
	$template = $uim->fetch_template('general/set_language_disabled');
	$template->show();
}

// End the page and generate
$end = $uim->fetch_template('global/end');
$end->show();

if (!isset($success) || $success != true)
{
	$uim->generate(TITLE_PREFIX . $lm->language('frontend', 'change_language'));
}
else 
{
	$uim->generate(TITLE_PREFIX . $lm->language('frontend', 'change_language'), 'language.php');
}

?>