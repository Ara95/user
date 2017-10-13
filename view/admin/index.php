<?php

$logout = $di->get("url")->create("user/logout");
$edit = $di->get("url")->create("user/edit");
$create = $di->get("url")->create("admin/create");
$delete = $di->get("url")->create("admin/delete");


// Gather incoming variables and use default values if not set
$users = isset($users) ? $users : null;

$urlToDelete = $di->get("url")->create("book/delete");

?><h1>Redigera användare</h1>

<p>
    <a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" href="<?= $create ?>">Skapa ny användare</a>
</p>

<?php if (!$users) : ?>
    <p>Det finns inga användare</p>
<?php
    return;
endif;
?>

<table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
    <tr>
        <th class="mdl-data-table__cell--non-numeric">ID</th>
        <th class="mdl-data-table__cell--non-numeric">Email</th>
        <th class="mdl-data-table__cell--non-numeric">Namn</th>
        <th class="mdl-data-table__cell--non-numeric">Aktiv</th>
        <th class="mdl-data-table__cell--non-numeric">Admin</th>
        <th class="mdl-data-table__cell--non-numeric">Radera</th>

    </tr>
    <?php foreach ($users as $user) : ?>
    <tr>
        <td class="mdl-data-table__cell--non-numeric">
            <a href="<?= $di->get("url")->create("admin/edit/{$user->id}"); ?>"><?= $user->id ?></a>
        </td>
        <td class="mdl-data-table__cell--non-numeric"><?= $user->email ?></td>
        <td class="mdl-data-table__cell--non-numeric"><?= $user->name ?></td>
        <td class="mdl-data-table__cell--non-numeric"><?= $user->active ?></td>
        <td class="mdl-data-table__cell--non-numeric"><?= $user->admin ?></td>
        <td><a href="<?= $di->get("url")->create("admin/delete/{$user->id}"); ?>"> Radera</a></td>
    </tr>
    <?php endforeach; ?>
</table>

<p><a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" href="<?= $logout ?>">Logga ut</a></p>