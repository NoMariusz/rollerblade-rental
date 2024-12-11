<header>
    <div class="logo">
        <a href="/">
            <img src="/views/shared/rr_logo.png" alt="Rollerblade-Rental" />
        </a>
    </div>
    <div class="hamburger-wrapper">
        <button class="hamburger" aria-label="Toggle navigation">
            ☰
        </button>
        <nav>
            <a href="/rollerskates" class="accent-link">Rent!</a>
            <a href="/contact">Contact</a>

            <?php
            require_once './src/shared/AuthUtils.php';

            if (AuthUtils::isAuthorized()) {
                // Show "My Rentals" for all authorized users
                echo '<a href="/my-rentals">My Rentals</a>';

                // Show "All Rentals" for moderators and above
                if (AuthUtils::isModerator()) {
                    echo '<a href="/all-rentals">All Rentals</a>';
                }

                // Show "Edit Users" for admins
                if (AuthUtils::isAdmin()) {
                    echo '<a href="/edit-users">Edit Users</a>';
                }

                // Show username and logout button
                echo '<span class="username">Hello, <b>' . htmlspecialchars(AuthUtils::getUsername()) . '</b>!</span>';
                echo '<a href="/logout" class="btn btn-secondary btn-compact">Logout</a>';
            } else {
                // Show "Sign in" and "Register" buttons for guests
                echo '<a href="/login" class="btn btn-secondary btn-compact">Sign in</a>';
                echo '<a href="/register" class="btn btn-primary btn-compact">Register</a>';
            }
            ?>
        </nav>
    </div>
    <script src="/views/shared/header.js"></script>
</header>