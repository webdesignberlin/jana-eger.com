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

// Start sessions
session_start();

// 1. Check isset($input'file']) then get details
if (isset($input['file']))
{	
	validate_types($input, array('file' => 'INT', 'go' => 'INT', 'mirror' => 'INT'));
	
	// Get file details
	$details = $fldm->get_details($input['file']);
	
	if (empty($details['password']) || isset($_SESSION[$input['file'].'_auth']))
	{	
		// 2. If page called !isset($input['go']) and there is an agreement, display
		if (!isset($input['go']) && $details['id'] != 0 && !empty($details['agreement_id']))
		{
			// Get the agreement
			$agreement = $fldm->get_agreement($details['agreement_id']);		
			$agreement_template = $uim->fetch_template('files/agreement');		
			$agreement_template->assign_vars(array('agreement' => $agreement,
													'file_id' => $details['id']));								
			$agreement_template->show();
		}
		// 3. If !isset($input['go']) || $input['go'] == 1 check !empty($details) and get mirror data
		elseif (!isset($input['go']) || $input['go'] == 1)
		{	
			if (!empty($details))
			{	
				// Get mirrors
				$mirrors_result = $dbim->query('SELECT id, file_id, name, location, url
												FROM '.DB_PREFIX.'mirrors
												WHERE (file_id = '.$input['file'].')');
				
				// 4. If $dbim->num_rows($mirrors_result) == 1 redirect to get it
				if ($dbim->num_rows($mirrors_result) == 1)
				{				
					$mirror = $dbim->fetch_array($mirrors_result);
					header('Location: download.php?go=2&file='.$input['file'].'&mirror='.$mirror['id']); 
				}
				// 5. If $dbim->num_rows($mirrors_result) > 1 get and display list
				elseif ($dbim->num_rows($mirrors_result) > 1)
				{
					// Fetch and display
					$mirrors_template = $uim->fetch_template('files/mirrors');
					
					while ($mirror = $dbim->fetch_array($mirrors_result))
					{
						$mirrors_template->assign_var('file_id', $input['file']);
						$mirrors_template->assign_var('mirror', $mirror);
						$mirrors_template->use_block('mirror');
					}
					
					$mirrors_template->show();
				}
				else
				{
					$error_message = $lm->language('frontend', 'error_no_file');
					$error = $uim->fetch_template('global/error');
					$error->assign_var('error_message', $error_message);
					$error->show();
				}
			}
			else
			{
				$error_message = $lm->language('frontend', 'error_no_id');
				$error = $uim->fetch_template('global/error');
				$error->assign_var('error_message', $error_message);
				$error->show();
			}
		}
		// 6. If $input['go'] == 2 and isset($input['mirror']), validate id, increment download count then redirect to URL
		elseif ($input['go'] == 2 && isset($input['mirror']))
		{
			// Get referring domain
			if (empty($_SERVER['HTTP_REFERER']))
			{
				$referer_domain = false;
			}
			else
			{
				$referer = parse_url($_SERVER['HTTP_REFERER']);
				$referer_domain = $referer['host'];
			}
			
			if (($referer_domain !== false && !$sm->domain_can_leech($referer_domain) && empty($_SESSION['valid_user'])) || ($site_config['enable_leech_protection'] == 1 && empty($_SESSION['valid_user'])))
			{
				header('Location: details.php?file='.$input['file']);
				exit;
			}
			
			// Incrememnt and update
			$details['downloads']++;
			$update = $dbim->query('UPDATE '.DB_PREFIX.'files 
									SET downloads = '.$details['downloads'].'
									WHERE (id = '.$input['file'].')');
										
			if ($site_config['enable_stats'])
			{
				$dbim->query('INSERT INTO '.DB_PREFIX.'stats 
								SET file_id = '.$input['file'].', 
									timestamp = "'.time().'", 
									ip = "'.$_SERVER['REMOTE_ADDR'].'", 
									referrer = "'.$_SERVER['HTTP_REFERRER'].'", 
									user_agent = "'.$_SERVER['HTTP_USER_AGENT'].'"');
			}
																	
			// Get URL
			$mirrors_result = $dbim->query('SELECT id, url
											FROM '.DB_PREFIX.'mirrors
											WHERE (id = '.$input['mirror'].')');
												
			$mirror = $dbim->fetch_array($mirrors_result);
				
			if ($dbim->num_rows($mirrors_result) == 0)
			{
				$error_message = $lm->language('frontend', 'error_no_file');
				$error = $uim->fetch_template('global/error');
				$error->assign_var('error_message', $error_message);
				$error->show();
			}
			else
			{
				// Go
				header('Location: '.$mirror['url']);
			}
		}
	}
	else
	{
		// Get template
		$protection = $uim->fetch_template('files/protected');
		$protection->assign_var('file_id', $input['file']);
			
		// Show template
		$protection->show();	
	}
}
else
{
	$error_message = $lm->language('frontend', 'error_no_id');
	$error = $uim->fetch_template('global/error');
	$error->assign_var('error_message', $error_message);
	$error->show();
}

// End table
$end = $uim->fetch_template('global/end');
$end->show();

// Show everything
$uim->generate(TITLE_PREFIX.$lm->language('frontend', 'download').' '.$details['name']);
?>