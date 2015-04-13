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

// Initialisation
require('./includes/init.php');

// Amount of search results per page
$amount = $site_config['page_amount'];

// Show categories
$fcm->show_cats();

// Start sessions
session_start();
$_SESSION['valid_user'] = true;

validate_types($input, array('query' => 'STR'));

if ($site_config['enable_search'])
{
	// Get template
	$search_template = $uim->fetch_template('search/search');
	
	// Get all results for the page box
	$search_result = $dbim->query('SELECT id, name, description_small, description_big, date
								FROM '.DB_PREFIX.'files 
								WHERE MATCH (name, description_small, description_big) 
										AGAINST ("'.$input['query'].'" IN BOOLEAN MODE)
											AND (category_id != 0)
												AND (status != 0)');
	$results = array();
	
	while ($result = $dbim->fetch_array($search_result))
	{
		$results[] = $result;
	}
	
	// Checks
	if (isset($input['query']) && !empty($input['query']))
	{
		// Has a page been given
		$page = (isset($input['page'])) ? $input['page'] : 1;
		
		// Get result
		$search_result = $dbim->query('SELECT id, name, description_small, description_big, date
										FROM '.DB_PREFIX.'files 
										WHERE MATCH (name, description_small, description_big) 
												AGAINST ("'.$input['query'].'" IN BOOLEAN MODE)
													AND (category_id != 0)
														AND (status != 0)
										LIMIT '.($page - 1) * $amount .','.$amount);
		
		// Display
		while ($result = $dbim->fetch_array($search_result))
		{
			$search_template->assign_var('result', $result);
			$search_template->assign_var('date', format_date($result['date']));
			$search_template->use_block('search');
		}
		
		$submitted = true;
		
		$search_template->assign_var('query', stripslashes($input['query']));
		$search_template->assign_var('num_results', $dbim->num_rows($search_result));
		$search_template->assign_var('submitted', $submitted);
	}
	
	if (isset($submitted))
	{
		// Show pagebox
		$pagination = $fldm->make_page_box($results,'search.php?query='.$input['query'].'&amp;', $amount);
		$search_template->assign_var('pagination', $pagination);
	}
	
	// Show template
	$search_template->show();
	
	// End
	$search_end = $uim->fetch_template('search/search_end');
	$search_end->show();
	
	$end = $uim->fetch_template('global/end');
	$end->show();
	
	// Show everything
	$uim->generate(TITLE_PREFIX.'Search');
}
else
{
	// Get template
	$search_template = $uim->fetch_template('search/search_disabled');
	
	// Show template
	$search_template->show();
	
	// End table
	$end = $uim->fetch_template('global/end');
	$end->show();
	
	// Show everything
	$uim->generate(TITLE_PREFIX.$lm->language('frontend', 'search'));
}
?>