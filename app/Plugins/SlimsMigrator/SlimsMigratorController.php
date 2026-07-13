<?php

namespace App\Plugins\SlimsMigrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;

class SlimsMigratorController extends Controller
{
    protected $migratorService;

    public function __construct(MigratorService $migratorService)
    {
        $this->migratorService = $migratorService;
    }

    /**
     * Show migration configuration page.
     */
    public function index()
    {
        // Try to pre-fill configuration with default local credentials if possible
        $defaults = [
            'db_host' => 'localhost',
            'db_port' => '3306',
            'db_name' => 'slims9',
            'db_user' => 'root',
            'db_pass' => '',
            'slims_path' => '/Users/macos/Documents/aldi/coding/slims9_bulian/slims9_bulian'
        ];

        return view('SlimsMigrator::index', compact('defaults'));
    }

    /**
     * Test connection to SLiMS native database.
     */
    public function testConnection(Request $request)
    {
        $request->validate([
            'db_host' => 'required|string',
            'db_port' => 'required|string',
            'db_name' => 'required|string',
            'db_user' => 'required|string',
            'db_pass' => 'nullable|string',
            'slims_path' => 'nullable|string',
        ]);

        try {
            $this->migratorService->connect($request->only([
                'db_host', 'db_port', 'db_name', 'db_user', 'db_pass'
            ]), $request->slims_path);

            return response()->json([
                'status' => 'success',
                'message' => 'Koneksi ke database & direktori SLiMS Native berhasil terjalin!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Run migration step.
     */
    public function runMigrationStep(Request $request)
    {
        $request->validate([
            'step' => 'required|string|in:master_data,bibliography,members,circulation',
            'db_host' => 'required|string',
            'db_port' => 'required|string',
            'db_name' => 'required|string',
            'db_user' => 'required|string',
            'db_pass' => 'nullable|string',
            'slims_path' => 'nullable|string',
        ]);

        try {
            $this->migratorService->connect($request->only([
                'db_host', 'db_port', 'db_name', 'db_user', 'db_pass'
            ]), $request->slims_path);

            $step = $request->step;
            $result = [];

            switch ($step) {
                case 'master_data':
                    $result = $this->migratorService->migrateMasterData();
                    break;
                case 'bibliography':
                    $result = $this->migratorService->migrateBibliography();
                    break;
                case 'members':
                    $result = $this->migratorService->migrateMembers();
                    break;
                case 'circulation':
                    $result = $this->migratorService->migrateCirculation();
                    break;
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Migrasi tahap ' . $step . ' selesai dilakukan.',
                'details' => $result
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
