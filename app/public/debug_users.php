<?php
require( './wp-load.php' );
$users = get_users();
foreach ( $users as $user ) {
    echo "ID: " . $user->ID . " | Login: " . $user->user_login . " | Email: " . $user->user_email . " | Roles: " . implode( ', ', $user->roles ) . "\n";
}
