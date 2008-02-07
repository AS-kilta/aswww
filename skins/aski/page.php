<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="fi">
<head>
    <?php print $head ?>
    <title><?php print $head_title ?></title>

    <?php print $styles ?>
    <?php print $scripts ?>

    <link rel="stylesheet" type="text/css" href=<?= baseUrl() . "/skins/aski/style/misc.css" ?> />
    <link rel="stylesheet" type="text/css" href=<?= baseUrl() . "/skins/aski/style/sidebar.css" ?> />
    <link rel="stylesheet" type="text/css" href=<?= baseUrl() . "/skins/aski/style/nav.css" ?> />
    <link rel="stylesheet" type="text/css" href=<?= baseUrl() . "/skins/aski/style/main.css" ?> />
    <link rel="stylesheet" type="text/css" href=<?= baseUrl() . "/skins/aski/style/header.css" ?> />
    <link rel="stylesheet" type="text/css" href=<?= baseUrl() . "/skins/aski/style/footer.css" ?> />

    <!--[if IE]>
        <link type="text/css" rel="stylesheet" href="<?php //print base_path() . path_to_theme() ?>/style/IE_common.css">
        <![if lt IE 7]>
            <link type="text/css" rel="stylesheet" href="<?php //print base_path() . path_to_theme() ?>/style/IE_no_alpha.css">
            <style type="text/css" media="all">@import "<?php //print base_path() . path_to_theme() ?>/fix-ie.css";</style>
        <![endif]>
    <![endif]-->

</head>
<body>
<div class="bg">&nbsp;
<div id="wrap">
    <div id="header">
    <div class="gears"><img alt="Automaatio- ja systeemitekniikan kilta" src="<?= baseUrl() . '/skins/aski/logo.png' ?>"></div>
    <div class="photo"><img alt="Kiltahenke&auml;" src=<?= baseUrl() . "/skins/aski/valokuva.jpg" ?>></div>

    <!-- External links -->
    <?php print $external ?>

    <span class="title">Automaatio- ja systeemitekniikan kilta ry</span>-
    </div>

    <div id="middle">

    <div id="nav">
        <!-- Topnavi -->
        <?php print $topnavi ?>
    </div>

    <div class="contentbg">
    <div id="sidebar"><!-- Vasen -->
        <?php print $left ?>
    </div>

    <div id="main">
        <?php if ($title) print '<h1>'. $title .'</h1>'; ?>
        <div class="tabs"><?php print $tabs ?></div>
        <?php print $content ?>
    </div>

    <div id="clearer"> </div>
    </div><!--contentbg-->
    </div><!--middle-->


    <div id="footer">
        <p><?php print $footer_message ?></p>
    </div>

</div>

</div>

<?php print $closure ?>
</body>
</html>
