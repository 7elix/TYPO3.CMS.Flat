<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

if (TYPO3_MODE == 'BE' || TYPO3_MODE == 'FE' && isset($GLOBALS['BE_USER'])) {

	$TBE_STYLES['skins'][$_EXTKEY] = array(
		'name' => 'flat'
	);

}