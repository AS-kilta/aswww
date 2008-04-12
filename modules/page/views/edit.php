<?= $this->formStart('edit') ?>

<h1>Edit page</h1>

<textarea name="content" rows="20" style="width:100%">
<?= $editableContent ?>
</textarea>

<p>
    <input type='submit' name='save' value='Save' />
    <input type='submit' name='preview' value='Preview' />
</p>

<?= $this->formEnd() ?>


<?= $htmlContent ?>
