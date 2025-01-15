<?php
require __DIR__ . '/../vendor/autoload.php';
use Eaglewatch\DomainSearch\UrlScan;
use Eaglewatch\DomainSearch\CrtSearch;
use Eaglewatch\DomainSearch\Dnsdumpster;
use Eaglewatch\DomainSearch\WebCheckAnalyzer;


$urlscan = new UrlScan();
$search = $urlscan->search("peppa.io");

print_r($search);
