<h1><?= $heading ?></h1>

<?php if (isset($message)) { ?>
    <p><?= $message ?></p>
<?php }  ?>

<p>
<?php if (isset($back)) { ?>
    <a href="<?= baseUrl() . '/' . $back ?>">Back</a>
<?php }  ?>
<?php if (isset($continue)) { ?>
    <a href="<?= baseUrl() . '/' . $back ?>">Continue</a>
<?php }  ?>
</p>