<?php
namespace PHORAX\Flat\Hooks;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Felix Kopp <felix-source@phorax.com>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  A copy is found in the text file GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

class IconUtility {

	static public $flatSpriteIconName = array(
		't3-icon t3-icon-actions t3-icon-actions-document t3-icon-document-new' => 'fa-plus-square',
		't3-icon t3-icon-actions t3-icon-actions-document t3-icon-document-open' => 'fa-pencil',
		't3-icon t3-icon-actions t3-icon-actions-document t3-icon-document-close' => 'fa-close',
		't3-icon t3-icon-actions t3-icon-actions-document t3-icon-document-info' => 'fa-info-circle',
		't3-icon t3-icon-actions t3-icon-actions-document t3-icon-document-view' => 'fa-play-circle',
		't3-icon t3-icon-actions t3-icon-actions-document t3-icon-document-history-open' => 'fa-history',
		't3-icon t3-icon-actions t3-icon-actions-document t3-icon-document-move' => 'fa-arrows',
		't3-icon t3-icon-actions t3-icon-actions-document t3-icon-document-save' => 'fa-save',

		't3-icon t3-icon-actions t3-icon-actions-window t3-icon-window-open' => 'fa-arrows-alt',

		't3-icon t3-icon-actions t3-icon-actions-system t3-icon-system-refresh' => 'fa-refresh',
		't3-icon t3-icon-actions t3-icon-actions-system t3-icon-system-help-open' => 'fa-question-circle',
		't3-icon t3-icon-actions t3-icon-actions-system t3-icon-system-shortcut-new' => 'fa-star',
		't3-icon t3-icon-actions t3-icon-actions-system t3-icon-system-tree-search-open' => 'fa-search',
		't3-icon t3-icon-actions t3-icon-actions-system t3-icon-system-cache-clear' => 'fa-bolt',

		't3-icon t3-icon-actions t3-icon-actions-system t3-icon-system-extension-download' => 'fa-download',
		't3-icon t3-icon-actions t3-icon-system-extension-import' => 'fa-cloud-download',
		't3-icon t3-icon-actions t3-icon-actions-system t3-icon-system-extension-sqldump' => 'fa-database',
		't3-icon t3-icon-actions t3-icon-actions-system t3-icon-system-extension-install' => 'fa-plus-circle',
		't3-icon t3-icon-actions t3-icon-actions-system t3-icon-system-extension-uninstall' => 'fa-minus-square',
		't3-icon t3-icon-actions t3-icon-actions-system t3-icon-system-extension-download ' => 'fa-cloud-download',
		't3-icon t3-icon-actions t3-icon-actions-system t3-icon-system-extension-configure' => 'fa-gear',
		't3-icon t3-icon-extensions t3-icon-extensions-extensionmanager t3-icon-extensionmanager-update-script' => 'fa-refresh',

		't3-icon t3-icon-actions t3-icon-system-extension-import' => 'fa-cloud-download',

		't3-icon t3-icon-mimetypes t3-icon-mimetypes-word t3-icon-word' => 'fa-file-world-o',
		't3-icon t3-icon-mimetypes t3-icon-mimetypes-pdf t3-icon-pdf' => 'fa-file-pdf-o',
		't3-icon t3-icon-mimetypes t3-icon-mimetypes-text t3-icon-text-html' => 'fa-file-html-o',

		't3-icon t3-icon-actions t3-icon-actions-edit t3-icon-edit-pick-date' => 'fa-calendar',
		't3-icon t3-icon-actions t3-icon-actions-edit t3-icon-edit-delete' => 'fa-trash',
		't3-icon t3-icon-actions t3-icon-actions-edit t3-icon-edit-hide' => 'fa-circle',
		't3-icon t3-icon-actions t3-icon-actions-edit t3-icon-edit-unhide' => 'fa-circle-thin',
		't3-icon t3-icon-actions t3-icon-actions-edit t3-icon-edit-cut' => 'fa-cut',
		't3-icon t3-icon-actions t3-icon-actions-edit t3-icon-edit-copy' => 'fa-copy',
		't3-icon t3-icon-actions t3-icon-actions-edit t3-icon-edit-paste' => 'fa-paste',
		't3-icon t3-icon-actions t3-icon-actions-edit t3-icon-edit-upload' => 'fa-upload',

		't3-icon t3-icon-actions t3-icon-actions-page t3-icon-page-move' => 'fa-arrows',
		't3-icon t3-icon-actions t3-icon-actions-page t3-icon-page-open' => 'fa-pencil',
		't3-icon t3-icon-actions t3-icon-actions-page t3-icon-page-new' => 'fa-plus-square',

		't3-icon t3-icon-status t3-icon-status-status t3-icon-status-locked' => 'fa-lock',
		't3-icon t3-icon-status t3-icon-status-status t3-icon-status-readonly' => 'fa-lock',

		't3-icon t3-icon-actions t3-icon-actions-view t3-icon-view-table-expand' => 'fa-angle-double-right',

		't3-icon t3-icon-actions t3-icon-actions-move t3-icon-move-up' => 'fa-arrow-up',
		't3-icon t3-icon-actions t3-icon-actions-move t3-icon-move-down' => 'fa-arrow-down',
		't3-icon t3-icon-actions t3-icon-actions-move t3-icon-move-left' => 'fa-arrow-left',
		't3-icon t3-icon-actions t3-icon-actions-move t3-icon-move-right' => 'fa-arrow-right',

		't3-icon t3-icon-status t3-icon-status-status t3-icon-status-permission-granted' => 'fa-check-circle-o',
		't3-icon t3-icon-status t3-icon-status-status t3-icon-status-permission-denied' => 'fa-minus-square',
		't3-icon t3-icon-status t3-icon-status-warning t3-icon-warning-lock' => 'fa-lock',

		't3-icon t3-icon-actions t3-icon-actions-view t3-icon-view-go-up' => 'fa-level-up',
		't3-icon t3-icon-actions t3-icon-actions-view t3-icon-view-go-back' => 'fa-angle-double-left',
		't3-icon t3-icon-actions t3-icon-actions-view t3-icon-view-go-forward' => 'fa-angle-double-right',

		't3-icon t3-icon-actions t3-icon-actions-view t3-icon-view-paging-first' => 'fa-step-backward',
		't3-icon t3-icon-actions t3-icon-actions-view t3-icon-view-paging-previous' => 'fa-arrow-left',
		't3-icon t3-icon-actions t3-icon-actions-view t3-icon-view-paging-next' => 'fa-arrow-right',
		't3-icon t3-icon-actions t3-icon-actions-view t3-icon-view-paging-last' => 'fa-step-forward',

#		't3-icon t3-icon-actions t3-icon-actions-system t3-icon-system-backend-user-switch' => '',

		't3-icon t3-icon-status t3-icon-status-dialog t3-icon-dialog-error' => 'fa-exclamation-triangle'
	);

	/**
	 * Hook to manipulate IconUtility html output code
	 *
	 * @param array $tagAttributes
	 * @param null $innerHtml
	 * @param null $tagName
	 */
	public function buildSpriteHtmlIconTag(array &$tagAttributes, &$innerHtml = NULL, &$tagName = NULL) {
		$class = self::$flatSpriteIconName[$tagAttributes['class']];
		if ($class) {
			$tagAttributes['class'] = 't3-icon-fa fa fa-lg fa-fw ' . $class;
			// Keep tag name - since JavaScript selectors might depend on <span>
#			$tagName = 'i';
		}
	}

}