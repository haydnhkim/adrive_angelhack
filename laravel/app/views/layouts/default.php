<?php echo View::make('partials.header'); ?>
	<?php echo View::make('partials.navbar'); ?>
	<?php echo $content; ?>
<?php echo View::make('partials.footer')->with('name', $name); ?>