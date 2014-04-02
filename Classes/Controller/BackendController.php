<?php
namespace PHORAX\Flat\Controller;

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

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * TYPO3 backend
 */
class BackendController extends \TYPO3\CMS\Backend\Controller\BackendController {

	/**
	 * Main function generating the BE scaffolding
	 *
	 * @return void
	 */
	public function render() {
		$this->executeHook('renderPreProcess');

		// Prepare the scaffolding, at this point extension may still add javascript and css
		$logo = GeneralUtility::makeInstance('TYPO3\\CMS\\Backend\\View\\LogoView');

		// Create backend scaffolding
		$backendScaffolding = '
		<div id="typo3-top-container" class="x-hide-display">
			<div id="typo3-module-menu-toogle"><a href="#"><i class="fa fa-bars fa-lg"></i></a></div>
			<div id="typo3-logo">' . $logo->render() . '</div>
			<div id="typo3-top" class="typo3-top-toolbar">' . $this->renderToolbar() . '</div>
		</div>';
		/******************************************************
		 * Now put the complete backend document together
		 ******************************************************/
		foreach ($this->cssFiles as $cssFileName => $cssFile) {
			$this->pageRenderer->addCssFile($cssFile);
			// Load additional css files to overwrite existing core styles
			if (!empty($GLOBALS['TBE_STYLES']['stylesheets'][$cssFileName])) {
				$this->pageRenderer->addCssFile($GLOBALS['TBE_STYLES']['stylesheets'][$cssFileName]);
			}
		}
		if (!empty($this->css)) {
			$this->pageRenderer->addCssInlineBlock('BackendInlineCSS', $this->css);
		}
		foreach ($this->jsFiles as $jsFile) {
			$this->pageRenderer->addJsFile($jsFile);
		}
		$this->generateJavascript();
		$this->pageRenderer->addJsInlineCode('BackendInlineJavascript', $this->js, FALSE);
		$this->loadResourcesForRegisteredNavigationComponents();
		// Add state provider
		$GLOBALS['TBE_TEMPLATE']->setExtDirectStateProvider();
		$states = $GLOBALS['BE_USER']->uc['BackendComponents']['States'];
		// Save states in BE_USER->uc
		$extOnReadyCode = '
			Ext.state.Manager.setProvider(new TYPO3.state.ExtDirectProvider({
				key: "BackendComponents.States",
				autoRead: false
			}));
		';
		if ($states) {
			$extOnReadyCode .= 'Ext.state.Manager.getProvider().initState(' . json_encode($states) . ');';
		}
		$extOnReadyCode .= '
			TYPO3.Backend = new TYPO3.Viewport(TYPO3.Viewport.configuration);
			if (typeof console === "undefined") {
				console = TYPO3.Backend.DebugConsole;
			}
			TYPO3.ContextHelpWindow.init(' . GeneralUtility::quoteJSvalue(BackendUtility::getModuleUrl('help_cshmanual')) . ');';
		$this->pageRenderer->addExtOnReadyCode($extOnReadyCode);
		// Set document title:
		$title = $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'] ? $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'] . ' [TYPO3 CMS ' . TYPO3_version . ']' : 'TYPO3 CMS ' . TYPO3_version;
		$this->content = $backendScaffolding;
		// Renders the module page
		$this->content = $GLOBALS['TBE_TEMPLATE']->render($title, $this->content);
		$hookConfiguration = array('content' => &$this->content);
		$this->executeHook('renderPostProcess', $hookConfiguration);
		echo $this->content;
	}

}
