/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
        
        // config.extraPlugins = 'allMedias';
    config.extraPlugins = 'widgetselection';
    config.extraPlugins = 'widget';
    config.extraPlugins = 'toolbar';
    config.extraPlugins = 'dialog';
    config.extraPlugins = 'dialogui';
    config.extraPlugins = 'button';
    config.extraPlugins = 'notification';
	// config.extraPlugins = 'dialogui';
	// config.extraPlugins = 'fakeobjects';
    config.extraPlugins = 'audio','html5audio';
    config.extraPlugins = 'clipboard';
    config.extraPlugins = 'imager2';    
    config.extraPlugins = 'lineutils';
    config.extraPlugins = 'imageresponsive';
    config.extraPlugins = 'slideshow,tliyoutube2','recordmp3js','imageresponsive';
	        // config.extraPlugins = 'Audio';
	// config.extraPlugins = 'slideshow,tliyoutube2,bgimage';
	// config.extraPlugins = 'allmedias';
	// config.extraPlugins = 'dialog';
	// config.extraPlugins = 'dialogui';
	// config.extraPlugins = 'fakeobjects';
	//alert("Eu sou um alert!");
};
