<?php

use Venom\Core\Registry;
use Venom\Views\Asset;

$reg = Registry::getInstance();
$lang = $reg->getLang();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>VenomBase</title>
    <?php
    Asset::get()->renderCSS();
    ?>
</head>
<body>
<header>
    <?= $lang->getTranslation("HEADLINE") ?>
</header>
<main>
    <?php
    $lang->getTranslation("TEST_TRANSLATION");
    echo $this->templateData;
    ?>
</main>

<?php
Asset::get()->renderJS();
?>
</body>
</html>