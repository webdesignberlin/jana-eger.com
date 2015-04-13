<?php require('inc/doc.php'); ?>
<meta http-equiv="description" content="imprint &amp; terms of use - jana eger" />
<title>imprint &amp; terms of use - Jana Eger Berlin</title>
<?php require('inc/css.php'); ?>
<script type="text/javascript" src="js/bg.js"></script>
<script type="text/javascript">
	$(document).ready(function() {

		// E-Mail Adresse anzeigen
		$('a.emailval').each(function() {
			e = this.rel.replace('/5$§§','@');
			this.href = 'mailto:' + e;
			$(this).text(e);
		});
		 $("a.info").click(function(event){
     alert("Folgt demnächst. Bei Fragen bitte die Email im Impressum nutzen!");
   });
	});
</script>
</head>

<body id="impressum">
<?php require('inc/header-en.php'); ?>
<div class="container contentMain">
	<div class="colLeft">
        <h2>RESPONSIBLE FOR CONTENT</h2>
          <p>Jana Eger<br />
          Warschauer Straße 46<br />
          D-10243 Berlin<br />
          +49(0)1 76 23 57 84 48<br />
        <a class="emailval" rel="contact/5$§§jana-eger.com" href="#"></a></p>
        <h2>DESIGN │ CONCEPT</h2>
          <p>Jana Eger</p>
       <h2>CODING</h2>
           <p><a href="http://seiler-gerstmann.de" title="Webdesign Berlin" class="unLink">Michael Gerstmann</a><br />
          <a href="http://webdesign-portfolio.de" title="Webdesign Homepage Berlin" class="unLink">webdesign-portfolio.de</a></p>
  </div><!--.colLeft-->
    <div class="colRight">
    	<h2>TERMS OF USE (translation coming soon)</h2>
   	    <p>Quellcode, Inhalte und Design dieser Website unterliegen urheberrechtlichem Schutz. Jede vom Urheberrechtsgesetz nicht zugelassene Verwertung bedarf der vorherigen schriftlichen Zustimmung. Dies gilt insbesondere für die kommerzielle Vervielfältigung, Verbreitung, Weitergabe, Bearbeitung, Übersetzung, Einspeicherung, Verarbeitung bzw. Wiedergabe der Veröffentlichungen sowie sonstiger Inhalte in Datenbanken oder andere elektronische Medien und Systemen. Fotokopien und Downloads von dieser Website dürfen auch für den persönlichen, privaten Gebrauch nicht ohne schriftliche Genehmigung hergestellt werden. Haftungsausschluss und weitere Nutzungshinweise.</p>
    	<p>Auf dieser Website finden Sie Informationen allgemeiner Art. Die Inhalte dieser Website werden mit größtmöglicher Sorgfalt recherchiert. Gleichwohl übernehme ich keine Haftung für die Richtigkeit, Vollständigkeit und Aktualität der bereit gestellten Informationen. Für Schäden, die wegen etwaiger Unrichtigkeiten, Unvollständigkeit oder mangelnder Aktualität meiner Website Nutzern entstehen, übernehme ich keine Haftung.</p>
    </div><!--.colRight--><br clear="all" />
</div><!--.container-->
<?php require('inc/footer-en.php'); ?>