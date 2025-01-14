<?php
require __DIR__ . '/../vendor/autoload.php';
use Eaglewatch\DomainSearch\CrtSearch;
use Eaglewatch\DomainSearch\Dnsdumpster;
use Eaglewatch\DomainSearch\WebCheckAnalyzer;


$dumpster = new Dnsdumpster();
$search = $dumpster->search("peppa.io");

print_r($search);

// $crt = new CrtSearch();
// $search = $crt->search("initsng.com");

// print_r($search);

// $webcheck = new WebCheckAnalyzer();
// $analyze = $webcheck->analyze("https://initsng.com");

// print_r($analyze);
