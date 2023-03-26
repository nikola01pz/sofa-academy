<?php

declare(strict_types=1);

require dirname(__DIR__).'/config/autoload.php';

use App\Kernel;
use SimpleFW\HTTP\Request;

$request = Request::createFromGlobals();
$kernel = new Kernel();
$response = $kernel->handle($request);
$response->send();
