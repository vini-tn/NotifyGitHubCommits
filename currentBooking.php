<!DOCTYPE html>
<html lang="en">
<head>
    <!-- meta tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- styling for table -->
    <style>
        table, th, td {
          border: 1px solid black;
        }
        th, td {
          padding-left: 4px;
          padding-right: 4px;
        }
    </style>

    <!-- title of page -->
    <title>Current Bookings</title>
</head>
<header>
        <!-- heading for current bookings -->
        <h1>Current Bookings</h1>
        
        <!-- nav links -->
        <a href="makeBooking.php">[Make a booking]</a>
        <a href="index.php">[Return to Home page]</a>
</header>
<body>
    <?php
    //create connector to sql using config
    include "config.php";
    $DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

    //if connection is not successful
    if (mysqli_connect_errno()) {
        //display message
        echo "Error: Unable to connect to MySQL. " . mysqli_connect_error();
        exit; //stop processing the page further
    }

    //prepare a query and send it to the server to display in table
    $query = 'SELECT room.roomID, roomname, checkinDate, checkoutDate, firstname, lastname
                FROM room
                INNER JOIN booking ON room.roomID = booking.roomID
                INNER JOIN customer ON booking.customerID = customer.customerID';
    //executing the query to the connector using result
    $result = mysqli_query($DBC, $query);
    //if unable to retrieve results
    if (!$result) {
        //display message
        die('Error executing query: ' . mysqli_error($DBC));
    }
    //function takes result as a parameter and returns the number of rows in result
    $rowcount = mysqli_num_rows($result);

    //continued after html
    ?>
    <!--heading for table -->
    <h4>Current Bookings</h4>
    <!--table displays all bookings in the database -->
    <table>
        <tr>
            <!-- headings -->
            <th>Booking (room, dates)</th>
            <th>Customer</th>
            <th>Action</th>
        </tr>
        <?php
        //if successfully connected and retrieved results
        if ($rowcount > 0) {
            //while loop to display each field in each row
            while ($row = mysqli_fetch_assoc($result)) {
                //id variable to roomID for action links
                $id = $row['roomID'];
                //display roomname, checkinDate, checkoutDate
                echo '<tr><td>' . $row['roomname'] . ', ' . $row['checkinDate'] . ', ' . $row['checkoutDate'] . '</td>';
                //display firstname, lastname
                echo '<td>' . $row['firstname'] . ', ' . $row['lastname'] . '</td>';
                //action links that are corresponding to the roomID 'id=$id'
                echo '<td><a href="detailBooking.php?id=' . $id . '">[view]</a>';
                echo '<a href="editBooking.php?id=' . $id . '">[edit]</a>';
                echo '<a href="roomReview.php?id=' . $id . '">[manage reviews]</a>';
                echo '<a href="deleteBooking.php?id=' . $id . '">[delete]</a></td>';
                echo '</tr>' . PHP_EOL;
            }
            //if empty
        } else {
            //error message
            echo "<h2>No bookings found!</h2>";
        }
        mysqli_free_result($result); //free any memory used by the query
        mysqli_close($DBC); //close the connection once done
        ?>
    </table>
</body>
</html>
