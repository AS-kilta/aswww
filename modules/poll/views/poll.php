<?php if ($poll) { ?>
    <div class="poll">
    <h1><?= $_['heading'] ?></h1>

    <?= $this->formStart('vote') ?>

    <p>
    <?= $poll->getQuestion() ?><br />
    <?php foreach ($poll->getOptions() as $optionId => $option) { ?>
        <input type="radio" name="poll" value="<?= $optionId ?>" /><?= $option ?><br />
    <?php } ?>
    </p>

    <input type='submit' name='vote' value='<?= $_['vote'] ?>' />

    <?= $this->formEnd() ?>

    <?php if ($editable) {
        echo '<p>' . $this->link('poll/edit?id=' . $poll->getId() . '&lang=' . $poll->getLang(), 'Edit') . "</p>\n";
    } ?>
    </div>
<?php } ?>

<?php if ($editable) {
    echo '<p>' . $this->link('poll/edit?lang=' . getLanguage(), 'Create new poll') . "</p>\n";
} ?>
