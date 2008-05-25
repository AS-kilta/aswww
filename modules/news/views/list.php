<h1>Ajankohtaista</h1>

<table>

<?php if ($editable) { ?>
    <tr>
        <td><?= $this->link('news/edit', 'Create new'); ?></td>
        <td></td>
        <td></td>
    </tr>
<?php } ?>

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

