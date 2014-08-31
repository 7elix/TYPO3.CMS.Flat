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