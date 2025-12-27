<?php

// config and connection to DB
include_once('../classes/config.php');
include_once('../classes/DB.php');
include_once('../classes/caching.php');

$caching = new caching();
$caching->clearCache();
