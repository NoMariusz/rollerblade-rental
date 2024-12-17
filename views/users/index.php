<?php
require_once './src/includes.php';

// Check if user is an Admin
ViewsUtils::checkPriviledges(AuthLevels::Admin);

// Fetch all users
$dbManager = new DbManager();
$users = $dbManager->make_query(
    "SELECT u.id, username, r.name role, up.email, up.phone_number 
            FROM users u 
            inner join roles r on r.id = u.role_id 
            inner join user_profiles up on up.user_id = u.id
            ORDER BY username ASC"
);

// Fetch all possible roles dynamically
$roles = $dbManager->make_query("SELECT id, name FROM roles");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Users</title>
    <link rel="stylesheet" href="/views/shared/index.css" />
    <link rel="stylesheet" href="/views/shared/objectsTable.css" />
</head>

<body>
    <?php include_once './views/shared/header.php'; ?>

    <main>
        <h1>All Users</h1>

        <?php if (empty($users)): ?>
            <p>No users found.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Change role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['phone_number']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                            <td>
                                <div class="buttons">

                                    <!-- Dynamic Role Buttons -->
                                    <?php foreach ($roles as $role): ?>
                                        <button class="btn btn-secondary" <?php echo $role['name'] === $user['role'] ? 'disabled' : ''; ?>
                                            onclick="changeUserRole(<?php echo $user['id']; ?>, <?php echo $role['id']; ?>, '<?php echo htmlspecialchars($role['name']); ?>')">
                                            Set to <?php echo htmlspecialchars($role['name']); ?>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                            <td>
                                <!-- Delete User Button -->
                                <button class="btn btn-primary" onclick="deleteUser(<?php echo $user['id']; ?>)">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>

    <script src="/views/users/main.js"></script>
</body>

</html>