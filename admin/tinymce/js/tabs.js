(function() {
	tinymce.create('tinymce.plugins.tabs', {
		init : function(ed, url) {
			ed.addCommand('tabs_insert_shortcode', function() {
				selected = tinyMCE.activeEditor.selection.getContent();
				if(selected){
						ed.execCommand('mceInsertContent', false, '[tabbed_section]'+selected+'[/tabbed_section]');
				}
            });		
			ed.addButton('tabs_button', {	
				title : 'Вкладки', 
				image : url + '/tabs.png',
				cmd : 'tabs_insert_shortcode'
			});		
		},
        createControl : function(n, cm) {
			  return null;
        },
		getInfo : function() {
			return {
				longname : 'tabs',
				author : 'Ayan Yenbekbay',
				authorurl : 'http://yenbekbay.kz',
				infourl : '',
				version : "1.0"
			};
		}
    });
    tinymce.PluginManager.add('tabs_button', tinymce.plugins.tabs);
})();