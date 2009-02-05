<?php

require_once (t3lib_extMgm::extPath ('templavoila').'mod1/db_new_content_el.php');

class ux_tx_templavoila_dbnewcontentel extends tx_templavoila_dbnewcontentel {
	function wizardArray()	{
		$wizardItems = parent::wizardArray();
		$storageFolderPID = $this->apiObj->getStorageFolderPid($this->id);
		//debug($wizardItems);
		
			// Fetch static data structures which are stored in XML files:
		if (is_array($GLOBALS['TBE_MODULES_EXT']['xMOD_tx_templavoila_cm1']['staticDataStructures'])) {
			foreach($GLOBALS['TBE_MODULES_EXT']['xMOD_tx_templavoila_cm1']['staticDataStructures'] as $staticDataStructureArr)	{
				if (!isset($staticDataStructureArr['scope']) || $staticDataStructureArr['scope'] != 2) {
					continue;
				}
				$staticDataStructureArr['_STATIC'] = TRUE;
				$dataStructureRecords[$staticDataStructureArr['path']] = $staticDataStructureArr;
			}
		}
			// Fetch all template object records which uare based one of the previously fetched data structures:
		$addWhere = $this->buildRecordWhere('tx_templavoila_tmplobj');
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			'tx_templavoila_tmplobj',
			'pid='.intval($storageFolderPID).' AND parent=0' . $addWhere .
				t3lib_BEfunc::deleteClause('tx_templavoila_tmplobj').
				t3lib_BEfunc::versioningPlaceholderClause('tx_templavoila_tmpl'), '', 'sorting'
		);
		$wizardItems['fce']['header'] = $GLOBALS['LANG']->getLL('fce');
		$index = 0;
		while(FALSE !== ($templateObjectRecord = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))) {
			if (!is_array($dataStructureRecords[$templateObjectRecord['datastructure']])) {
				continue;
			}
			$templateobjectIconPathAndFilename = 'uploads/tx_templavoila/' . $templateObjectRecord['previewicon'];
			$datastructureIconPathAndFilename = isset($dataStructureRecords[$templateObjectRecord['datastructure']]['icon']) ? $dataStructureRecords[$templateObjectRecord['datastructure']]['icon'] : t3lib_extMgm::siteRelPath('templavoila') . 'res1/default_previewicon.gif';
			if (@is_file(PATH_site . $templateobjectIconPathAndFilename)) {
				$iconPathAndFilename = $templateobjectIconPathAndFilename;
			} else if (@is_file(PATH_site . $datastructureIconPathAndFilename)) {
				$iconPathAndFilename = $datastructureIconPathAndFilename;
			}
			$wizardItems['fce_'.$index]['icon'] = '../' . $iconPathAndFilename;
			$wizardItems['fce_'.$index]['description'] = $templateObjectRecord['description'] ? htmlspecialchars($templateObjectRecord['description']) : $GLOBALS['LANG']->getLL ('template_nodescriptionavailable');
			$wizardItems['fce_'.$index]['title'] = $templateObjectRecord['title'];
			$wizardItems['fce_'.$index]['params'] = '&defVals[tt_content][CType]=templavoila_pi1&defVals[tt_content][tx_templavoila_ds]='.$templateObjectRecord['datastructure'].'&defVals[tt_content][tx_templavoila_to]='.$templateObjectRecord['uid'];
			$index++;
		}
		
		return $wizardItems;
	}
}
?>