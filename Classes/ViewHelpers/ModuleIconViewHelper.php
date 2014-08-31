<?php
namespace PHORAX\Flat\ViewHelpers;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Ingo Pfennigstorf <i.pfennigstorf@gmail.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */
use PHORAX\Flat\Utility\IconUtility;

/**
 * Gets the corresponding module icon
 */
class ModuleIconViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	protected $correspondingIcons = array(
		'web_layout' => 'fa-pencil-square-o',
		'file_list' => 'fa-files-o',
		'web_ts' => 'fa-file-code-o',
		'tools_isearch' => 'fa-bar-chart-o',
		'web_list' => 'fa-list',
		'web_func' => 'fa-magic',
		'web_info' => 'fa-info',
		'web_txrecyclerM1' => 'fa-trash-o',
		'web_perm' => 'fa-lock',
		'system_BeuserTxBeuser' => 'fa-users',
		'system_BelogLog' => 'fa-video-camera',
		'tools_ExtensionmanagerExtensionmanager' => 'fa-plus-square-o',
		'tools_LangLanguage' => 'fa-language',
		'system_InstallInstall' => 'fa-desktop',
		'system_config' => 'fa-wrench',
		'system_dbint' => 'fa-database',
		'system_ReportsTxreportsm1' => 'fa-book',
		'system_txschedulerM1' => 'fa-clock-o',
	);

	/**
	 * @param array $moduleItem
	 * @return string
	 */
	public function render($moduleItem) {
		if (IconUtility::hasFontIcon($moduleItem['name'])) {
			return '<i class="fa ' . IconUtility::getFontIcon($moduleItem['name']) . ' fa-2x"></i><span class="t3-fa">' . $moduleItem['title'] . '</span>';
		}

		/** @var \TYPO3\CMS\Fluid\ViewHelpers\ImageViewHelper $imageViewHelper */
		$imageViewHelper = $this->objectManager->get('TYPO3\\CMS\\Fluid\\ViewHelpers\\ImageViewHelper');
		$imageViewHelper->initialize();
		return '<span class="t3-app-icon">' . $imageViewHelper->render($moduleItem['icon']['filename']) . '</span>' . $moduleItem['title'];
	}

}