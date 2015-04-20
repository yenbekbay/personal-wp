(function() {
	tinymce.create('tinymce.plugins.toggle', {
		init : function(ed, url) {
			ed.addCommand('toggle_insert_shortcode', function() {
				selected = tinyMCE.activeEditor.selection.getContent();
				if(selected){
					var title = prompt("Заголовок", "Впишите сюда заголовок");
					if (title != null)
						ed.execCommand('mceInsertContent', false, '[toggle title="'+title+'"]'+selected+'[/toggle]');
				}
            });		
			ed.addButton('toggle_button', {	
				title : 'Показать/Скрыть', 
				image : url + '/toggle.png',
				cmd : 'toggle_insert_shortcode'
			});		
		},
        createControl : function(n, cm) {
			  return null;
        },
		getInfo : function() {
			return {
				longname : 'Toggle',
				author : 'Ayan Yenbekbay',
				authorurl : 'http://yenbekbay.kz',
				infourl : '',
				version : "1.0"
			};
		}
    });
    tinymce.PluginManager.add('toggle_button', tinymce.plugins.toggle);
})();