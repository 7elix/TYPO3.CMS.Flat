<?php
namespace PHORAX\Flat\Toolbar;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */


if (TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_AJAX) {
	require_once \TYPO3\CMS\Core\Extension\ExtensionManager::extPath('backend') . 'Classes/Toolbar/ToolbarItemHookInterface.php';
}

/**
 * Class to render the workspace selector
 *
 * @author Ingo Renner <ingo@typo3.org>
 */
class WorkspaceToolbarItem extends \TYPO3\CMS\Workspaces\ExtDirect\WorkspaceSelectorToolbarItem {

	/**
	 * Creates the selector for workspaces
	 *
	 * @return string workspace selector as HTML select
	 */
	public function render() {
		$title = $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xlf:toolbarItems.workspace', TRUE);
		$this->addJavascriptToBackend();

		/** @var \TYPO3\CMS\Workspaces\Service\WorkspaceService $wsService */
		$wsService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Workspaces\\Service\\WorkspaceService');
		$availableWorkspaces = $wsService->getAvailableWorkspaces();

		// Dissolve yourself, mister!!
		if (count($availableWorkspaces) <= 1) {
			return '';
		}

		$activeWorkspace = (int)$GLOBALS['BE_USER']->workspace;

		foreach ($availableWorkspaces as $workspaceId => $label) {
			$workspaceId = (int)$workspaceId;
			$isActive = $workspaceId === $activeWorkspace;
			$workspaceLinks[] = '<li' . ($isActive ? ' class="active"' : '') . '>' . '<a href="backend.php?changeWorkspace=' . $workspaceId . '" id="ws-' . $workspaceId . '" class="ws">' . htmlspecialchars($label) . '</a></li>';
		}

		return '<a href="#" class="toolbar-item">' . \TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIcon('apps-toolbar-menu-workspace', array('title' => $title)) . '</a>' .
			'<ul class="toolbar-item-menu" >' .
			implode(LF, $workspaceLinks) .
			'</ul>';
	}
}

if (!(TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_AJAX)) {
	$GLOBALS['TYPO3backend']->addToolbarItem('workSpaceSelector', 'TYPO3\\CMS\\Workspaces\\ExtDirect\\WorkspaceSelectorToolbarItem');
}
