@extends('opac.templates.default.layout')

@section('title', $page->title . ' - SIRITA OPAC')

@section('content')
<style>
    .page-container {
        max-width: 800px;
        margin: 0 auto;
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        padding: 3rem;
        border-radius: 28px;
        backdrop-filter: blur(12px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    }

    .page-header h1 {
        margin: 0 0 1.5rem 0;
        font-size: 2.5rem;
        font-weight: 800;
        letter-spacing: -0.03em;
        color: #f1f5f9;
        border-bottom: 2px solid var(--primary);
        padding-bottom: 0.75rem;
    }

    .page-body {
        color: #cbd5e1;
        line-height: 1.8;
        font-size: 1.05rem;
    }

    .page-body h1, .page-body h2, .page-body h3 {
        color: #f8fafc;
        margin-top: 2rem;
        margin-bottom: 1rem;
        font-weight: 700;
    }

    .page-body ul, .page-body ol {
        padding-left: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .page-body li {
        margin-bottom: 0.5rem;
    }

    .page-body blockquote {
        border-left: 4px solid var(--primary);
        padding-left: 1rem;
        margin: 1.5rem 0;
        color: #94a3b8;
        font-style: italic;
    }
</style>

<div class="page-container">
    <div class="page-header">
        <h1>{{ $page->title }}</h1>
    </div>
    <div class="page-body">
        {!! $page->content !!}
    </div>
</div>
@endsection
