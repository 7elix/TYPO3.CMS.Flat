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
	$TBE_STYLES['skinImg'] = array_merge($presetSkinImgs, array(
		'MOD:web_layout/layout.gif' => array($temp_eP . 'Resources/Public/Icons/module-layout.png', 'width="32" height="32"'),
		'EXT:viewpage/ext_icon.gif' => array($temp_eP . 'Resources/Public/Icons/module-viewpage.png', 'width="32" height="32"'),
		'MOD:web_info/info.gif' => array($temp_eP . 'Resources/Public/Icons/module-info.png', 'width="32" height="32"'),
		'MOD:web_func/func.gif' => array($temp_eP . 'Resources/Public/Icons/module-functions.png', 'width="32" height="32"'),
		'MOD:web_ts/ts1.gif' => array($temp_eP . 'Resources/Public/Icons/module-tstemplate.png', 'width="32" height="32"'),

		'MOD:file_list/list.gif' => array($temp_eP . 'Resources/Public/Icons/module-filelist.png', 'width="22" height="32"'),

		'MOD:user_task/task.gif' => array($temp_eP . 'Resources/Public/Icons/module-taskcenter.png', 'width="22" height="22"'),
		'MOD:user_setup/setup.gif' => array($temp_eP . 'Resources/Public/Icons/module-setup.png', 'width="22" height="22"'),
		'MOD:user_ws/sys_workspace.gif' => array($temp_eP . 'Resources/Public/Icons/module-workspaces.png', 'width="22" height="22"'),

		'MOD:tools_isearch/isearch.gif' => array($temp_eP . 'Resources/Public/Icons/module-indexed_search.png', 'width="32" height="32"'),

		'MOD:system_dbint/db.gif' => array($temp_eP . 'Resources/Public/Icons/module-lowlevel.png', 'width="25" height="32"'),
		'MOD:system_beuser/beuser.gif' => array($temp_eP . 'Resources/Public/Icons/module-beuser.png', 'width="32" height="32"'),
		'MOD:system_install/install.gif' => array($temp_eP . 'Resources/Public/Icons/module-setup.png', 'width="32" height="32"'),
		'MOD:system_config/config.gif' => array($temp_eP . 'Resources/Public/Icons/module-config.png', 'width="32" height="32"'),
		'MOD:system_log/log.gif' => array($temp_eP . 'Resources/Public/Icons/module-belog.png', 'width="32" height="32"'),
	));
}