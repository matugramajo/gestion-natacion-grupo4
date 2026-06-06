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
}
