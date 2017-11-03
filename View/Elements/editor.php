<div id="TegakiEditor_canvases">
	<canvas id="TegakiEditor_layer_preview" width="900" height="600"></canvas>
	<canvas id="TegakiEditor_layer_draw" width="900" height="600"></canvas>
</div>

<ul id="TegakiEditor_colors"></ul>

<div id="TegakiEditor_sliders">
	線の太さ
	<div id="TegakiEditor_slider_weight"></div>
	透明度
	<div id="TegakiEditor_slider_transparency"></div>
	<?= $this->BcForm->hidden($fieldName) ?>
</div>

<?= $this->BcForm->hidden('BlogPost.editorType', ['value' => 'TegakiEditor']) ?>
