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
</head>
<body <?=Config::get()->isDevMode() ? 'debug' : ''?>>
<h1>Welcome to VenomCMS Admin</h1>
<p>Current User: <strong><?=$this->getVar('current.user');?></strong></p>
<?php

Asset::get()->renderJS();
?>
</body>
</html>
