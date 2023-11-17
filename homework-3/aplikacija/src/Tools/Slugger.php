<?php

declare(strict_types=1);

namespace App\Tools;

final class Slugger
{
    public function slugify(string $text, string $divider = '-'): string
    {
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
        $text = iconv('utf-8', 'ascii//TRANSLIT', $text);
        $text = preg_replace(sprintf('~[^%s\w]+~', preg_quote($divider)), '', $text);
        $text = trim($text, $divider);
        $text = preg_replace(sprintf('~%s+~', preg_quote($divider)), $divider, $text);

        return strtolower($text);
    }
}
