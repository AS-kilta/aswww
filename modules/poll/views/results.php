<div class="poll">
<h1><?= $_['heading'] ?></h1>

<p><?= $poll->getQuestion() ?></p>
<div class="results">
    <?php
    $percentages = $poll->getPercentages();
    $votes = $poll->getVotes();
    foreach ($poll->getOptions() as $optionId => $option) {
    ?>
        <p><?= $option ?>: <?= $votes[$optionId] ?> (<?= $percentages[$optionId] ?>%)</p>
        <div class="bar" style="width: <?= $percentages[$optionId] ?>%"></div>
    <?php } ?>

    <p><?= $_['total_votes'] ?>: <?= $poll->getTotalVotes() ?></p>
</div>

<?php if ($editable) {
    echo '<p>' . $this->link('poll/edit?lang=' . getLanguage(), 'Create new poll') . "</p>\n";
} ?>

</div>
