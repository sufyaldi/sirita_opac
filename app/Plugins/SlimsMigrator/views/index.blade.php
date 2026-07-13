@extends('layouts.admin')

@section('title', 'Migrasi Data SLiMS - SIRITA Admin')
@section('header_title', 'Migrator SLiMS Native')

@section('content')
<div class="migrator-wrap">
    <div class="glass-card">
        <div class="card-header-simple">
            <h2 class="form-section-title"><i class="fas fa-file-import text-primary"></i> Migrasi Data dari SLiMS Native</h2>
            <p class="form-section-subtitle">Pindahkan data buku, anggota, sirkulasi denda, beserta file cover & foto dari SLiMS 9 Bulian.</p>
        </div>

        <div class="migrator-grid">
            {{-- Kiri: Konfigurasi --}}
            <div class="config-column">
                <h3 class="column-subtitle"><i class="fas fa-plug"></i> Konfigurasi Source SLiMS Native</h3>
                
                <form id="migratorConfigForm">
                    <div class="form-group">
                        <label class="form-label">Database Host</label>
                        <input type="text" name="db_host" class="form-control" value="{{ $defaults['db_host'] }}" required>
                    </div>

                    <div class="form-group-row">
                        <div class="form-group-half">
                            <label class="form-label">DB Port</label>
                            <input type="text" name="db_port" class="form-control" value="{{ $defaults['db_port'] }}" required>
                        </div>
                        <div class="form-group-half">
                            <label class="form-label">Nama Database</label>
                            <input type="text" name="db_name" class="form-control" value="{{ $defaults['db_name'] }}" required>
                        </div>
                    </div>

                    <div class="form-group-row">
                        <div class="form-group-half">
                            <label class="form-label">DB Username</label>
                            <input type="text" name="db_user" class="form-control" value="{{ $defaults['db_user'] }}" required>
                        </div>
                        <div class="form-group-half">
                            <label class="form-label">DB Password</label>
                            <input type="password" name="db_pass" class="form-control" value="{{ $defaults['db_pass'] }}" placeholder="Kosongkan jika root local">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Direktori Folder SLiMS Native</label>
                        <input type="text" name="slims_path" class="form-control" value="{{ $defaults['slims_path'] }}" placeholder="Contoh: /var/www/html/slims9_bulian">
                        <span style="font-size: 0.72rem; color: #64748b; margin-top: 0.35rem; display: block;">Untuk mengambil berkas cover bibliografi dan foto anggota.</span>
                    </div>

                    <div class="btn-group-action" style="margin-top: 1.5rem; display: flex; gap: 0.75rem;">
                        <button type="button" id="btnTestConn" class="btn-secondary" style="flex:1;"><i class="fas fa-network-wired"></i> Cek Koneksi</button>
                        <button type="button" id="btnStartMigrate" class="btn-primary" style="flex:1.5;" disabled><i class="fas fa-play"></i> Mulai Migrasi</button>
                    </div>
                </form>
            </div>

            {{-- Kanan: Progress & Log Console --}}
            <div class="progress-column">
                <h3 class="column-subtitle"><i class="fas fa-terminal"></i> Status & Log Migrasi</h3>

                <div class="status-box" id="migrateStatusBox">
                    <span class="status-indicator-badge standby">Siap</span>
                    <span class="status-text">Menunggu konfigurasi & tes koneksi...</span>
                </div>

                <div class="progress-bar-wrap">
                    <div class="progress-bar" id="progressBar"></div>
                </div>

                <div class="console-log-wrap">
                    <div class="console-header">Console Output Log</div>
                    <div class="console-body" id="consoleLog">
                        [System] Silakan isi formulir konfigurasi di sebelah kiri, lalu tekan tombol "Cek Koneksi" terlebih dahulu.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Dashboard layout */
