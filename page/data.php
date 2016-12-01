<?php

require("../functions.php");

require("../class/Helper.class.php");
$Helper = new Helper();

require("../class/Rides.class.php");
$Rides = new Rides($mysqli);

if (!isset($_SESSION["userId"])) {
  header("Location: login.php");
  exit();
}

if (isset($_GET["logout"])) {

  session_destroy();

  header("Location: login.php");
  exit();

}



//Check if forms are filled
$start_location = "";
$start_time = "";
$arrival_location = "";
$arrival_time = "";
$free_seats = "";
$price = "";

$emptyStartL = "*";
$emptyStartT = "*";
$emptyArrivalL = "*";
$emptyArrivalT = "*";
$emptySeats = "*";

if(isset($_GET["register"])){

  $Rides->registertoride($_GET["register"]);

  header("Location: data.php");
  exit();

}

if (isset ($_POST["start_location"])) {
    if (empty ($_POST["start_location"])) {
        $emptyStartL = "* Please fill in starting location!";
    } else {
        $start_location = $Helper->cleanInput($_POST["start_location"]);
    }
}

if (isset ($_POST["start_time"])) {
    if (empty ($_POST["start_time"])) {
        $emptyStartT = "* Please fill in start time!";
    } else {
        $start_time = $Helper->cleanInput($_POST["start_time"]);
    }
}

if (isset ($_POST["arrival_location"])) {
    if (empty ($_POST["arrival_location"])) {
        $emptyArrivalL = "* Please fill in arrival location!";
    } else {
        $arrival_location = $Helper->cleanInput($_POST["arrival_location"]);
    }
}

if (isset ($_POST["arrival_time"])) {
    if (empty ($_POST["arrival_time"])) {
        $emptyArrivalT = "* Please fill in arrival time!";
    } else {
        $arrival_time = $Helper->cleanInput($_POST["arrival_time"]);
    }
}

if (isset ($_POST["free_seats"])) {
    if (empty ($_POST["free_seats"])) {
        $emptySeats = "* Please fill in number of seats!";
    } else {
        $free_seats = $Helper->cleanInput($_POST["free_seats"]);
    }
}

if(isset ($_POST["price"])) {

  $price = $Helper->cleanInput($_POST["price"]);
}

//If forms are filled
if (isset($_POST["start_location"]) &&
isset($_POST["start_time"]) &&
isset($_POST["arrival_location"]) &&
isset($_POST["arrival_time"]) &&
isset($_POST["free_seats"]) &&

!empty($_POST["start_location"]) &&
!empty($_POST["start_time"]) &&
!empty($_POST["arrival_location"]) &&
!empty($_POST["arrival_time"]) &&
!empty($_POST["free_seats"]))




{
    //echo = "Saved";
    $Rides->save($start_location, $start_time, $arrival_location,
    $arrival_time, $free_seats, $price);
    header("Location: data.php");
    exit();
 }

$upcomingRides = $Rides->get();
?>

<h1>Data</h1>

<p>

    Welcome <?=$_SESSION["userEmail"];?>!
    <br><br>
    <a href="?logout=1">Log out</a>
    <br><br>
    <a href="user.php">User page</a>
</p>


<h2>Register a ride</h2>
<form method="POST" >

    <label>Start location</label><br>
    <input name="start_location" type="text" value="<?=$start_location;?>"> <?php echo $emptyStartL; ?>

    <br><br>
    <label>Start time</label><br>
    <input name="start_time" type="datetime-local" value="<?=$start_time;?>"> <?php echo $emptyStartT; ?>

    <br><br>
    <label>Arrival location</label><br>
    <input name="arrival_location" type="text" value="<?=$arrival_location;?>"> <?php echo $emptyArrivalL; ?>

    <br><br>
    <label>Arrival time</label><br>
    <input name="arrival_time" type="datetime-local" value="<?=$arrival_time;?>"> <?php echo $emptyArrivalT; ?>

    <br><br>
    <label>Free seats</label><br>
    <input name="free_seats" type="number" value="<?=$free_seats;?>"> <?php echo $emptySeats; ?>

    <br><br>
    <label>Price</label><br>
    <input name="price" type="number">

    <br><br>
    <input type="submit" value="Submit">

</form>

<h2>Find a ride</h2>

<?php

    $html = "<table>";

        $html .= "<tr>";
            $html .= "<th>email</th>";
            $html .= "<th>start_location</th>";
            $html .= "<th>start_time</th>";
            $html .= "<th>arrival_location</th>";
            $html .= "<th>arrival_time</th>";
            $html .= "<th>free_seats</th>";
            $html .= "<th>price</th>";
        $html .= "</tr>";

        //iga liikme kohta massiivis
        foreach ($upcomingRides as $r) {
          
            $html .= "<tr>";
                $html .= "<td>".$r->email."</td>";
                $html .= "<td>".$r->start_location."</td>";
                $html .= "<td>".$r->start_time."</td>";
                $html .= "<td>".$r->arrival_location."</td>";
                $html .= "<td>".$r->arrival_time."</td>";
                $html .= "<td>".$r->free_seats."</td>";
                $html .= "<td>".$r->price."</td>";
                $html .= "<td><a href='?register=".$r->id."'>nupp ".$r->id."</a></td>";
            $html .= "</tr>";

        }

    $html .= "</table>";

    echo $html;

    ?>
