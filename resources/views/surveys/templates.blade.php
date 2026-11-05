@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header p-3 mb-2 border-bottom bg-white">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 22px;">Start from a template</h1>
                <p class="text-muted small mb-0">Professionally-worded starting points. Pick one, then tweak and publish.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('survey.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> All surveys
                </a>
                <a href="{{ route('survey.create') }}" class="btn btn-primary btn-sm font-weight-bold">
                    <i class="fas fa-file mr-1"></i> Start blank
                </a>
            </div>
        </div>
    </div>

    <section class="content px-3">
        @include('surveys.partials.subnav')

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3 mb-4">
            @foreach($templates as $key => $tpl)
                <div class="col mb-3">
                    <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px; transition: transform 0.2s;">
                        <div class="card-body p-4 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge badge-light border text-muted px-2 py-1" style="font-size: 11px;">
                                        {{ ucfirst(str_replace('_', ' ', $tpl['kind'])) }}
                                        @if($tpl['is_anonymous'])
                                            · Anonymous
                                        @endif
                                    </span>
                                    <span class="text-muted small font-weight-bold">{{ $tpl['questions_count'] }} questions</span>
                                </div>
                                <h5 class="card-title font-weight-bold text-dark mb-2">{{ $tpl['title'] }}</h5>
                                <p class="card-text text-muted small mb-4" style="line-height: 1.5;">
                                    {{ $tpl['description'] }}
                                </p>
                            </div>
                            <form action="{{ route('survey.templates.use', $key) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary btn-block font-weight-bold py-2" style="border-radius: 8px;">
                                    <i class="fas fa-magic mr-1.5"></i> Use template
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection

