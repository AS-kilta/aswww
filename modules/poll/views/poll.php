<div class="poll">
<h1><?= $_['heading'] ?></h1>

<?= $this->formStart() ?>

<p>
    <input type="radio" name="poll" value="1" />This<br />
    <input type="radio" name="poll" value="2" />is<br />
    <input type="radio" name="poll" value="3" />a<br />
    <input type="radio" name="poll" value="4" />placeholder
</p>

<input type='submit' name='vote' value='<?= $_['vote'] ?>' />

<?= $this->formEnd() ?>
</div>
