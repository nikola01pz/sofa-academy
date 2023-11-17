<?php

namespace Sofa\Homework;

include __DIR__.'/../src/Autoloader.php';
include 'db.php';

use PDO;

if (!isset($argv[1]))
{
    exit("No filename provided\n");
}

if (isValidType($argv[1]))
{
    $filePath = __DIR__.'/../data/'.$argv[1];
    $data = file_get_contents($filePath);
} else {
    exit("File type is not valid\n");
}

if (isJson($data))
{
    $parsedData = parseJson($data);
} elseif (isXml($data))
{
    $parsedData = parseXml($data);
}

$dsn = 'pgsql:host=localhost;dbname=postgres';
$conn = new PDO($dsn.';user=sofa;password=sofa');

if (isset($parsedData))
{
    insertData($conn, $parsedData);
}

function isValidType ($fileName): bool
{
    if (
        str_ends_with($fileName, ".json") or
        str_ends_with($fileName, ".xml")
    ) {
        return true;
    }
    return false;
}

function isJson ($data): bool
{
    json_decode ($data, true);
    if (json_last_error() === JSON_ERROR_NONE)
    {
        return true;
    }
    return false;
}

function isXml ($data): bool
{
    $xmlData = simplexml_load_string ($data);
    if ($xmlData === FALSE)
    {
        exit("Your file is not proper json or xml format\n");
    }
    return true;
}

function parseJson ($data): Sport
{
    $jsonData = json_decode($data, true);
    $parser = new JsonFeedParser(new Slugger());
    return $parser->parse($jsonData);
}

function parseXml ($data): Sport
{
    $xmlData = simplexml_load_string($data);
    $parser = new XmlFeedParser(new Slugger());
    return $parser->parse($xmlData);
}