<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('location: login.php?error=badrequest');
    exit();
}

include '../Model/db_connection.php';

$user_id = $_SESSION['user_id'];
$sql_user = "SELECT * FROM users WHERE id = '$user_id'";
$result_user = mysqli_query($con, $sql_user);
$admin_user = mysqli_fetch_assoc($result_user);

$success_message = '';
$display_users = [];
$search_message = '';
$search_performed = false;
$search_email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_user'])) {

        $full_name = mysqli_real_escape_string($con, $_POST['full_name']);
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $user_type = mysqli_real_escape_string($con, $_POST['user_type']);
        
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
                VALUES ('$full_name', '$username', '$email', '$password', '$user_type', 'Active')";
        mysqli_query($con, $sql);
        
        $success_message = "User added successfully! Username generated: " . $username;
        
        $_SESSION['active_section'] = 'user-management-section';
    } elseif (isset($_POST['delete_user'])) 
    {
        $user_id_to_delete = mysqli_real_escape_string($con, $_POST['user_id']);
        $sql = "DELETE FROM users WHERE id = '$user_id_to_delete'";
        mysqli_query($con, $sql);
        
        $success_message = "User deleted successfully!";
        
        $_SESSION['active_section'] = 'user-management-section';
    } elseif (isset($_POST['update_user_type'])) {
        $user_id_to_update = mysqli_real_escape_string($con, $_POST['user_id']);
        $new_user_type = mysqli_real_escape_string($con, $_POST['user_type']);
        $sql = "UPDATE users SET user_type = '$new_user_type' WHERE id = '$user_id_to_update'";
        mysqli_query($con, $sql);
        
        $success_message = "User type updated successfully!";
        
        $_SESSION['active_section'] = 'user-management-section';
    } elseif (isset($_POST['update_email'])) {
        $user_id_to_update = mysqli_real_escape_string($con, $_POST['user_id']);
        $new_email = mysqli_real_escape_string($con, $_POST['email']);
        $sql = "UPDATE users SET email = '$new_email' WHERE id = '$user_id_to_update'";
        mysqli_query($con, $sql);
        
        $success_message = "Email updated successfully!";
        
        $_SESSION['active_section'] = 'user-management-section';
    } elseif (isset($_POST['update_status'])) {
        $user_id_to_update = mysqli_real_escape_string($con, $_POST['user_id']);
        $new_status = mysqli_real_escape_string($con, $_POST['status']);
        $sql = "UPDATE users SET status = '$new_status' WHERE id = '$user_id_to_update'";
        mysqli_query($con, $sql);
        
        $action = ($new_status == 'Blocked') ? 'blocked' : 'activated';
        $success_message = "User $action successfully!";
        
        $_SESSION['active_section'] = 'user-management-section';
    } elseif (isset($_POST['search_user'])) {
        $search_email = mysqli_real_escape_string($con, $_POST['search_email']);
        $sql_search = "SELECT id, full_name, username, email, user_type, status, created_at FROM users WHERE email LIKE '%$search_email%'";
        $result_search = mysqli_query($con, $sql_search);
        
        if (mysqli_num_rows($result_search) > 0) {
            while($row = mysqli_fetch_assoc($result_search)) {
                $display_users[] = $row;
            }
        } else {
            $search_message = "No users found matching the email: " . htmlspecialchars($search_email);
        }
        
        $_SESSION['active_section'] = 'user-management-section';
        $_SESSION['search_performed'] = true;
        $search_performed = true;
    } elseif (isset($_POST['clear_search'])) {
        $search_email = '';
        $_SESSION['search_performed'] = false;
        $search_performed = false;
        
        $_SESSION['active_section'] = 'user-management-section';
        
        $sql_all = "SELECT id, full_name, username, email, user_type, status, created_at FROM users";
        $result_all = mysqli_query($con, $sql_all);
        if (mysqli_num_rows($result_all) > 0) {
            while($row = mysqli_fetch_assoc($result_all)) {
                $display_users[] = $row;
            }
        }
    }
}

if (empty($display_users)) {
    $sql_all = "SELECT id, full_name, username, email, user_type, status, created_at FROM users";
    $result_all = mysqli_query($con, $sql_all);
    if (mysqli_num_rows($result_all) > 0) {
        while($row = mysqli_fetch_assoc($result_all)) {
            $display_users[] = $row;
        }
    }
}
$active_section = isset($_SESSION['active_section']) ? $_SESSION['active_section'] : 'dashboard-section';
$search_performed = isset($_SESSION['search_performed']) ? $_SESSION['search_performed'] : false;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    unset($_SESSION['active_section']);
    unset($_SESSION['search_performed']);
}

