<?php
	$_type = isset($_POST['type']) ? $_POST['type'] : '';
	$_name = isset($_POST['name']) ? $_POST['name'] : '';
	echo $W->newFile($_name, $_type);
