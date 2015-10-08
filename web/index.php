<?php

require_once(__DIR__.'/../framework/Loader.php');

Loader::addNamespacePath('Blog\\',__DIR__.'/../src/Blog');

$app = new \Framework\Application(__DIR__.'/../app/config/config.php');

putenv("LANG=ru_RU.utf8");

setlocale (LC_ALL,"ru_RU.utf8");

$domain = 'messages';

$path = __DIR__.'/../locale';
bindtextdomain ($domain, './locale');

textdomain($domain);

$app->run();