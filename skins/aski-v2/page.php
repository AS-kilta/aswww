<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />

    <?php print $head ?>
    <title><?= $_['title'] ?></title>

    <?php print $styles ?>
    <?php print $scripts ?>
    <link rel="stylesheet" type="text/css" href="<?= baseUrl() . "/skins/aski-v2/tyylit.css" ?>" />
</head>

<body>
<div id="bg-gfx">

    <div id="logo">
        <a href="<?= baseUrl() ?>"><img alt="Automaatio- ja systeemitekniikan kilta" src="<?= baseUrl() . $_['logoUrl'] ?>" /></a>
    </div>

    <div id="toplinks">
        <?php print $external ?>
        <ul>
            <li><a href="http://aski.hut.fi/gallery/"><?= $_['Gallery'] ?></a></li>
            <li><a href="http://aski.hut.fi/askicam/">AskiCam</a></li>
            <li><a href="http://aski.hut.fi/gt">Kultainen Tomaatti</a></li>
            <li><a href="http://aski.hut.fi/AskiWiki">Wiki</a></li>
            <li><a href="http://www.tenttiarkisto.fi/"><?= $_['Exam_archive'] ?></a></li>
        </ul>
    </div>
    <div class="blank"></div>
    <div id="page-container">
        <!-- TopVavi -->
        <div id="nav-container">
		<?php print $topnavi ?>
	</div>

        <div class="blank"></div>

	<div id="content-top"></div>
	<div id="content">
		<div><?php print $topbanner ?></div>
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
		<div class="blank"></div>

		<!-- Footer -->
		<div id="footer">
			<span class="footer-text"><?= $_['footerText'] ?></span>
        </div>
        </div>

        <div id="content-bottom"></div>
	</div>
</div>

</body>
</html>
