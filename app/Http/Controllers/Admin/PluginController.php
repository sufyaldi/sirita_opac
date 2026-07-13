<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plugin as PluginModel;
use Illuminate\Http\Request;
use File;

class PluginController extends Controller
{
    /**
     * Display listing of all scanned plugins with their metadata and database status.
     */
    public function index()
    {
        $pluginsPath = app_path('Plugins');
        $pluginsList = [];

        if (File::exists($pluginsPath)) {
            $pluginDirs = File::directories($pluginsPath);

            foreach ($pluginDirs as $dir) {
                $key = basename($dir);
                
                // Read plugin.json metadata
                $metaPath = $dir . '/plugin.json';
                $meta = [
                    'name'        => $key,
                    'description' => 'Tidak ada deskripsi untuk plugin ini.',
                    'version'     => '1.0.0',
                    'author'      => 'Unknown',
                    'author_uri'  => '#'
                ];

                if (File::exists($metaPath)) {
                    $jsonContent = json_decode(File::get($metaPath), true);
                    if (is_array($jsonContent)) {
                        $meta = array_merge($meta, $jsonContent);
                    }
                }

                // Check or create status in Database
                $pluginDb = PluginModel::firstOrCreate(
                    ['key' => $key],
                    ['is_active' => true] // Default active when first scanned
                );

                $pluginsList[] = [
                    'key'         => $key,
                    'name'        => $meta['name'],
                    'description' => $meta['description'],
                    'version'     => $meta['version'],
                    'author'      => $meta['author'],
                    'author_uri'  => $meta['author_uri'],
                    'is_active'   => $pluginDb->is_active
                ];
            }
        }

        return view('admin.system.plugins.index', compact('pluginsList'));
    }

    /**
     * Toggle the active status of a plugin.
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'key'       => 'required|string',
            'is_active' => 'required|boolean'
        ]);

        $plugin = PluginModel::where('key', $request->key)->first();
        if ($plugin) {
            $plugin->update([
                'is_active' => $request->is_active
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Status plugin ' . $request->key . ' berhasil diperbarui.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Plugin tidak ditemukan.'
        ], 404);
    }
}
