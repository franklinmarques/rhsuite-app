<?php
header('Content-type: text/json');

// create an instance of the Database class and call it $db
include_once('../includes/database.php');

$db = new Database();
$eventos = $db->query("SELECT * FROM events ORDER BY date_to ASC");

echo '[';
$separator = "";

while ($row = mysql_fetch_object($eventos)) {
    echo $separator;
    echo '	{ "id": "' . $row->id . '", "date": "' . $row->date_to . '", "type": "meeting", "title": "' . $row->title . '", "description": "' . $row->description . '", "url": "' . $row->link . '", "status": "' . $row->status . '", "color": "' . $row->color . '" }';
    $separator = ",";
}

echo ']';