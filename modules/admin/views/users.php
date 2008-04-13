<h1>Users</h1>

<table>
    <tr>
        <td>Username</td>
        <td>Realname</td>
        <td></td>
    </tr>
    <?php foreach ($users as $user) { ?>
        <tr>
            <td><?= $this->actionLink('editUser',$user['username'], 'userId=' . $user['id']); ?></td>
            <td><?= $user['realname'] ?></td>
            <td><?= $this->actionLink('deleteUser','Delete', "userId={$user['id']}"); ?></td>
        </tr>
    <?php } ?>
</table>

<?= $this->actionLink('editUser','Create user'); ?>
