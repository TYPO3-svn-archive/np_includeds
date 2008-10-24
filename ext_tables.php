<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

if (TYPO3_MODE != 'FE') {
	$conf = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$GLOBALS['_EXTKEY']];
	
	function addStaticDataStructures($path, $scope) {
		$absolutePath = t3lib_div::getFileAbsFileName($path);
		
		$files = t3lib_div::getFilesInDir($absolutePath, 'xml', true);
		foreach($files as $filePath) {
			$staticDataStructure = array();
			$pathInfo = pathinfo($filePath);
			
			$staticDataStructure['title'] = $pathInfo['filename'].' ('.$scope.')';
			$staticDataStructure['path'] = substr($filePath, strlen(PATH_site));
			$iconPath = $pathInfo['dirname'].'/'.$pathInfo['filename'].'.gif';
			if (file_exists($iconPath)) {
				$staticDataStructure['icon'] = substr($iconPath, strlen(PATH_site));
			}
			$staticDataStructure['scope'] = $scope == 'fce' ? 2 : 1;
			
			$GLOBALS['TBE_MODULES_EXT']['xMOD_tx_templavoila_cm1']['staticDataStructures'][] = $staticDataStructure;
		}
	}

	addStaticDataStructures($conf['path_page'], 'page');
	addStaticDataStructures($conf['path_fce'], 'fce');
}
?>