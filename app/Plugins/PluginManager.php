<?php

namespace App\Plugins;

class PluginManager
{
    protected static $menus = [];

    /**
     * Daftarkan menu plugin untuk diletakkan di sidebar modul admin tertentu.
     *
     * @param string $module Nama modul induk (e.g. bibliography, circulation, membership, reporting, system)
     * @param string $label Label menu yang tampil
     * @param string $route Nama route Laravel
     * @return void
     */
    public static function registerMenu(string $module, string $label, string $route): void
    {
        if (!isset(self::$menus[$module])) {
            self::$menus[$module] = [];
        }

        self::$menus[$module][] = [
            'label' => $label,
            'route' => $route
        ];
    }

    /**
     * Dapatkan semua menu terdaftar berdasarkan modul.
     *
     * @param string $module
     * @return array
     */
    public static function getMenus(string $module): array
    {
        return self::$menus[$module] ?? [];
    }
}
