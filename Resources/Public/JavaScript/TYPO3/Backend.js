
TYPO3.Backend = {};
TYPO3.Backend = {

	// @Todo: Develop object for current navigation status (moduleConfiguration, page id, fal storage id, …)
	// "I want to be a ember model / persistence"
	id: 0,
	currentModule: {},

	currentPath: '',
	currentContentUrl: '',

	/**
	 * Show mobule & navigation component

	 * @param path
	 * @param params
	 */
	openModule: function(path) {
		console.log('Loading path: ' + path);

		moduleConfiguration = TYPO3.Routing.resolvePath(path);
		console.log(moduleConfiguration);

		this.currentModule = moduleConfiguration;

		// Backwards compatibility
		this.currentPath = path;
		this.currentContentUrl = moduleConfiguration['link'];
		top.currentSubScript = moduleConfiguration['link'];

		TYPO3.Viewport.setNavigationVisible((moduleConfiguration['parentNavigationFrameScript'] != null));
		this.openContentUrl(moduleConfiguration['link']);
		this.openNavigationUrl(moduleConfiguration['parentNavigationFrameScript']);

		// @TODO: Hook external functionality in here
		TYPO3.ModuleMenu.setActiveModule(moduleConfiguration);
		TYPO3.Routing.pushDeeplink(moduleConfiguration, this.id);
	},

	setId: function(id) {
		this.id = id;
	},

	/**
	 * Navigation to new page id
	 *
	 * @param id new page uid
	 */
	openId: function(id) {
		this.id = id;

		var contentPath = TYPO3.jQuery('#typo3-content').attr('src');
		if (contentPath.indexOf('id') != -1 && !contentPath.match('id=' + this.id)) {
			var newContentPath = contentPath.replace(/id=\d+/, 'id=' + this.id);
			TYPO3.jQuery('#typo3-content').attr('src', newContentPath);
		}

		return false;
	},

	/**
	 * Navigation to url in navigation section
	 * If no ID is given, attached
	 * If ID is given, no replaced - we trust your url
	 *
	 * @param url string
	 */
	openNavigationUrl: function(url) {
		if (!url) {
			return;
		}

		// No ID= given, attach this.id
		if (url.indexOf('id=') == -1) {
			url += (url.indexOf('?') == -1 ? '?id=' : '&id=') + this.id;
		}

		if (TYPO3.jQuery('#typo3-navigation').attr('src') != url) {
			TYPO3.jQuery('#typo3-navigation').attr('src', url);
		}
	},

	/**
	 * Navigation to url in navigation section
	 * If no ID is given, attached
	 * If ID is given, no replaced - we trust your url
	 *
	 * @param url string
	 */
	openContentUrl: function(url) {
		// No ID= given, attach this.id
		if (url.indexOf('id=') == -1) {
			url += (url.indexOf('?') == -1 ? '?id=' : '&id=') + 'id=' + this.id;
		}

		if (TYPO3.jQuery('#typo3-content').attr('src') != url) {
			TYPO3.jQuery('#typo3-content').attr('src', url);
		}
	},

	persistModuleState: function() {

	},

	loadModuleState: function() {

	}

};

TYPO3.jQuery(document).ready(function() {
	TYPO3.Backend.openModule(top.startInModule[0]);
});