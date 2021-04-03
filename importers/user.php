<?php

function userExists($username)
{
    global $nextcloud_pdo;

    $stmt = $nextcloud_pdo->prepare("SELECT * FROM oc_users WHERE uid=:username");
    $stmt->execute([
        ":username" => $username
    ]);
    
    return $stmt->fetch() !== false;
}
