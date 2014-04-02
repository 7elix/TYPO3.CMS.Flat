TYPO3.jQuery(function () {

	TYPO3.jQuery('#typo3-module-menu-toogle a').click(function() {
		if (TYPO3.Backend.ModuleMenuContainer.collapsed) {
			TYPO3.Backend.ModuleMenuContainer.expand();
		} else {
			TYPO3.Backend.ModuleMenuContainer.collapse();
		}
	});

});