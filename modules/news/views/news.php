<h1><?= $_['news-heading'] ?></h1>

<?php foreach ($news as $item) { ?>
    <div class="news">
    <h2><?= $item['heading'] ?></h2>
    <p>
    <?= $item['content'] ?><br />
    <?php if ($editable) {
        echo $this->link('news/edit?id=' . $item['id'], 'Edit');
    } ?>
    </p>

    </div>
<?php } ?>

<?php
if ($editable) {
    echo $this->link('news/edit', 'New');
    echo $this->link('news/list', 'List all');
}
?>
