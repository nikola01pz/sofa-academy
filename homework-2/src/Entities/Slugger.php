<?php

namespace Sofa\Homework;

class Slugger
{
    public function slugify ($title): string
    {
        $slug = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $title);
        $slug = str_replace(["-","_"], " ", $slug);
        $slug = preg_replace("/[^A-Za-z0-9_ ]/", "", $slug);
        $slug = preg_replace("/\s\s+/", " ", $slug);
        $slug = trim(strtolower($slug));
        return str_replace(" ", "-", $slug);
    }
}
