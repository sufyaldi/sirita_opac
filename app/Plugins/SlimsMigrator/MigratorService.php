<?php

namespace App\Plugins\SlimsMigrator;

use PDO;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MigratorService
{
    protected $pdo;
    protected $slimsPath;

    /**
     * Test dynamic connection to SLiMS native database.
     */
    public function connect($config, $slimsPath = null)
    {
        $dsn = "mysql:host={$config['db_host']};port={$config['db_port']};dbname={$config['db_name']};charset=utf8mb4";
        
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $config['db_user'], $config['db_pass'], $options);
        } catch (Exception $e) {
            throw new Exception("Gagal menghubungkan ke Database SLiMS: " . $e->getMessage());
        }

        if ($slimsPath) {
            if (!File::isDirectory($slimsPath)) {
                throw new Exception("Folder instalasi SLiMS Native tidak ditemukan di path: " . $slimsPath);
            }
            $this->slimsPath = rtrim($slimsPath, '/');
        }

        return true;
    }

    /**
     * Migrate Master Data tables.
     */
    public function migrateMasterData()
    {
        $report = [];

        // 1. Tipe Anggota (mst_member_type)
        $report['member_types'] = $this->syncTable('mst_member_type', 'member_type_id', [
            'member_type_name', 'loan_limit', 'loan_periode', 'reborrow_limit', 
            'fine_each_day', 'grace_periode', 'membership_periode', 'input_date', 'last_update'
        ]);

        // 2. GMD (mst_gmd)
        $report['gmd'] = $this->syncTable('mst_gmd', 'gmd_id', [
            'gmd_code', 'gmd_name', 'input_date', 'last_update'
        ]);

        // 3. Penerbit (mst_publisher)
        $report['publishers'] = $this->syncTable('mst_publisher', 'publisher_id', [
            'publisher_name', 'input_date', 'last_update'
        ]);

        // 4. Tempat Terbit (mst_place)
        $report['places'] = $this->syncTable('mst_place', 'place_id', [
            'place_name', 'input_date', 'last_update'
        ]);

        // 5. Pengarang (mst_author)
        $report['authors'] = $this->syncTable('mst_author', 'author_id', [
            'author_name', 'authority_type', 'input_date', 'last_update'
        ]);

        // 6. Subjek / Topik (mst_topic)
        $report['topics'] = $this->syncTable('mst_topic', 'topic_id', [
            'topic', 'topic_type', 'input_date', 'last_update'
        ]);

        // 7. Lokasi Rak (mst_location)
        $report['locations'] = $this->syncTable('mst_location', 'location_id', [
            'location_code', 'location_name', 'input_date', 'last_update'
        ]);

        return $report;
    }

    /**
     * Migrate Bibliography & Items.
     */
    public function migrateBibliography()
    {
        $report = [];

        // 1. Bibliografi (biblio)
        $report['biblio'] = $this->syncTable('biblio', 'biblio_id', [
            'title', 'edition', 'isbn_issn', 'publisher_id', 'publish_year', 
            'collation', 'series_title', 'call_number', 'source', 'publish_place_id',
            'classification', 'notes', 'image', 'file_att', 'opac_hide', 'promoted', 
            'labels', 'input_date', 'last_update'
        ]);

        // 2. Eksemplar Buku (item)
        $report['items'] = $this->syncTable('item', 'item_code', [
            'biblio_id', 'call_number', 'coll_type_id', 'location_id', 'shelf_location',
            'inventory_code', 'received_date', 'supplier', 'item_status_id', 'source', 
            'invoice', 'price', 'input_date', 'last_update'
        ], 'string');

        // Copy Book Covers if slims path is set
        if ($this->slimsPath) {
            $srcCoverDir = $this->slimsPath . '/images/docs';
            $destCoverDir = public_path('images/docs'); // or storage path
            
            if (File::isDirectory($srcCoverDir)) {
                if (!File::isDirectory($destCoverDir)) {
                    File::makeDirectory($destCoverDir, 0755, true);
                }
                
                // Copy all cover files
                $files = File::files($srcCoverDir);
                $copied = 0;
                foreach ($files as $file) {
                    $destFile = $destCoverDir . '/' . $file->getFilename();
                    if (!File::exists($destFile)) {
                        File::copy($file->getRealPath(), $destFile);
                        $copied++;
                    }
                }
                $report['covers_copied'] = $copied;
            }
        }

        return $report;
    }

    /**
     * Migrate Members & Photos.
     */
    public function migrateMembers()
    {
        $report = [];

        // 1. Members (member)
        $report['members'] = $this->syncTable('member', 'member_id', [
            'member_name', 'gender', 'member_type_id', 'member_email', 'member_address',
            'member_phone', 'inst_name', 'expire_date', 'pin', 'mpasswd', 
            'member_image', 'register_date', 'member_since_date', 'input_date', 'last_update'
        ], 'string');

        // Copy Member Photos
        if ($this->slimsPath) {
            $srcPhotoDir = $this->slimsPath . '/images/persons';
            $destPhotoDir = public_path('images/persons');
            
            if (File::isDirectory($srcPhotoDir)) {
                if (!File::isDirectory($destPhotoDir)) {
                    File::makeDirectory($destPhotoDir, 0755, true);
                }
                
                $files = File::files($srcPhotoDir);
                $copied = 0;
                foreach ($files as $file) {
                    $destFile = $destPhotoDir . '/' . $file->getFilename();
                    if (!File::exists($destFile)) {
                        File::copy($file->getRealPath(), $destFile);
                        $copied++;
                    }
                }
                $report['photos_copied'] = $copied;
            }
        }

        return $report;
    }

    /**
     * Migrate Circulation & Overdue fines.
     */
    public function migrateCirculation()
    {
        $report = [];

        // 1. Peminjaman (loan)
        $report['loans'] = $this->syncTable('loan', 'loan_id', [
            'item_code', 'member_id', 'loan_date', 'due_date', 'renewed', 
            'loan_rules_id', 'is_lent', 'is_return', 'return_date', 'actual',
            'input_date', 'last_update'
        ]);

        // 2. Fines (fines)
        $report['fines'] = $this->syncTable('fines', 'fines_id', [
            'fines_date', 'member_id', 'debet', 'credit', 'description'
        ]);

        return $report;
    }

    /**
     * Generic sync table function.
     */
    protected function syncTable($tableName, $primaryKey, $fields, $pkType = 'int')
    {
        // Get rows from SLiMS Native DB
        $stmt = $this->pdo->query("SELECT * FROM {$tableName}");
        $rows = $stmt->fetchAll();
        $imported = 0;
        $updated = 0;

        foreach ($rows as $row) {
            $pkValue = $row[$primaryKey];
            
            // Build insert/update array
            $data = [];
            foreach ($fields as $field) {
                $data[$field] = $row[$field] ?? null;
            }

            // Check if record already exists in SIRITA DB
            $exists = DB::table($tableName)->where($primaryKey, $pkValue)->exists();
            if ($exists) {
                DB::table($tableName)->where($primaryKey, $pkValue)->update($data);
                $updated++;
            } else {
                $data[$primaryKey] = $pkValue;
                DB::table($tableName)->insert($data);
                $imported++;
            }
        }

        return [
            'total_source' => count($rows),
            'imported' => $imported,
            'updated' => $updated
        ];
    }
}
