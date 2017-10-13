<?php

$logout = $di->get("url")->create("user/logout");
$edit = $di->get("url")->create("user/edit");
$admin = $di->get("url")->create("admin");
?>

<h3><?= $title ?></h3>

<p>Välkommen <?= $user->name ?>!</p>

<p><a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" href="<?= $edit ?>">Redigera profil</a></p>

<?php if ($isAdmin == 1) : ?>
<p><a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" href="<?= $admin ?>">Administration</a></p>
<?php endif; ?>

<p><a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" href="<?= $logout ?>">Logga ut</a></p>