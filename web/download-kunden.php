<?php require('inc/doc.php'); ?>
<title>Jana Eger Portfolio</title>
<?php require('inc/css.php'); ?>
<meta name="robots" content="noindex,nofollow">
</head>

<body id="upload-jana">
<?php require('inc/header.php'); ?>
<div class="container contentMain">
	<div class="colLeft">
    	<h2 class="blue">Download</h2>
		<p>Verzeichnis Kunden</p>
    </div><!--.colLeft-->
    <div class="colRight">
   <ul>
<?php
$alledateien = scandir('upload/kunden'); //Ordner "files" auslesen
foreach ($alledateien as $datei) { // Ausgabeschleife
echo $datei."<br />"; //Ausgabe Einzeldatei
};
?>
</ul>
    </div><!--.colRight--><br clear="all" />
</div><!--.container-->
<div id="footer">
	<div class="container">
		<ul id="navAdd">
            <li class="sn01"><a href="impressum.php" title="Impressum und Datenschutz Jana Eger">Impressum</a> |</li>
            <li class="sn02"><a href="kontakt.php" title="Kontakt Jana Eger">Kontakt</a> |</li>
            <li class="langDe"><a href="index.php" title="Deutsche Version Jana Eger">DEU</a> |</li>
            <li class="langEn"><a href="start.php" title="Englische Version Jana Eger">ENG</a> |</li>
            <li class="sn03"><a href="#" title="Login" class="info"><span class="hidden">Login</span></a> |</li>
            <li class="sn04"><a href="http://seiler-gerstmann.de" title="Webdesign Berlin">seiler-gerstmann.de</a></li>
        </ul><!--#navAdd-->
	</div><!--.container-->
</div><!--#footer-->
<!-- Piwik -->
<script type="text/javascript">
var pkBaseURL = (("https:" == document.location.protocol) ? "https://info.webdesign-portfolio.de/" : "http://info.webdesign-portfolio.de/");
document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
</script><script type="text/javascript">
try {
var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 4);
piwikTracker.trackPageView();
piwikTracker.enableLinkTracking();
} catch( err ) {}
</script><noscript><p><img src="http://info.webdesign-portfolio.de/piwik.php?idsite=4" style="border:0" alt="" /></p></noscript>
<!-- End Piwik Tag -->
</body>
</html>
