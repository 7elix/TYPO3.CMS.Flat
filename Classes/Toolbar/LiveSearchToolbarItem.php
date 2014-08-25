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

class LiveSearchToolbarItem extends \TYPO3\CMS\Backend\Toolbar\LiveSearchToolbarItem {

	/**
	 * Creates the selector for workspaces
	 *
	 * @return string Workspace selector as HTML select
	 */
	public function render() {
		$this->addJavascriptToBackend();
		return '
			<form class="navbar-form navbar-left" role="search">
				<div class="form-group live-search-wrapper">
					<div class="input-group">
						<input type="text" id="live-search-box" class="form-control" placeholder="' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_common.xlf:search') . '" />
						<span class="input-group-btn">
							<button type="submit" class="btn btn-default">
								<i class="fa fa-search"></i>
							</button>
						</span>
					</div>
				</div>
			</form>
		';
	}

	/**
	 * Adds the necessary JavaScript to the backend
	 *
	 * @return void
	 */
	protected function addJavascriptToBackend() {
		$pageRenderer = $GLOBALS['TBE_TEMPLATE']->getPageRenderer();
		$this->backendReference->addJavascriptFile('../typo3conf/ext/flat/Resources/Public/JavaScript/Toolbar/LiveSearch.js');
	}

}
