<?php

class ViewsUtils
{
    public static function checkPriviledges(int $priviledgesLevel = AuthLevels::None)
    {
        if (!AuthUtils::hasPriviledges($priviledgesLevel)) {
            if ($priviledgesLevel == AuthLevels::User) {
                header('Location: /login');
                die();
            }
            header('Location: /403');
            die();
        }
    }
}