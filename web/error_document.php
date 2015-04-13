<?php header("HTTP/1.0 404 Not Found");
function links($link,$Txt){
	if(@file_exists($link)){
		echo '<li><a href="'.$link.'">'.$Txt.'</a></li>';
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Server Verarbeitungsfehler</title>
<style type="text/css">
<!--
body{background:#fff;font-family:Tahoma, Geneva, sans-serif;font-size:76%;color:#333;line-height:160%}
a{color:#000099;text-decoration:underline;}
a:hover{text-decoration:none;}
-->
</style>
</head>

<body>

<h1>Server Verarbeitungsfehler</h1>
<p>Aufgrund eines Verarbeitungsfehlers kann Ihnen die folgende Seite leider nicht angezeigt werden.<br />
Bitte vergewissern Sie sich, dass die von Ihnen aufgerufene URL keine Tippfehler enthält.<br />
Sollte das Problem weiterhin bestehen, versuchen Sie doch einmal folgendes:</p>

<p>Öffnen Sie unsere <a href="/">Startseite</a> und navigieren Sie von dort aus zu den gewünschten Inhalten.</p>
<ul>
	<?php
	links('index.php','Startseite');
	links('suche.php','Suche');
	links('search.php','Suche');
	links('kontakt.php','Kontakt');
	links('anfahrt.php','Anfahrt');
	links('sitemap.php','Sitemap');
	links('impressum.php','Impressum');
	?>
</ul>
<hr />
<p><?=$_SERVER['SERVER_SOFTWARE'];?></p>
</body>
</html>
