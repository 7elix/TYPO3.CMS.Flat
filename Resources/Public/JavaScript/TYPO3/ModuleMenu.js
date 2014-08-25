
TYPO3.ModuleMenu = {};
TYPO3.ModuleMenu = {

	/**
	 * Mark main navigation eleemnts active
	 *
	 * @param moduleConfiguration
	 */
	setActiveModule: function(moduleConfiguration) {
		// Reset active flag
		TYPO3.jQuery('ul[data-typo3-role="typo3-module-menu"] li.active').removeClass('active');
		TYPO3.jQuery('ul[data-typo3-role="typo3-module-menu"] li[data-path="' + moduleConfiguration['name'] + '"]').addClass('active');
		TYPO3.jQuery('ul[data-typo3-role="typo3-module-menu"] li[data-path="' + moduleConfiguration['name'] + '"]').parents('li').addClass('active');
	}

};