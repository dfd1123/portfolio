<?php

use Swagger\Annotations as SWG;



require("../vendor/autoload.php");

$swagger = \Swagger\scan('\routes');

dd($swagger);

header('Content-Type: application/json');



echo $swagger;



?>