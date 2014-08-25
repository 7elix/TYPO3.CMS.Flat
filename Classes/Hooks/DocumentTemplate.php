<?php
namespace PHORAX\Flat\Hooks;

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

class DocumentTemplate {

	/**
	 * Force jQuery
	 * Load select2
	 *
	 * @param $hookParameters
	 * @param $documentTemplateInstance
	 */
	public function preHeaderRenderHook($hookParameters, $documentTemplateInstance) {

		# Force jQuery in Backend
#		$hookParameters['pageRenderer']->loadJquery();

		# Central initialization hub for flat
#		$hookParameters['pageRenderer']->addJsLibrary(
#			'flat',
#			'../typo3conf/ext/flat/Resources/Public/JavaScript/flat.js'
#		);

	}

}