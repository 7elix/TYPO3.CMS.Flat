<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

if (TYPO3_MODE == 'BE' || TYPO3_MODE == 'FE' && isset($GLOBALS['BE_USER'])) {

	$TBE_STYLES['skins'][$_EXTKEY] = array(
		'name' => 'flat'
	);

	# BackendController
	$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Backend\\Controller\\BackendController'] = array(
		'className' => 'PHORAX\\Flat\\Controller\\BackendController'
	);

	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/template.php']['preHeaderRenderHook']['PHORAX\\Flat\\Hooks\\DocumentTemplate'] =
		'PHORAX\\Flat\\Hooks\\DocumentTemplate->preHeaderRenderHook';

}