<?php
class TegakiEditorHelperEventListener extends BcHelperEventListener {
	public $events = [
		'Form.beforeInput',
	];

	public Function formBeforeInput(CakeEvent $event) {
		if ($event->data['fieldName'] !== 'BlogPost.detail') {
			return;
		}

		$View = $event->subject();
		$View->BcBaser->element('TegakiEditor.change_editor');
	}
}
