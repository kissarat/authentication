<?php
if (isset($_GET['show'])) {
    foreach(gd_info() as $key => $value) {
        $value = !$value ? 0 : $value;
        echo "<div>$key: $value</div>";
    }
}
else
    phpinfo();