$sql_count_all = "SELECT count(*) FROM users";
$result_count_all = mysqli_query($con, $sql_count_all);
$total_users_count = mysqli_fetch_row($result_count_all)[0];

$sql_count_active = "SELECT count(*) FROM users WHERE created_at > DATE_SUB(NOW(), INTERVAL 30 DAY)";
$result_count_active = mysqli_query($con, $sql_count_active);
$active_users_count = mysqli_fetch_row($result_count_active)[0];

$sql_count_recent = "SELECT count(*) FROM users WHERE created_at > DATE_SUB(NOW(), INTERVAL 30 DAY)";
$result_count_recent = mysqli_query($con, $sql_count_recent);
$recent_users_count = mysqli_fetch_row($result_count_recent)[0];

$sql_count_admin = "SELECT count(*) FROM users WHERE user_type = 'admin'";
$result_count_admin = mysqli_query($con, $sql_count_admin);
$admin_users_count = mysqli_fetch_row($result_count_admin)[0];

$sql_count_blocked = "SELECT count(*) FROM users WHERE status = 'Blocked'";
$result_count_blocked = mysqli_query($con, $sql_count_blocked);
$blocked_users_count = mysqli_fetch_row($result_count_blocked)[0];

$sql_admin_users = "SELECT id, full_name, username, email, status, created_at 
                    FROM users 
                    WHERE user_type = 'admin'
                    ORDER BY created_at DESC";
$result_admin_users = mysqli_query($con, $sql_admin_users);
$admin_users = [];
if (mysqli_num_rows($result_admin_users) > 0) {
    while($row = mysqli_fetch_assoc($result_admin_users)) {
        $admin_users[] = $row;
    }
}


mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Finance Tracker - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../Asset/admin-dashboard.css" />
</head>

