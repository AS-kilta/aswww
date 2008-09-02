<?= $this->formStart() ?>

<?php if ($warning) { ?>
    <p class="warning"><?= $warning ?></p>
<?php } ?>

<?php if ($success) { ?>
    <p class="success"><?= $success ?></p>
<?php } ?>

<input type='hidden' name='id' value='<?= $id ?>' />

<?php foreach ($versions as $version) { ?>
    <h1><?= strtoupper($version->getLang()) ?></h1>

    <p>Heading: <input type="text" width="100%" name="<?= $version->getLang() . '-heading' ?>" value="<?= $version->getHeading(); ?>" /></p>
    <textarea name="<?= $version->getLang() . '-content' ?>" rows="4" style="width:100%"><?= $version->getContent() ?></textarea>
<?php } ?>

<p>
    <?= $this->link('news/list', 'Cancel') ?>
    <input type='submit' name='delete' value='Delete' onClick="return confirm('Are you sure you want to delete?')" />
    <input type='submit' name='save' value='Save' />
</p>

<?= $this->formEnd() ?>
