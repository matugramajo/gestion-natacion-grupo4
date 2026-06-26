<?php

class Role {
    public const ADMIN   = 1;
    public const COACH   = 2;
    public const SWIMMER = 3;

    public static function name( int $roleId ): string {
        return match ( $roleId ) {
            self::ADMIN   => 'Administrator',
            self::COACH   => 'Coach',
            self::SWIMMER => 'Swimmer',
            default       => 'User',
        };
    }
}
