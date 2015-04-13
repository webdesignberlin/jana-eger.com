<h1>{lang:admin:admin_panel}</h1>

<p>{lang:admin:admin_panel_welcome}</p>

{if: !isset($up_to_date)}

<p> {$vcheck_style_top} {lang:admin:updates_available_desc_1} {$global_vars[version]} {lang:admin:updates_available_desc_2} ({$latest_version}) {lang:admin:updates_desc_312}<br />
{if: $check == 1}
 {lang:admin:v_desc_1} 
{elseif: $check == 2} 
	{lang:admin:v_desc_2} 
{elseif: $check == 3} 
	{lang:admin:v_desc_3} 
{elseif: $check == 4} 
	{lang:admin:v_desc_4} 
{elseif: $check == 5} 
	{lang:admin:v_desc_5} 
{endif}
<br />{lang:admin:updates_available_desc_3}
{$vcheck_style_end}
{endif}

<h2>{lang:admin:statistics}</h2>

<ul class="children">
	<li class="nolink">{lang:admin:total_files}: {$total_files}</li>
{if: $user_permissions[acp_files_approve_files]}
	<li><a href="admin.php?cmd=files_approve_files">{lang:admin:total_inactive_files}: {$total_inactive_files}</a></li>
{else}
	<li class="nolink">{lang:admin:total_inactive_files}: {$total_inactive_files}</li>
{endif}
	<li class="nolink">{lang:admin:total_downloads}: {$total_downloads}</li>
	<li><a href="admin.php?cmd=files_manage_comments">{lang:admin:total_comments}: {$total_comments}</a></li>	
	<li><a href="admin.php?cmd=files_approve_comments">{lang:admin:total_comments_pending}: {$pending_comments}</a></li>
	<li class="nolink">{lang:admin:total_users}: {$total_users}</li>
</ul>

<h2>{lang:admin:tech_support}</h2>

<p>{lang:admin:tech_support_desc}</p>
