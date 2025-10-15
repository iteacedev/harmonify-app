<?php
set_time_limit(0);

echo "Starting PHP runner to call index.php every 30 seconds...\n";

while (true) {
    $currentTime = date('Y-m-d H:i:s');
    echo "Running index.php at {$currentTime}\n";

    include 'index.php';

    echo "Sleeping for 30 seconds...\n\n";
    sleep(30);
}
?>
