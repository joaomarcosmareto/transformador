/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

    //config.contentsCss = 'contents.css';

	//config.line_height='1em;1.1em;1.2em;1.3em;1.4em;1.5em';
    //config.line_height='1em;1.1em;1.2em;1.3em;1.4em;1.5em';
	config.line_height='6px;8px;10px;12px;14px;16px;18px;20px;22px;24px;26px;';
	config.extraPlugins = 'popup,filebrowser,backgrounds';

	config.filebrowserImageUploadUrl = 'upload.php';
    config.filebrowserBrowseUrl = 'browser';
};

CKEDITOR.on('dialogDefinition', function( ev ) {
	var dialogName = ev.data.name;
	var dialogDefinition = ev.data.definition;
	if(dialogName === 'table') {
		//overrides default properties of info tab
		var infoTab = dialogDefinition.getContents('info');
		var cellSpacing = infoTab.get('txtCellSpace');
		cellSpacing['default'] = "0";
		var cellPadding = infoTab.get('txtCellPad');
		cellPadding['default'] = "0";
		var border = infoTab.get('txtBorder');
		border['default'] = "1";
		var width = infoTab.get('txtWidth');
		width['default'] = "100%";

		//overrides default properties of advanced tab
        var advancedTab = dialogDefinition.getContents('advanced');
        var classes = advancedTab.get('advCSSClasses');
        classes['default'] = "table-mobile";

	}
	console.log(dialogDefinition);
	var advancedTab = dialogDefinition.getContents('advanced');
});