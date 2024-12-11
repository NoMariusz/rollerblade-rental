<?php

class AuthUtils
{
    /**
     * Authorizes a user by setting their data in the session.
     *
     * @param string $username The username of the logged-in user.
     * @param string $role The role of the user (e.g., 'user', 'moderator', 'admin').
     */
    public static function authorizeUser($username, $role)
    {
        $_SESSION['user'] = [
            'username' => $username,
            'role' => $role
        ];
    }

    /**
     * Logs out the user by clearing their session data.
     */
    public static function unauthorizeUser()
    {
        // Clear session data
        unset($_SESSION['user']);
        session_destroy();
    }

    /**
     * Checks if a user is authorized (logged in).
     *
     * @return bool True if the user is authorized, otherwise false.
     */
    public static function isAuthorized()
    {
        return isset($_SESSION['user']);
    }

    /**
     * Checks if the authorized user has moderator privileges.
     *
     * @return bool True if the user is a moderator or higher, otherwise false.
     */
    public static function isModerator()
    {
        return isset($_SESSION['user']) && in_array($_SESSION['user']['role'], ['moderator', 'admin']);
    }

    /**
     * Checks if the authorized user has admin privileges.
     *
     * @return bool True if the user is an admin, otherwise false.
     */
    public static function isAdmin()
    {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
    }

    /**
     * Gets the currently logged-in user's username.
     *
     * @return string|null The username if logged in, otherwise null.
     */
    public static function getUsername()
    {
        return $_SESSION['user']['username'] ?? null;
    }

    /**
     * Gets the role of the currently logged-in user.
     *
     * @return string|null The role of the user if logged in, otherwise null.
     */
    public static function getUserRole()
    {
        return $_SESSION['user']['role'] ?? null;
    }
}