<body class="finance-body-default-style">
    <div class="finance-overlay-background" id="mobileOverlay"></div>

    <div class="finance-admin-modal" id="logoutModal">
        <div class="finance-admin-modal-content">
            <h3 class="finance-modal-title">Confirm Logout</h3>
            <p>Are you sure you want to logout?</p>
            <form method="post" action="../Controller/logout.php">
                <div class="finance-modal-buttons">
                    <button type="button" class="finance-cancel-btn" id="cancelLogout">Cancel</button>
                    <button type="submit" class="finance-confirm-btn">Logout</button>
                </div>
            </form>
        </div>
    </div>

    <div class="finance-admin-modal" id="addUserModal">
        <div class="finance-admin-modal-content">
            <h3 class="finance-modal-title">Add New User</h3>
            <form method="post" id="addUserForm">
                <div class="finance-form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" required>
                </div>
                <div class="finance-form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="finance-form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="finance-form-group">
                    <label for="user_type">User Type</label>
                    <select id="user_type" name="user_type">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="finance-modal-buttons">
                    <button type="button" class="finance-cancel-btn" id="cancelAddUser">Cancel</button>
                    <button type="submit" class="finance-confirm-btn" name="add_user">Add User</button>
                </div>
            </form>
        </div>
    </div>

    <div class="finance-admin-modal" id="editUserModal">
        <div class="finance-admin-modal-content">
            <h3 class="finance-modal-title">Edit User Type</h3>
            <form method="post" id="editUserForm">
                <input type="hidden" id="edit_user_id" name="user_id">
                <div class="finance-form-group">
                    <label for="edit_user_type">User Type</label>
                    <select id="edit_user_type" name="user_type">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="finance-modal-buttons">
                    <button type="button" class="finance-cancel-btn" id="cancelEditUser">Cancel</button>
                    <button type="submit" class="finance-confirm-btn" name="update_user_type">Update User</button>
                </div>
            </form>
        </div>
    </div>

    <div class="finance-admin-modal" id="updateEmailModal">
        <div class="finance-admin-modal-content">
            <h3 class="finance-modal-title">Update User Email</h3>
            <form method="post" id="updateEmailForm">
                <input type="hidden" id="email_user_id" name="user_id">
                <div class="finance-form-group">
                    <label for="new_email">New Email</label>
                    <input type="email" id="new_email" name="email" required>
                </div>
                <div class="finance-modal-buttons">
                    <button type="button" class="finance-cancel-btn" id="cancelUpdateEmail">Cancel</button>
                    <button type="submit" class="finance-confirm-btn" name="update_email">Update Email</button>
                </div>
            </form>
        </div>
    </div>

    <div class="finance-admin-modal" id="updateStatusModal">
        <div class="finance-admin-modal-content">
            <h3 class="finance-modal-title" id="statusModalTitle">Update User Status</h3>
            <form method="post" id="updateStatusForm">
                <input type="hidden" id="status_user_id" name="user_id">
                <div class="finance-form-group">
                    <label for="new_status">User Status</label>
                    <select id="new_status" name="status">
                        <option value="Active">Active</option>
                        <option value="Blocked">Blocked</option>
                    </select>
                </div>
                <div class="finance-modal-buttons">
                    <button type="button" class="finance-cancel-btn" id="cancelUpdateStatus">Cancel</button>
                    <button type="submit" class="finance-confirm-btn" name="update_status">Update Status</button>
                </div>
            </form>
        </div>
    </div>

    <div class="finance-admin-modal" id="deleteUserModal">
        <div class="finance-admin-modal-content">
            <h3 class="finance-modal-title">Delete User</h3>
            <p>Are you sure you want to delete this user? This action cannot be undone.</p>
            <form method="post" id="deleteUserForm">
                <input type="hidden" id="delete_user_id" name="user_id">
                <div class="finance-modal-buttons">
                    <button type="button" class="finance-cancel-btn" id="cancelDeleteUser">Cancel</button>
                    <button type="submit" class="finance-confirm-btn" name="delete_user">Delete User</button>
                </div>
            </form>
        </div>
    </div>

    <div class="finance-sidebar-container" id="sidebarContainer">
        <div class="finance-sidebar-header-container">
            <h3 class="finance-sidebar-header-title">
                <i class="fas fa-wallet"></i> 
                <span>Finance Tracker</span>
            </h3>
        </div>
        
        <div class="finance-sidebar-menu-container">
            <a href="#" class="finance-sidebar-menu-item-link <?php echo $active_section === 'dashboard-section' ? 'active-menu-link' : ''; ?>" data-section="dashboard-section">
                <i class="fas fa-home finance-sidebar-menu-item-icon"></i> <span>Dashboard</span>
            </a>
            <div>

            </div>
            
            <a href="#" class="finance-sidebar-menu-item-link <?php echo $active_section === 'user-management-section' ? 'active-menu-link' : ''; ?>" id="userManagementLink" data-section="user-management-section">
                <i class="fas fa-users-cog finance-sidebar-menu-item-icon"></i> <span>User Management</span>
            </a>
            
            <a href="#" class="finance-sidebar-menu-item-link" id="logoutLink">
                <i class="fas fa-sign-out-alt finance-sidebar-menu-item-icon"></i> <span>Logout</span>
            </a>
        </div>
    </div>

    <div class="finance-main-content-container">
        <div class="finance-top-navigation-bar">
            <div class="finance-nav-left">
                <div class="finance-menu-toggle-button" id="menuToggleButton">
                    <i class="fas fa-bars"></i>
                </div>
                <h5 class="finance-top-navigation-heading" id="topNavigationTitle">Admin Dashboard</h5>
            </div>

            <div class="finance-nav-right">
                <div class="finance-notification-badge-container">
                    <i class="fas fa-bell"></i>
                    <span class="finance-notification-badge-dot">3</span>
                </div>
                <div class="finance-user-profile-container">
                    <span class="finance-user-welcome-text">
                        Welcome, <?php echo isset($admin_user['username']) ? htmlspecialchars($admin_user['username']) : 'Admin'; ?><br>
                        Email: <?php echo isset($admin_user['email']) ? htmlspecialchars($admin_user['email']) : 'N/A'; ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="finance-page-content-container">
            <?php if (!empty($success_message)): ?>
                <div class="finance-message success">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <div id="dashboard-section" class="finance-content-section <?php echo $active_section === 'dashboard-section' ? 'active' : ''; ?>">
                <div class="finance-dashboard-header">
                    <h2>Admin Overview</h2>
                    <p>System overview and statistics</p>
                </div>
                
                <div class="finance-admin-cards-container">
                    <div class="finance-admin-card">
                        <div class="finance-admin-card-header">
                            <div class="finance-admin-card-icon users">
                                <i class="fas fa-users"></i>
                            </div>
                            <h4 class="finance-admin-card-title">Total Users</h4>
                        </div>
                        <div class="finance-admin-card-value"><?php echo $total_users_count; ?></div>
                        <p class="finance-admin-card-label">Registered users in the system</p>
                       
                    </div>
                    
                    <div class="finance-admin-card">
                        <div class="finance-admin-card-header">
                            <div class="finance-admin-card-icon active">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <h4 class="finance-admin-card-title">Active Users</h4>
                        </div>
                        <div class="finance-admin-card-value"><?php echo $active_users_count; ?></div>
                        <p class="finance-admin-card-label">Active in last 30 days</p>
                        
                    </div>

                    <div class="finance-admin-card">
                        <div class="finance-admin-card-header">
                            <div class="finance-admin-card-icon active">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <h4 class="finance-admin-card-title">Recent Users</h4>
                        </div>
                        <div class="finance-admin-card-value"><?php echo $recent_users_count; ?></div>
                        <p class="finance-admin-card-label">Recent user in last 30 days</p>
                      
                    </div>

                    <div class="finance-admin-card">
                        <div class="finance-admin-card-header">
                            <div class="finance-admin-card-icon system">
                                <i class="fas fa-user-slash"></i>
                            </div>
                            <h4 class="finance-admin-card-title">Blocked Users</h4>
                        </div>
                        <div class="finance-admin-card-value"><?php echo $blocked_users_count; ?></div>
                        <p class="finance-admin-card-label">Currently blocked users</p>
                        
                    </div>
                    
                    <div class="finance-admin-card">
                        <div class="finance-admin-card-header">
                            <div class="finance-admin-card-icon admin">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h4 class="finance-admin-card-title">Admin Users</h4>
                        </div>
                        <div class="finance-admin-card-value"><?php echo $admin_users_count; ?></div>
                        <p class="finance-admin-card-label">Administrators in system</p>
                        
                    </div>
                </div>
                


                <div class="finance-action-cards">
                    <div class="finance-action-card" onclick="openAddUserModal()">
                        <div class="finance-action-card-icon manage">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h4 class="finance-action-card-title">Add New User</h4>
                        <p class="finance-action-card-desc">Create a new user account</p>
                    </div>
                </div>

