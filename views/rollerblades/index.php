<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Rollerblade Rental</title>
    <link rel="stylesheet" href="/views/rollerblades/styles.css" />
    <link rel="stylesheet" href="/views/shared/index.css" />
</head>

<body>
    <?php include_once './views/shared/header.php'; ?>

    <div class="container">
        <aside class="filter card">
            <h2>Filter</h2>
            <form method="GET" action="/rollerblades">
                <div class="price-range">
                    <div class="price-top">
                        <label for="priceRange" class="filter-heading">Price</label>
                        <div class="slider-labels">
                            <span>PLN</span>
                            <span id="minPriceValue"><?= htmlspecialchars($_GET['price_min'] ?? '0') ?></span>
                            <span>-</span>
                            <span id="maxPriceValue"><?= htmlspecialchars($_GET['price_max'] ?? '100') ?></span>
                        </div>
                    </div>
                    <div class="slider-container">
                        <input type="range" id="priceMin" name="price_min" min="0" max="100" step="1"
                            value="<?= htmlspecialchars($_GET['price_min'] ?? '0') ?>" />
                        <input type="range" class="hide-track" id="priceMax" name="price_max" min="0" max="100" step="1"
                            value="<?= htmlspecialchars($_GET['price_max'] ?? '100') ?>" />
                    </div>
                </div>

                <div>
                    <h3 class="filter-heading">Color</h3>
                    <label><input type="checkbox" name="color[]" value="red" <?= in_array('red', $_GET['color'] ?? []) ? 'checked' : '' ?> /> Red</label>
                    <label><input type="checkbox" name="color[]" value="black" <?= in_array('black', $_GET['color'] ?? []) ? 'checked' : '' ?> /> Black</label>
                    <label><input type="checkbox" name="color[]" value="white" <?= in_array('white', $_GET['color'] ?? []) ? 'checked' : '' ?> /> White</label>
                    <label><input type="checkbox" name="color[]" value="green" <?= in_array('green', $_GET['color'] ?? []) ? 'checked' : '' ?> /> Green</label>
                    <label><input type="checkbox" name="color[]" value="pink" <?= in_array('pink', $_GET['color'] ?? []) ? 'checked' : '' ?> /> Pink</label>
                </div>

                <div>
                    <h3 class="filter-heading">Size</h3>
                    <label><input type="checkbox" name="size[]" value="39" <?= in_array('39', $_GET['size'] ?? []) ? 'checked' : '' ?> /> 39</label>
                    <label><input type="checkbox" name="size[]" value="42" <?= in_array('42', $_GET['size'] ?? []) ? 'checked' : '' ?> /> 42</label>
                    <label><input type="checkbox" name="size[]" value="44" <?= in_array('44', $_GET['size'] ?? []) ? 'checked' : '' ?> /> 44</label>
                </div>

                <div>
                    <label for="startDate" class="filter-heading">Date start</label>
                    <input type="datetime-local" id="startDate" name="start_date" placeholder="dd-mm-yyyy HH:mm"
                        value="<?= htmlspecialchars($_GET['start_date'] ?? '') ?>" />
                    <label for="endDate" class="filter-heading">Date end</label>
                    <input type="datetime-local" id="endDate" name="end_date" placeholder="dd-mm-yyyy HH:mm"
                        value="<?= htmlspecialchars($_GET['end_date'] ?? '') ?>" />
                </div>

                <button type="submit" class="btn btn-primary">Apply Filters</button>
            </form>
        </aside>

        <main class="rollerblades">
            <?php
            include_once './src/includes.php';

            $dbManager = new DbManager();

            // Default query
            $start_date = empty($_GET['start_date']) ? null : $_GET['start_date'];
            $end_date = empty($_GET['end_date']) ? null : $_GET['end_date'];

            $query = 'SELECT * FROM all_available_rollerblades_between_dates(:start_date, :end_date)';
            $params = [
                ["key" => ":start_date", "type" => PDO::PARAM_STR, "value" => $start_date],
                ["key" => ":end_date", "type" => PDO::PARAM_STR, "value" => $end_date]
            ];

            // Build WHERE clause dynamically based on filters
            $whereClauses = [];

            // Price filter
            if (!empty($_GET['price_min'])) {
                $whereClauses[] = 'hourly_rate >= :price_min';
                $params[] = ["key" => ":price_min", "type" => PDO::PARAM_INT, "value" => $_GET['price_min']];
            }
            if (!empty($_GET['price_max'])) {
                $whereClauses[] = 'hourly_rate <= :price_max';
                $params[] = ["key" => ":price_max", "type" => PDO::PARAM_INT, "value" => $_GET['price_max']];
            }

            // Color filter
            if (!empty($_GET['color'])) {
                $whereClauses[] = "color IN (:colors)";
                $params[] = ["key" => ":colors", "type" => PDO::PARAM_STR, "value" => implode(',', $_GET['color'])];
            }

            // Size filter
            if (!empty($_GET['size'])) {
                $whereClauses[] = "size IN (:sizes)";
                $params[] = ["key" => ":sizes", "type" => PDO::PARAM_STR, "value" => implode(',', $_GET['size'])];
            }

            if (!empty($whereClauses)) {
                $query .= ' WHERE ' . implode(' AND ', $whereClauses);
            }


            $rollerblades = $dbManager->make_safe_query($query, $params);

            foreach ($rollerblades as $rollerblade) {
                echo '
                <a href="/rollerblade?id=' . $rollerblade['rollerblade_id'] . '" class="card rollerblade-card">
                    <img src="' . htmlspecialchars($rollerblade['photo_url']) . '" alt="' . htmlspecialchars($rollerblade['rollerblade_name']) . '">
                    <div class="card-body">
                        <h3>' . htmlspecialchars($rollerblade['rollerblade_name']) . '</h3>
                        <p>' . htmlspecialchars($rollerblade['hourly_rate']) . ' PLN</p>
                    </div>
                </a>';
            }
            ?>
        </main>
    </div>

    <script src="/views/rollerblades/main.js"></script>
</body>

</html>