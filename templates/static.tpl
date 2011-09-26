<!doctype html>
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
	<title>The Event Day &bull; {$title}</title>
	{include "content/head.tpl"}
</head>

<body>
	<div class="container">
			{include "content/header.tpl"}
			<section id="main">
				<div id="content">
				{include "static/{$page}.tpl"}
				</div>
			</section>
			{include "content/footer.tpl"}
	</div>
	<!--[if lt IE 7 ]>
	<script src="js/libs/dd_belatedpng.js"></script>
	<script> DD_belatedPNG.fix("img, .png_bg");</script>
	<![endif]-->
</body>
</html>