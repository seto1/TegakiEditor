$(function(){
	var TegakiEditor = function() {
		$('#TegakiEditor_change_editor').click(function() {
			var url = $(this).attr('data-url');
			var blogPostId = $('#BlogPostId').val();
			var status = ($('#TegakiEditor_layer_draw').length) ? 0: 1;
			var token = $('#BlogPostForm').find('input:hidden[name="data[_Token][key]"]').val();

			$.post(url, { 'blogPostId': blogPostId, 'status': status, 'data[_Token][key]': token }, function(data) {
				if (data['result'] == 'success') {
					location.reload();
				} else {
					alert('エディタの変更に失敗しました。');
				}
			}, 'json').fail(function() {
				alert('エディタの変更に失敗しました。');
			});
		})

		var canvasLayerDraw = document.getElementById('TegakiEditor_layer_draw');
		if (canvasLayerDraw) {
			var canvasLayerPreview = document.getElementById('TegakiEditor_layer_preview');
			setCanvas();
		}

		function setCanvas() {
			var contextDraw = canvasLayerDraw.getContext('2d');
			var contextPreview = canvasLayerPreview.getContext('2d');
			var currentMouse = { x: 0, y: 0 }
			var isDrawing = false;
			var detailForm = $('#BlogPostDetail');
			var paths = [];
			var opacity = 1;
			var lineWidth = 10;
			var lineColor = '#000';

			var colors = ['#222', '#fff', '#f66', '#f3c401', '#6cc', '#33c', '#c6c', '#963', '#80bd07'];
			$.each(colors, function(index, color) {
				var li = $('<li></li>');
				li.attr('data-color', color);
				li.css('background', color)
				$('#TegakiEditor_colors').append(li);
			});

			$('#TegakiEditor_colors li').click(function() {
				$('#TegakiEditor_colors li').css('border-radius', '2px');
				$(this).css('border-radius', '10px');
				lineColor = $(this).attr('data-color');
			});

			$('#TegakiEditor_colors li').get(0).click();

			$('#TegakiEditor_slider_weight').slider({
				min: 1,
				range: 'min',
				value: lineWidth,
				stop: function(event, ui) {
					lineWidth = ui.value * 2;
				}
			});

			$('#TegakiEditor_slider_transparency').slider({
				range: 'min',
				value: (100 - opacity * 100),
				stop: function(event, ui) {
					opacity = (100 - ui.value) / 100;
					$(canvasLayerDraw).css('opacity', opacity);
				}
			});

			var match = detailForm.val().match(/<img src="(data:.+)">/);
			if (match) {
				var img = new Image();
				img.src = match[1];
				img.onload = function() {
					contextPreview.drawImage(img, 0, 0, 900, 600);
				}
			} else {
				contextPreview.fillStyle = '#fff';
				contextPreview.fillRect(0, 0, 900, 600);
			}

			$(canvasLayerDraw).mousedown(function(e){
				isDrawing = true;
				currentMouse = getCurrentMousePosition(e);
				paths.push([currentMouse]);
			});

			$(canvasLayerDraw).mouseup(function(e){
				isDrawing = false;
				refresh('preview');
			});

			$(canvasLayerDraw).mouseleave(function(e){
				if (! isDrawing) {
					return
				}

				if (paths.length) {
					currentMouse = getCurrentMousePosition(e);
					paths[paths.length-1].push(currentMouse);
					refresh('preview');
				}
			});

			$(canvasLayerDraw).mouseenter(function(e){
				isDrawing = (e.buttons) ? true: false;

				if (! isDrawing) {
					return
				}

				currentMouse = getCurrentMousePosition(e);
				if (paths.length) {
					paths[paths.length-1].push(currentMouse);
				} else {
					paths.push([currentMouse]);
				}
				refresh('draw');
			});

			$(canvasLayerDraw).mousemove(function(e){
				if (! isDrawing) {
					return
				}

				if (paths.length) {
					currentMouse = getCurrentMousePosition(e);
					paths[paths.length-1].push(currentMouse);
					refresh('draw');
				}
			});

			function refresh(canvasLayerType) {
				if (canvasLayerType == 'draw') {
					ctx = contextDraw;
					ctx.clearRect(0, 0, 900, 600);
				} else {
					ctx = contextPreview;
					ctx.globalAlpha = opacity;
				}

				for (var i = 0; i < paths.length; ++i) {
					var path = paths[i];

					if (path.length < 1) {
						continue;
					}

					ctx.lineCap = 'round';
					ctx.lineJoin = 'round';
					ctx.lineWidth = lineWidth;
					ctx.strokeStyle = lineColor;
					ctx.beginPath();
					ctx.moveTo(path[0].x, path[0].y);

					for (var j = 1; j < path.length; ++j) {
						ctx.lineTo(path[j].x, path[j].y);
					}

					ctx.stroke();
				}

				if (canvasLayerType == 'preview') {
					paths = [];
					contextDraw.clearRect(0, 0, 900, 600);

					var imgData = canvasLayerPreview.toDataURL();
					detailForm.val('<img src="' + imgData + '">');
				}
			}

			function getCurrentMousePosition(e) {
				var canvasPos = canvasLayerDraw.getBoundingClientRect();
				return currentMouse = { x: e.clientX - canvasPos.left, y:  e.clientY - canvasPos.top};
			}
		}
	}

	TegakiEditor();
});
