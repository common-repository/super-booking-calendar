<?php
if(is_user_logged_in() && current_user_can("activate_plugins")) {
    global $wpdb;
    $error = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (preg_match("@^[0-9]{2}-[0-9]{2}-[0-9]{4}$@", $_POST["event_date"]) && !empty($_POST["event_name"]) && isset($_POST["_wpnonce"])) {
            if ( ! wp_verify_nonce( $_POST["_wpnonce"], 'spininteractive' ) ) {
                die( 'This action is not allowed' );
            }
            list($d, $m, $y) = explode("-", sanitize_text_field($_POST["event_date"]));
            $wpdb->query($wpdb->prepare("INSERT INTO booking (date, event_date, event_name) VALUES (%d, %s, %s)", time(), "$y-$m-$d", sanitize_text_field($_POST["event_name"])));
            if (empty($wpdb->last_error)) {
                print <<<SCRIPT
         <script>location.href="?page=super-booking-calendar"</script>
SCRIPT;
            } else {
                echo($wpdb->last_error);
            }
        } else {
            $error = array();
            if (empty($_POST["event_date"])) $error[] = ("Please fill event date field !");
            if (empty($_POST["event_name"])) $error[] = ("Please fill event name field !");
        }
    }
}
$nonce = wp_create_nonce( 'spininteractive' );
?>

<style type="text/css">
    #wpbody form { border:1px solid #CCC; width:90%;padding:40px;background-color:#FFF }
    #wpbody form input[type=text] { width:100%;height:40px;margin:12px 0; }
    #wpbody form textarea { width: 500px;height:100px; }
</style>
<div id="wpbody">
    <h1>NEW EVENT</h1>
    <form method="post" action="?page=super-booking-calendar&view=form" enctype="multipart/form-data">
        <?php if($error != "") echo implode("<br/>", $error) ?>
        <input id="datepicker" class="color-placeholder" value="" type="text" name="event_date" placeholder="Event date*">
        <br/><input class="color-placeholder" value="" type="text" name="event_name" id="name" placeholder="Event name">
        <input type="hidden" name="_wpnonce" value="<?php echo $nonce ?>" />
        <input type="submit" value="Save">
    </form>
    <script type="text/javascript">
        jQuery(document).ready(function($){
            $( "#datepicker" ).datepicker({dateFormat: "dd-mm-yy"});
        });
    </script>
</div>

