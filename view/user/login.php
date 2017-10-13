<?php

$create = $di->get("url")->create("user/create");

?>



<h1><?= $title ?> </h1>
<div class="mdl-card-wide mdl-card mdl-shadow--2dp">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text"><?= $card ?></h2>
    </div>
        <div class="mdl-card__supporting-text">
            <?= $form ?>
        </div>
</div>

<p><a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" href="<?= $create ?>">Skapa ny användare</a></p>