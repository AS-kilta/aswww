<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <?php print $head ?>
    <title><?php print $head_title ?></title>

    <?php print $styles ?>
    <?php print $scripts ?>
    <link rel="stylesheet" type="text/css" href="<?= baseUrl() . "/skins/aski-v2/tyylit.css" ?>" />
</head>

<body>
<div id="bg-gfx">

    <div id="logo">
        <a href="<?= baseUrl() ?>"><img alt="Logo" src="<?= baseUrl() . "/skins/aski-v2/logo.png" ?>" /></a>
    </div>

    <div id="toplinks">
        <?php print $external ?>
        <ul>
            <li><a href="http://aski.hut.fi/askicam">AskiCam</a></li>
            <li><a href="http://aski.hut.fi/AskiWiki">Wiki</a></li>
            <li><a href="http://www.tenttiarkisto.fi/">Tenttiarkisto</a></li>
        </ul>
    </div>
    <div id="blank"></div>
    <div id="page-container">
        <!-- TopVavi -->
        <div id="nav-container">
		<?php print $topnavi ?>
	</div>

        <div id="blank"></div>

	<div id="content-top"></div>
	<div id="content">
		<div><img src="<?= baseUrl() . "/skins/aski-v2/photo.png" ?>"/></div>
		<div id="content-center">
			<div id="border"></div>
            		<div id="text-container">
                		<?php if ($title) print $title ?>
                		<?php print $content ?>
            		</div>
            		<!-- Menu -->
            		<div id="menu-container">
		                <?php print $left ?>
			</div>
		</div>
		<div id="blank"></div>

		<!-- Footer -->
		<div id="footer">
			<span class="footer-text"><?php print $footer_message ?></span>
            	</div>
        </div>

        <div id="content-bottom"></div>
	</div>
</div>

</body>
</html>
