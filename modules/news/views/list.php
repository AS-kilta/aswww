<h1>Ajankohtaista</h1>

<table>

<?php foreach ($news as $item) { ?>
    <tr>
        <td><?= $item['heading'] ?></td>
        <?php if ($editable) { ?>
            <td><?= $this->link('news/edit?id=' . $item['id'], 'Edit'); ?></td>
            <td><?= $this->link('news/edit?id=' . $item['id'], 'Delete'); ?></td>
        <?php } ?>
    </tr>
<?php } ?>

</table>

<?php
if ($editable) {
    echo $this->link('news/edit', 'New');
}
?>