<?php

namespace Sofa\Homework;

include 'src/Autoloader.php';

echo "test";

$slugger = new Slugger();

echo $slugger->slugify('Ovo je neki tekst sa šđčćž');