<h1>{lang:admin:updates}</h1>

{if: !isset($error)}
{if: isset($up_to_date)}
<p>{lang:admin:updates_latest}</p>
{else}
<p>{lang:admin:updates_not_latest}</p>
{endif}
{if: ! $up_to_date}
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
	{$vcheck_style_end}</p>
{else}
	{lang:admin:updates_current} {$global_vars[version]}<br />
	{lang:admin:updates_new} {$latest_version}<br />
{endif}
<p>{lang:admin:updates_download}</p>
{else}
<p>{$error}</p>
{endif}