<?php
require_once './src/includes.php';

// Check if user is logged in
ViewsUtils::checkPriviledges(AuthLevels::User);

$userId = AuthUtils::getUserId();

// Fetch all rentals for the user using the `rental_details` view
$dbManager = new DbManager();
$query = "SELECT rental_id, rental_status, start_date, end_date, 
                 rollerblade_name, rollerblade_size, notes
          FROM rental_details
          WHERE user_id = $userId
          ORDER BY start_date DESC";
$rentals = $dbManager->make_query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Rollerblade Rental</title>
    <link rel="stylesheet" href="/views/rentals/styles.css" />
    <link rel="stylesheet" href="/views/shared/index.css" />
</head>

<body>
    <?php include_once './views/shared/header.php'; ?>

    <main>
        <h1>My Rentals</h1>

        <?php if (empty($rentals)): ?>
            <p>No rentals found. Start your first rental now!</p>
        <?php else: ?>
            <form method="POST" action="/cancel-rental" id="cancelForm">
                <table>
                    <thead>
                        <tr>
                            <th>Rollerblade</th>
                            <th>Size</th>
                            <th>Status</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rentals as $rental): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($rental['rollerblade_name']); ?></td>
                                <td><?php echo htmlspecialchars($rental['rollerblade_size']); ?></td>
                                <td><?php echo htmlspecialchars($rental['rental_status']); ?></td>
                                <td><?php echo htmlspecialchars($rental['start_date']); ?></td>
                                <td><?php echo htmlspecialchars($rental['end_date']); ?></td>
                                <td><?php echo htmlspecialchars($rental['notes']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </form>
        <?php endif; ?>
    </main>
</body>

</html>