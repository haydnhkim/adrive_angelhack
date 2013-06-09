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
				<?php if (Session::has('ok')){ ?>
				<div class="alert alert-block alert-success">
					<button type="button" class="close" data-dismiss="alert">
						<i class="icon-remove"></i>
					</button>

					<i class="icon-ok green"></i>
					등록 성공!
				</div>
				<?php }elseif (Session::has('error')){ ?>
				<div class="alert alert-block alert-error">
					<button type="button" class="close" data-dismiss="alert">
						<i class="icon-remove"></i>
					</button>

					<i class="icon-remove"></i>
					<?php echo Session::get('error'); ?>
				</div>
				<?php } ?>

				<div class="span5">
					<form method="post" enctype="multipart/form-data" class="form-horizontal">
						<div class="control-group">
							<label class="control-label" for="name">상점 이름</label>
					
							<div class="controls">
								<input type="text" id="name" name="name" placeholder="상점 이름" />
							</div>
						</div>
					
						<div class="control-group">
							<label class="control-label" for="category">카테고리</label>
					
							<div class="controls">
								<?php
								$category = array(
									'커피/베이커리',
									'패밀리 레스토랑',
									'푸드',
									'편의점'
								);
								?>
								<select id="category" name="category">
									<option value=""></option>
									<?php foreach($category as $row){ ?>
									<option value="<?php echo $row; ?>"><?php echo $row; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					
						<div class="control-group">
							<label class="control-label" for="ll">위도, 경도</label>
					
							<div class="controls">
								<input type="text" id="ll" name="y" class="span5 lat" readonly="readonly" placeholder="위도" />
								<input type="text" name="x" class="span5 lng" readonly="readonly" placeholder="경도" />
							</div>
						</div>
					
						<div class="control-group">
							<label class="control-label" for="address">주소</label>
					
							<div class="controls">
								<input type="text" id="address" name="address" class="span10" readonly="readonly" />
							</div>
						</div>

						<div class="control-group">
							<label class="control-label" for="phone">전화번호</label>
					
							<div class="controls">
								<div class="input-prepend">
									<span class="add-on">
										<i class="icon-phone"></i>
									</span>

									<input class="input-medium input-mask-phone" type="text" id="phone" name="phone" maxlength="11" style="ime-mode:disabled" />
								</div>
							</div>
						</div>
					
						<div class="control-group">
							<label class="control-label" for="description">설명</label>
					
							<div class="controls">
								<textarea class="span10 limited" id="description" name="description" data-maxlength="50"></textarea>
							</div>
						</div>
					
						<div class="control-group">
							<label class="control-label" for="img">상점 사진 업로드</label>
					
							<div class="controls">
								<div class="span10">
									<input type="file" id="img" name="img" />
								</div>
							</div>
						</div>
					
						<div class="control-group">
							<label class="control-label" for="file_url">광고 음원 업로드</label>
					
							<div class="controls">
								<div class="span10">
									<input type="file" id="file_url" name="file_url" />
								</div>
							</div>
						</div>
					
						<div class="form-actions">
							<button class="btn btn-info" type="submit">
								<i class="icon-ok bigger-110"></i>
								Submit
							</button>
						</div>
					</form>
				</div>

				<div class="span5">
					<ul class="unstyled spaced2">
						<li class="text-warning orange">
							<i class="icon-warning-sign"></i>
							위도, 경도, 주소를 입력하시려면 지도를 클릭해 주세요.
						</li>

					</ul>
					<div id="map_canvas" style="width:100%; height:300px;"></div>
					<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>
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