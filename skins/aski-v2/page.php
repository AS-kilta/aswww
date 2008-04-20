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

	<div id="logo"><img src="<?= baseUrl() . "/skins/aski-v2/logo.png" ?>" /></div>

    <div id="page-container">
		<div id="toplinks-container">
			<?php print $external ?>
		</div>

        <!-- TopVavi -->
        <div id="nav-container">
			<?php print $topnavi ?>
		</div>

        <div id="blank"></div>

		<div id="content-top"></div>
		<div id="content">
			<div><img src="<?= baseUrl() . "/skins/aski-v2/photo.png" ?>"/></div>
			<div id="content-left">
				<div id="title">
					<?php if ($title) print $title ?>
                </div>
				<div id="content-text">
					<?php print $content ?>
				</div>
			</div>

            <!-- Menu -->
            <div id="menu-container">
                <?php print $left ?>
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
