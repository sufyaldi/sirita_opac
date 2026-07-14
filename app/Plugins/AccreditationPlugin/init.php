<?php

use App\Plugins\PluginManager;

// Daftarkan sub-menu "Akreditasi BAN-PT" ke modul "reporting" (Pelaporan) di admin panel
PluginManager::registerMenu('reporting', 'Akreditasi BAN-PT', 'admin.accreditation.index');
