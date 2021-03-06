<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

if (TYPO3_MODE == 'BE' || TYPO3_MODE == 'FE' && isset($GLOBALS['BE_USER'])) {

	$TBE_STYLES['skins'][$_EXTKEY] = array(
		'name' => 'flat'
	);

	// iWhite/inverted logo
	$TBE_STYLES['logo_login'] = 'gfx/typo3-topbar@2x.png';

	# Backend
	$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Backend\\Controller\\BackendController'] = array(
		'className' => 'PHORAX\\Flat\\Controller\\BackendController'
	);
	$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Backend\\Template\\DocumentTemplate'] = array(
		'className' => 'PHORAX\\Flat\\Template\\DocumentTemplate'
	);

	# ToolbarItems
	$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Backend\\Toolbar\\LiveSearchToolbarItem'] = array(
		'className' => 'PHORAX\\Flat\\Toolbar\\LiveSearchToolbarItem'
	);
	$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Backend\\Toolbar\\ShortcutToolbarItem'] = array(
		'className' => 'PHORAX\\Flat\\Toolbar\\ShortcutToolbarItem'
	);
	$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Backend\\Toolbar\\ClearCacheToolbarItem'] = array(
		'className' => 'PHORAX\\Flat\\Toolbar\\ClearCacheToolbarItem'
	);
	if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('workspaces')) {
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Workspaces\\ExtDirect\\WorkspaceSelectorToolbarItem'] = array(
			'className' => 'PHORAX\\Flat\\Toolbar\\WorkspaceToolbarItem'
		);
	}
	if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('opendocs')) {
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Opendocs\\Controller\\OpendocsController'] = array(
			'className' => 'PHORAX\\Flat\\Toolbar\\OpenDocumentToolbarItem'
		);
	}

	# Hooks
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/template.php']['preHeaderRenderHook']['PHORAX\\Flat\\Hooks\\DocumentTemplate'] =
		'PHORAX\\Flat\\Hooks\\DocumentTemplate->preHeaderRenderHook';

	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['TYPO3/Backend/Utility/IconUtility']['buildSpriteHtmlIconTag'][] =
		'PHORAX\\Flat\\Hooks\\IconUtility';

	# Metro skin - thank you "dakirby309"
	$temp_eP = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY);
	$presetSkinImgs = is_array($TBE_STYLES['skinImg']) ? $TBE_STYLES['skinImg'] : array();

}