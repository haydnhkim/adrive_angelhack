requirejs.config({
	shim: {
		'partials/chart_line_flow': {
			deps: ['highcharts']
		}
	},
	paths: {
		highcharts: 'http://code.highcharts.com/highcharts',
	}
});
require([
	'partials/chart_line_flow'
], function(chart_lf) {
	// activity line chart 실행
	var chart_fl_act = $.extend(true, {}, {}, chart_lf);
	chart_fl_act.draw({
		chart: {
			renderTo: 'chart_act_line'
		},
		plotOptions: {
			spline: {
				pointStart: chart_json.start_time
			}
		},
		series: chart_json.data
	});

	// google map 실행
	function initialize() {
		var myLatlng = new google.maps.LatLng(35.81905,127.8733);
		var mapOptions = {
			zoom: 6,
			center: myLatlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		}
		var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);

		var markers = [];
		var marker_data = [];
		for(var i = 0, len = map_nodes.length; i < len; i++){
			marker_data.push(new google.maps.LatLng(map_nodes[i][0], map_nodes[i][1]));
		}

		setTimeout(function() {
			for(var i = 0, len = marker_data.length; i < len; i++){
				markers.push(new google.maps.Marker({
					position: marker_data[i],
					icon: {
						path: google.maps.SymbolPath.CIRCLE,
						scale: 4,
						strokeWeight: 1,
						strokeColor: '#652610',
						fillColor: '#e7641c',
						fillOpacity: 1
					},
					draggable: true,
					map: map
				}));
			}
		}, 100);
	}
	initialize();


	$('.dialogs,.comments').slimScroll({
	        height: '300px'
	    });
		
		$('#tasks').sortable();
		$('#tasks').disableSelection();
		$('#tasks input:checkbox').removeAttr('checked').on('click', function(){
			if(this.checked) $(this).closest('li').addClass('selected');
			else $(this).closest('li').removeClass('selected');
		});
	
		var oldie = $.browser.msie && $.browser.version < 9;
		$('.easy-pie-chart.percentage').each(function(){
			var $box = $(this).closest('.infobox');
			var barColor = $(this).data('color') || (!$box.hasClass('infobox-dark') ? $box.css('color') : 'rgba(255,255,255,0.95)');
			var trackColor = barColor == 'rgba(255,255,255,0.95)' ? 'rgba(255,255,255,0.25)' : '#E2E2E2';
			var size = parseInt($(this).data('size')) || 50;
			$(this).easyPieChart({
				barColor: barColor,
				trackColor: trackColor,
				scaleColor: false,
				lineCap: 'butt',
				lineWidth: parseInt(size/10),
				animate: oldie ? false : 1000,
				size: size
			});
		});
	
	
	
	
	
	
	
		var d1 = [];
		for (var i = 0; i < Math.PI * 2; i += 0.5) {
			d1.push([i, Math.sin(i)]);
		}
	
		var d2 = [];
		for (var i = 0; i < Math.PI * 2; i += 0.5) {
			d2.push([i, Math.cos(i)]);
		}
	
		var d3 = [];
		for (var i = 0; i < Math.PI * 2; i += 0.2) {
			d3.push([i, Math.tan(i)]);
		}
		
	
	
	
		$('#recent-box [data-rel="tooltip"]').tooltip({placement: tooltip_placement});
		function tooltip_placement(context, source) {
			var $source = $(source);
			var $parent = $source.closest('.tab-content')
			var off1 = $parent.offset();
			var w1 = $parent.width();
	
			var off2 = $source.offset();
			var w2 = $source.width();
	
			if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
			return 'left';
		}
});