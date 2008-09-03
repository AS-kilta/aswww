<div class="poll">
<h1><?= $_['heading'] ?></h1>
<?= $this->formStart() ?>

<p><input type="text" name="question" value="<?= $poll->getQuestion() ?>" /></p>

<table>
<?php
$i = 0;
foreach ($poll->getOptions() as $option) { ?>
    <tr>
        <td><input type="text" name="option[<?= $i ?>]" value="<?= $option ?>" />
        <?=  ?></td>
    </tr>
<?php $i++; } ?>
</table>

<input type='submit' name='save' value='Save' />

<?= $this->formEnd() ?>
</div>

