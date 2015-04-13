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
<script type="text/javascript">
			jQuery(document).ready(function($) {
				// We only want these styles applied when javascript is enabled
				$('div.navigation').css({'width' : '170px', 'float' : 'left'});
				$('div.content').css('display', 'block');

				// Initially set opacity on thumbs and add
				// additional styling for hover effect on thumbs
				var onMouseOutOpacity = 1.0;
				$('#thumbs ul.thumbs li').opacityrollover({
					mouseOutOpacity:   onMouseOutOpacity,
					mouseOverOpacity:  1.0,

					fadeSpeed:         'slow',
					exemptionSelector: '.selected'
				});
				
				// Initialize Advanced Galleriffic Gallery
				var gallery = $('#thumbs').galleriffic({
					delay:                     2500,
					numThumbs:                 28,
					preloadAhead:              10,
					enableTopPager:            false,
					enableBottomPager:         true,
					maxPagesToShow:            7,
					imageContainerSel:         '#slideshow',
					controlsContainerSel:      '#controls',
					captionContainerSel:       '#caption',
					loadingContainerSel:       '#loading',
					renderSSControls:          true,
					renderNavControls:         true,
					playLinkText:              'Play Slideshow',
					pauseLinkText:             'Pause Slideshow',
					prevLinkText:              '&lsaquo;',
					nextLinkText:              '&rsaquo;',
					nextPageLinkText:          'Next &rsaquo;',
					prevPageLinkText:          '&lsaquo; Prev',
					enableHistory:             false,
					autoStart:                 false,
					syncTransitions:           false,
					defaultTransitionDuration: 1000,
					onSlideChange:             function(prevIndex, nextIndex) {
						// 'this' refers to the gallery, which is an extension of $('#thumbs')
						this.find('ul.thumbs').children()
							.eq(prevIndex).fadeTo('fast', onMouseOutOpacity).end()
							.eq(nextIndex).fadeTo('fast', 1.0);
					},
					onPageTransitionOut:       function(callback) {
						this.fadeTo('fast', 0.0, callback);
					},
					onPageTransitionIn:        function() {
						this.fadeTo('fast', 1.0);
					}
				});
			});
		</script>
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
