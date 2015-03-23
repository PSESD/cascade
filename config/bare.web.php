<?php
$parent = CANIS_APP_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'web.php';
$config = include $parent;
$config['id'] = 'cascade-template';
$config['name'] = 'Cascade Template';
return $config;