--TEST--
IBM-DB2: PECL bug 6572 -- SQL strings containing NULL characters
--SKIPIF--
<?php require_once('skipif.inc'); ?>
--FILE--
<?php

require_once('connection.inc');

$conn = db2_connect($database, $user, $password);
db2_autocommit( $conn, DB2_AUTOCOMMIT_OFF );

if ($conn) {
    $null_string = "scaly\0guy";
    print "PHP value of null_string is {$null_string}.\n";

    $stmt = db2_exec($conn, "INSERT INTO animals (breed, name) VALUES ('gecko', '{$null_string}')");
    $stmt = db2_prepare($conn, "SELECT breed, name FROM animals WHERE name = ?");
    $result = db2_execute($stmt, array($null_string));
    if (!$result) { 
        print("ERROR: " . db2_stmt_errormsg($stmt)); 
        exit();
    } 
    while ($row = db2_fetch_array($stmt)) { 
        var_dump($row);
    }

    db2_rollback($conn);
    db2_close($conn);
    
}
else {
    echo "Connection failed.\n";
}

?>
--EXPECT--
PHP value of null_string is scaly guy.
array(2) {
  [0]=>
  string(5) "gecko"
  [1]=>
  string(5) "scaly"
}
