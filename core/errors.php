<?php
    function errorMessage($type, $message) {
        if ($type == 'info'){
            echo '<div class="alert_info"><h3>'. $message .'</h3></div>';
        } elseif ($type == 'warning') {
            echo '<div class="alert warning"><h3>'. $message .'</h3></div>';
        } elseif ($type == 'success') {
            echo '<div class="alert success"><h3>'. $message .'</h3></div>';
        } elseif ($type == 'error') {
            echo '<div class="alert danger"><h3>'. $message .'</h3></div>';
        }
    }

?>