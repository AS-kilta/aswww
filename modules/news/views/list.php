<h1>Ajankohtaista</h1>

<?php if ($warning) { ?>
    <p class="warning"><?= $warning ?></p>
<?php } ?>

<?php if ($success) { ?>
    <p class="success"><?= $success ?></p>
<?php } ?>

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
            <td><a onclick="return confirm('Are you sure you want to delete this event?')"  href="<?= baseUrl() . '/news/delete?id=' . $item['id'] ?>">Delete</a></td>
        <?php } ?>
    </tr>
<?php } ?>

</table>

