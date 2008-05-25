<h1><?= $_['news-heading'] ?></h1>

<?php
$i = 0;
foreach ($news as $item) {
    if ($i >= $numNews) {
        break;
    }
?>
    <div class="news">
    <h2><?= $item['heading'] ?></h2>
    <p>
    <?= $item['content'] ?><br />
    <?php if ($editable) {
        echo $this->link('news/edit?id=' . $item['id'], 'Edit');
    } ?>
    </p>

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
