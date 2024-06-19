<?php
//create connector to sql using config
include "config.php";
$DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE) or die("Connection error: " . mysqli_connect_error());

//retrieve dates from search
$sqa = isset($_GET['searchStart']) ? $_GET['searchStart'] : '';
$sqb = isset($_GET['searchEnd']) ? $_GET['searchEnd'] : '';
//create variable to use when getting values from database
$searchresult = '';

//check if dates are not empty
if (!empty($sqa) && !empty($sqb)) {
    //prepare a query and send it to the server to display in table
    $query = "SELECT * FROM room WHERE roomID NOT IN (SELECT roomID FROM booking WHERE checkinDate >= '$sqa' AND checkoutDate <= '$sqb')";
    //executing the query to the connector using result
    $result = mysqli_query($DBC, $query);
    //function takes result as a parameter and returns the number of rows in result
    $rowcount = mysqli_num_rows($result);
    //if rowcount not empty
    if ($rowcount > 0) {
        //creating rows array to store rowcount values
        $rows = [];
        //while loop to store each field in each row for roomSearch table
        while ($row = mysqli_fetch_assoc($result)) {
            //split rows by field
            $room = array(
                'number' => $row['roomID'],
                'name' => $row['roomname'],
                'type' => $row['roomtype'],
                'beds' => $row['beds']
            );
            //add room rows to rows array
            $rows[] = $room;
        }

        // Encode the array of rooms to JSON
        $searchresult = json_encode(array('availableRooms' => $rows));
        // Set the proper Content-Type header
        header('Content-Type: application/json');
    } else { //empty array
        $searchresult = array('availableRooms' => []);
    }
} else {//empty array
    $searchresult = array('availableRooms' => []);
}
//close connection
mysqli_close($DBC);
//send back to search in makeBooking
echo $searchresult;
?>
