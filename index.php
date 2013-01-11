<?php

require "upc.php";

$upc = new upc(null,4);
$number = "123456789015";
$upc->set_label("http://twitter.com/digitalphantom");

if(isset($_GET['multiple'])) {
	echo "<img src=\"".$upc->embed($number)."\" /><br/>";
	echo "<img src=\"".$upc->embed("563263569535")."\" /><br/>";
	echo "<img src=\"".$upc->embed("452136521582")."\" />";
}
else {
	$upc->build($number);
}

?>