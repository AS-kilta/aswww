<div class="poll">
<h1><?= $_['heading'] ?></h1>

<?= $this->formStart('vote') ?>

<p>
  <?= $poll->getQuestion() ?><br />
  <?php
  $i = 0;
  $options = $poll->getOptions();
  $percentages = $poll->getPercentages();
  for ($i = 0; $i < count($options); $i++) { ?>
    <?= $options[$i] ?>
    <?= $percentages[$i] ?><br />
  <?php } ?>
</p>

<input type='submit' name='vote' value='<?= $_['vote'] ?>' />

<?= $this->formEnd() ?>
</div>
