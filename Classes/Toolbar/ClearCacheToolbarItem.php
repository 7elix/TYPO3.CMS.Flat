<?php
namespace PHORAX\Flat\Toolbar;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\Utility\IconUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ClearCacheToolbarItem extends \TYPO3\CMS\Backend\Toolbar\ClearCacheToolbarItem {

	/**
	 * Creates the selector for workspaces
	 *
	 * @return string Workspace selector as HTML select
	 */
	public function render() {
		$title = $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xlf:rm.clearCache_clearCache', TRUE);
		$this->addJavascriptToBackend();
		$cacheMenu = array();
		$cacheMenu[] = '<a href="#" class="toolbar-item"><i class="fa fa-lg fa-bolt"></i></a>';
		$cacheMenu[] = '<ul class="toolbar-item-menu" style="display: none;">';
		foreach ($this->cacheActions as $actionKey => $cacheAction) {
			$cacheMenu[] = '<li><a href="' . htmlspecialchars($cacheAction['href'])
				. '" title="' . htmlspecialchars($cacheAction['description'] ?: $cacheAction['title']) . '">'
				. $cacheAction['icon'] . ' ' . htmlspecialchars($cacheAction['title']) . '</a></li>';
		}
		$cacheMenu[] = '</ul>';
		return implode(LF, $cacheMenu);
	}

	/**
	 * Adds the necessary JavaScript to the backend
	 *
	 * @return void
	 */
	protected function addJavascriptToBackend() {
		$this->backendReference->addJavascriptFile('../typo3conf/ext/flat/Resources/Public/JavaScript/Toolbar/ClearCache.js');
	}

	/**
	 * Returns additional attributes for the list item in the toolbar
	 *
	 * @return string List item HTML attributes
	 */
	public function getAdditionalAttributes() {
		return 'id="clear-cache-actions-menu"';
	}

}
