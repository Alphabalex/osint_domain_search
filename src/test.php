<?php
require __DIR__ . '/../vendor/autoload.php';
use Eaglewatch\DomainSearch\WayBack;


$wayback = new WayBack();
$search = $wayback->search("initsng.com");

print_r($search);
