<?php

use Venom\Venom;

//register modules -> need to have the Module Class at parent with the init function ;)
$modules = [];

Venom::makeDefaultRouter("api/");
