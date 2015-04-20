(function() {
	tinymce.create('tinymce.plugins.tab', {
		init : function(ed, url) {
			ed.addCommand('tab_insert_shortcode', function() {
				selected = tinyMCE.activeEditor.selection.getContent();
				if(selected){
					var title = prompt("Заголовок", "Впишите сюда заголовок"),
						id = prompt("ID", "Впишите сюда идентификатор");
					if (title != null)
						ed.execCommand('mceInsertContent', false, '[tab title="'+title+'" id="'+id+'"]'+selected+'[/tab]');
				}
            });		
			ed.addButton('tab_button', {	
				title : 'Вкладки', 
				image : url + '/tab.png',
				cmd : 'tab_insert_shortcode'
			});		
		},
        createControl : function(n, cm) {
			  return null;
        },
		getInfo : function() {
			return {
				longname : 'tab',
				author : 'Ayan Yenbekbay',
				authorurl : 'http://yenbekbay.kz',
				infourl : '',
				version : "1.0"
			};
		}
    });
    tinymce.PluginManager.add('tab_button', tinymce.plugins.tab);
})();