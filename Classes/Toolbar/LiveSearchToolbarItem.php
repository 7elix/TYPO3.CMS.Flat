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
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-lg fa-inline fa-search"></i> <span class="visible-xs-inline">Search</span></a>
            <ul class="dropdown-menu">
                <li>
                    <form class="form-inline" role="search">
                        <div class="form-group">
                            <input type="text" id="live-search-box" class="form-control" placeholder="' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_common.xlf:search') . '" />
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </li>
            </ul>
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
