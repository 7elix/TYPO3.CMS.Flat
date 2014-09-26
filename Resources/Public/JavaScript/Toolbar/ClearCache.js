

/**
 * Bind clear cache
 */

// @TODO: When I grow up, I want to be (document).ready() {}
Ext.onReady(function() {
	TYPO3.jQuery('[data-action=clearCache]').click(function() {
		// Success message
		var title = TYPO3.jQuery(this).attr('title');
		TYPO3.jQuery.ajax(TYPO3.jQuery(this).attr('href')).done(function() {
			TYPO3.Flashmessage.display(2, 'Successful', title);
		});
		return false;
	});
});