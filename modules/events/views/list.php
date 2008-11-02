<!-- Pitka lista -->
<div class="events-long">
    <h1><?= $_['heading'] ?></h1>

    <?php foreach ($events as $event) { ?>
        <h2><?= $event->getTime() ?> <?= $event->getHeading() ?></h2>
        <p class="place"><?= $event->getPlace() ?></p>
        <p class="description"><?= $event->getDescription() ?></p>

        <?php if ($editable) {
            echo '<p>' . $this->link('events/edit?id=' . $event->getId(), 'Edit') . "<p>\n";
        } ?>
    <?php } ?>
</div>

<?php
if ($editable) {
    echo $this->link('events/edit', 'Create new') . "<br />\n";
}
?>
