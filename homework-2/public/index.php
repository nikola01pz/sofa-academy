<?php

namespace Sofa\Homework;

use PDO;

include __DIR__.'/../src/Autoloader.php';

$slugger = new Slugger();
echo $slugger->slugify('Ovo je neki tekst sa šđčćž')."\n";
