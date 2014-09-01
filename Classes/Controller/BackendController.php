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
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * TYPO3 backend
 */
class BackendController extends \TYPO3\CMS\Backend\Controller\BackendController {

	/**
	 * @var array
	 */
	protected $modules = array();

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
		$this->menuWidth = 200;

		if (isset($GLOBALS['TBE_STYLES']['dims']['leftMenuFrameW']) && (int)$GLOBALS['TBE_STYLES']['dims']['leftMenuFrameW'] != (int)$this->menuWidth) {
			$this->menuWidth = (int)$GLOBALS['TBE_STYLES']['dims']['leftMenuFrameW'];
		}
		$this->executeHook('constructPostProcess');

		$this->modules = $this->restructureModules($this->moduleMenu->getRawModuleData());
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
			<nav class="navbar navbar-inverse" role="navigation" id="typo3-topbar">
				<div class="container-fluid">
					<div class="navbar-header">
						<a class="navbar-brand" href="#">' . $this->renderLogo() . '</a>
					</div>

					<ul class="nav navbar-nav navbar-right collapse navbar-collapse" data-typo3-role="typo3-module-menu">' .
						$this->renderToolbar($this->modules) .
						$this->renderUserMenu($this->modules) .
						$this->renderHelpMenu($this->modules) .
					'</ul>

					<div class="navbar-left collapse navbar-collapse">
						<div class="" id="typo3-module-menu">
							<ul class="nav navbar-nav" data-typo3-role="typo3-module-menu">' .
								$this->renderModuleMenu($this->modules) .
							'</ul>
						</div>
					</div>

				</div>
			</nav>

			<!-- Content -->
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
	 * @param array
	 * @return string
	 */
	protected function renderModuleMenu(array $moduleMenu) {

		// remove unneeded items from module menu
		unset($moduleMenu['modmenu_user']);
		unset($moduleMenu['modmenu_help']);

		$templatePathAndFilename = $this->backendTemplatePath . 'RenderModuleMenu.html';
		$this->template->setTemplatePathAndFilename($templatePathAndFilename);
		$this->template->assign('moduleMenu', $moduleMenu);

		return $this->template->render();
	}

	/**
	 * @return string
	 */
	protected function renderLogo() {

		$image = array();

		$this->logo = 'gfx/typo3-topbar@2x.png';

		$imgInfo = getimagesize(PATH_site . TYPO3_mainDir . $this->logo);
		$image['url'] = PATH_typo3 . $this->logo;

		// Overwrite with custom logo
		if ($GLOBALS['TBE_STYLES']['logo']) {
			$imgInfo = @getimagesize(GeneralUtility::resolveBackPath((PATH_typo3 . $GLOBALS['TBE_STYLES']['logo']), 3));
			$image['url'] = $GLOBALS['TBE_STYLES']['logo'];
		}

		// High-res?
		$image['width'] = $imgInfo[0];
		$image['height'] = $imgInfo[1];

		if (strpos($image['url'], '@2x.')) {
			$image['width'] = $image['width']/2;
			$image['height'] = $image['height']/2;
		}

		$templatePathAndFilename = $this->backendTemplatePath . 'RenderLogo.html';
		$this->template->setTemplatePathAndFilename($templatePathAndFilename);
		$this->template->assign('image', $image);
		return $this->template->render();
	}

	/**
	 * Render modal dialog open/close button
	 *
	 * @return string
	 */
	protected function renderLiveSearchButton() {
		return '<li><a href="#" class="btn btn-default" data-toggle="modal" data-target="#typo3-live-search-modal"><i class="glyphicon glyphicon-search"></i></button></li>';
	}

