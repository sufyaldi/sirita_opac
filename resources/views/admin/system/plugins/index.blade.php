@extends('layouts.admin')

@section('title', 'Daftar Plugin - SIRITA Admin')
@section('header_title', 'Daftar Plugin')

@section('content')
<style>
    .plugin-card-grid {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .plugin-item-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        padding: 1.5rem 2rem;
        border-radius: 16px;
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .plugin-item-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    }
    .plugin-info {
        flex: 1;
    }
    .plugin-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: white;
        margin: 0 0 0.4rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .plugin-desc {
        color: #94a3b8;
        font-size: 0.92rem;
        margin: 0 0 0.5rem;
        line-height: 1.5;
    }
    .plugin-meta {
        font-size: 0.78rem;
        color: #64748b;
        display: flex;
        gap: 1rem;
        font-weight: 500;
    }
    .plugin-meta a {
        color: #818cf8;
        text-decoration: none;
    }
    .plugin-meta a:hover {
        text-decoration: underline;
    }
    .plugin-version {
        color: #ec4899;
        font-weight: 600;
    }

    /* Switch Style Toggle */
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 26px;
        margin-left: 2rem;
    }
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #334155;
        transition: .4s;
        border-radius: 34px;
        border: 1px solid var(--border-color);
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: #94a3b8;
        transition: .4s;
        border-radius: 50%;
    }
    input:checked + .slider {
        background-color: #10b981;
        border-color: #34d399;
    }
    input:checked + .slider:before {
        transform: translateX(24px);
        background-color: white;
    }
    .status-text {
        font-size: 0.8rem;
        font-weight: 600;
        color: #64748b;
        margin-left: 0.5rem;
        vertical-align: middle;
        display: inline-block;
        line-height: 26px;
    }
</style>

<div class="plugin-card-grid">
    @if(empty($pluginsList))
        <div style="background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 16px; padding: 3rem; text-align: center; color: #64748b;">
            Belum ada plugin yang terdeteksi di folder <code>app/Plugins</code>.
        </div>
    @else
        @foreach($pluginsList as $index => $plugin)
            <div class="plugin-item-card">
                <div class="plugin-info">
                    <h3 class="plugin-title">
                        <span>🔌</span> {{ $plugin['name'] }}
                    </h3>
                    <p class="plugin-desc">{{ $plugin['description'] }}</p>
                    <div class="plugin-meta">
                        <span>Versi: <span class="plugin-version">{{ $plugin['version'] }}</span></span>
                        <span>Dibuat oleh: <a href="{{ $plugin['author_uri'] }}" target="_blank">{{ $plugin['author'] }}</a></span>
                        <span>Folder: <code>app/Plugins/{{ $plugin['key'] }}</code></span>
                    </div>
                </div>
                <div style="display: flex; align-items: center;">
                    <label class="switch">
                        <input type="checkbox" class="toggle-plugin" data-key="{{ $plugin['key'] }}" {{ $plugin['is_active'] ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                    <span class="status-text" id="status-{{ $plugin['key'] }}">{{ $plugin['is_active'] ? 'Aktif' : 'Nonaktif' }}</span>
                </div>
            </div>
        @endforeach
    @endif
</div>

<script>
    document.querySelectorAll('.toggle-plugin').forEach(item => {
        item.addEventListener('change', function() {
            const key = this.getAttribute('data-key');
            const isActive = this.checked ? 1 : 0;
            const statusLabel = document.getElementById(`status-${key}`);

            statusLabel.textContent = 'Updating...';

            fetch("{{ route('admin.system.plugins.toggle') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    key: key,
                    is_active: isActive
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    statusLabel.textContent = isActive ? 'Aktif' : 'Nonaktif';
                    // Show a toast message if needed
                } else {
                    alert(data.message);
                    this.checked = !this.checked;
                    statusLabel.textContent = !isActive ? 'Aktif' : 'Nonaktif';
                }
            })
            .catch(err => {
                console.error(err);
                alert("Gagal mengubah status plugin.");
                this.checked = !this.checked;
                statusLabel.textContent = !isActive ? 'Aktif' : 'Nonaktif';
            });
        });
    });
</script>
@endsection
