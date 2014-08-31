<?php
namespace PHORAX\Flat\Utility;

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

/**
 * Icon Utility for flat backend
 */
class IconUtility {

	protected static $correspondingIcons = array(
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
	 * @param string $moduleName
	 * @return string
	 */
	static public function getFontIcon($moduleName) {
		return self::$correspondingIcons[$moduleName];
	}

	/**
	 * @param string $moduleName
	 * @return bool
	 */
	static public function hasFontIcon($moduleName) {
		if (array_key_exists($moduleName, self::$correspondingIcons)) {
			return TRUE;
		}
		return FALSE;
	}

}