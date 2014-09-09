<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher')->connect(
	'TYPO3\\CMS\\Backend\\Utility\\IconUtility',
	'buildSpriteHtmlIconTag',
	'PHORAX\\Flat\\Hooks',
	'buildSpriteHtmlIconTag'
);
