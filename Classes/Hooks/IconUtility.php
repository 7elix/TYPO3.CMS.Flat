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
		'actions-document-new' => 'fa-plus-square',
		'actions-document-open' => 'fa-pencil',
		'actions-document-info' => 'fa-info-circle',
		'actions-document-view' => 'fa-play-circle',
		'actions-document-history-open' => 'fa-history',
		'actions-document-move' => 'fa-arrows',
		'actions-document-save' => 'fa-save',

		'actions-system-refresh' => 'fa-refresh',
		'actions-system-help-open' => 'fa-question-circle',
		't3-icon t3-icon-actions t3-icon-actions-system t3-icon-system-shortcut-new' => 'fa-star',
		'actions-system-tree-search-open' => 'fa-search',
		'actions-system-extension-download' => 'fa-download',
		'actions-system-cache-clear' => 'fa-bolt',

		't3-icon t3-icon-actions t3-icon-actions-edit t3-icon-edit-pick-date' => 'fa-calendar',
		't3-icon t3-icon-actions t3-icon-actions-edit t3-icon-edit-delete' => 'fa-remove',
		't3-icon t3-icon-actions t3-icon-actions-edit t3-icon-edit-hide' => 'fa-circle',
		't3-icon t3-icon-actions t3-icon-actions-edit t3-icon-edit-unhide' => 'fa-circle-thin',
		't3-icon t3-icon-actions t3-icon-actions-edit t3-icon-edit-cut' => 'fa-cut',
		't3-icon t3-icon-actions t3-icon-actions-edit t3-icon-edit-copy' => 'fa-copy',
		't3-icon t3-icon-actions t3-icon-actions-edit t3-icon-edit-paste' => 'fa-paste',
		't3-icon t3-icon-actions t3-icon-actions-edit t3-icon-edit-upload' => 'fa-upload',

		'actions-page-move' => 'fa-arrows',
		'actions-page-open' => 'fa-pencil',
		'actions-page-new' => 'fa-plus-square',

		'status-status-locked' => 'fa-lock',

		'actions-move-up' => 'fa-arrow-up',
		'actions-move-down' => 'fa-arrow-down',
		'actions-move-left' => 'fa-arrow-left',
		'actions-move-right' => 'fa-arrow-right',

		'actions-view-go-up' => 'fa-level-up',
		'actions-view-go-back' => 'fa-angle-double-left'
	);

	public function buildSpriteHtmlIconTag(array &$tagAttributes, &$innerHtml = NULL, &$tagName = NULL) {

		$class = self::$flatSpriteIconName[$tagAttributes['class']];

		if ($class) {
			unset($tagAttributes);

			$tagAttributes[] = array(
				'attribute' => 'class',
				'value' => 't3-icon-fa fa fa-lg ' . $class
			);

			$innerHtml = '';
			$tagName = 'i';

		}
	}

}