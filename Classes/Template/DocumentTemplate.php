<?php
namespace PHORAX\Flat\Template;

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

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\Utility\IconUtility;
use TYPO3\CMS\Core\Html\HtmlParser;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * TYPO3 Backend Template Class
 *
 * This class contains functions for starting and ending the HTML of backend modules
 * It also contains methods for outputting sections of content.
 * Further there are functions for making icons, links, setting form-field widths etc.
 * Color scheme and stylesheet definitions are also available here.
 * Finally this file includes the language class for TYPO3's backend.
 *
 * After this file $LANG and $TBE_TEMPLATE are global variables / instances of their respective classes.
 * This file is typically included right after the init.php file,
 * if language and layout is needed.
 *
 * Please refer to Inside TYPO3 for a discussion of how to use this API.
 *
 * @author Kasper Skårhøj <kasperYYYY@typo3.com>
 */
class DocumentTemplate extends \TYPO3\CMS\Backend\Template\DocumentTemplate {

	/**
	 * Creates a DYNAMIC tab-menu where the tabs are switched between with DHTML.
	 * Should work in MSIE, Mozilla, Opera and Konqueror. On Konqueror I did find a serious problem: <textarea> fields loose their content when you switch tabs!
	 *
	 * @param array $menuItems Numeric array where each entry is an array in itself with associative keys: "label" contains the label for the TAB, "content" contains the HTML content that goes into the div-layer of the tabs content. "description" contains description text to be shown in the layer. "linkTitle" is short text for the title attribute of the tab-menu link (mouse-over text of tab). "stateIcon" indicates a standard status icon (see ->icon(), values: -1, 1, 2, 3). "icon" is an image tag placed before the text.
	 * @param string $identString Identification string. This should be unique for every instance of a dynamic menu!
	 * @param integer $toggle If "1", then enabling one tab does not hide the others - they simply toggles each sheet on/off. This makes most sense together with the $foldout option. If "-1" then it acts normally where only one tab can be active at a time BUT you can click a tab and it will close so you have no active tabs.
	 * @param boolean $foldout If set, the tabs are rendered as headers instead over each sheet. Effectively this means there is no tab menu, but rather a foldout/foldin menu. Make sure to set $toggle as well for this option.
	 * @param boolean $noWrap If set, tab table cells are not allowed to wrap their content
	 * @param boolean $fullWidth If set, the tabs will span the full width of their position
	 * @param integer $defaultTabIndex Default tab to open (for toggle <=0). Value corresponds to integer-array index + 1 (index zero is "1", index "1" is 2 etc.). A value of zero (or something non-existing) will result in no default tab open.
	 * @param integer $dividers2tabs If set to '1' empty tabs will be remove, If set to '2' empty tabs will be disabled
	 * @return string JavaScript section for the HTML header.
	 */
	public function getDynTabMenu($menuItems, $identString, $toggle = 0, $foldout = FALSE, $noWrap = TRUE, $fullWidth = FALSE, $defaultTabIndex = 1, $dividers2tabs = 2) {
		if (!is_array($menuItems)) {
			return '';
		}

		$this->pageRenderer->loadJquery();
		$this->loadJavascriptLib('../typo3conf/ext/flat/Resources/Public/JavaScript/Bootstrap/tab.js');

		$content = '';

		$tabs = array();
		$divs = array();

		$id = $this->getDynTabMenuId($identString);
		$index = 0;

		foreach ($menuItems as $index => $def) {
			// Need to add one so checking for first index in JavaScript
			// is different than if it is not set at all.
			$index += 1;
			$isEmpty = trim($def['content']) === '' && trim($def['icon']) === '';

			// "Removes" empty tabs
			if ($isEmpty && $dividers2tabs == 1) {
				continue;
			}

			$requiredIcon = '<img name="' . $id . '-' . $index . '-REQ" src="' . $GLOBALS['BACK_PATH'] . 'gfx/clear.gif" class="t3-TCEforms-reqTabImg" alt="" />';

			$tabs[] = '<li class="' . ($isEmpty ? 'disabled ' : '') . ($index === 1 ? 'active' : '') . '">' .
				'<a href="#' . $id . '-' . $index . '" data-toggle="tab">' .
				$def['icon'] .
				($def['label'] ? htmlspecialchars($def['label']) : '&nbsp;') .
				$requiredIcon .
				$this->icons($def['stateIcon']) .
				'</a>' .
				'</li>';

			$divs[] = '<div class="tab-pane fade' . ($index === 1 ? ' in active' : '') . '" id="' . $id . '-' . $index . '">' .
				($def['description'] ? '<p class="c-descr">' . nl2br(htmlspecialchars($def['description'])) . '</p>' : '') .
				$def['content'] .
				'</div>';
		}


		$content .= '<ul class="nav nav-tabs" role="tablist">' . implode('', $tabs) . '</ul>';
		$content .= '<div class="tab-content">' . implode('', $divs) . '</div>';

		return $content;
	}

}
