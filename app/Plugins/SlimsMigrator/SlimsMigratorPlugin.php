<?php

namespace App\Plugins\SlimsMigrator;

class SlimsMigratorPlugin
{
    /**
     * Get plugin metadata.
     */
    public static function getInfo()
    {
        return [
            'name' => 'SLiMS Data Migrator',
            'version' => '1.0.0',
            'author' => 'SIRITA Core Team',
            'description' => 'Plugin untuk memindahkan/migrasi data dari instalasi SLiMS 9 Bulian Native ke SIRITA Laravel.',
        ];
    }

    /**
     * Define menu routes structure for integration.
     */
    public static function getMenus()
    {
        return [
            [
                'group' => 'system',
                'label' => 'Migrasi SLiMS',
                'route' => 'admin.plugins.slims-migrator.index',
            ],
        ];
    }
}
