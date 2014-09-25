<?php
namespace PHORAX\Flat\Toolbar;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\Utility\IconUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

class ShortcutToolbarItem extends \TYPO3\CMS\Backend\Toolbar\ShortcutToolbarItem {

	/**
	 * Creates the shortcut menu (default renderer)
	 *
	 * @return string Workspace selector as HTML select
	 */
	public function render() {
		// Dissolve, monsieur!!
		if (empty($this->shortcuts)) {
			return '';
		}

		$title = $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xlf:toolbarItems.bookmarks', TRUE);
		$this->addJavascriptToBackend();

		return '<a href="#" class="toolbar-item" tite="' . $title . '"><i class="fa fa-lg fa-inline fa-star"></i></a>' .
			'<ul class="toolbar-item-menu" style="display: none;">' .
			$this->renderMenu() .
			'</ul>';
	}

	/**
	 * Renders the pure contents of the menu
	 *
	 * @return string The menu's content
	 */
	public function renderMenu() {
		$shortcutEdit = $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xlf:toolbarItems.bookmarksEdit', TRUE);
		$shortcutDelete = $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xlf:toolbarItems.bookmarksDelete', TRUE);

		$editIcon = '<img' . IconUtility::skinImg($this->backPath, 'gfx/edit2.gif', 'width="11" height="12"') . ' title="' . $shortcutEdit . '" alt="' . $shortcutEdit . '"';
		$deleteIcon = '<img' . IconUtility::skinImg($this->backPath, 'gfx/garbage.gif', 'width="11" height="12"') . ' title="' . $shortcutDelete . '" alt="' . $shortcutDelete . '" />';

		$shortcutMenu[] = '<ul class="table table-hover shortcut-list">';

		// Render shortcuts with no group (group id = 0) first
		$noGroupShortcuts = $this->getShortcutsByGroup(0);
		foreach ($noGroupShortcuts as $shortcut) {
			$shortcutMenu[] = '<li id="shortcut-' . $shortcut['raw']['uid'] . '" class="shortcut">' .
				$shortcut['icon'] .
				'<a id="shortcut-label-' . $shortcut['raw']['uid'] . '" href="#" onclick="' . $shortcut['action'] . '; return false;">' . htmlspecialchars($shortcut['label']) . '</a>
				<span class="shortcut-edit">' . $editIcon . ' id="shortcut-edit-' . $shortcut['raw']['uid'] . '" /></span>
				<span class="shortcut-delete">' . $deleteIcon . '</span>
				</li>';
		}

		// Now render groups and the contained shortcuts
		$groups = $this->getGroupsFromShortcuts();
		krsort($groups, SORT_NUMERIC);

		foreach ($groups as $groupId => $groupLabel) {
			if ($groupId != 0) {
				$shortcutGroup = '<li class="shortcut-group" id="shortcut-group-' . $groupId . '"><h3>' . $groupLabel . '</h3></li>';
				$shortcuts = $this->getShortcutsByGroup($groupId);

				$i = 0;
				foreach ($shortcuts as $shortcut) {
					$i++;
					$firstRow = '';
					if ($i == 1) {
						$firstRow = ' first-row';
					}

					$shortcutGroup .= '<li id="shortcut-' . $shortcut['raw']['uid'] . '" class="shortcut' . $firstRow . '">' .
						$shortcut['icon'] .
						'<a id="shortcut-label-' . $shortcut['raw']['uid'] . '" href="#" onclick="' . $shortcut['action'] . '; return false;">' . htmlspecialchars($shortcut['label']) . '</a>' .
						'<span class="shortcut-edit">' . $editIcon . ' id="shortcut-edit-' . $shortcut['raw']['uid'] . '" /></span>
						<span class="shortcut-delete">' . $deleteIcon . '</span>
						</li>';
				}
				$shortcutMenu[] = $shortcutGroup;
			}
		}
		$shortcutMenu[] = '</ul>';

		$compiledShortcutMenu = implode(LF, $shortcutMenu);
		return $compiledShortcutMenu;
	}


	/**
	 * Adds the necessary JavaScript to the backend
	 *
	 * @return void
	 */
	protected function addJavascriptToBackend() {
		$this->backendReference->addJavascriptFile('../typo3conf/ext/flat/Resources/Public/JavaScript/Toolbar/Shortcut.js');
	}

}
