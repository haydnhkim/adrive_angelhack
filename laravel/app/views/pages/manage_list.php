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
						Manage List
					</small>
				</h1>
			</div><!--/.page-header-->

			<div class="row-fluid">
				<!--PAGE CONTENT BEGINS HERE-->

				<div class="row-fluid">
					<div class="span12">
						<table id="table_bug_report" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th class="center">
										사진
									</th>
									<th>상점명</th>
									<th>카테고리</th>
									<th>지역</th>
									<th>전화번호</th>
									<th>
										<i class="icon-time"></i>
										기간
									</th>
									<th>광고횟수</th>
									<th>음성파일</th>
								</tr>
							</thead>

							<tbody>
								<?php
								if(!empty($list)){
									foreach ($list as $row) {
								?>
								<tr>
									<td class="center">
										<img src="<?php echo $row->img; ?>" width="30" height="20" alt="" style="width:30px;height:20px;">
									</td>
									<td><?php echo $row->name; ?></td>
									<td><?php echo $row->category; ?></td>
									<td><?php echo $row->address; ?></td>
									<td><?php echo $row->phone; ?></td>
									<td><?php echo date('Y-m-d', $row->signdate); ?> ~ 2013-07-23</td>
									<td><?php echo $row->count_view; ?></td>
									<td class="center">
										<audio src="<?php echo $row->file_url; ?>" controls style="width:80px;">
											HTML5 audio not supported
										</audio>
									</td>
									<!--<td>
										<div class="inline position-relative">
											<button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown">
												<i class="icon-cog icon-only bigger-110"></i>
											</button>

											<ul class="dropdown-menu dropdown-icon-only dropdown-light pull-right dropdown-caret dropdown-close">
												<li>
													<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit" data-placement="left">
														<span class="green">
															<i class="icon-edit"></i>
														</span>
													</a>
												</li>

												<li>
													<a href="#" class="tooltip-warning" data-rel="tooltip" title="Flag" data-placement="left">
														<span class="blue">
															<i class="icon-flag"></i>
														</span>
													</a>
												</li>

												<li>
													<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete" data-placement="left">
														<span class="red">
															<i class="icon-trash"></i>
														</span>
													</a>
												</li>
											</ul>
										</div>
									</td>-->
								</tr>
								<?php
									}
								}?>
							</tbody>
						</table>
					</div><!--/span-->
				</div><!--/row-->

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