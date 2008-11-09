<h1><?= $_['news-heading'] ?></h1>

<?php
$i = 0;
foreach ($news as $item) {
    if ($i >= $numNews) {
        break;
    }

    if (strlen($item['heading']) < 1) {
        continue;
    }
?>
    <div class="news">
    <h2><?= $item['heading'] ?></h2>
    <div>
    <?= $item['content'] ?>
    <?php if ($editable) {
        echo "<br />\n";
        echo $this->link('news/edit?id=' . $item['id'], 'Edit');
    } ?>
    </div>

    </div>
<?php
    $i++;
}
?>

<?php
if ($editable) {
    echo $this->link('news/edit', 'Create new') . "<br />\n";
    echo $this->link('news/list', 'List all');
}
?>
