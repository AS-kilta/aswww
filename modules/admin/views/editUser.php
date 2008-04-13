<h1>Edit user</h1>

<?php
if (isset($message)) {
    echo "<p>$message</p>\n";
}
?>

<?= $this->formStart('editUser') ?>
    <input type="hidden" name="userId" value="<?= $user->getId(); ?>" />

    <table>
        <tr>
            <td>Username:</td>
            <td><input type="text" name="username" value="<?= $user->getUsername(); ?>" /></td>
        </tr>
        <tr>
            <td>Password:</td>
            <td><input type="password" name="password" /></td>
        </tr>
        <tr>
            <td>Real name:</td>
            <td><input type="text" name="realname"  value="<?= $user->getRealname(); ?>"/></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="submit" name="save" value="Save" />
            </td>
        </tr>
    </table>
<?= $this->formEnd() ?>
