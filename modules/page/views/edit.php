<?= $this->formStart('edit') ?>

<h1>Edit page</h1>

<textarea name="content" rows="20" style="width:100%">
<?= $editableContent ?>
</textarea>

<table>
    <tr>
        <td>Language</td>
        <td>Title</td>
    </tr>
    <tr>
        <td>
            <select name="language">
                <option value="fi" <?= $naviNode->getLang() == 'fi' ? 'selected="true"' : ''; ?>>Suomi</option>
                <option value="en" <?= $naviNode->getLang() == 'en' ? 'selected="true"' : ''; ?>>English</option>
            </select>
        </td>
        <td>
            <input type="text" name="title" value="<?= $naviNode->getTitle(); ?>" />
        </td>
    </tr>
</table>

<table>
    <tr>
        <td>Parent</td>
        <td>Url</td>
    </tr>
    <tr>
        <td>
            <select name="parent">
                <?= $naviTree ?>
            </select>
            /
        </td>
        <td>
            <input type="text" name="url" value="<?= $naviNode->getUrl(); ?>"/>
        </td>
    </tr>
</table>

<p>
    <input type='submit' name='save' value='Save' />
    <input type='submit' name='preview' value='Preview' />
</p>



<?= $this->formEnd() ?>

<hr />
<?= $htmlContent ?>
