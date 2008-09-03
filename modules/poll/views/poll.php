<div class="poll">
<h1><?= $_['heading'] ?></h1>

<?php if ($poll) { ?>
    <?= $this->formStart('vote') ?>
    
    <p>
    <?= $poll->getQuestion() ?><br />
    <?php
    $i = 0;
    foreach ($poll->getOptions() as $option) { ?>
        <input type="radio" name="poll" value="<?= $i ?>" /><?= $option ?><br />
    <?php
    }
    $i++;
    ?>
    </p>
    
    <input type='submit' name='vote' value='<?= $_['vote'] ?>' />
    
    <?= $this->formEnd() ?>
<?php } ?>

</div>

<?php if ($editable) {
    echo '<p>' . $this->link('poll/edit', 'Create new') . "</p>\n";
} ?>
