<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <?php print $head ?>
    <title><?php print $head_title ?></title>

    <?php print $styles ?>
    <?php print $scripts ?>
    
<link href="tyylit.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="bg-gfx">&nbsp;
	<div id="logo"><img src="logo.png"/></div>
	<div id="page-container">
		<div id="toplinks-container">
			<?php print $external ?>
		</div>
		<div id="nav-container">
			<div id="nav-left"></div>
				<?php print $topnavi ?>
			<div id="nav-right"></div>
		</div>
		<div id="content-top"></div>
		<div id="content">
			<div><img src="photo.png"/></div>
			<div id="content-left">
				<div id="title">
					<?php if ($title) print $title ?>
                </div>
				<div id="content-text">
					<?php print $content ?>
				</div>
			</div>
			<div id="menu-container">
					<?php print $left ?>
				</div> 
			<div id="blank"></div>
			<div id="footer">
				<div class="absolute footer-left"></div>
				<div class="absolute footer-right"></div>
				<span class="footer-text"><?php print $footer_message ?></span>			
			</div> 
		</div>
		<div id="content-bottom"></div>
	</div>
</div>
</body>
</html>
