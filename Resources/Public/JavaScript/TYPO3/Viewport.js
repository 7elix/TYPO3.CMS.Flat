
TYPO3.Viewport = {};
TYPO3.Viewport = {

	navigationVisible: true,

	navigationFrame: TYPO3.jQuery('#typo3-navigation'),
	contentFrame: TYPO3.jQuery('#typo3-content'),

	/**
	 * Draw TYPO3 backend iFrames & div containers
	 */
	draw: function() {
		var windowHeight = TYPO3.jQuery(window).height();
		var topbarHeight = TYPO3.jQuery('#typo3-topbar').height();
		var contentHeight = windowHeight - topbarHeight;

		TYPO3.jQuery('#typo3-navigation').css({
			height: contentHeight + 'px'
		});
		TYPO3.jQuery('#typo3-content').css({
			height: contentHeight + 'px'
		});

		if (this.navigationVisible) {
			TYPO3.jQuery('#typo3-navigation').parent().removeClass('hidden');
			TYPO3.jQuery('#typo3-navigation').parent().addClass('col-xs-3');

			TYPO3.jQuery('#typo3-content').parent().removeClass('col-xs-12');
			TYPO3.jQuery('#typo3-content').parent().addClass('col-xs-9');

		} else {
			TYPO3.jQuery('#typo3-navigation').parent().removeClass('col-xs-3');
			TYPO3.jQuery('#typo3-navigation').parent().addClass('hidden');

			TYPO3.jQuery('#typo3-content').parent().removeClass('col-xs-9');
			TYPO3.jQuery('#typo3-content').parent().addClass('col-xs-12');
		}
	},

	/**
	 * Redraw frameset
	 */
	refresh: function() {
		this.navigationFrame.reload();
		this.contentFrame.reload();
	},

	/**
	 * Navigation visible?
	 *
	 * @param visible boolean
	 */
	setNavigationVisible: function(visible) {
		this.navigationVisible = visible;
		TYPO3.Viewport.draw();
	},

	/**
	 * Open window / popup
	 *
	 * @param url
	 * @param windowName
	 * @returns {boolean}
	 */
	openWindow: function(url, windowName) {
		regularWindow = window.open(
			url,
			windowName,
			"status=1,menubar=1,resizable=1,location=1,directories=0,scrollbars=1,toolbar=1");
		regularWindow.focus();
		return false;
	},

	/**
	 * Launch View
	 *
	 * @param table
	 * @param uid
	 * @param backPath
	 */
	launchView: function(table, uid, backPath) {
		var backPath = backPath ? backPath : "";
		var thePreviewWindow = "";
		thePreviewWindow = window.open(TS.PATH_typo3 + "show_item.php?table=" + encodeURIComponent(table) + "&uid=" + encodeURIComponent(uid),
				"ShowItem" + TS.uniqueID,
				"width=650,height=600,status=0,menubar=0,resizable=0,location=0,directories=0,scrollbars=1,toolbar=0");
		if (thePreviewWindow && thePreviewWindow.focus) {
			thePreviewWindow.focus();
		}
	},

	/**
	 *
	 */
	openModal: function() {
	}

};

// Initialize Viewport
TYPO3.jQuery(document).ready(function() {
	TYPO3.Viewport.draw();
});

// Update Viewport on resize
TYPO3.jQuery(window).resize(function() {
	TYPO3.Viewport.draw();
});

