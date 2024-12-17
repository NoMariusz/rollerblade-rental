<?php

// enum like class for authorization levels
abstract class AuthLevels
{
    const None = 0;
    const User = 1;
    const Moderator = 2;
    const Admin = 3;

}