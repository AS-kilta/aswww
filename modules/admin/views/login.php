<h1>Login</h1>

<?php if ($invalidLogin) { ?>
    <p class="warning">Invalid username or password</p>
<?php } ?>

<?= $this->formStart('login') ?>
    <table>
        <tr>
            <td>Username:</td>
            <td><input type="text" name="username" /></td>
        </tr>
        <tr>
            <td>Password:</td>
            <td><input type="password" name="password" /></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="submit" value="Login" />
            </td>
        </tr>
    </table>
<?= $this->formEnd() ?>
