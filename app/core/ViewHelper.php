<?php

class ViewHelper {
    public static function levelBadgeClass( string $levelName ): string {
        return match ( strtolower( $levelName ) ) {
            'principiante' => 'badge-level badge-level-prin',
            'intermedio'   => 'badge-level badge-level-inter',
            'avanzado'     => 'badge-level badge-level-avanz',
            'competitivo'  => 'badge-level badge-level-comp',
            default        => 'badge-level bg-light text-muted',
        };
    }

    public static function sidebarLinkClass( string $route, string $active ): string {
        return 'nav-link' . ( $route === $active ? ' active' : '' );
    }

    public static function initials( string $firstName, string $lastName = '' ): string {
        $first = trim( $firstName );
        $last  = trim( $lastName );

        if ( $first === '' && $last === '' ) {
            return '?';
        }

        if ( $last === '' ) {
            return mb_strtoupper( mb_substr( $first, 0, 2 ) );
        }

        return mb_strtoupper( mb_substr( $first, 0, 1 ) . mb_substr( $last, 0, 1 ) );
    }

    public static function hasProfilePhoto( ?string $profileImage, string $subdir = 'swimmers' ): bool {
        if ( empty( $profileImage ) || $profileImage === 'default-profile.png' ) {
            return false;
        }

        $path = __DIR__ . '/../../public/img/uploads/profiles/' . $subdir . '/' . $profileImage;

        return is_file( $path );
    }

    public static function profileAvatar(
        string $firstName,
        string $lastName = '',
        ?string $profileImage = null,
        string $class = 'profile-img-nav',
        string $subdir = 'swimmers'
    ): string {
        $classAttr = htmlspecialchars( $class, ENT_QUOTES, 'UTF-8' );

        if ( self::hasProfilePhoto( $profileImage, $subdir ) ) {
            $url = rtrim( Env::get( 'ASSET_URL' ), '/' )
                . '/img/uploads/profiles/' . $subdir . '/'
                . rawurlencode( (string) $profileImage );

            return '<img src="' . htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' ) . '" alt="Perfil" class="' . $classAttr . '">';
        }

        $initials = htmlspecialchars( self::initials( $firstName, $lastName ), ENT_QUOTES, 'UTF-8' );
        $color    = self::avatarColor( $firstName . $lastName );

        return '<span class="profile-avatar-initials ' . $classAttr . '" style="background-color:' . $color . '" aria-label="Perfil">' . $initials . '</span>';
    }

    private static function avatarColor( string $seed ): string {
        $palette = [ '#1a6cf6', '#0bc5c5', '#6366f1', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981' ];
        $index   = abs( crc32( $seed ) ) % count( $palette );

        return $palette[ $index ];
    }
}
