<h1>Ajankohtaista</h1>

<?php foreach ($news as $item) { ?>
    <div class="news">
    <h2><?= $item['heading'] ?></h2>
    <p>
    <?= $item['content'] ?><br />
    <?php if ($editable) {
        echo $this->link('frontpage/edit?id=' . $item['id'], 'Edit');
    } ?>
    </p>

    </div>
<?php } ?>

<?php 
if ($editable) {
    echo $this->link('frontpage/edit', 'New');
    echo $this->link('frontpage/list', 'List all');
}
?>