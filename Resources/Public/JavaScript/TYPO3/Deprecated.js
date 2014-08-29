
function launchView(table, uid, backPath) {
	TYPO3.Viewport.launchView(table, uid, backPath);
}

function openUrlInWindow(url, windowName) {
	TYPO3.Viewport.openWindow(url, windowName);
}

function goToModule(modName, cMR_flag, parameters) {
	TYPO3.Backend.openModule(modName, parameters);
}

/**
 * Jump
 *
 * @param url
 * @param modName
 * @param mainModName
 * @param pageId
 */
function jump(url, modName, mainModName, pageId) {
	if (modName) {
		TYPO3.Backend.openModule('modName');
	}
	if (pageId) {
		TYPO3.Backend.setId(pageId);
	}
	if (url) {
		TYPO3.Backend.openContentUrl(url);
	}

	/*
		if (isNaN(pageId)) {
			pageId = -2;
		}



			// clear information about which entry in nav. tree that might have been highlighted.
		top.fsMod.navFrameHighlightedID = [];
		top.fsMod.recentIds['web'] = pageId;

		if (top.TYPO3.Backend.NavigationContainer.PageTree) {
			top.TYPO3.Backend.NavigationContainer.PageTree.refreshTree();
		}

		top.nextLoadModuleUrl = url;
		top.TYPO3.ModuleMenu.App.openModule(modName);
	*/
}

var TYPO3BackendToolbarManager = {};

/**
 * Frameset Module object
 *
 * Used in main modules with a frameset for submodules to keep the ID between modules
 * Typically that is set by something like this in a Web>* sub module:
 *		if (top.fsMod) top.fsMod.recentIds["web"] = "\'.(int)$this->id.\'";
 * 		if (top.fsMod) top.fsMod.recentIds["file"] = "...(file reference/string)...";
 */
function fsModules() {
	this.recentIds = new Array();
	this.navFrameHighlightedID = new Array();
	this.currentMainLoaded="";
	this.currentBank="0";
}
var fsMod = new fsModules();

/*
TYPO3 = Ext.apply(TYPO3, {
	// store instances that only should be running once
	_instances: {},
	getInstance: function(className) {
		return TYPO3._instances[className] || false;
	},
	addInstance: function(className, instance) {
		TYPO3._instances[className] = instance;
		return instance;
	},

	helpers: {
		// creates an array by splitting a string into parts, taking a delimiter
		split: function(str, delim) {
			var res = [];
			while (str.indexOf(delim) > 0) {
				res.push(str.substr(0, str.indexOf(delim)));
				str = str.substr(str.indexOf(delim) + delim.length);
			}
			return res;
		}
	}
});
*/

var T3AJAX = {};
T3AJAX.showError = function(xhr, json) {
	Console.log('Deprecated ' + T3AJAX.showError);

/*	if (typeof xhr.responseText !== undefined && xhr.responseText) {
		if (typeof Ext.MessageBox !== undefined) {
			Ext.MessageBox.alert('TYPO3', xhr.responseText);
		}
		else {
			alert(xhr.responseText);
		}
	}
*/
};


function loadEditId(id,addGetVars) {
/*
	top.fsMod.recentIds.web = id;
	top.fsMod.navFrameHighlightedID.web = "pages" + id + "_0";		// For highlighting

	if (top.content && top.content.nav_frame && top.content.nav_frame.refresh_nav) {
		top.content.nav_frame.refresh_nav();
	}
	if (TYPO3.configuration.pageModule) {
		top.goToModule(TYPO3.configuration.pageModule, 0, addGetVars?addGetVars:"");
	}
*/
}

var condensedMode = false;

/**
 * @TODO:
 * Must be filled for compatibility?
 */
var currentSubScript = '';
var currentSubNavScript = "";

/**
 * @TODO:
 * Find fitting solutions of all browsers to observe/watch top.content.list_frame
 * OR
 * break direct frame manipulation JavaScript
 */

// top.content.list_frame deprecated API syntax
top.content = {
	list_frame: ''
};

Object.observe(top.content, function(changes) {
	changes.forEach(function(change) {
		if (change.name == 'list_frame') {
			console.log(change.oldValue);
			console.log(content.list_frame);
			TYPO3.Backend.openContentUrl(content.list_frame)
		}
	});
});


/*

TYPO3.Backend.ContentContainer = {};
TYPO3.Backend.ContentContainer = {
	setUrl: function(url) {
		TYPO3.jQuery('#typo3-content').attr('src', url);
	}
};
*/

// if(top.content.list_frame){top.content.list_frame.location.href = top.TS.PATH_typo3+'db_new.php?id=81';}Clickmenu.hideAll();

var TYPO3BackendToolbarManager = {};
TYPO3BackendToolbarManager = {
	hideAll: function() { }
}

TYPO3ModuleMenu = {};
TYPO3ModuleMenu = {
	refreshMenu: function() {
		TYPO3.Viewport.refresh();
	}
};

var TYPO3BackendClearCacheMenu = {};

TYPO3.Backend.ContentContainer = {};
TYPO3.Backend.ContentContainer.setUrl = function(url) {
	TYPO3.Backend.openContentUrl(url);
};


/**
 * shortcut manager to delegate the action of creating shortcuts to the new
 * backend.php shortcut menu or the old shortcut frame depending on what is available
 */
var ShortcutManager = {

	/**
	 * central entry point to create a shortcut, delegates the call to correct endpoint
	 */
	createShortcut: function(confirmQuestion, backPath, moduleName, url) {
		if(confirm(confirmQuestion)) {
			if (typeof TYPO3BackendShortcutMenu !== undefined) {
					// backend.php
				TYPO3BackendShortcutMenu.createShortcut('', moduleName, url);
			}
		}
	}
}