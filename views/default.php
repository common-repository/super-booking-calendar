<?php

    if(is_user_logged_in() && current_user_can("activate_plugins")) {
        global $wpdb;
        $wpdb->query("CREATE TABLE IF NOT EXISTS booking (id INT PRIMARY KEY AUTO_INCREMENT, date INT, event_date DATE, event_name VARCHAR(255))");
        if (isset($_GET["delete-id"]) && is_numeric($_GET["delete-id"]) && isset($_GET["_wpnonce"])) {
            if ( ! wp_verify_nonce( $_GET["_wpnonce"], 'spininteractive' ) ) {
                die( 'This action is not allowed' );
            }
            $wpdb->query($wpdb->prepare("DELETE FROM booking WHERE id = %s LIMIT 1", $_GET["delete-id"]));
        }
        $date = (isset($_GET["d"]) && !empty($_GET["d"])) ? $_GET["d"] . date("Y") : date("mY");
        $bookings = ($wpdb->get_results("SELECT * FROM booking WHERE DATE_FORMAT(event_date, '%m%Y') = '$date' ORDER BY date ASC"));
    }
    $nonce = wp_create_nonce( 'spininteractive' );
?>

<style type="text/css">
    #wpbody table { width:95%; border-collapse: collapse;}
    #wpbody table th { background-color:#FFF; }
    #wpbody table td { background-color:#EFEFEF;height:100px; }
    #wpbody table th, #wpbody table td { border:1px solid #CCC; }
    #wpbody table th:first-child, #wpbody table td:first-child { width:110px;text-align:center }
    #notice { background-color:#FFF;width:95%; }
    #wpbody div { margin:10px 0 }
</style>
<div id="wpbody">
    <h1>Booking</h1>

    <select onchange="location.href='?page=super-booking-calendar&d='+this.value">
        <option value="01" <?php if($date[0].$date[1] == "01") echo "selected"; ?>>January</option>
        <option value="02" <?php if($date[0].$date[1] == "02") echo "selected"; ?>>February</option>
        <option value="03" <?php if($date[0].$date[1] == "03") echo "selected"; ?>>March</option>
        <option value="04" <?php if($date[0].$date[1] == "04") echo "selected"; ?>>April</option>
        <option value="05" <?php if($date[0].$date[1] == "05") echo "selected"; ?>>Mai</option>
        <option value="06" <?php if($date[0].$date[1] == "06") echo "selected"; ?>>June</option>
        <option value="07" <?php if($date[0].$date[1] == "07") echo "selected"; ?>>July</option>
        <option value="08" <?php if($date[0].$date[1] == "08") echo "selected"; ?>>August</option>
        <option value="09" <?php if($date[0].$date[1] == "09") echo "selected"; ?>>September</option>
        <option value="10" <?php if($date[0].$date[1] == "10") echo "selected"; ?>>October</option>
        <option value="11" <?php if($date[0].$date[1] == "11") echo "selected"; ?>>November</option>
        <option value="12" <?php if($date[0].$date[1] == "12") echo "selected"; ?>>December</option>
    </select>

    <div>
        <button onclick="location.href='?page=super-booking-calendar&view=form'">New event</button>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Event name</th>
                    <th></th>
                </tr>
            </thead>
        <?php foreach($bookings as $event): ?>
            <tr>
                <td><?php list($y, $m, $d) = explode("-", $event->event_date); echo "$d-$m-$y"; ?> </td>
                <td><?php echo $event->event_name ?></td>
                <td><button onclick="location.href='?page=super-booking-calendar&delete-id=<?php echo $event->id ?>&_wpnonce=<?php echo $nonce ?>'">Delete</button></td>
            </tr>
        <?php endforeach; ?>
        </table>
    </div>
    <div id="notice">Calendar Shortcode: [super-booking-calendar]</div>
</div>


