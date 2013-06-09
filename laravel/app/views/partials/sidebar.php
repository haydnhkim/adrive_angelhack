<a id="menu-toggler" href="#">
	<span></span>
</a>

<div id="sidebar">
	<div id="sidebar-shortcuts">
		<div id="sidebar-shortcuts-large">
			<button class="btn btn-small btn-success">
				<i class="icon-signal"></i>
			</button>

			<button class="btn btn-small btn-info">
				<i class="icon-pencil"></i>
			</button>

			<button class="btn btn-small btn-warning">
				<i class="icon-group"></i>
			</button>

			<button class="btn btn-small btn-danger">
				<i class="icon-cogs"></i>
			</button>
		</div>

		<div id="sidebar-shortcuts-mini">
			<span class="btn btn-success"></span>

			<span class="btn btn-info"></span>

			<span class="btn btn-warning"></span>

			<span class="btn btn-danger"></span>
		</div>
	</div><!--#sidebar-shortcuts-->

	<ul class="nav nav-list">
		<?php $on = ' class="active"'; ?>
		<li<?php echo ($name == 'main')?$on:'';?>>
			<a href="/">
				<i class="icon-dashboard"></i>
				<span>Dashboard</span>
			</a>
		</li>

		<li<?php echo ($name == 'manage_list' || $name == 'manage_regist')?$on . ' open':'';?>>
			<a href="/manage" class="dropdown-toggle">
				<i class="icon-edit"></i>
				<span>광고 상품 관리</span>

				<b class="arrow icon-angle-down"></b>
			</a>

			<ul class="submenu">
				<li<?php echo ($name == 'manage_list')?$on:'';?>>
					<a href="/manage/list">
						<i class="icon-double-angle-right"></i>
						광고 상품 리스트
					</a>
				</li>

				<li<?php echo ($name == 'manage_regist')?$on:'';?>>
					<a href="/manage/regist">
						<i class="icon-double-angle-right"></i>
						광고 상품 등록
					</a>
				</li>
			</ul>
		</li>

		<li<?php echo ($name == 'detail')?$on:'';?>>
			<a href="/detail">
				<i class="icon-desktop"></i>
				<span>광고 상품 상세정보</span>
			</a>
		</li>

		<li<?php echo ($name == 'order')?$on:'';?>>
			<a href="/order">
				<i class="icon-credit-card"></i>
				<span>광고 상품 구매</span>
			</a>
		</li>
	</ul><!--/.nav-list-->

	<div id="sidebar-collapse">
		<i class="icon-double-angle-left"></i>
	</div>
</div>