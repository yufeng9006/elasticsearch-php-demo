<?php

use Elasticsearch\ClientBuilder;

require_once("vendor/autoload.php");

$es = ClientBuilder::create()->setHosts(['hosts' => '127.0.0.1:9200'])->build();
//$es = ClientBuilder::create()->build();

//require_once("vendor/autoload.php");
//
//$es = new Elasticsearch\Client([
//        'hosts' => '127.0.0.1:9200'
//]);