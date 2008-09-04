<?php if ($warning) { ?>
    <p class="warning"><?= $warning ?></p>
<?php } ?>

<?php if ($success) { ?>
    <p class="success"><?= $success ?></p>
<?php } ?>


<h1><?= $_['heading'] ?></h1>

<?= $this->formStart() ?>
    <input type='hidden' name='id' value='<?= $poll->getId() ?>' />
    <input type='hidden' name='lang' value='<?= $poll->getLang() ?>' />

    <p>Question:</p>
    <p><input type="text" name="question" value="<?= $poll->getQuestion() ?>" /></p>

    <p>Options:</p>

    <table>
    <?php
    $i = 0;
    foreach ($poll->getOptions() as $option) { ?>
        <tr>
            <td><input type="text" name="option-<?= $i++ ?>" value="<?= $option ?>" /></td>
        </tr>
    <?php } ?>
    </table>

    <?php if (is_numeric($poll->getId())) { ?>
        <input type='submit' name='delete' value='Delete' />
    <?php } ?>

    <input type='submit' name='save' value='Save' />

<?= $this->formEnd() ?>
