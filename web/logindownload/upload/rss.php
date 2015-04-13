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

if ($site_config['enable_rss'])
{	
	validate_types($input, array('mode' => 'INT', 'cat' => 'INT', 'file' => 'INT'));
	
	// Doctype
	header('Content-Type: text/xml');
	?>
	<rss version="0.91">
		
		<channel>
		
		<title><?php echo TITLE_PREFIX; ?> RSS Feed</title>
		<link><?php echo $site_config['url']; ?></link>
	<?php	
	// 3 modes - display latest files, specific file stats or general stats
	if (empty($input['mode']) || $input['mode'] == 1)
	{
		if (!empty($input['cat']))
		{
			$category = $input['cat'];
		}
		else
		{
			$category = false;
		}
															
		// Get files data
		$latest_files = $fldm->get_files('date DESC', $category, $site_config['latest_files']);
										
		// Display
		foreach ($latest_files as $file)
		{
		?>
		
		<item>
		<title><?php echo htmlspecialchars($file['name']); ?></title>
		<description><?php echo htmlspecialchars($file['description_small']); ?></description>
		<link><?php echo $site_config['url'].'details.php?file='.$file['id']; ?></link>
		<downloads><?php echo $file['downloads']; ?></downloads>
		</item>
		
		<?php
		}
	}
	elseif ($input['mode'] == 2 && !empty($input['file']))
	{	
		$file = $fldm->get_details($input['file']);
	?>
		<item>
		<title><?php echo htmlspecialchars($file['name']); ?></title>
		<description><?php echo htmlspecialchars($file['description_small']); ?></description>
		<link><?php echo $site_config['url'].'details.php?file='.$file['id']; ?></link>
		<downloads><?php echo $file['downloads']; ?></downloads>
		</item>
	<?php
	}
	elseif ($input['mode'] == 3)
	{
		// Count files
		$count_result = $dbim->query('SELECT COUNT(*) AS files
										FROM '.DB_PREFIX.'files
										WHERE (status = 1)');	
		$count = $dbim->fetch_array($count_result);
		$files = $count['files'];
		
		// Count downloads
		$count_result = $dbim->query('SELECT COUNT(*) AS downloads
										FROM '.DB_PREFIX.'stats');	
		$count = $dbim->fetch_array($count_result);
		$downloads = $count['downloads'];
	?>	
		<item>
		<title><?php echo $lm->language('admin', 'total_files'); ?></title>
		<count><?php echo $files; ?></count>
		<link><?php echo $site_config['url']; ?></link>
		</item>
		<item>
		<title><?php echo $lm->language('admin', 'total_downloads'); ?></title>
		<count><?php echo $downloads; ?></count>
		<link><?php echo $site_config['url']; ?></link>
		</item>
	<?php
	}
	?>	
		</channel>		
	</rss>
<?php
}
else
{
	$lm->language('frontend', 'rss_disabled');
}
?>