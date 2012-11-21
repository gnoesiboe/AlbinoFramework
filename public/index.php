<?php

use App\Bootstrap;

require_once dirname(__FILE__) . '/../App/Bootstrap.php';
$bootstrap = new Bootstrap();
$bootstrap->getApplication()->run();