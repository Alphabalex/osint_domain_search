<?php
require __DIR__ . '/../vendor/autoload.php';
use Eaglewatch\DomainSearch\Facebook;


$facebook = new Facebook();
$search = $facebook->search("peppa.io");

print_r($search);
