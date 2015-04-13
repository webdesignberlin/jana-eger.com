<?php require('inc/doc.php'); ?>
<meta http-equiv="description" name="description" content="Kontakt Jana Eger. Fotografin und Illustratorin aus Berlin." />
<title>Jana Eger Kontakt</title>
<?php require('inc/css.php'); ?>
<script type="text/javascript" src="jquery.form.js"></script>
<script type="text/javascript">
$(document).ready(function() { 
var options = { 
target:        '#alert',
}; 
$('#contactForm').ajaxForm(options); 
}); 

$.fn.clearForm = function() {
  return this.each(function() {
	var type = this.type, tag = this.tagName.toLowerCase();
	if (tag == 'form')
	  return $(':input',this).clearForm();
	if (type == 'text' || type == 'password' || tag == 'textarea')
	  this.value = '';
	else if (type == 'checkbox' || type == 'radio')
	  this.checked = false;
	else if (tag == 'select')
	  this.selectedIndex = -1;
  });
};

</script>
</head>

<body id="kontakt">
<?php require('inc/header.php'); ?>
<div class="container contentMain">
	<div class="colLeft">
    	<h2 class="blue">Kontakt</h2>
		<p>von Jana Eger</p>
        <div class="message"><div id="alert"></div></div>
    </div><!--.colLeft-->
    <div class="colRight">
            <div id="wrapper">
<div class="contact">
<form action="sendmail.php" method="post" id="contactForm">
<ul>
<li>
<label for="name">Name:</label>
<input type="text" name="name" value="" id="name" />
</li>
<li>
<label for="email">Email:</label>
<input type="text" name="email" value="" id="email" />
</li>
<li>
<label for="tele">Telefon:</label>
<input type="text" name="tele" value="" id="tele" />
</li>
<li class="special">
<label for="last">Don't fill this in:</label>
<input type="text" name="last" value="" id="last" />
</li>
<li>
<label for="message">Nachricht:</label><textarea rows="5" name="message"></textarea>
</li>
<li class="submitbutton">
<input type="submit" value="Send Message" />
</li>
</ul>
</form>
</div>

</div>

        </div>
    </div><!--.colRight--><br clear="all" />
</div><!--.container-->
<?php require('inc/footer.php'); ?>