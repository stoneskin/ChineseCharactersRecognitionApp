<?php
require "../connect.php";
//require "../_needSession.php";


// api to return list of words by give grade and number of words
// could used for javascript ajax 
//http://localhost:3000/src/api/words.php?grade=1&numberOfWords=5
/*
Javascript code example
var settings = {
  "url": "http://localhost:3000/src/api/words.php?grade=1&numberOfWords=5",
  "method": "GET",
  "timeout": 0,
  "headers": {
    "Cookie": "PHPSESSID=c7l22tgsrkbvofv6kg2v78kepe"
  },
};

$.ajax(settings).done(function (response) {
  console.log(response);
});
*/
$grade=1;
$numberOfWords=10;
if (isset($_GET['grade'])){
    $grade = $_GET['grade']; 
}
if (isset($_GET['numberOfWords'])){
    $numberOfWords =$_GET['numberOfWords'];
}

$sql_words = sprintf(
    "SELECT ID, Words FROM wordslibrary WHERE Level = %s  ORDER BY RAND() limit %s",
    $conn->real_escape_string($grade),
    $conn->real_escape_string($numberOfWords));

$result = $conn->query($sql_words);       
$rows = $result->fetch_all(MYSQLI_ASSOC);

//$cars = array("Volvo", "BMW", "Toyota");

echo json_encode($rows);
?>