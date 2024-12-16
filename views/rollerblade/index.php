<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Rollerblade Rental</title>
    <link rel="stylesheet" href="/views/rollerblade/styles.css" />
    <link rel="stylesheet" href="/views/shared/index.css" />
</head>

<body>
    <?php include_once './views/shared/header.php'; ?>

    <?php
    // Database connection
    include_once './src/includes.php';

    // get rollerblade ID
    if (!$_GET['id']) {
        echo "<p>No rollerblade ID provided!</p>";
        exit;
    }
    $rollerbladeId = intval($_GET['id']);

    $db = new DbManager();
    $res = $db->make_safe_query(
        "select * from rollerblades r 
        inner join rollerblades_detailed on r.id = rollerblade_id 
        inner join model_versions mv on mv.id = r.model_version_id 
        inner join models m on mv.model_id = m.id 
        WHERE r.id = :id",
        [['key' => ':id', 'value' => $rollerbladeId, 'type' => PDO::PARAM_INT]]
    );

    if (sizeof($res) === 0) {
        echo "<p>Rollerblade not found!</p>";
        exit;
    }
    $rollerblade = $res[0];

    // Fetch sizes and colors for the same brand and model
    $db = new DbManager();
    $sizes = $db->make_safe_query(
        "select r.id, s.id size_id, s.name from rollerblades r inner join sizes s on s.id = size_id where model_version_id = :v_id",
        [['key' => ':v_id', 'value' => intval($rollerblade['model_version_id']), 'type' => PDO::PARAM_INT]]
    );

    $db = new DbManager();
    $colors = $db->make_safe_query(
        "select r.id, c.id color_id, c.name from rollerblades r inner join model_versions mv on mv.id = r.model_version_id inner join colors c on mv.color_id = c.id where model_id = :mv_id and wheel_size_id = :ws_id and size_id = :s_id",
        [
            ['key' => ':mv_id', 'value' => intval($rollerblade['model_id']), 'type' => PDO::PARAM_INT],
            ['key' => ':ws_id', 'value' => intval($rollerblade['wheel_size_id']), 'type' => PDO::PARAM_INT],
            ['key' => ':s_id', 'value' => intval($rollerblade['size_id']), 'type' => PDO::PARAM_INT],
        ]
    );

    ?>


    <div class="rollerblade-container">
        <div class="card main-image">
            <img src="<?= htmlspecialchars($rollerblade['photo_url']) ?>"
                alt="<?= htmlspecialchars($rollerblade['rollerblade_name']) ?>" />
        </div>

        <div class="rollerblade-details">
            <h1><?= htmlspecialchars($rollerblade['rollerblade_name']) ?></h1>

            <span class="color-badge" style="background-color: <?= htmlspecialchars($rollerblade['color']) ?>;">
                <span><?= htmlspecialchars($rollerblade['color']) ?></span>
            </span>

            <h2 class="price"><?= htmlspecialchars($rollerblade['hourly_rate']) ?> PLN</h2>
            <p class="price-label">rental price</p>

            <form action="/rent" method="post" class="rent-form">
                <input type="hidden" name="rollerblade_id" value="<?= htmlspecialchars($rollerbladeId) ?>" />

                <label for="sizeSelect">Size</label>
                <select id="sizeSelect" name="size">
                    <?php
                    foreach ($sizes as $variant) {
                        echo '<option value="' . htmlspecialchars($variant['size_id']) . '"';
                        if ($variant['size_id'] === $rollerblade['size_id']) {
                            echo ' selected>';
                            echo $variant['name'];
                        } else {
                            echo ' onClick="navigateToRollerblade(' . $variant['id'] . ')">';
                            echo htmlspecialchars($variant['name']);
                            echo '</a>';
                        }
                        echo '</option>';
                    }
                    ?>

                </select>

                <label for="colorSelect">Color</label>
                <select id="colorSelect" name="color">
                    <?php
                    foreach ($colors as $variant) {
                        echo '<option value="' . htmlspecialchars($variant['color_id']) . '"';
                        if ($variant['color_id'] === $rollerblade['color_id']) {
                            echo ' selected>';
                            echo $variant['name'];
                        } else {
                            echo ' onClick="navigateToRollerblade(' . $variant['id'] . ')">';
                            echo htmlspecialchars($variant['name']);
                            echo '</a>';
                        }
                        echo '</option>';
                    }
                    ?>

                </select>

                <label for="startDate">Date start</label>
                <input type="datetime-local" id="startDate" name="start_date" required />

                <label for="endDate">Date end</label>
                <input type="datetime-local" id="endDate" name="end_date" required />

                <div class="buttons-block">
                    <button type="submit" class="btn btn-primary">Rent</button>
                    <p>Or</p>
                    <a href="<?= htmlspecialchars($rollerblade['purchase_link']) ?>" target="_blank"
                        class="btn btn-secondary">Buy</a>
                </div>
            </form>
        </div>

        <div class="rollerblade-extras">
            <div class="card description">
                <div class="description-header">
                    <h3>Description</h3>
                    <img src='/views/shared/arrow.svg' alt="Arrow" id="description-arrow"
                        onclick="toggleDescription()" />
                </div>
                <div id="description-content">
                    <p><?= htmlspecialchars($rollerblade['description']) ?></p>
                </div>
                </p>
            </div>
        </div>
    </div>

    <script src="/views/rollerblade/main.js"></script>

</body>

</html>