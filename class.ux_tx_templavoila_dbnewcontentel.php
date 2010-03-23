<?php

require_once (t3lib_extMgm::extPath ('templavoila').'mod1/db_new_content_el.php');

class ux_tx_templavoila_dbnewcontentel extends tx_templavoila_dbnewcontentel {
	function wizardArray()	{
		$selectGlobal = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['np_includeds']['xclass_global'] === TRUE ? TRUE : FALSE;
		$wizardItems = parent::wizardArray();
		
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
			// Fetch all template object records which are based one of the previously fetched data structures:
		$addWhere = $this->buildRecordWhere('tx_templavoila_tmplobj');
		if (!$selectGlobal) {
			$addWhere = ' AND pid = ' . intval($this->apiObj->getStorageFolderPid($this->id)) . $addWhere;
		}
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			'tx_templavoila_tmplobj',
			'parent = 0' . $addWhere .
				t3lib_BEfunc::deleteClause('tx_templavoila_tmplobj').
				t3lib_BEfunc::versioningPlaceholderClause('tx_templavoila_tmpl'), '', 'sorting'
		);

		$fceWizardItems = array();
		if (isset($wizardItems['fce']['header'])) {
			$fceIndex = (integer)array_search('fce', array_keys($wizardItems)) + 1;
		} else {
			$fceWizardItems['fce']['header'] = $GLOBALS['LANG']->getLL('fce');
			$fceIndex = (integer)array_search('plugins', array_keys($wizardItems));
		}
		$fceIndex++;
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
			$fceWizardItems['fce_static_'.$fceIndex]['icon'] = '../' . $iconPathAndFilename;
			$fceWizardItems['fce_static_'.$fceIndex]['description'] = $templateObjectRecord['description'] ? htmlspecialchars($templateObjectRecord['description']) : $GLOBALS['LANG']->getLL ('template_nodescriptionavailable');
			$fceWizardItems['fce_static_'.$fceIndex]['title'] = $templateObjectRecord['title'];
			$fceWizardItems['fce_static_'.$fceIndex]['params'] = '&defVals[tt_content][CType]=templavoila_pi1&defVals[tt_content][tx_templavoila_ds]='.$templateObjectRecord['datastructure'].'&defVals[tt_content][tx_templavoila_to]='.$templateObjectRecord['uid'];
			$fceIndex++;
		}
		if (count($fceWizardItems) == 1) {
			return $wizardItems;
		}
		array_splice($wizardItems, $fceIndex - count($fceWizardItems), 0, $fceWizardItems);

		return $wizardItems;
	}
}
?>