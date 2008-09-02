<?= $this->formStart('edit') ?>

<h1>Edit page</h1>

<?php if ($warning) { ?>
    <p class="warning"><?= $warning ?></p>
<?php } ?>

<?php if ($success) { ?>
    <p class="success"><?= $success ?></p>
<?php } ?>

<?php
// Language versions
foreach ($pageVersions as $version) { ?>
    <h1><?= strtoupper($version->getLang()) ?></h1>

    <textarea name="<?= $version->getLang() . '-content' ?>" rows="20" style="width:100%"><?= $version->getContent() ?></textarea>
<?php } ?>

<h1>Location</h1>
<table>
    <tr>
        <td>Language</td>
        <td>Title</td>
        <td>Url</td>
    </tr>

    <?php
    $languages = getConfiguredLanguages();
    foreach ($languages as $lang) {
    ?>
        <tr>
            <td><?= $lang ?></td>
            <td><input type="text" name="<?= $lang . '-title' ; ?>" value="<?= $naviNode->getTitle($lang); ?>" /></td>
            <td><input type="text" name="<?= $lang . '-url' ; ?>" value="<?= $naviNode->getUrl($lang); ?>" /></td>
        </tr>
    <?php } ?>
</table>

<p>
    Parent:
    <select name="parent">
        <?= $naviTree ?>
    </select>
    
    Position:
    <select name="position">
        <?php
            for ($i = -10; $i < 10; $i++) {
                echo "<option value='$i'";
                if ($naviNode->position == $i) {
                    echo " selected='true'";
                }
                echo ">$i</option>\n";
            }
        ?>
    </select>

    Hidden:
    <input type="checkbox" name="hidden" value="1" <?= $naviNode->hidden ? 'checked="true"' : '' ?> />
</p>

<p>
    <input onclick="return confirm('Are you sure you want to delete this page?')" type='submit' name='delete' value='Delete' />
    <input type='submit' name='preview' value='Preview' />
    <input type='submit' name='save' value='Save' />
</p>



<?= $this->formEnd() ?>


<!-- Preview -->
<hr />

<?php
// Language versions
foreach ($pageVersions as $version) { ?>
    <h1>Preview: <?= strtoupper($version->getLang()) ?></h1>

    <?= $version->getContent() ?>
<?php } ?>
