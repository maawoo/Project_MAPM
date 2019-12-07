<?php
$writeFile = fopen('./test.yaml', 'w');

$yamlTemplate = file_get_contents('template.yaml');
var_dump($_POST);
$var1 = $_POST['var1'];
$var2 = $_POST['var2'];
$var3 = $_POST['var3'];
$var4 = $_POST['var4'];
$var5 = $_POST['var5'];
$var6 = $_POST['var6'];
$var7 = $_POST['var7'];
$var8 = $_POST['var8'];


$replaceString = '( (a * ' . $var1 . ') + (b * ' . $var2 . ') + (c * ' . $var3 .
') + (d * ' . $var4 . ') + (e * ' . $var5 . ') + (f * ' . $var6 . ') + (g * ' . $var7 .
') + (h * ' . $var8 . ') ) / ( '
. $var1 . ' + ' . $var2 . ' + ' . $var3 . ' + ' . $var4 . ' + ' .
$var5 . ' + ' . $var6 . ' + ' . $var7 . ' + ' . $var8 .' );' ;

if ($var1 == 0 && $var2 == 0 && $var3 == 0 && $var4 == 0 &&
$var5 == 0 && $var6 == 0 && $var7 == 0 && $var8 == 0){
  $replaceString = 1;
}

$yaml = str_replace('_REPLACE_ME_', $replaceString, $yamlTemplate);

fwrite($writeFile, $yaml);
fclose($writeFile);
 ?>
