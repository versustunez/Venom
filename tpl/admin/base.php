<?php

use Venom\Views\Asset;
use Venom\Core\Config;

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Venom Admin Interface</title>
    <?php Asset::get()->renderCSS(); ?>
    <!--link rel="stylesheet" href="/theme/admin/css/admin-panel.css"-->
</head>
<body <?=Config::getInstance()->isDevMode() ? 'debug' : ''?>>
<?php
if (!$this->getVar('isLoggedIn')) {
    $this->renderTemplate('login');
} else {
    $this->renderTemplate('admin-panel');
}
Asset::get()->renderJS();
?>
</body>
</html>
