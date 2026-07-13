@extends('opac.templates.iainpare.layout')

@section('title', $page->title . ' - OPAC IAIN Parepare')

@section('content')
<style>
    .page-container {
        max-width: 800px;
        margin: 0 auto;
        background: #ffffff;
        border: 1px solid var(--border-color);
        padding: 3rem;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }

    .page-header h1 {
        margin: 0 0 1.5rem 0;
        font-size: 2.3rem;
        font-weight: 800;
        color: #14532d;
        border-bottom: 3px solid var(--accent);
        padding-bottom: 0.75rem;
    }

    .page-body {
        color: #1e293b;
        line-height: 1.8;
        font-size: 1rem;
    }

    .page-body h1, .page-body h2, .page-body h3 {
        color: #14532d;
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
        border-left: 4px solid var(--accent);
        padding-left: 1rem;
        margin: 1.5rem 0;
        color: #4b5563;
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
