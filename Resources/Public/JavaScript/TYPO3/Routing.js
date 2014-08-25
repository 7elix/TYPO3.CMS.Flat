
TYPO3.Routing = {};
TYPO3.Routing = {

	/**
	 * Find module object by module path
	 *
	 * @param path
	 * @returns object
	 */
	resolvePath: function(path) {
		var moduleItem = {};

		TYPO3.jQuery.each(
			TYPO3.RoutingConfiguration,
			function(index, object) {

				TYPO3.jQuery.each(object['subitems'], function(indexSub, objectSub) {
					if (objectSub['name'] == path) {
						moduleItem = objectSub;
					}
				});

			}
		);

		return moduleItem;
	},

	/**
	 * Push module item state to browser url bar
	 *
	 * @param moduleItem
	 */
	pushDeeplink: function(moduleItem, id) {
		// @TOD: Migrate to history API (pushState function and popState event)
		document.location.hash = moduleItem['name'];
	}

};