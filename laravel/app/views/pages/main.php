<div class="container-fluid" id="main-container">
	<?php echo View::make('partials.sidebar')->with('name', $name); ?>
	<div id="main-content" class="clearfix">
		<?php echo View::make('partials.history'); ?>

		<div id="page-content" class="clearfix">
			<div class="page-header position-relative">
				<h1>
					Dashboard
					<small>
						<i class="icon-double-angle-right"></i>
						overview &amp; stats
					</small>
				</h1>
			</div><!--/.page-header-->

			<div class="row-fluid">
				<!--PAGE CONTENT BEGINS HERE-->

				<div class="space-6"></div>

				<div class="row-fluid">
					<div class="span7 infobox-container">
						<div class="infobox infobox-green  ">
							<div class="infobox-icon">
								<i class="icon-bullhorn"></i>
							</div>

							<div class="infobox-data">
								<span class="infobox-data-number">32</span>
								<div class="infobox-content">오늘 음성광고 횟수 +2</div>
							</div>
							<div class="stat stat-success">8%</div>
						</div>

						<div class="infobox infobox-blue  ">
							<div class="infobox-icon">
								<i class="icon-book"></i>
							</div>

							<div class="infobox-data">
								<span class="infobox-data-number">11</span>
								<div class="infobox-content">오늘 발급 쿠폰 수 +10</div>
							</div>

							<div class="badge badge-success">
								32%
								<i class="icon-arrow-up"></i>
							</div>
						</div>

						<div class="infobox infobox-pink  ">
							<div class="infobox-icon">
								<i class="icon-eye-open"></i>
							</div>

							<div class="infobox-data">
								<span class="infobox-data-number">8</span>
								<div class="infobox-content">오늘 배너광고 뷰 수 -20</div>
							</div>
							<div class="stat stat-important">4%</div>
						</div>

						<div class="infobox infobox-red  ">
							<div class="infobox-icon">
								<i class="icon-beaker"></i>
							</div>

							<div class="infobox-data">
								<span class="infobox-data-number">7</span>
								<div class="infobox-content">experiments</div>
							</div>
						</div>

						<div class="infobox infobox-orange2  ">
							<div class="infobox-progress">
								<div class="easy-pie-chart percentage" data-percent="42" data-size="46">
									<span class="percent">42</span>%
								</div>
							</div>

							<div class="infobox-data">
								<span class="infobox-data-number">₩6,251</span>
								<div class="infobox-content">
									<span class="bigger-110">/</span>
									₩20,100 지출설정금액
								</div>
							</div>
						</div>

						<div class="infobox infobox-blue2  ">
							<div class="infobox-progress">
								<div class="easy-pie-chart percentage" data-percent="23" data-size="46">
									<span class="percent">23</span>%
								</div>
							</div>

							<div class="infobox-data">
								<span class="infobox-data-number">12</span>
								<div class="infobox-content">
									<span class="bigger-110">/</span>
									32 총 광고 상품 개수
								</div>
							</div>
						</div>

						<div class="space-6"></div>

						<div class="infobox infobox-green infobox-small infobox-dark">
							<div class="infobox-progress">
								<div class="easy-pie-chart percentage" data-percent="61" data-size="39">
									<span class="percent">61</span>%
								</div>
							</div>

							<div class="infobox-data">
								<div class="infobox-content">Task</div>
								<div class="infobox-content">Completion</div>
							</div>
						</div>

						<div class="infobox infobox-blue infobox-small infobox-dark">
							<div class="infobox-chart">
								<span class="sparkline" data-values="3,4,2,3,4,4,2,2"></span>
							</div>

							<div class="infobox-data">
								<div class="infobox-content">지출내역</div>
								<div class="infobox-content">₩32,000</div>
							</div>
						</div>

						<div class="infobox infobox-grey infobox-small infobox-dark">
							<div class="infobox-icon">
								<i class="icon-download-alt"></i>
							</div>

							<div class="infobox-data">
								<div class="infobox-content">Downloads</div>
								<div class="infobox-content">1,205</div>
							</div>
						</div>
					</div>

					<div class="vspace"></div>

					<div class="span5">
						<div class="widget-box">
							<div class="widget-header widget-header-flat widget-header-small">
								<h5>
									<i class="icon-signal"></i>
									전체 광고 노출 지역
								</h5>

							</div>

							<div class="widget-body">
								<div class="widget-main">
									<div id="map_canvas" style="width:100%; height:300px;"></div>
									<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>
								</div><!--/widget-main-->
							</div><!--/widget-body-->
						</div><!--/widget-box-->
					</div><!--/span-->
				</div><!--/row-->

				<div class="hr hr32 hr-dotted"></div>

				<div class="row-fluid">
					<div class="span5">
						<div class="widget-box transparent">
							<div class="widget-header widget-header-flat">
								<h4 class="lighter">
									<i class="icon-star orange"></i>
									가장 많이 노출된 상점
								</h4>

								<div class="widget-toolbar">
									<a href="#" data-action="collapse">
										<i class="icon-chevron-up"></i>
									</a>
								</div>
							</div>

							<div class="widget-body">
								<div class="widget-main no-padding">
									<table class="table table-bordered table-striped">
										<thead>
											<tr>
												<th>
													<i class="icon-caret-right blue"></i>
													상점명
												</th>

												<th>
													<i class="icon-caret-right blue"></i>
													지출 광고비
												</th>

												<th class="hidden-phone">
													<i class="icon-caret-right blue"></i>
													상태
												</th>
											</tr>
										</thead>

										<tbody>
											<tr>
												<td>선릉 스타벅스</td>

												<td>
													<b class="green">₩4,200</b>
												</td>

												<td class="hidden-phone">
													<span class="label label-info arrowed-right arrowed-in">in progress</span>
												</td>
											</tr>

											<tr>
												<td>강남역 맥도날드</td>

												<td>
													<small>
														<s class="red"></s>
													</small>
													<b class="green">₩3,100</b>
												</td>

												<td class="hidden-phone">
													<span class="label label-info arrowed-right arrowed-in">in progress</span>
												</td>
											</tr>

											<tr>
												<td>홍대 타코벨</td>

												<td>
													<small>
														<s class="red"></s>
													</small>
													<b class="green">₩1,200</b>
												</td>

												<td class="hidden-phone">
													<span class="label label-info arrowed-right arrowed-in">in progress</span>
												</td>
											</tr>

											<tr>
												<td>시청역 국수나무</td>

												<td>
													<b class="green">₩960</b>
												</td>

												<td class="hidden-phone">
													<span class="label label-info arrowed-right arrowed-in">in progress</span>
												</td>
											</tr>

											<tr>
												<td>한양대 피자마루</td>

												<td>
													<small>
														<s class="red"></s>
													</small>
													<b class="green">₩460</b>
												</td>

												<td class="hidden-phone">
													<span class="label label-warning arrowed arrowed-right">END</span>
												</td>
											</tr>
										</tbody>
									</table>
								</div><!--/widget-main-->
							</div><!--/widget-body-->
						</div><!--/widget-box-->
					</div>

					<div class="span7">
						<div class="widget-box transparent">
							<div class="widget-header widget-header-flat">
								<h4 class="lighter">
									<i class="icon-signal"></i>
									광고 상품별 노출 횟수
								</h4>

								<div class="widget-toolbar">
									<a href="#" data-action="collapse">
										<i class="icon-chevron-up"></i>
									</a>
								</div>
							</div>

							<div class="widget-body">
								<div class="widget-main padding-4">
									<div id="chart_act_line" style="height:200px;"></div>
								</div><!--/widget-main-->
							</div><!--/widget-body-->
						</div><!--/widget-box-->
					</div>
				</div>

				<!--PAGE CONTENT ENDS HERE-->
			</div><!--/row-->
		</div><!--/#page-content-->

		<div id="ace-settings-container">
			<div class="btn btn-app btn-mini btn-warning" id="ace-settings-btn">
				<i class="icon-cog"></i>
			</div>

			<div id="ace-settings-box">
				<div>
					<div class="pull-left">
						<select id="skin-colorpicker" class="hidden">
							<option data-class="default" value="#438EB9">#438EB9</option>
							<option data-class="skin-1" value="#222A2D">#222A2D</option>
							<option data-class="skin-2" value="#C6487E">#C6487E</option>
							<option data-class="skin-3" value="#D0D0D0">#D0D0D0</option>
						</select>
					</div>
					<span>&nbsp; Choose Skin</span>
				</div>

				<div>
					<input type="checkbox" class="ace-checkbox-2" id="ace-settings-header" />
					<label class="lbl" for="ace-settings-header"> Fixed Header</label>
				</div>

				<div>
					<input type="checkbox" class="ace-checkbox-2" id="ace-settings-sidebar" />
					<label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
				</div>
			</div>
		</div><!--/#ace-settings-container-->
	</div><!--/#main-content-->
</div><!--/.fluid-container#main-container-->

<script>
	var map_nodes = <?php echo $map_data ?>;
	var chart_json = {
		start_time: 1370165594296,
		data: [{
			name: '음성 광고',
			data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
		}, {
			name: '쿠폰 발급',
			data: [-0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5]
		}, {
			name: '배너 광고',
			data: [-0.9, 0.6, 3.5, 8.4, 13.5, 17.0, 18.6, 17.9, 14.3, 9.0, 3.9, 1.0]
		}]
	};
</script>