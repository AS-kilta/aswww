<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="fi">
<head>
    <title><?= $heading ?></title>
    <!--link rel='stylesheet' type='text/css' media="print" href='skins/ilmo/print.css' /-->
    <link rel='stylesheet' type='text/css' media="screen" href='skins/ilmo/tyyli.css' />
</head>
<body>


<div id="sivu">
    <div id="otsikko">
        <h1><?= $heading ?></h1>
    </div>

    <div id="menu">
        <?php echo $menu; ?>
    </div>

    <div id="content">
        <?php echo $content; ?>
    </div>
</div>

</body>
</html>
