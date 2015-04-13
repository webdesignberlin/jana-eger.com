<?php require('inc/doc.php'); ?>
<title>Jana Eger Portfolio</title>
<meta name="robots" content="noindex,nofollow">
<link href="css/styles.css" rel="stylesheet" type="text/css" media="all" />
   <link href="/uploadify/uploadify.css" type="text/css" rel="stylesheet" />
   <script type="text/javascript" src="/uploadify/jquery-1.4.2.min.js"></script>
   <script type="text/javascript" src="/uploadify/swfobject.js"></script>
   <script type="text/javascript" src="/uploadify/jquery.uploadify.v2.1.4.min.js"></script>
   <script type="text/javascript">
    $(document).ready(function() {
      $('#kunden_upload').uploadify({
        'uploader'  : '/uploadify/uploadify.swf',
        'script'    : '/uploadify/uploadify.php',
        'cancelImg' : '/uploadify/cancel.png',
        'folder'    : '/upload/kunden/',
		'multi'    : true,
        'auto'      : true
      });
	  $('#privat_upload').uploadify({
        'uploader'  : '/uploadify/uploadify.swf',
        'script'    : '/uploadify/uploadify.php',
        'cancelImg' : '/uploadify/cancel.png',
        'folder'    : '/upload/privat/',
		'multi'    : true,
        'auto'      : true
      });
	  $('#share_upload').uploadify({
        'uploader'  : '/uploadify/uploadify.swf',
        'script'    : '/uploadify/uploadify.php',
        'cancelImg' : '/uploadify/cancel.png',
        'folder'    : '/upload/share/',
		'multi'    : true,
        'auto'      : true
      });
    });
   </script>
</head>

<body id="upload-jana">
<?php require('inc/header.php'); ?>
<div class="container contentMain">
	<div class="colLeft">
    	<h2 class="blue">Upload</h2>
		<p>Verzeichnis Kunden</p>
         <ul>
<?php
$ordner = "upload/kunden"; //auch Pfade möglich ($ordner = "download/files";)
$alledateien = scandir($ordner);
 
foreach ($alledateien as $datei) {
 
    // Zusammentragen der Dateiinfo
    $dateiinfo = pathinfo($ordner."/".$datei);
    //Folgende Variablen stehen nach pathinfo zur Verfügung
    // $dateiinfo['filename'] =Dateiname ohne Dateiendung  *erst mit PHP 5.2
    // $dateiinfo['dirname'] = Verzeichnisname
    // $dateiinfo['extension'] = Dateityp -/endung
    // $dateiinfo['basename'] = voller Dateiname mit Dateiendung
 
    // Größe ermitteln zur Ausgabe
    $size = ceil(filesize($ordner."/".$datei)/1024);
    //1024 = kb | 1048576 = MB | 1073741824 = GB
 
        // verhindert Anzeige von "." und ".."
    if ($datei != "." && $datei != "..") {
    ?>
    <li><a href="<?php echo $dateiinfo['dirname']."/".$dateiinfo['basename'];?>"><?php echo $dateiinfo['filename']; ?></a> (<?php echo $dateiinfo['extension']; ?> | <?php echo $size ; ?>kb)</li>
    <li><a href=""><?php echo $dateiinfo['dirname']."/".$dateiinfo['basename']; ?> löschen</a></li>
    <li>
    <?php echo $_FILES['Filedata']['tmp_name']; ?>vb
    </li>
<?php
    };
 };
?>
</ul>
    </div><!--.colLeft-->
    <div class="colRight">
    <input id="kunden_upload" name="kunden_upload" type="file" />
    <p><a href="javascript:$('#kunden_upload').uploadifyUpload();">Bei Startproblemen hier starten</a></p>  
    </div><!--.colRight--><br clear="all" /><hr />
    
    <div class="colLeft">
    	<h2 class="blue">Upload</h2>
		<p>Verzeichnis Privat</p>
    </div><!--.colLeft-->
    <div class="colRight">
    <input id="privat_upload" name="privat_upload" type="file" />
    <a href="javascript:$('#privat_upload').uploadifyUpload();">Bei Startproblemen hier starten</a>
    </div><!--.colRight--><br clear="all" /><hr />
    
    <div class="colLeft">
    	<h2 class="blue">Upload</h2>
		<p>Verzeichnis Share</p>
    </div><!--.colLeft-->
    <div class="colRight">
    <input id="share_upload" name="share_upload" type="file" />
    <a href="javascript:$('#share_upload').uploadifyUpload();">Bei Startproblemen hier starten</a>
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
