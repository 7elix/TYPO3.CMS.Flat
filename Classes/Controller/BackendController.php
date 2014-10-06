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
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * TYPO3 backend
 */
class BackendController extends \TYPO3\CMS\Backend\Controller\BackendController {

	/**
	 * @var array
	 */
	protected $modules = array();

	/**
	 * @var \SplObjectStorage
	 */
	protected $moduleStorage;

	/**
	 * @var string
	 */
	protected $backendTemplatePath;

	/**
	 * @var \TYPO3\CMS\Fluid\View\StandaloneView $template
	 */
	protected $template;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->debug = (int)$GLOBALS['TYPO3_CONF_VARS']['BE']['debug'] === 1;

		$this->backendTemplatePath = ExtensionManagementUtility::extPath('flat') . 'Resources/Private/Templates/Backend/';

		$this->moduleLoader = GeneralUtility::makeInstance('TYPO3\\CMS\\Backend\\Module\\ModuleLoader');
		$this->moduleLoader->load($GLOBALS['TBE_MODULES']);

		$this->moduleMenu = GeneralUtility::makeInstance('TYPO3\\CMS\\Backend\\View\\ModuleMenuView');

		$this->pageRenderer = $GLOBALS['TBE_TEMPLATE']->getPageRenderer();
		$this->pageRenderer->loadJquery();

		$this->template = GeneralUtility::makeInstance('TYPO3\\CMS\\Fluid\\View\\StandaloneView');

		// Add default BE javascript
		$this->js = '';
		$this->jsFiles = array(
#			'common' => 'sysext/backend/Resources/Public/JavaScript/common.js',
			'locallang' => $this->getLocalLangFileName(),
#			'modernizr' => 'contrib/modernizr/modernizr.min.js',
			'md5' => 'sysext/backend/Resources/Public/JavaScript/md5.js',
#			'toolbarmanager' => 'sysext/backend/Resources/Public/JavaScript/toolbarmanager.js',
#			'modulemenu' => 'sysext/backend/Resources/Public/JavaScript/modulemenu.js',
#			'iecompatibility' => 'sysext/backend/Resources/Public/JavaScript/iecompatibility.js',
			'evalfield' => 'sysext/backend/Resources/Public/JavaScript/jsfunc.evalfield.js',
			'flashmessages' => 'sysext/backend/Resources/Public/JavaScript/flashmessages.js',
#			'tabclosemenu' => 'js/extjs/ux/ext.ux.tabclosemenu.js',
			'notifications' => 'sysext/backend/Resources/Public/JavaScript/notifications.js',

			'backend' => 'sysext/backend/Resources/Public/JavaScript/backend.js',
			'loginrefresh' => 'sysext/backend/Resources/Public/JavaScript/loginrefresh.js',
#			'debugPanel' => 'js/extjs/debugPanel.js',
#			'viewport' => 'js/extjs/viewport.js',
#			'iframepanel' => 'sysext/backend/Resources/Public/JavaScript/iframepanel.js',
#			'backendcontentiframe' => 'js/extjs/backendcontentiframe.js',
#			'modulepanel' => 'js/extjs/modulepanel.js',
#			'viewportConfiguration' => 'js/extjs/viewportConfiguration.js',
			'util' => 'sysext/backend/Resources/Public/JavaScript/util.js',

            'bootstrap-dropdown' => '../typo3conf/ext/flat/Resources/Public/JavaScript/Bootstrap/dropdown.js',
			'bootstrap-modal' => '../typo3conf/ext/flat/Resources/Public/JavaScript/Bootstrap/modal.js',

			'typo3-Routing' => '../typo3conf/ext/flat/Resources/Public/JavaScript/TYPO3/Routing.js',
			'typo3-Backend' => '../typo3conf/ext/flat/Resources/Public/JavaScript/TYPO3/Backend.js',
			'typo3-ModuleMenu' => '../typo3conf/ext/flat/Resources/Public/JavaScript/TYPO3/ModuleMenu.js',
			'typo3-Viewport' => '../typo3conf/ext/flat/Resources/Public/JavaScript/TYPO3/Viewport.js',

			'typo3-Deprecated' => '../typo3conf/ext/flat/Resources/Public/JavaScript/TYPO3/Deprecated.js'
		);

