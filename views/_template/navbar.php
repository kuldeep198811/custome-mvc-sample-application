<nav>
	<div>
		<h1>Cygnate Test</h1>
	</div>
	<div>
		<li><a href="#">Home</a></li>
		<li><a href="#">Student Listing</a></li>
		<li><a href="#">Add Students</a></li>
		<?php
		if(Session::get('loggin') == true){
		?>
		<li><a href="#">Logout</a></li>
		<?php }else { ?>
		<li><a href="#">Login</a></li>
		<?php } ?>
	</div>
	
</nav>