	/**
	 * Render modal dialog
	 *
	 * @return string
	 * @TODO: Load as AJAX modal
	 */
	protected function renderLiveSearchModal() {

		if (!array_key_exists('liveSearch', $this->toolbarItems)) {
			return '';
		}

		$content = '';

		$content .= '
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
	 * Renders the items in the top toolbar
	 *
	 * @return string top toolbar elements as HTML
	 */
	protected function renderToolbar() {
		// Move search to last position
		if (array_key_exists('liveSearch', $this->toolbarItems)) {
#			$search = $this->toolbarItems['liveSearch'];
#			unset($this->toolbarItems['liveSearch']);
#			$this->toolbarItems['liveSearch'] = $search;
		}

		$toolbar = '';
		$i = 0;

		$numberOfToolbarItems = count($this->toolbarItems);
		foreach ($this->toolbarItems as $key => $toolbarItem) {
			$i++;
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
	 * Gets the label of the BE user currently logged in
	 *
	 * @return string Html
	 */
	protected function getLoggedInUserLabel() {
	}

	/**
	 * @param array
	 * @return string
	 */
	protected function renderUserMenu(array $moduleMenu) {
#		$icon = \TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIcon('status-user-' . ($GLOBALS['BE_USER']->isAdmin() ? 'admin' : 'backend'));
		$icon = '<i class="fa fa-lg fa-user"></i>';

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
		$content .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="' . htmlspecialchars($title) . '">' . $icon . ' <span class="hidden-xs hidden-sm">' . htmlspecialchars($label) . '</span></a>';
		$content .= '<ul class="dropdown-menu" role="menu">';

		foreach ($moduleMenu as $moduleMenuSection) {
			if ($moduleMenuSection['name'] !== 'user') {
				continue;
			}

			foreach ($moduleMenuSection['subitems'] as $moduleItem) {
				$content .= '<li title="' . $moduleItem['description'] . '" data-path="' . $moduleItem['name'] . '">';
				$content .= '<a href="#" onClick="TYPO3.Backend.openModule(\'' . $moduleItem['name'] . '\');" title="' . $moduleItem['description'] . '">';
				$content .= '<span class="t3-app-icon"><img src="' . $moduleItem['icon']['filename'] . '"></span> ';
				$content .= $moduleItem['title'];
				$content .= '</a>';
				$content .= '</li>';
			}
		}

		$content .= '<li role="presentation" class="divider"></li>';

		$buttonLabel = $GLOBALS['BE_USER']->user['ses_backuserid'] ? 'LLL:EXT:lang/locallang_core.xlf:buttons.exit' : 'LLL:EXT:lang/locallang_core.xlf:buttons.logout';
		$content .= '<li><a href="logout.php" target="_top">' . $GLOBALS['LANG']->sL($buttonLabel, TRUE) . '</a></li>';

		$content .= '</ul>';
		return '<li class="dropdown">' . $content . '</li>';
	}

	/**
	 * @param array
	 * @return string
	 */
	public function renderHelpMenu(array $moduleMenu) {
		$content = '';
		$content .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-lg fa-question-circle"></i></a></a>';
		$content .= '<ul class="dropdown-menu">';

		foreach ($moduleMenu as $moduleMenuSection) {
			if ($moduleMenuSection['name'] !== 'help') {
				continue;
			}

			if (is_array($moduleMenuSection['subitems'])) {
				foreach ($moduleMenuSection['subitems'] as $moduleItem) {
					$content .= '<li title="' . $moduleItem['description'] . '" data-path="' . $moduleItem['name'] . '">';
					$content .= '<a href="#" onClick="TYPO3.Backend.openModule(\'' . $moduleItem['name'] . '\');" title="' . $moduleItem['description'] . '">';
					$content .= '<span class="t3-app-icon"><img src="' . $moduleItem['icon']['filename'] . '"></span> ';
					$content .= $moduleItem['title'];
					$content .= '</a>';
					$content .= '</li>';
				}
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

	/**
	 * Manipulate and restructure module menu configuration
	 *
	 * @param array $moduleConfiguration
	 * @return array
	 */
	protected function restructureModules(array $moduleConfiguration) {
		$finalModuleConfiguration = array();

		/**
		 * Present
		 */
		$finalModuleConfiguration['modmenu_present'] = array(
			'name' => 'present',
			'title' => 'Present',
			'subitems' => array(
				'web_layout_tab' => $moduleConfiguration['modmenu_web']['subitems']['web_layout_tab'],
				'web_ViewpageView_tab' => $moduleConfiguration['modmenu_web']['subitems']['web_ViewpageView_tab'],
				'file_list_tab' => $moduleConfiguration['modmenu_file']['subitems']['file_list_tab'],
				'web_ts_tab' => $moduleConfiguration['modmenu_web']['subitems']['web_ts_tab'],
				'tools_isearch_tab' => $moduleConfiguration['modmenu_tools']['subitems']['tools_isearch_tab'],
				'web_txformhandlermoduleM1_tab' => $moduleConfiguration['modmenu_web']['subitems']['web_txformhandlermoduleM1_tab']
			)
		);

		unset($moduleConfiguration['modmenu_web']['subitems']['web_layout_tab']);
		unset($moduleConfiguration['modmenu_web']['subitems']['web_ViewpageView_tab']);
		unset($moduleConfiguration['modmenu_file']['subitems']['file_list_tab']);
		unset($moduleConfiguration['modmenu_web']['subitems']['web_ts_tab']);
		unset($moduleConfiguration['modmenu_tools']['subitems']['tools_isearch_tab']);
		unset($moduleConfiguration['modmenu_web']['subitems']['web_txformhandlermoduleM1_tab']);

		/**
		 * Manage
		 */
		$finalModuleConfiguration['modmenu_manage'] = array(
			'name' => 'manage',
			'title' => 'Manage',
			'subitems' => array(
				'web_list_tab' => $moduleConfiguration['modmenu_web']['subitems']['web_list_tab'],
				'web_func_tab' => $moduleConfiguration['modmenu_web']['subitems']['web_func_tab'],
				'web_info_tab' => $moduleConfiguration['modmenu_web']['subitems']['web_info_tab'],
				'web_txrecyclerM1_tab' => $moduleConfiguration['modmenu_web']['subitems']['web_txrecyclerM1_tab'],
			)
		);

		unset($moduleConfiguration['modmenu_web']['subitems']['web_list_tab']);
		unset($moduleConfiguration['modmenu_web']['subitems']['web_func_tab']);
		unset($moduleConfiguration['modmenu_web']['subitems']['web_info_tab']);
		unset($moduleConfiguration['modmenu_web']['subitems']['web_txrecyclerM1_tab']);

		/**
		 * Edit
		 */
		$finalModuleConfiguration['modmenu_edit'] = array(
			'name' => 'edit',
			'title' => 'Edit',
			'subitems' => array(
				'web_WorkspacesWorkspaces_tab' => $moduleConfiguration['modmenu_web']['subitems']['web_WorkspacesWorkspaces_tab'],
				'system_BeuserTxBeuser_tab' => $moduleConfiguration['modmenu_system']['subitems']['system_BeuserTxBeuser_tab'],
				'system_BelogLog_tab' => $moduleConfiguration['modmenu_system']['subitems']['system_BelogLog_tab'],
			)
		);

		unset($moduleConfiguration['modmenu_web']['subitems']['web_WorkspacesWorkspaces_tab']);
		unset($moduleConfiguration['modmenu_system']['subitems']['system_BeuserTxBeuser_tab']);
		unset($moduleConfiguration['modmenu_system']['subitems']['system_BelogLog_tab']);

		/**
		 * Develop
		 */
		$finalModuleConfiguration['modmenu_develop'] = array(
			'name' => 'develop',
			'title' => 'Develop',
			'subitems' => array(
			)
		);

		/**
		 * System
		 */
		$finalModuleConfiguration['modmenu_system'] = array(
			'name' => 'system',
			'title' => 'System',
			'subitems' => array(
				'tools_ExtensionmanagerExtensionmanager_tab' => $moduleConfiguration['modmenu_tools']['subitems']['tools_ExtensionmanagerExtensionmanager_tab'],
				'tools_LangLanguage_tab' => $moduleConfiguration['modmenu_tools']['subitems']['tools_LangLanguage_tab'],
				'system_InstallInstall_tab' => $moduleConfiguration['modmenu_system']['subitems']['system_InstallInstall_tab'],
				'system_config_tab' => $moduleConfiguration['modmenu_system']['subitems']['system_config_tab'],
				'system_dbint_tab' => $moduleConfiguration['modmenu_system']['subitems']['system_dbint_tab'],
				'system_ReportsTxreportsm1_tab' => $moduleConfiguration['modmenu_system']['subitems']['system_ReportsTxreportsm1_tab'],
				'system_txschedulerM1_tab' => $moduleConfiguration['modmenu_system']['subitems']['system_txschedulerM1_tab'],
			)
		);

		unset($moduleConfiguration['modmenu_tools']['subitems']['tools_ExtensionmanagerExtensionmanager_tab']);
		unset($moduleConfiguration['modmenu_tools']['subitems']['tools_LangLanguage_tab']);
		unset($moduleConfiguration['modmenu_system']['subitems']['system_InstallInstall_tab']);
		unset($moduleConfiguration['modmenu_system']['subitems']['system_config_tab']);
		unset($moduleConfiguration['modmenu_system']['subitems']['system_dbint_tab']);
		unset($moduleConfiguration['modmenu_system']['subitems']['system_ReportsTxreportsm1_tab']);
		unset($moduleConfiguration['modmenu_system']['subitems']['system_txschedulerM1_tab']);

		/**
		 * User
		 */
		$finalModuleConfiguration['modmenu_user'] = $moduleConfiguration['modmenu_user'];
		unset($moduleConfiguration['modmenu_user']);

		/**
		 * Help
		 */
		$finalModuleConfiguration['modmenu_help'] = $moduleConfiguration['modmenu_help'];
		unset($moduleConfiguration['modmenu_help']);


		/**
		 * Individual modules
		 */

		// Add "Web"
		if (!empty($moduleConfiguration['modmenu_web']['subitems'])) {
			$finalModuleConfiguration['modmenu_manage']['subitems'] = array_merge(
				$finalModuleConfiguration['modmenu_manage']['subitems'],
				$moduleConfiguration['modmenu_web']['subitems']
			);
		}
		unset($moduleConfiguration['modmenu_web']);

		// Add "File"
		if (!empty($moduleConfiguration['modmenu_file']['subitems'])) {
			$finalModuleConfiguration['modmenu_present']['subitems'] = array_merge(
				$finalModuleConfiguration['modmenu_present']['subitems'],
				$moduleConfiguration['modmenu_file']['subitems']
			);
		}
		unset($moduleConfiguration['modmenu_file']);

		// Add "Tools"
		if (!empty($moduleConfiguration['modmenu_tools']['subitems'])) {
			$finalModuleConfiguration['modmenu_develop']['subitems'] = array_merge(
				$finalModuleConfiguration['modmenu_develop']['subitems'],
				$moduleConfiguration['modmenu_tools']['subitems']
			);
		}
		unset($moduleConfiguration['modmenu_tools']);

		// Add "System"
		if (!empty($moduleConfiguration['modmenu_system']['subitems'])) {
			$finalModuleConfiguration['modmenu_system']['subitems'] = array_merge(
				$finalModuleConfiguration['modmenu_system']['subitems'],
				$moduleConfiguration['modmenu_system']['subitems']
			);
		}
		unset($moduleConfiguration['modmenu_system']);

		/**
		 * Custom groups
		 */
		foreach ($moduleConfiguration as $module) {
			array_push($finalModuleConfiguration, $module);
			unset($module);
		}

		return $finalModuleConfiguration;
	}

}
