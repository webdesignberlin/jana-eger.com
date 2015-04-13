<?php
/*****************************************
* Olate Download
* http://www.centrixonline.com/products/od
******************************************
* Copyright Centrix Information Systems 2007
*
* @author $Author: gburnes $ (Olate Ltd)
* @version $Revision: 259 $
* @package od
*
* Updated: $Date: 2007-20-08 20:27:12 +0100 (Tue, 10 Oct 2006) $
*/

function plain_encrypt( $data_array, $is_array=false, $key=false)
{	
	$key = ! key ? $site_config['secure_key'] : $key;
	
	$char = '';
	$keychar = '';
	$char = '';
	$serialized_data = '';
	
		
	
	if( $is_array == TRUE )
	{
		$data_array = serialize( $data_array );
	}
	
		
	for($i=1; $i<=strlen($data_array); $i++)
	{
		$char 				 = substr($data_array, $i-1, 1);
		$keychar 			 = substr($key, ($i % strlen($key))-1, 1);
		$char 				 = chr(ord($char)+ord($keychar));
		$serialized_data	.= $char;
	}
	
	
	
	$serialized_data = base64_encode(base64_encode(trim( $serialized_data ) ) );
	
	return $serialized_data;


}
function plain_decrypt( $data_array , $is_array=false, $key)
{
	$key = ! key ? $site_config['secure_key'] : $key;
	
	$char = '';
	$keychar = '';
	$char = '';
	$serialized_data = '';
	
	$data_array = base64_decode(base64_decode(trim( $data_array ) ) );
	
			
	for($i=1; $i<=strlen($data_array); $i++)
	{
		$char 				 = substr($data_array, $i-1, 1);
		$keychar 			 = substr($key, ($i % strlen($key))-1, 1);
		$char 				 = chr(ord($char)-ord($keychar));
		$serialized_data	.= $char;
	}
	
	if( $is_array == TRUE )
	{
		$serialized_data = unserialize( $serialized_data );
	}
	
	return $serialized_data;

}

function executive_service( &$array, $iteration = 0 )
{
	if( $iteration >= 10 )
	{
		return $array;
	}
	
	foreach( $array as $k => $v )
	{
		if( is_array($v) )
		{
			executive_service($v, $iteration++);
		}
		else
		{
			$v = preg_replace( '/\\\0/'	, '', $v );
			$v = preg_replace( '/\\x00/', '', $v );
			$v = str_replace( '%00'		, '', $v );
			
			$v = str_replace( '../'		, '&#45;&#46;/', $v );
			
			$array[$k] = $v;
		}
	}
}

function dishwasher( &$array, $input = array(), $i = 0 )
{
	if( $i >= 10 )
	{
		return $input;
	}
	
	foreach( $array as $k => $v )
	{
		if( is_array( $v ) )
		{
			$input[ $k ] = dishwasher($array[$k], array(), $i++ );
		}
		else
		{
			$k = clean_evil_key( $k );
			$v = clean_the_evil( $v );
			
			$input[$k] = $v;
		}
	}
	
	return $input;
}

function sanitizer()
{
	@set_magic_quotes_runtime(0);
	
	$things_we_need = array('GET', 'POST', 'COOKIE', 'REQUEST');
	foreach( $things_we_need as $g )
	{
		executive_service($GLOBALS['_'.$g] );
	}
	
	$input = dishwasher($_GET);
	
	$input = dishwasher($_POST, $input );
			
	return $input;
}

function clean_evil_key($evil)
{
	if ($evil == "")
	{
		return "";
	}

	$evil = htmlspecialchars(urldecode($evil));
	$evil = preg_replace( "/\.\./"           , ""  , $evil );
	$evil = preg_replace( "/^([\w\.\-\_]+)$/", "$1", $evil );
	$evil = preg_replace( "/\_\_(.+?)\_\_/"  , ""  , $evil );

	return $evil;
}

function clean_the_evil( $buggers )
{
	$buggers = preg_replace( "/javascript/i" , "j&#097;v&#097; script", $buggers );
	$buggers = preg_replace( "/document\./i" , "&#100;ocument."      , $buggers );
	$buggers = preg_replace( "/alert/i"      , "&#097;lert"          , $buggers );
	$buggers = preg_replace( "/about:/i"     , "&#097;bout:"         , $buggers );
	$buggers = preg_replace( "/onmouseover/i", "&#111;nmouseover"    , $buggers );
	$buggers = preg_replace( "/onclick/i"    , "&#111;nclick"        , $buggers );
	$buggers = preg_replace( "/onsubmit/i"   , "&#111;nsubmit"       , $buggers );
	$buggers = preg_replace( "/onload/i"     , "&#111;nload"         , $buggers );
	$buggers = preg_replace( "/<html/i"      , "&lt;html"            , $buggers );
	$buggers = preg_replace( "/\%/i"		 , "&#37;"				 , $buggers );
	//----------------------------------------------------------------------------
	// Query Safety      
	//----------------------------------------------------------------------------
	$buggers = preg_replace( "/crack/i"		 , ""					 , $buggers );
	$buggers = preg_replace( "/exec/i"		 , ""					 , $buggers );
	$buggers = preg_replace( "/<body/i"      , "&lt;body"            , $buggers );
	$buggers = preg_replace( "/usertable/i"	 , ""					 , $buggers );
	$buggers = preg_replace( "/master/i"	 , ""					 , $buggers	);
	$buggers = preg_replace( "/xp_cmdshell/i", "" 					 , $buggers );
	$buggers = preg_replace( "/cmd_shell/i"	 , ""					 , $buggers );
	

	return $buggers;
}

/*-------------------------------------------------------------------------*/
// Clean Evil value
/*-------------------------------------------------------------------------*/

function clean_evil_values($ugh)
{
	
	if ($ugh == "")
	{
		return "";
	}

	$ugh = str_replace( "&#032;", " ", $ugh );

	$ugh = str_replace( "&"            , "&amp;"         , $ugh );
	$ugh = str_replace( "<!--"         , "&#60;&#33;--"  , $ugh );
	$ugh = str_replace( "-->"          , "--&#62;"       , $ugh );
	$ugh = preg_replace( "/<script/i"  , "&#60;script"   , $ugh );
	$ugh = str_replace( ">"            , "&gt;"          , $ugh );
	$ugh = str_replace( "<"            , "&lt;"          , $ugh );
	$ugh = str_replace( "\""           , "&quot;"        , $ugh );
	$ugh = preg_replace( "/\n/"        , "<br />"        , $ugh ); 
	$ugh = preg_replace( "/\\\$/"      , "&#036;"        , $ugh );
	$ugh = preg_replace( "/\r/"        , ""              , $ugh );
	$ugh = str_replace( "!"            , "&#33;"         , $ugh );
	$ugh = str_replace( "'"            , "&#39;"         , $ugh ); 
	$ugh = preg_replace( "/\\\(?!&amp;#|\?#)/", "&#092;", $ugh );

	return $ugh;
}

?>