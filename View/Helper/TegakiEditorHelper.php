<?php
class TegakiEditorHelper extends AppHelper {
	public function editor($fieldName, $options = []) {
		$this->_View->BcBaser->element('TegakiEditor.editor', compact('fieldName'));
	}
}