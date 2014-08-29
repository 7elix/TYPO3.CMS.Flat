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

class OpenDocumentToolbarItem extends \TYPO3\CMS\Opendocs\Controller\OpendocsController {

	/**
	 * Renders the toolbar item and the initial menu
	 *
	 * @return string The toolbar item including the initial menu content as HTML
	 */
	public function render() {
		$this->addJavascriptToBackend();
		$this->addCssToBackend();
		$numDocs = count($this->openDocs);
		$opendocsMenu = array();
		$title = $GLOBALS['LANG']->getLL('toolbaritem', TRUE);

		// Toolbar item icon
		$opendocsMenu[] = '<a href="#" class="toolbar-item">';
		$opendocsMenu[] = '<input type="text" id="tx-opendocs-counter" disabled="disabled" value="' . $numDocs . '" />';
		$opendocsMenu[] = \TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIcon('apps-toolbar-menu-opendocs', array('title' => $title)) . '</a>';

		// Toolbar item menu and initial content
		$opendocsMenu[] = '<ul class="toolbar-item-menu" style="display: none;">';
		$opendocsMenu[] = $this->renderMenu();
		$opendocsMenu[] = '</ul>';
		return implode(LF, $opendocsMenu);
	}

	/**
	 * renders the pure contents of the menu
	 *
	 * @return string The menu's content
	 */
	public function renderMenu() {
		$openDocuments = $this->openDocs;
		$recentDocuments = $this->recentDocs;
		$entries = array();

		$content = '';
		if (count($openDocuments)) {
			$entries[] = '<li class="dropdown-header">' . $GLOBALS['LANG']->getLL('open_docs', TRUE) . '</li>';
			$i = 0;

			foreach ($openDocuments as $md5sum => $openDocument) {
				$i++;
				$entries[] = $this->renderMenuEntry($openDocument, $md5sum, FALSE, $i == 1);
			}
		}

		// If there are "recent documents" in the list, add them
		if (count($recentDocuments)) {
			$entries[] = '<li class="dropdown-header">' . $GLOBALS['LANG']->getLL('recent_docs', TRUE) . '</li>';
			$i = 0;
			foreach ($recentDocuments as $md5sum => $recentDocument) {
				$i++;
				$entries[] = $this->renderMenuEntry($recentDocument, $md5sum, TRUE, $i == 1);
			}
		}

		if (count($entries)) {
			$content = implode('', $entries);
		} else {
			$content = '<li class="no-docs">' . $GLOBALS['LANG']->getLL('no_docs', TRUE) . '</li>';
		}
		return $content;
	}

	/**
	 * Returns the recent documents list as an array
	 *
	 * @return array All recent documents as list-items
	 */
	public function renderMenuEntry($document, $md5sum, $isRecentDoc = FALSE, $isFirstDoc = FALSE) {
		$table = $document[3]['table'];
		$uid = $document[3]['uid'];
		$record = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecordWSOL($table, $uid);
		if (!is_array($record)) {
			// Record seems to be deleted
			return '';
		}
		$label = htmlspecialchars(strip_tags(htmlspecialchars_decode($document[0])));
		$icon = \TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIconForRecord($table, $record);
		$link = $GLOBALS['BACK_PATH'] . 'alt_doc.php?' . $document[2];
		$pageId = (int)$document[3]['uid'];
		if ($document[3]['table'] !== 'pages') {
			$pageId = (int)$document[3]['pid'];
		}
		$firstRow = '';
		if ($isFirstDoc) {
			$firstRow = ' first-row';
		}
		if (!$isRecentDoc) {
			$title = $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xlf:rm.closeDoc', TRUE);
			// Open document
			$closeIcon = \TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIcon('actions-document-close');
			$entry = '<li class="opendoc' . $firstRow . '">' .
				'<a href="#" onclick="jump(unescape(\'' . htmlspecialchars($link) . '\'), \'web_list\', \'web\', ' . $pageId . '); TYPO3BackendOpenDocs.toggleMenu(); return false;" target="content">' .
				'<div class="close" onclick="return TYPO3BackendOpenDocs.closeDocument(\'' . $md5sum . '\');">' . $closeIcon . '</div>' .
				$icon . ' ' . $label .
				'</a>' .
				'</li>';
		} else {
			// Recently used document
			$entry = '<li class="recentdoc' . $firstRow . '">' .
				'<a href="#" onclick="jump(unescape(\'' . htmlspecialchars($link) . '\'), \'web_list\', \'web\', ' . $pageId . '); TYPO3BackendOpenDocs.toggleMenu(); return false;" target="content">' .
				$icon .
				$label .
				'</a>' .
				'</li>';
		}
		return $entry;
	}

}
