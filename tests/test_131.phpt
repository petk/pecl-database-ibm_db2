--TEST--
IBM-DB2: db2_prepare / db2_execute: SELECT statement with parameter
--SKIPIF--
<?php require_once('skipif.inc'); ?>
--FILE--
<?php

require_once('connection.inc');

$conn = db2_connect($database, $user, $password);

if ($conn) {
    require_once('prepare.inc');
    $stmt = db2_prepare( $conn, "SELECT id, breed, name, weight
        FROM animals WHERE id = ?" );

    if (db2_execute($stmt, array(0))) {
        while ($row = db2_fetch_into($stmt)) {
            var_dump($row);
        }
    }
}
else {
    echo "Connection failed.\n";
}

?>
--EXPECT--
array(4) {
  [0]=>
  int(0)
  [1]=>
  string(3) "cat"
  [2]=>
  string(16) "Pook            "
  [3]=>
  string(4) "3.20"
}