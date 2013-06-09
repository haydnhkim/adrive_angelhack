define(function(){
	Highcharts.setOptions({
		global: {
			useUTC: false
		}
	});
	var chart_lf = {
		chart: null,
		option: {
			chart: {
				renderTo: null,
				type: 'spline',
				backgroundColor: 'rgba(0,0,0,0)'
			},
			legend: {
				enabled: false
			},
			xAxis: {
				tickWidth: 1,
				minPadding: 0.03,
				maxPadding: 0.03,
				type: 'datetime',
				gridLineWidth: 1,
				gridLineDashStyle: 'dash',
				gridLineColor: '#ebeaea',
				lineWidth: 1,
				lineColor: '#dbdada',
				tickWidth: 0,
				tickmarkPlacement: 'on',
				labels: {
					align: 'center',
					overflow: 'justify',
					formatter: function() {
						return Highcharts.dateFormat('%y.%m.%d',this.value);
					},
					style: {
						color: '#333',
						fontSize: '12px'
					},
					y: 25
				}
			},
			yAxis: {
				min: 0,
				tickPixelInterval: 60,
				tickWidth: 0,
				gridLineWidth: 1,
				gridLineDashStyle: 'dash',
				gridLineColor: '#ebeaea',
				lineWidth: 1,
				lineColor: '#dbdada',
				title: { text: '' },
				labels: {
					formatter: function() {
						if((this.chart.yAxis[0].max < 20) && (this.value != 0)){
							var value = Highcharts.numberFormat(this.value, 1, '.', ',');
						}else{
							var value = Highcharts.numberFormat(this.value, 0, ',');
						}
						return value;
					},
					style: {
						color: '#888',
						fontSize: '12px'
					}
				}
			},
			tooltip: {
				useHTML: true,
				xDateFormat: '%y.%m.%d',
				formatter: function() {
						return this.series.name + ' <b>'+ Highcharts.numberFormat(this.y, 0, ',') + '</b>&nbsp; <i class="icon-eye-open"></i> '+ Highcharts.dateFormat('%y.%m.%d',this.x);
				}
			},
			plotOptions: {
				spline: {
					pointStart: null,
					pointInterval: 24 * 3600 * 1000,
					marker: {
						radius: 5,
						lineWidth: 3,
						lineColor: '#fcfbfb',
						symbol: 'circle'
					}
				}
			},
			series: null,
			title: { text: '' },
			subtitle: {text: '' },
			exporting: { enabled: false },
			credits: { enabled: false }
		},
		draw: function(option){
			option = option?option:{};
			option = $.extend(true, {}, this.option, option);
			return this.chart = new Highcharts.Chart(option);
		}
	};

	return chart_lf;
});