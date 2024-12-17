<?php
require_once './src/includes.php';

// Check if user has Moderator privileges
ViewsUtils::checkPriviledges(AuthLevels::Moderator);

// Fetch all rentals using the `rental_details` view
$dbManager = new DbManager();
$query = "SELECT rental_id, user_name, rental_status, start_date, end_date, 
                 rollerblade_name, rollerblade_size, notes
          FROM rental_details
          ORDER BY start_date DESC";
$rentals = $dbManager->make_query($query);

// Define possible status transitions
$query = "SELECT id, name FROM rental_statuses";
$statuses = $dbManager->make_query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>All Rentals - Moderator Panel</title>
    <link rel="stylesheet" href="/views/shared/index.css" />
    <link rel="stylesheet" href="/views/shared/objectsTable.css" />
</head>

<body>
    <?php include_once './views/shared/header.php'; ?>

    <main>
        <h1>All Rentals - Moderator Panel</h1>

        <?php if (empty($rentals)): ?>
            <p>No rentals found.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Renter</th>
                        <th>Rollerblade</th>
                        <th>Size</th>
                        <th>Status</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Notes</th>
                        <th>Change status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rentals as $rental): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($rental['user_name']); ?></td>
                            <td><?php echo htmlspecialchars($rental['rollerblade_name']); ?></td>
                            <td><?php echo htmlspecialchars($rental['rollerblade_size']); ?></td>
                            <td><?php echo htmlspecialchars($rental['rental_status']); ?></td>
                            <td><?php echo htmlspecialchars($rental['start_date']); ?></td>
                            <td><?php echo htmlspecialchars($rental['end_date']); ?></td>
                            <td><?php echo htmlspecialchars($rental['notes']); ?></td>
                            <td>
                                <div class="buttons">

                                    <?php
                                    $currentStatus = $rental['rental_status'];
                                    foreach ($statuses as $newStatus):
                                        ?>
                                        <button class="btn btn-secondary"
                                            onclick="changeRentalStatus(<?php echo $rental['rental_id']; ?>, '<?php echo $newStatus['id']; ?>')">
                                            <?php echo htmlspecialchars($newStatus['name']); ?>
                                        </button>
                                        <?php
                                    endforeach;

                                    ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
    <script src="/views/rentals/all/main.js"></script>
</body>

</html>