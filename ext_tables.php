<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

if (TYPO3_MODE == 'BE' || TYPO3_MODE == 'FE' && isset($GLOBALS['BE_USER'])) {
#	global $TBE_STYLES;
#
#	// Register as a skin
	$TBE_STYLES['skins'][$_EXTKEY] = array(
		'name' => 'flat'
	);
#
#	// Support for other extensions to add own icons...
#	$presetSkinImgs = is_array($TBE_STYLES['skinImg']) ? $TBE_STYLES['skinImg'] : array();
#
#	// Alternative dimensions for frameset sizes:
#	// Left menu frame width
#	$TBE_STYLES['dims']['leftMenuFrameW'] = 190;
#
#	// Top frame height
#	$TBE_STYLES['dims']['topFrameH'] = 42;
#
#	// Default navigation frame width
#	$TBE_STYLES['dims']['navFrameWidth'] = 280;
#
#	// Setting up auto detection of alternative icons:
#	$TBE_STYLES['skinImgAutoCfg'] = array(
#		'absDir' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'icons/',
#		'relDir' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'icons/',
#		'forceFileExtension' => 'gif',
#		// Force to look for PNG alternatives...
#		'iconSizeWidth' => 16,
#		'iconSizeHeight' => 16
#	);


}