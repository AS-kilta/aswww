<h1>Select skin</h1>

<?= $this->formStart('skinSelector') ?>

<p class="warning">Warning: this takes place everywhere immediately.</p>

<p>
<select name="skin">
    <?php foreach ($skins as $skin) { ?>
        <option value="<?= $skin ?>"><?= $skin ?></option>
    <?php } ?>
</select>
</p>

<p><input type='submit' name='ok' value='OK' /></p>

<?= $this->formEnd() ?>
