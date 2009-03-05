<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

$_EXTCONF = unserialize($GLOBALS['_EXTCONF']);

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$GLOBALS['_EXTKEY']]['path_fce'] = $_EXTCONF['path_fce'];
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$GLOBALS['_EXTKEY']]['path_page'] = $_EXTCONF['path_page'];
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$GLOBALS['_EXTKEY']]['xclass_global'] = $_EXTCONF['xclass_global'] === '1' ? TRUE : FALSE;

if ($_EXTCONF['xclass'] == 1) {
	$GLOBALS['TYPO3_CONF_VARS']['BE']['XCLASS']['ext/templavoila/mod1/db_new_content_el.php'] = t3lib_extMgm::extPath($GLOBALS['_EXTKEY']).'class.ux_tx_templavoila_dbnewcontentel.php';
}
?>