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
		$title = $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xlf:toolbarItems.bookmarks', TRUE);
		$this->addJavascriptToBackend();
		$shortcutMenu = array();
		$shortcutMenu[] = '<a href="#" class="toolbar-item" tite="' . $title . '"><i class="fa fa-lg fa-star"></i></a>';
		$shortcutMenu[] = '<div class="toolbar-item-menu" style="display: none;">';
		$shortcutMenu[] = $this->renderMenu();
		$shortcutMenu[] = '</div>';
		return implode(LF, $shortcutMenu);
	}

	/**
	 * Renders the pure contents of the menu
	 *
	 * @return string The menu's content
	 */
	public function renderMenu() {
		$shortcutGroup = $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xlf:toolbarItems.bookmarksGroup', TRUE);
		$shortcutEdit = $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xlf:toolbarItems.bookmarksEdit', TRUE);
		$shortcutDelete = $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xlf:toolbarItems.bookmarksDelete', TRUE);
		$groupIcon = '<img' . IconUtility::skinImg($this->backPath, 'gfx/i/sysf.gif', 'width="18" height="16"') . ' title="' . $shortcutGroup . '" alt="' . $shortcutGroup . '" />';
		$editIcon = '<img' . IconUtility::skinImg($this->backPath, 'gfx/edit2.gif', 'width="11" height="12"') . ' title="' . $shortcutEdit . '" alt="' . $shortcutEdit . '"';
		$deleteIcon = '<img' . IconUtility::skinImg($this->backPath, 'gfx/garbage.gif', 'width="11" height="12"') . ' title="' . $shortcutDelete . '" alt="' . $shortcutDelete . '" />';
		$shortcutMenu[] = '<table class="table table-hover shortcut-list">';

		// Render shortcuts with no group (group id = 0) first
		$noGroupShortcuts = $this->getShortcutsByGroup(0);
		foreach ($noGroupShortcuts as $shortcut) {
			$shortcutMenu[] = '
			<tr id="shortcut-' . $shortcut['raw']['uid'] . '" class="shortcut">
				<td class="shortcut-icon">' . $shortcut['icon'] . '</td>
				<td class="shortcut-label">
					<a id="shortcut-label-' . $shortcut['raw']['uid'] . '" href="#" onclick="' . $shortcut['action'] . '; return false;">' . htmlspecialchars($shortcut['label']) . '</a>
				</td>
				<td class="shortcut-edit">' . $editIcon . ' id="shortcut-edit-' . $shortcut['raw']['uid'] . '" /></td>
				<td class="shortcut-delete">' . $deleteIcon . '</td>
			</tr>';
		}

		// Now render groups and the contained shortcuts
		$groups = $this->getGroupsFromShortcuts();
		krsort($groups, SORT_NUMERIC);
		foreach ($groups as $groupId => $groupLabel) {
			if ($groupId != 0) {
				$shortcutGroup = '
				<tr class="shortcut-group" id="shortcut-group-' . $groupId . '">
					<td class="shortcut-group-icon">' . $groupIcon . '</td>
					<td class="shortcut-group-label">' . $groupLabel . '</td>
					<td colspan="2">&nbsp;</td>
				</tr>';
				$shortcuts = $this->getShortcutsByGroup($groupId);
				$i = 0;
				foreach ($shortcuts as $shortcut) {
					$i++;
					$firstRow = '';
					if ($i == 1) {
						$firstRow = ' first-row';
					}
					$shortcutGroup .= '
					<tr id="shortcut-' . $shortcut['raw']['uid'] . '" class="shortcut' . $firstRow . '">
						<td class="shortcut-icon">' . $shortcut['icon'] . '</td>
						<td class="shortcut-label">
							<a id="shortcut-label-' . $shortcut['raw']['uid'] . '" href="#" onclick="' . $shortcut['action'] . '; return false;">' . htmlspecialchars($shortcut['label']) . '</a>
						</td>
						<td class="shortcut-edit">' . $editIcon . ' id="shortcut-edit-' . $shortcut['raw']['uid'] . '" /></td>
						<td class="shortcut-delete">' . $deleteIcon . '</td>
					</tr>';
				}
				$shortcutMenu[] = $shortcutGroup;
			}
		}

		if (count($shortcutMenu) == 1) {
			// No shortcuts added yet, show a small help message how to add shortcuts
			$title = $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xlf:toolbarItems.bookmarks', TRUE);
			$icon = IconUtility::getSpriteIcon('actions-system-shortcut-new', array(
				'title' => $title
			));
			$label = str_replace('%icon%', $icon, $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_misc.xlf:bookmarkDescription'));
			$shortcutMenu[] = '<tr><td><p>' . $label . '</p></td></tr>';
		}

		$shortcutMenu[] = '</table>';
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
