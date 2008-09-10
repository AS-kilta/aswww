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

    <table>
        <tr>
            <td><?= $_['edit_heading'] ?>:</td>
            <td><input type="text" width="100%" name="<?= $version->getLang() . '-heading' ?>" value="<?= $version->getHeading(); ?>" /></td>
        </tr>
        <tr>
            <td><?= $_['edit_time'] ?>:</td>
            <td><input type="text" width="100%" name="<?= $version->getLang() . '-time' ?>" value="<?= $version->getTime(); ?>" /></td>
            <td><?= $_['help_time'] ?></td>
        </tr>
        <tr>
            <td><?= $_['edit_timestamp'] ?>:</td>
            <td><input type="text" width="100%" name="<?= $version->getLang() . '-timestamp' ?>" value="<?= $version->getTimestamp(); ?>" /></td>
            <td><?= $_['help_timestamp'] ?></td>
        </tr>
        <tr>
            <td><?= $_['edit_place'] ?>:</td>
            <td><input type="text" width="100%" name="<?= $version->getLang() . '-place' ?>" value="<?= $version->getPlace(); ?>" /></td>
            <td></td>
        </tr>
    </table>

    <p><?= $_['edit_description'] ?>:</p>
    <textarea name="<?= $version->getLang() . '-description' ?>" rows="4" style="width:100%"><?= $version->getDescription() ?></textarea>
<?php } ?>

<p>
    <?= $this->link('events', 'Cancel') ?>
    <input type='submit' name='delete' value='Delete' onclick="return confirm('Are you sure you want to delete?')" />
    <input type='submit' name='save' value='Save' />
</p>

<?= $this->formEnd() ?>
