<div class="container-fluid" id="main-container">
	<?php echo View::make('partials.sidebar')->with('name', $name); ?>
	<div id="main-content" class="clearfix">
		<?php echo View::make('partials.history'); ?>

		<div id="page-content" class="clearfix">
			<div class="page-header position-relative">
				<h1>
					Detail
					<small>
						<i class="icon-double-angle-right"></i>
						Manage List
					</small>
				</h1>
			</div><!--/.page-header-->

			<div class="row-fluid">
				<!--PAGE CONTENT BEGINS HERE-->

				

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