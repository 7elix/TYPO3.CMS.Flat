<?php
namespace PHORAX\Flat\Utility;

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

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Utility for ModuleMenu processing
 */
class ModuleMenuUtility {

	/**
	 * Manipulate and restructure module menu configuration
	 *
	 * @param \SplObjectStorage $moduleStorage
	 * @return array
	 */
	static function restructureModules($moduleStorage) {

		/**
		 * Present
		 *
		 * @var \TYPO3\CMS\Backend\Domain\Model\Module\BackendModule
		 */
		$present = new \TYPO3\CMS\Backend\Domain\Model\Module\BackendModule();
		$present->setName('present');
		$present->setTitle('Website');
		$present->setIcon(array('class' => 'desktop'));

		/**
		 * Manage
		 *
		 * @var \TYPO3\CMS\Backend\Domain\Model\Module\BackendModule
		 */
		$manage = new \TYPO3\CMS\Backend\Domain\Model\Module\BackendModule();
		$manage->setName('manage');
		$manage->setTitle('Manage');
		$manage->setIcon(array('class' => 'code-fork'));

		/**
		 * Develop
		 *
		 * @var \TYPO3\CMS\Backend\Domain\Model\Module\BackendModule
		 */
		$develop = new \TYPO3\CMS\Backend\Domain\Model\Module\BackendModule();
		$develop->setName('develop');
		$develop->setTitle('Develop');
		$develop->setIcon(array('class' => 'rocket'));

		/**
		 * System
		 *
		 * @var \TYPO3\CMS\Backend\Domain\Model\Module\BackendModule
		 */
		$system = new \TYPO3\CMS\Backend\Domain\Model\Module\BackendModule();
		$system->setName('system');
		$system->setTitle('System');
		$system->setIcon(array('class' => 'cube'));

		/**
		 * Reorganize
		 */

		$moduleMapping = array(
			'web_layout' => $present,
			'web_ViewpageView' => $present,
			'web_ts' => $present,
			'web_txformhandlermoduleM1' => $present,
			'web_list' => $manage,
			'web_func' => $manage,
			'web_info' => $manage,
			'web_txrecyclerM1' => $manage,
			'web_WorkspacesWorkspaces' => $manage,
			'file_list' => $present,
			'tools_isearch' => $present,
			'tools_ExtensionmanagerExtensionmanager' => $system,
			'tools_LangLanguage' => $system,
			'system_BeuserTxBeuser' => $system,
			'system_BelogLog' => $system,
			'system_InstallInstall' => $system,
			'system_config' => $system,
			'system_dbint' => $system,
			'system_ReportsTxreportsm1' => $system,
			'system_txschedulerM1' => $system
		);

		// Move Modules to new groups
		$moduleStorage->rewind();
		foreach ($moduleStorage as $moduleGroup) {
			foreach ($moduleGroup->getChildren() as $module) {
				if (array_key_exists($module->getName(), $moduleMapping)) {
					$moduleMapping[$module->getName()]->addChild($module);
					$moduleGroup->getChildren()->detach($module);
				}
			}
		}

		// Mapping for groups
		$groupMapping = array(
			'web' => $manage,
			'file' => $present,
			'tools' => $develop,
			'system' => $system
		);

		// Attach remaining Modules by group mapping
		$moduleStorage->rewind();
		foreach ($moduleStorage as $moduleGroup) {
			if (array_key_exists($moduleGroup->getName(), $groupMapping)) {
				foreach ($moduleGroup->getChildren() as $module) {
					$groupMapping[$moduleGroup->getName()]->addChild($module);
					$moduleGroup->getChildren()->detach($module);
				}
			}
		}

		// Remove empty groups
		$moduleStorage->rewind();
		foreach ($moduleStorage as $moduleGroup) {
			if ($moduleGroup->getChildren()->count() == 0) {
				$moduleStorage->detach($moduleGroup);
			}
		}

		// Attach expected Module groups
		$finalModuleConfiguration = new \SplObjectStorage();
		$finalModuleConfiguration->attach($present);
		$finalModuleConfiguration->attach($manage);
		$finalModuleConfiguration->attach($develop);
		$finalModuleConfiguration->attach($system);

		// Attach individual Module groups
		$moduleStorage->rewind();
		foreach ($moduleStorage as $moduleGroup) {
			$finalModuleConfiguration->attach($moduleGroup);
			$moduleStorage->detach($moduleGroup);
		}

		return $finalModuleConfiguration;
	}

}
