<!-- Lyhyt lista -->
<div class="events">
  <h1><?= $_['heading'] ?></h1>

  <?php foreach ($events as $event) { ?>
    <h2><?= $event->getTime() ?> <?= $event->getHeading() ?></h2>
    <p>
        <?= $event->getPlace() ?>
        <?php if ($editable) {
            echo "<br />\n";
            echo $this->link('events/edit?id=' . $event->getId(), 'Edit');
        } ?>
    </p>
  <?php } ?>

    <?= $this->link($_['list_url'], $_['list_all']); ?>
</div>

<?php
if ($editable) {
    echo $this->link('events/edit', 'Create new');
}
?>
