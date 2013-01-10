<?php

require "upc.php";

$upc = new upc(null,4);
$number = "123456789015";
$upc->set_label("http://twitter.com/digitalphantom");
$upc->build($number);

?>