<?php

use App\Plugins\PluginManager;

// Daftarkan sub-menu "Statistik Pengunjung" ke modul "reporting" (Pelaporan) di admin panel
PluginManager::registerMenu('reporting', 'Statistik Pengunjung', 'admin.visitor.index');