.migrator-wrap {
    max-width: 1000px;
    margin: 0 auto;
    padding: 1rem 0;
}
.glass-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    padding: 2.2rem 2.8rem;
    border-radius: 18px;
    backdrop-filter: blur(12px);
}
.card-header-simple {
    margin-bottom: 2rem;
    border-bottom: 1px solid var(--border-color);
    padding-bottom: 1.2rem;
}
.form-section-title { font-size: 1.25rem; font-weight: 700; color: white; margin: 0 0 .3rem; }
.form-section-title i { margin-right: .4rem; }
.form-section-subtitle { color: #64748b; font-size: .875rem; margin: 0; }

/* Grid columns */
.migrator-grid {
    display: grid;
    grid-template-columns: 1.1fr 1.3fr;
    gap: 2.5rem;
}
.column-subtitle {
    font-size: .95rem;
    font-weight: 600;
    color: white;
    margin: 0 0 1.25rem;
    border-bottom: 1px dashed rgba(255,255,255,.05);
    padding-bottom: .5rem;
}
.column-subtitle i { margin-right: .3rem; color: var(--primary); }

.form-group { margin-bottom: 1.2rem; }
.form-group-row { display: flex; gap: 1rem; margin-bottom: 1.2rem; }
.form-group-half { flex: 1; }

.form-label {
    display: block; margin-bottom: 0.45rem;
    color: #94a3b8; font-weight: 600; font-size: .82rem;
}
.form-control {
    width: 100%; padding: .65rem .95rem; border-radius: 8px;
    background: rgba(30, 41, 59, 0.45); border: 1px solid var(--border-color);
    color: white; outline: none; box-sizing: border-box; font-size: .875rem;
}
.form-control:focus { border-color: var(--primary); }

/* Buttons */
.btn-primary {
    background: var(--primary); border: none; color: white;
    padding: .7rem 1.4rem; border-radius: 8px; font-weight: 600;
    font-size: .875rem; cursor: pointer; display: inline-flex; align-items: center; gap: .4rem;
    justify-content: center;
}
.btn-primary:hover:not(:disabled) { background: var(--primary-hover); }
.btn-primary:disabled { opacity: 0.4; cursor: not-allowed; }

.btn-secondary {
    background: transparent; border: 1px solid var(--border-color);
    color: #94a3b8; padding: .7rem 1.4rem; border-radius: 8px;
    font-weight: 600; font-size: .875rem; cursor: pointer;
    transition: all .25s; text-decoration: none; display: inline-flex; align-items: center; gap: .4rem;
    justify-content: center;
}
.btn-secondary:hover { background: rgba(255, 255, 255, 0.05); color: white; }

/* Status Box */
.status-box {
    background: rgba(30, 41, 59, 0.5);
    border: 1px solid var(--border-color);
    padding: 0.85rem 1.2rem;
    border-radius: 10px;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.25rem;
}
.status-indicator-badge {
    padding: 0.2rem 0.6rem;
    border-radius: 6px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
}
.status-indicator-badge.standby { background: rgba(100, 116, 139, 0.15); color: #94a3b8; }
.status-indicator-badge.success { background: rgba(16, 185, 129, 0.15); color: #34d399; }
.status-indicator-badge.running { background: rgba(245, 158, 11, 0.15); color: #fbbf24; }
.status-indicator-badge.error   { background: rgba(239, 68, 68, 0.15); color: #f87171; }
.status-text { font-size: 0.85rem; color: #cbd5e1; font-weight: 500; }

/* Progress bar */
.progress-bar-wrap {
    height: 8px;
    background: rgba(255,255,255,0.05);
    border-radius: 99px;
    overflow: hidden;
    margin-bottom: 1.5rem;
    border: 1px solid rgba(255,255,255,0.03);
}
.progress-bar {
    width: 0%;
    height: 100%;
    background: linear-gradient(to right, #6366f1, #a855f7);
    border-radius: 99px;
    transition: width 0.3s ease;
}

/* Console Log */
.console-log-wrap {
    border: 1px solid var(--border-color);
    border-radius: 12px;
    overflow: hidden;
}
.console-header {
    background: rgba(30, 41, 59, 0.7);
    padding: 0.6rem 1rem;
    font-size: 0.75rem;
    font-weight: 700;
    color: #64748b;
    border-bottom: 1px solid var(--border-color);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.console-body {
    height: 180px;
    background: #090d16;
    padding: 0.9rem 1.1rem;
    font-family: 'Courier New', Courier, monospace;
    font-size: 0.8rem;
    color: #38bdf8;
    overflow-y: auto;
    white-space: pre-line;
    line-height: 1.45;
}
.log-error { color: #f87171; }
.log-success { color: #34d399; }
.log-system { color: #94a3b8; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnTestConn = document.getElementById('btnTestConn');
    const btnStartMigrate = document.getElementById('btnStartMigrate');
    const configForm = document.getElementById('migratorConfigForm');
    const consoleLog = document.getElementById('consoleLog');
    const progressBar = document.getElementById('progressBar');
    const statusBox = document.getElementById('migrateStatusBox');

    let isConnected = false;

    // Helper: append text to console log
    function log(message, type = 'system') {
        const span = document.createElement('span');
        span.className = 'log-' + type;
        span.innerText = message + '\n';
        consoleLog.appendChild(span);
        consoleLog.scrollTop = consoleLog.scrollHeight;
    }

    // Helper: update status badge
    function updateStatus(text, badgeClass, badgeText) {
        statusBox.innerHTML = `
            <span class="status-indicator-badge ${badgeClass}">${badgeText}</span>
            <span class="status-text">${text}</span>
        `;
    }

    // Test Connection
    btnTestConn.addEventListener('click', function () {
        const formData = new FormData(configForm);
        
        btnTestConn.disabled = true;
        updateStatus('Mengecek koneksi database...', 'running', 'menghubungkan');
        log('[System] Melakukan uji koneksi ke database & direktori SLiMS Native...', 'system');

        fetch('{{ route("admin.plugins.slims-migrator.test-conn") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            isConnected = true;
            btnTestConn.disabled = false;
            btnStartMigrate.disabled = false;
            updateStatus(data.message, 'success', 'koneksi ok');
            log('[Success] ' + data.message, 'success');
        })
        .catch(err => {
            isConnected = false;
            btnTestConn.disabled = false;
            btnStartMigrate.disabled = true;
            const errMsg = err.message || 'Koneksi gagal.';
            updateStatus(errMsg, 'error', 'koneksi error');
            log('[Error] ' + errMsg, 'error');
        });
    });

    // Run Step-by-Step Migration (Async chain to avoid timeout)
    btnStartMigrate.addEventListener('click', async function () {
        if (!isConnected) return;

        // Disable UI
        btnStartMigrate.disabled = true;
        btnTestConn.disabled = true;
        configForm.querySelectorAll('input').forEach(i => i.disabled = true);

        const formData = new FormData(configForm);
        const steps = [
            { id: 'master_data', name: 'Master Data (Tipe Anggota, GMD, Penerbit, Pengarang, Subjek, Rak)' },
            { id: 'bibliography', name: 'Bibliografi & Eksemplar Buku (Buku & Cover Docs)' },
            { id: 'members', name: 'Keanggotaan (Akun Anggota & Foto Persons)' },
            { id: 'circulation', name: 'Circulation & Denda (Peminjaman & Fines)' }
        ];

        let completed = 0;
        progressBar.style.width = '0%';

        log('\n[System] Memulai migrasi data tahap demi tahap...', 'system');

        for (let i = 0; i < steps.length; i++) {
            const step = steps[i];
            const pct = Math.round((i / steps.length) * 100);
            progressBar.style.width = pct + '%';
            
            updateStatus(`Migrasi: ${step.name}...`, 'running', 'memproses');
            log(`[System] Sedang memigrasi ${step.name}...`, 'system');

            formData.set('step', step.id);

            try {
                const response = await fetch('{{ route("admin.plugins.slims-migrator.run-step") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                if (!response.ok) {
                    const errData = await response.json();
                    throw new Error(errData.message || `Gagal pada tahap ${step.id}`);
                }

                const data = await response.json();
                log(`[Success] Tahap ${step.id} berhasil dimigrasikan!`, 'success');
                
                // Print detail records
                if (data.details) {
                    Object.keys(data.details).forEach(key => {
                        const val = data.details[key];
                        if (typeof val === 'object') {
                            log(`  → ${key}: Source ${val.total_source} | Diimpor ${val.imported} | Diupdate ${val.updated}`, 'system');
                        } else {
                            log(`  → ${key}: ${val}`, 'system');
                        }
                    });
                }

                completed++;
            } catch (err) {
                log(`[Error] Gagal memigrasikan ${step.name}: ${err.message}`, 'error');
                updateStatus(`Gagal pada tahap ${step.name}`, 'error', 'error');
                
                // Re-enable config inputs
                btnTestConn.disabled = false;
                configForm.querySelectorAll('input').forEach(i => i.disabled = false);
                return; // Stop migration
            }
        }

        // Completion
        progressBar.style.width = '100%';
        updateStatus('Migrasi data selesai dikerjakan secara sukses!', 'success', 'selesai');
        log('\n[Success] SEMUA TAHAP MIGRASI BERHASIL DISELESAIKAN DENGAN SEMPURNA!', 'success');

        // Re-enable config inputs
        btnTestConn.disabled = false;
        btnStartMigrate.disabled = false;
        configForm.querySelectorAll('input').forEach(i => i.disabled = false);
    });
});
</script>
@endpush
