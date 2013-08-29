<?php
include 'ZENObject.php';
foreach (glob(dirname(__FILE__).'/*.php') as $pathname) {
    if (!in_array(pathinfo($pathname, PATHINFO_BASENAME),
        array('ZENObject.php', 'init.php')))
        include $pathname;
}