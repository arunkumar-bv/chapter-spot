<?php
$threads = array();
$assigns = array();
$moduleridarr = array();
$moduledirids = array();


$con = mysql_connect("localhost", "root", "password");

if (!$con) {
  die( 'Could not connect: ' . mysql_error());
}


mysql_select_db("cscom_cs40", $con);

$result = mysql_query("SELECT * FROM n_module_assigns  limit 1000");

while ( $row = mysql_fetch_array($result)) {
  $test = array();
  $moduledirid = $row['moduledirid'];
  $test['assignid'] = $row['assignid'];
  $test['relationid'] = $row['relationid'];
  $test['date'] = $row['date'];
  $test['deleted'] = $row['deleted'];
  $test['moduletypeid'] = $row['moduletypeid'];
  $test['time_application'] = $row['time_application'];
  $test['assign_moduledirid'] = $row['assign_moduledirid'];
  $assigns[$moduledirid] = $test;
}


echo "<xmp>";
print_r($assigns);
echo "</xmp>";


$result = mysql_query("SELECT * FROM n_module_assigns order by assignid , relationid  ,assign_moduledirid limit 1000");
$assignid = - 1;
$relationid = - 1;
$assign_moduledirid = - 1;
$moduledirid = - 1;
$buff = array();
while ( $row = mysql_fetch_array($result)) {
  if ($assignid != $row['assignid'] || $relationid != $row['relationid'] || $assign_moduledirid != $row['assign_moduledirid']) {
    $assignid = $row['assignid'];
    $relationid = $row['relationid'];
    $assign_moduledirid = $row['assign_moduledirid'];
    if ( count($buff) > 0 )
      $moduledirids[] = $buff;
    $buff = array();
    array_push($buff, $row['moduledirid']);
  } else {
    array_push($buff, $row['moduledirid']);
  }
}


$moduledirids[] = $buff;

echo "<xmp>";
print_r($moduledirids);
echo "</xmp>";


for ( $i = 1; $i < count($assigns); $i++ ) {
  mysql_query("INSERT INTO threads (threadtitle, submitterid, date,deleted)
VALUES ('',123, 123,0)");
  $threadid = mysql_insert_id();
  mysql_query("INSERT INTO n_module_directory2 (date, deleted, moduleid,moduletypeid,moduletitle,submitterid,group_collab)
VALUES (123,0, " . $threadid . ",900,'',123,1)");
  $moduledid = mysql_insert_id();
  $realtionid = $assigns[$i]['relationid'];
  $assign_moduledirid = $assigns[$i]['assign_moduledirid'];
  $moduletypeid = $assigns[$i]['moduletypeid'];
  $date_asigns = $assigns[$i]['date'];
  $assignid = $assigns[$i]['assignid'];
  $time_application = $assigns[$i]['time_application'];
  $deleted = $assigns[$i]['deleted'];
  mysql_query("INSERT INTO n_module_assigns2 (moduledirid,moduletypeid, assign_moduledirid, relationid,assignid,date,time_application,deleted)
VALUES (" . $moduledid . "," . $moduletypeid . "," . $assign_moduledirid . "," . $realtionid . "," . $assignid . "," . $date_asigns . "," . $time_application . "," . $deleted . ")");
}


mysql_close($con);
?>