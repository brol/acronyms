CKEDITOR.plugins.add('acronym', {
	requires: 'dialog',
	init: function(editor) {
		editor.addCommand('acronymCommand',
				  new CKEDITOR.dialogCommand('acronymDialog', { allowedContent: 'abbr[title,id]' })
				 );

		CKEDITOR.dialog.add('acronymDialog', this.path+'dialogs/popup.js');

		editor.ui.addButton("Acronym", {
			label: jsToolBar.prototype.elements.acronyms.title,
			icon: this.path+'icons/icon.png',
			command: 'acronymCommand'
		});
	}
});