		if ($this->debug) {
			unset($this->jsFiles['loginrefresh']);
		}

		// Add default BE css
#		$this->pageRenderer->addCssLibrary('contrib/normalize/normalize.css', 'stylesheet', 'all', '', TRUE, TRUE);

		$this->css = '';
		$this->cssFiles = array();
		$this->toolbarItems = array();

		$this->initializeCoreToolbarItems();
		$this->initializeModuleMenuStorage();

		$this->menuWidth = 200;

		if (isset($GLOBALS['TBE_STYLES']['dims']['leftMenuFrameW']) && (int)$GLOBALS['TBE_STYLES']['dims']['leftMenuFrameW'] != (int)$this->menuWidth) {
			$this->menuWidth = (int)$GLOBALS['TBE_STYLES']['dims']['leftMenuFrameW'];
		}
		$this->executeHook('constructPostProcess');
	}

	/**
	 * Load Module Menu storage
	 *
	 * @return void
	 */
	protected function initializeModuleMenuStorage() {
		/** @var $moduleRepository \TYPO3\CMS\Backend\Domain\Repository\Module\BackendModuleRepository */
		$moduleRepository = GeneralUtility::makeInstance('TYPO3\\CMS\\Backend\\Domain\\Repository\\Module\\BackendModuleRepository');

		/** @var \SplObjectStorage */
		$moduleStorage = $moduleRepository->loadAllowedModules();
		$this->moduleStorage = \PHORAX\Flat\Utility\ModuleMenuUtility::restructureModules($moduleStorage);
	}

	/**
	 * Main function generating the BE scaffolding
	 *
	 * @return void
	 */
	public function render() {
#		$this->executeHook('renderPreProcess');

#		// Prepare the scaffolding, at this point extension may still add javascript and css
#		$logo = GeneralUtility::makeInstance('TYPO3\\CMS\\Backend\\View\\LogoView');

		// Create backend scaffolding
		$backendScaffolding = '
			<div class="scaffolding-aside scaffolding-aside-sidebar">
				<div class="scaffolding-aside-header">
					Modules
				</div>
				<div class="scaffolding-navigation" id="typo3-module-menu">' .
					$this->renderModuleMenu($this->modules) .
				'</div>
			</div>

			<div class="scaffolding-aside scaffolding-aside-meta">
				<div class="scaffolding-aside-header">
					Meta
				</div>
				<nav class="navbar navbar-inverse navbar-right navbar-meta" role="navigation">
					<ul class="nav navbar-nav navbar-inverse" data-typo3-role="typo3-module-menu">' .
						$this->renderHelpMenu() .
						$this->renderUserMenu() .
						$this->renderToolbar() .
						$this->renderLiveSearch() .
					'</ul>
				</nav>
			</div>

			<div class="scaffolding-page" id="scaffolding-page">
				<div class="scaffolding-top" id="typo3-topbar">
					<a class="scaffolding-top-toggle scaffolding-top-toggle-modules" onClick="
						document.getElementsByTagName(\'body\')[0].classList.remove(\'scaffolding-open-meta\');
						document.getElementsByTagName(\'body\')[0].classList.toggle(\'scaffolding-open-modules\');
						return false;
					" href="#">
						<i class="fa fa-lg"></i>
						<span class="sr-only">Toggle Modules</span>
					</a>
					<a class="scaffolding-top-toggle scaffolding-top-toggle-meta" onClick="
						document.getElementsByTagName(\'body\')[0].classList.remove(\'scaffolding-open-modules\');
						document.getElementsByTagName(\'body\')[0].classList.toggle(\'scaffolding-open-meta\');
						return false;
					" href="#">
						<i class="fa fa-lg"></i>
						<span class="sr-only">Toggle Meta</span>
					</a>
					<a class="scaffolding-top-site" href="#">' . $this->renderLogo() . '</a>
				</div>

				<div class="scaffolding-main">
					<div class="container-fluid">
						<div class="row">
							<div class="col-xs-3">
								<!-- Page navigation -->
								<iframe src="" id="typo3-navigation" name="typo3-navigation" border="0" frameborder="0"></iframe>
							</div>
							<div class="col-xs-9">
								<!-- Content -->
								<iframe src="" id="typo3-content" name="typo3-content" border="0" frameborder="0"></iframe>
							</div>
						</div>
					</div>
				</div>
			</div>' .
			$this->renderLiveSearchModal()
		;

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
#		$extOnReadyCode .= '
#			TYPO3.Backend = new TYPO3.Viewport(TYPO3.Viewport.configuration);
#			if (typeof console === "undefined") {
#				console = TYPO3.Backend.DebugConsole;
#			}
#			TYPO3.ContextHelpWindow.init(' . GeneralUtility::quoteJSvalue(BackendUtility::getModuleUrl('help_cshmanual')) . ');';
		$this->pageRenderer->addExtOnReadyCode($extOnReadyCode);

		// Set document title:
		$title = $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'] ? $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'] . ' [TYPO3 CMS ' . TYPO3_version . ']' : 'TYPO3 CMS ' . TYPO3_version;

		// Renders the module page
		$this->content = $GLOBALS['TBE_TEMPLATE']->render($title, $backendScaffolding);

#		$hookConfiguration = array('content' => &$this->content);
#		$this->executeHook('renderPostProcess', $hookConfiguration);

		echo $this->content;
	}

	/**
	 * Render ModuleMenu with fluid
	 *
	 * @param array
	 * @return string
	 */
	protected function renderModuleMenu() {
		$templatePathAndFilename = $this->backendTemplatePath . 'RenderModuleMenu.html';
		$this->template->setTemplatePathAndFilename($templatePathAndFilename);
		$this->template->assign('moduleMenu', $this->moduleStorage);
		return $this->template->render();
	}

	/**
	 * @return string
	 */
	protected function renderLogo() {
		$this->logo = 'gfx/typo3-topbar@2x.png';

		$imgInfo = getimagesize(PATH_site . TYPO3_mainDir . $this->logo);
		$imgUrl = $this->logo;

		// Overwrite with custom logo
		if ($GLOBALS['TBE_STYLES']['logo']) {
			$imgInfo = @getimagesize(GeneralUtility::resolveBackPath((PATH_typo3 . $GLOBALS['TBE_STYLES']['logo']), 3));
			$imgUrl = $GLOBALS['TBE_STYLES']['logo'];
		}

		// High-res?
		$width = $imgInfo[0];
		$height = $imgInfo[1];

		if (strpos($imgUrl, '@2x.')) {
			$width = $width/2;
			$height = $height/2;
		}

		return '<img src="' . $imgUrl . '" width="' . $width . '" height="' . $height . '" title="TYPO3 Content Management System" alt="" />';
	}

	/**
	 * Render Live search section
	 *
	 * @return string
	 */
	protected function renderLiveSearch() {
		if (!array_key_exists('liveSearch', $this->toolbarItems)) {
			return '';
		}

		$toolbarItem = $this->toolbarItems['liveSearch'];
		$additionalAttributes = trim($toolbarItem->getAdditionalAttributes());

		return '<li ' . $additionalAttributes . '>' . $toolbarItem->render() . '</li>';
	}

	/**
	 * Render modal dialog
	 *
	 * @return string
	 * @TODO: Load as AJAX modal
	 */
	protected function renderLiveSearchModal() {
		$content = '';

		if (!array_key_exists('liveSearch', $this->toolbarItems)) {
			return '';
		}

		$content = '
			<!-- Live Search -->
			<div class="modal fade" id="typo3-live-search-modal">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
							<h4 class="modal-title" id="myModalLabel"><i class=""></i> Search</h4>
						</div>
						<div class="modal-body">' .

		$this->toolbarItems['liveSearch']->render() .

						'</div>
						<div class="modal-footer">
						        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						        <button type="button" class="btn btn-primary">Save changes</button>
						      </div>
					</div>
				</div>
			</div>';

		return $content;
	}

	/**
	 * Renders toolbar-items
	 *
	 * @return string top toolbar elements as HTML
	 */
	protected function renderToolbar() {
		$toolbar = '';

		foreach ($this->toolbarItems as $key => $toolbarItem) {
			// Skip liveSearch. Rendered by ->renderLiveSearch()
			if ($key === 'liveSearch') {
				continue;
			}

			$menu = $toolbarItem->render();

			// @TOD: Find a better solution for CSS requirements
			$menu = preg_replace('/display:.+none(;)*/', '', $menu);
			$menu = str_replace('toolbar-item-menu', 'dropdown-menu', $menu);

			if ($menu) {
				$additionalAttributes = $toolbarItem->getAdditionalAttributes();

				if (strpos($additionalAttributes, 'class="') !== FALSE) {
					$additionalAttributes = str_replace('class="', 'class="dropdown ', trim($toolbarItem->getAdditionalAttributes()));
				} else {
					$additionalAttributes .= ' class="dropdown"';
				}

				$toolbar .= '<li ' . $additionalAttributes . ' >' . $menu . '</li>';
			}
		}

		return $toolbar;
	}

	/**
	 * Render User menu dropdown-menu
	 *
	 * @param array
	 * @return string
	 */
	protected function renderUserMenu() {
#		$icon = \TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIcon('status-user-' . ($GLOBALS['BE_USER']->isAdmin() ? 'admin' : 'backend'));
		$icon = '<i class="fa fa-lg fa-inline fa-user"></i>';

		$realName = $GLOBALS['BE_USER']->user['realName'];
		$username = $GLOBALS['BE_USER']->user['username'];
		$label = $realName ?: $username;
		$title = $username;

		// Superuser mode
#		if ($GLOBALS['BE_USER']->user['ses_backuserid']) {
#			$title = $GLOBALS['LANG']->getLL('switchtouser') . ': ' . $username;
#			$label = $GLOBALS['LANG']->getLL('switchtousershort') . ' ' . ($realName ? $realName . ' (' . $username . ')' : $username);
#		}

		$content = '';
		$content .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="' . htmlspecialchars($title) . '">' . $icon . ' <span class="hidden-sm">' . htmlspecialchars($label) . '</span></a>';
		$content .= '<ul class="dropdown-menu" role="menu">';

		$this->moduleStorage->rewind();
		foreach ($this->moduleStorage as $moduleMenuSection) {
			if ($moduleMenuSection->getName() !== 'user') {
				continue;
			}

			foreach ($moduleMenuSection->getChildren() as $backendModule) {
				$icon = $backendModule->getIcon();
				$content .= '<li title="' . $backendModule->getDescription() . '" data-path="' . $backendModule->getName() . '">';
				$content .= '<a href="#" onClick="TYPO3.Backend.openModule(\'' . $backendModule->getName() . '\');" title="' . $backendModule->getDescription() . '">';
				$content .= '<span class="t3-app-icon t3-app-icon-inline"><img src="' . $icon['filename'] . '"></span> ';
				$content .= $backendModule->getTitle();
				$content .= '</a>';
				$content .= '</li>';
			}
		}

		$content .= '<li class="divider"></li>';

		$buttonLabel = $GLOBALS['BE_USER']->user['ses_backuserid'] ? 'LLL:EXT:lang/locallang_core.xlf:buttons.exit' : 'LLL:EXT:lang/locallang_core.xlf:buttons.logout';
		$content .= '<li><a href="logout.php" target="_top"><span class="btn"><i class="fa fa-lg fa-inline fa-power-off"></i> ' . $GLOBALS['LANG']->sL($buttonLabel, TRUE) . '</span></a></li>';

		$content .= '</ul>';
		return '<li class="dropdown">' . $content . '</li>';
	}

	/**
	 * Render Help menu dropdown-menu
	 *
	 * @param array
	 * @return string
	 */
	public function renderHelpMenu() {
		$content = '';
		$content .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-lg fa-inline fa-question-circle"></i> <span class="visible-xs-inline">Help</span></a></a>';
		$content .= '<ul class="dropdown-menu">';

		$this->moduleStorage->rewind();
		foreach ($this->moduleStorage as $moduleMenuSection) {
			if ($moduleMenuSection->getName() !== 'help') {
				continue;
			}

			foreach ($moduleMenuSection->getChildren() as $backendModule) {
				$icon = $backendModule->getIcon();
				$content .= '<li title="' . $backendModule->getDescription() . '" data-path="' . $backendModule->getName() . '">';
				$content .= '<a href="#" onClick="TYPO3.Backend.openModule(\'' . $backendModule->getName() . '\');" title="' . $backendModule->getDescription() . '">';
				$content .= '<span class="t3-app-icon t3-app-icon-inline"><img src="' . $icon['filename'] . '"></span> ';
				$content .= $backendModule->getTitle();
				$content .= '</a>';
				$content .= '</li>';
			}
		}

		$content .= '</ul>';

		return '<li class="dropdown">' . $content . '</li>';
	}

	/**
	 * Generates the JavaScript code for the backend.
	 *
	 * @return void
	 */
	protected function generateJavascript() {
		$pathTYPO3 = GeneralUtility::dirname(GeneralUtility::getIndpEnv('SCRIPT_NAME')) . '/';

		// If another page module was specified, replace the default Page module with the new one
		$newPageModule = trim($GLOBALS['BE_USER']->getTSConfigVal('options.overridePageModule'));
		$pageModule = BackendUtility::isModuleSetInTBE_MODULES($newPageModule) ? $newPageModule : 'web_layout';
		if (!$GLOBALS['BE_USER']->check('modules', $pageModule)) {
			$pageModule = '';
		}

#		$menuFrameName = 'menu';
#		if ($GLOBALS['BE_USER']->uc['noMenuMode'] === 'icons') {
#			$menuFrameName = 'topmenuFrame';
#		}

		// Determine security level from conf vars and default to super challenged
		if ($GLOBALS['TYPO3_CONF_VARS']['BE']['loginSecurityLevel']) {
			$this->loginSecurityLevel = $GLOBALS['TYPO3_CONF_VARS']['BE']['loginSecurityLevel'];
		} else {
			$this->loginSecurityLevel = 'superchallenged';
		}

		$t3Configuration = array(
			'siteUrl' => GeneralUtility::getIndpEnv('TYPO3_SITE_URL'),
			'PATH_typo3' => $pathTYPO3,
			'PATH_typo3_enc' => rawurlencode($pathTYPO3),
			'username' => htmlspecialchars($GLOBALS['BE_USER']->user['username']),
			'uniqueID' => GeneralUtility::shortMD5(uniqid('')),
			'securityLevel' => $this->loginSecurityLevel,
			'TYPO3_mainDir' => TYPO3_mainDir,
			'pageModule' => $pageModule,
			'inWorkspace' => $GLOBALS['BE_USER']->workspace !== 0 ? 1 : 0,
			'workspaceFrontendPreviewEnabled' => $GLOBALS['BE_USER']->user['workspace_preview'] ? 1 : 0,
			'veriCode' => $GLOBALS['BE_USER']->veriCode(),
			'denyFileTypes' => PHP_EXTENSIONS_DEFAULT,
#			'moduleMenuWidth' => $this->menuWidth - 1,
			'topBarHeight' => isset($GLOBALS['TBE_STYLES']['dims']['topFrameH']) ? (int)$GLOBALS['TBE_STYLES']['dims']['topFrameH'] : 30,
			'showRefreshLoginPopup' => isset($GLOBALS['TYPO3_CONF_VARS']['BE']['showRefreshLoginPopup']) ? (int)$GLOBALS['TYPO3_CONF_VARS']['BE']['showRefreshLoginPopup'] : FALSE,
			'listModulePath' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('recordlist') . 'mod1/',
			'debugInWindow' => $GLOBALS['BE_USER']->uc['debugInWindow'] ? 1 : 0,
			'ContextHelpWindows' => array(
				'width' => 600,
				'height' => 400
			),
		);

			$this->js .= '
			if (typeof TYPO3 === undefined) {
				window[\'TYPO3\'] = {};
			}

			TYPO3.Configuration = ' . json_encode($t3Configuration) . ';
			TYPO3.configuration = TYPO3.Configuration;

			TYPO3.RoutingConfiguration = ' . json_encode($this->moduleMenu->getRawModuleData()) . ';

			/**
			 * TypoSetup object.
			 */
			function typoSetup() {	//
				this.PATH_typo3 = TYPO3.configuration.PATH_typo3;
				this.PATH_typo3_enc = TYPO3.configuration.PATH_typo3_enc;
				this.username = TYPO3.configuration.username;
				this.uniqueID = TYPO3.configuration.uniqueID;
				this.navFrameWidth = 0;
				this.securityLevel = TYPO3.configuration.securityLevel;
				this.veriCode = TYPO3.configuration.veriCode;
				this.denyFileTypes = TYPO3.configuration.denyFileTypes;
			}

			var TS = new typoSetup();' .

			$this->setStartupModule();
			$this->handlePageEditing();
	}

}
