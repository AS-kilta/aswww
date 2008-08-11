<div class="events">
  <h1><?= $_['heading'] ?></h1>

  <?php foreach ($events as $item) { ?>
    <h2><?= $item['heading'] ?></h2>
    <p>
    <?= $item['content'] ?><br />
    <?php if ($editable) {
        echo $this->link('news/edit?id=' . $item['id'], 'Edit');
    } ?>
    </p>
  <?php } ?>
</div>

<?php
if ($editable) {
    echo $this->link('events/edit', 'Create new') . "<br />\n";
    echo $this->link('events/list', 'List all');
}
?>
