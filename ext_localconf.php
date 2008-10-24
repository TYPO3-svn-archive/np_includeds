<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

$_EXTCONF = unserialize($GLOBALS['_EXTCONF']);

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$GLOBALS['_EXTKEY']]['path_fce'] = $_EXTCONF['path_fce'];
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$GLOBALS['_EXTKEY']]['path_page'] = $_EXTCONF['path_page'];
?>