<div class="finance-user-table-container">
    <div class="finance-user-table-header">
        <h3 class="finance-user-table-title">Admin Users</h3>
        <span class="finance-admin-card-value"><?php echo $admin_users_count; ?> admins</span>
    </div>
    
    <?php if (empty($admin_users)): ?>
        <p>No admin users found.</p>
    <?php else: ?>
        <table class="finance-user-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Joined</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admin_users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <span class="finance-user-status-badge <?php echo strtolower($user['status']); ?>">
                                <?php echo $user['status']; ?>
                            </span>
                        </td>
                        <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
            </div>

            <div id="user-management-section" class="finance-content-section <?php echo $active_section === 'user-management-section' ? 'active' : ''; ?>">
                <div class="finance-user-table-container">
                    <div class="finance-user-table-header">
                        <h3 class="finance-user-table-title">User Management</h3>
                    </div>

                   <form method="post" class="finance-search-form">
    <input type="email" name="search_email" placeholder="Search by email..." 
           value="<?php echo htmlspecialchars($search_email); ?>">
    
    <?php if ($search_performed): ?>
        <button type="submit" name="clear_search" class="finance-clear-search-btn">
            <i class="fas fa-times"></i> Clear Search
        </button>
    <?php else: ?>
        <button type="submit" name="search_user">
            <i class="fas fa-search"></i> Search User
        </button>
    <?php endif; ?>
