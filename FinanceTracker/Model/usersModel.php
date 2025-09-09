<?php
require_once('db_connection.php');

function getUserByEmail($con, $email) {
    $email = mysqli_real_escape_string($con, $email);
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) === 1) {
        return mysqli_fetch_assoc($result);
    } else {
        return null;
    }
}

function getActiveUserById($con, $id) {
    $id = mysqli_real_escape_string($con, $id);
    $sql = "SELECT * FROM users WHERE id = '$id' AND status = 'active'";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) === 1) {
        return mysqli_fetch_assoc($result);
    } else {
        return null;
    }
}

function getAdminUserById($con, $id) {
    $id = mysqli_real_escape_string($con, $id);
    $sql = "SELECT * FROM users WHERE id = '$id'";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    } else {
        return null;
    }
}

function addUser($con, $full_name, $email, $password, $user_type) {
    $full_name = mysqli_real_escape_string($con, $full_name);
    $email = mysqli_real_escape_string($con, $email);
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    $user_type = mysqli_real_escape_string($con, $user_type);

    $username_base = strtolower(str_replace(' ', '', $full_name));
    $username = $username_base;
    $counter = 1;

    while (true) {
        $check_sql = "SELECT id FROM users WHERE username = '$username'";
        $check_result = mysqli_query($con, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            $username = $username_base . $counter;
            $counter++;
        } else {
            break;
        }
    }

    $sql = "INSERT INTO users (full_name, username, email, password, user_type, status) 
            VALUES ('$full_name', '$username', '$email', '$password_hashed', '$user_type', 'Active')";
    if (mysqli_query($con, $sql)) {
        return "User added successfully! Username generated: " . $username;
    } else {
        return "Error adding user.";
    }
}

function deleteUser($con, $user_id) {
    $user_id = mysqli_real_escape_string($con, $user_id);
    $sql = "DELETE FROM users WHERE id = '$user_id'";
    
    if (mysqli_query($con, $sql)) {
        return "User deleted successfully!";
    } else {
        return "Error deleting user.";
    }
}

function updateUserType($con, $user_id, $new_user_type) {
    $user_id = mysqli_real_escape_string($con, $user_id);
    $new_user_type = mysqli_real_escape_string($con, $new_user_type);
    $sql = "UPDATE users SET user_type = '$new_user_type' WHERE id = '$user_id'";
    
    if (mysqli_query($con, $sql)) {
        return "User type updated successfully!";
    } else {
        return "Error updating user type.";
    }
}

function updateEmail($con, $user_id, $new_email) {
    $user_id = mysqli_real_escape_string($con, $user_id);
    $new_email = mysqli_real_escape_string($con, $new_email);
    $sql = "UPDATE users SET email = '$new_email' WHERE id = '$user_id'";

    if (mysqli_query($con, $sql)) {
        return "Email updated successfully!";
    } else {
        return "Error updating email.";
    }
}

function updateUserStatus($con, $user_id, $new_status) {
    $user_id = mysqli_real_escape_string($con, $user_id);
    $new_status = mysqli_real_escape_string($con, $new_status);
    $sql = "UPDATE users SET status = '$new_status' WHERE id = '$user_id'";

    if (mysqli_query($con, $sql)) {
        return "User status updated successfully!";
    } else {
        return "Error updating user status.";
    }
}

function searchUsersByEmail($con, $email) {
    $email = mysqli_real_escape_string($con, $email);
    $sql = "SELECT id, full_name, username, email, user_type, status, created_at FROM users WHERE email LIKE '%$email%'";
    $result = mysqli_query($con, $sql);
    $users = [];

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }
    }
    
    if (empty($users)) {
        return null;
    } else {
        return $users;
    }
}

function getAllUsers($con) {
    $sql = "SELECT id, full_name, username, email, user_type, status, created_at FROM users";
    $result = mysqli_query($con, $sql);
    $users = [];

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }
    }

    return $users; 
}

function getUsersCount($con) {
    $sql = "SELECT count(*) FROM users";
    $result = mysqli_query($con, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_row($result)[0];
    } else {
        return 0;
    }
}

function getActiveUsersCount($con) {
    $sql = "SELECT count(*) FROM users WHERE created_at > DATE_SUB(NOW(), INTERVAL 30 DAY)";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_row($result)[0];
    } else {
        return 0; 
    }
}

function getRecentUsersCount($con) {
    $sql = "SELECT count(*) FROM users WHERE created_at > DATE_SUB(NOW(), INTERVAL 30 DAY)";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_row($result)[0];
    } else {
        return 0;
    }
}

function getAdminUsersCount($con) {
    $sql = "SELECT count(*) FROM users WHERE user_type = 'admin'";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_row($result)[0];
    } else {
        return 0;
    }
}

function getBlockedUsersCount($con) {
    $sql = "SELECT count(*) FROM users WHERE status = 'Blocked'";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_row($result)[0];
    } else {
        return 0; 
    }
}

function getAdminUsers($con) {
    $sql = "SELECT id, full_name, username, email, status, created_at 
            FROM users 
            WHERE user_type = 'admin'
            ORDER BY created_at DESC";
    $result = mysqli_query($con, $sql);
    $admin_users = [];

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $admin_users[] = $row;
        }
    }

    return $admin_users;
}

?>
