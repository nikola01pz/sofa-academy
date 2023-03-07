<?php

class Slugger 
{

	public function slugify($title)
	{
		$slug = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $title);
		$slug = str_replace(['-','_'], " ", $slug);
		$slug = preg_replace('/[^A-Za-z0-9_ ]/', '', $slug);
		$slug = preg_replace('/\s\s+/', ' ', $slug);
		$slug = trim(strtolower($slug));
		$slug = str_replace(" ", "-", $slug);
		echo $slug,"\n";
	}
}

$slugger = new Slugger();
echo $slugger->slugify('Ovo je neki tekst sa šđčćž');
echo $slugger->slugify('Ovaj \ (tekst) =ima $ neke_znakove / koji #nisu +slova!');
echo $slugger->slugify('Ovaj tekst ima ---- u sebi.');
echo $slugger->slugify('- Ovaj tekst ima - na početku i kraju-');
echo $slugger->slugify('Ovaj je 1. tekst koji ma brojeve u sebi, npr 12 37 4.');