</form>
                    
                    <?php if (!empty($search_message)): ?>
                        <p class="finance-message">
                            <?php echo $search_message; ?>
                        </p>
                    <?php endif; ?>
                    
                    <?php if (empty($display_users)): ?>
                        <p>No users found.</p>
                    <?php else: ?>
                        <table class="finance-user-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Joined</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($display_users as $user): ?>
                                    <tr>
                                        <td><?php echo $user['id']; ?></td>
                                        <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td>
                                            <span class="finance-user-type-badge <?php echo $user['user_type']; ?>">
                                                <?php echo ucfirst($user['user_type']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="finance-user-status-badge <?php echo strtolower($user['status']); ?>">
                                                <?php echo $user['status']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                                        <td>
                                            <div class="finance-admin-actions">
                                                <button class="finance-edit-btn" onclick="openEditUserModal(<?php echo $user['id']; ?>, '<?php echo $user['user_type']; ?>')">
                                                    <i class="fas fa-edit"></i> Edit User Type
                                                </button>
                                                <button class="finance-email-btn" onclick="openUpdateEmailModal(<?php echo $user['id']; ?>, '<?php echo $user['email']; ?>')">
                                                    <i class="fas fa-envelope"></i> Update Email
                                                </button>
                                                <?php if ($user['status'] === 'Active'): ?>
                                                    <button class="finance-block-btn" onclick="openUpdateStatusModal(<?php echo $user['id']; ?>, 'Blocked')">
                                                        <i class="fas fa-user-slash"></i> Block User
                                                    </button>
                                                <?php else: ?>
                                                    <button class="finance-unblock-btn" onclick="openUpdateStatusModal(<?php echo $user['id']; ?>, 'Active')">
                                                        <i class="fas fa-user-check"></i> Unblock User
                                                    </button>
                                                <?php endif; ?>
                                                <button class="finance-delete-btn" onclick="openDeleteUserModal(<?php echo $user['id']; ?>)">
                                                    <i class="fas fa-trash"></i> Delete User
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function showModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
        }

        function hideModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        document.getElementById('logoutLink').addEventListener('click', function(event) {
            event.preventDefault();
            showModal('logoutModal');
        });

        document.getElementById('cancelLogout').addEventListener('click', function() {
            hideModal('logoutModal');
        });

        function openAddUserModal() {
            showModal('addUserModal');
        }

        document.getElementById('cancelAddUser').addEventListener('click', function() {
            hideModal('addUserModal');
        });
        
        function openEditUserModal(userId, userType) {
            document.getElementById('edit_user_id').value = userId;
            document.getElementById('edit_user_type').value = userType;
            showModal('editUserModal');
        }
        
        document.getElementById('cancelEditUser').addEventListener('click', function() {
            hideModal('editUserModal');
        });

        function openUpdateEmailModal(userId, userEmail) {
            document.getElementById('email_user_id').value = userId;
            document.getElementById('new_email').value = userEmail;
            showModal('updateEmailModal');
        }
        
        document.getElementById('cancelUpdateEmail').addEventListener('click', function() {
            hideModal('updateEmailModal');
        });

        function openUpdateStatusModal(userId, status) {
            document.getElementById('status_user_id').value = userId;
            document.getElementById('new_status').value = status;
            showModal('updateStatusModal');
        }

        document.getElementById('cancelUpdateStatus').addEventListener('click', function() {
            hideModal('updateStatusModal');
        });
        
        function openDeleteUserModal(userId) {
            document.getElementById('delete_user_id').value = userId;
            showModal('deleteUserModal');
        }

        document.getElementById('cancelDeleteUser').addEventListener('click', function() {
            hideModal('deleteUserModal');
        });

        const sidebar = document.getElementById('sidebarContainer');
        const overlay = document.getElementById('mobileOverlay');
        
        document.getElementById('menuToggleButton').addEventListener('click', function() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        });

        overlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
        
        document.querySelectorAll('.finance-sidebar-menu-item-link').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                }
            });
        });

        const message = document.querySelector('.finance-message');
        if (message) {
            setTimeout(() => {
                message.style.display = 'none';
            }, 5000);
        }

        const sections = document.querySelectorAll('.finance-content-section');
        const sidebarLinks = document.querySelectorAll('.finance-sidebar-menu-item-link');

        function showSection(sectionId) {
            sections.forEach(section => {
                section.classList.remove('active');
            });
            document.getElementById(sectionId).classList.add('active');
            
            updateActiveLink(sectionId);
        }

        function updateActiveLink(sectionId) {
            sidebarLinks.forEach(link => {
                link.classList.remove('active-menu-link');
                if (link.getAttribute('data-section') === sectionId) {
                    link.classList.add('active-menu-link');
                }
            });
        }

        document.querySelector('[data-section="dashboard-section"]').addEventListener('click', function(event) {
            event.preventDefault();
            showSection('dashboard-section');
        });

        document.getElementById('userManagementLink').addEventListener('click', function(event) {
            event.preventDefault();
            showSection('user-management-section');
        });

        showSection('<?php echo $active_section; ?>');
    </script>
</body>
</html>