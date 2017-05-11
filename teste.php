<?php 

define('SERVER', '192.168.1.100\sage14');
define('PORT', '1579');
define('DATABASE', 'ECOSULdata');
define('USER', 'sa');
define('PASS', 'Ecosul2017#');


$pdo = new PDO('sqlsrv:Server=' . SERVER . ',' . PORT . ';Database=' . DATABASE . '', USER, PASS);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$query = $pdo->prepare("SELECT * FROM Item");
$query->execute();

echo '<pre>';
print_r( $query->fetchAll() );
echo '</pre>';
