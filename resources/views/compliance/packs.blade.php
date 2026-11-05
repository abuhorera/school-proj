@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header p-3 mb-2 border-bottom bg-white">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 22px;">Compliance Packs</h1>
                <p class="text-muted small mb-0">Install the statutory country/board pack for your school. Uninstalling never deletes saved field values.</p>
            </div>
            <a href="{{ route('compliance.overview') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> Back to Overview
            </a>
        </div>
    </div>

    <section class="content px-3 pb-5">
        @include('compliance.partials.subnav')

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="row row-cols-1 row-cols-md-2 g-3 mb-4">
            @foreach($packs as $pack)
                <div class="col mb-3">
                    <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px; {{ $pack->is_primary ? 'border-left: 4px solid #e11d48 !important;' : '' }}">
                        <div class="card-body p-4 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <h5 class="card-title font-weight-bold text-dark m-0">{{ $pack->name }}</h5>
                                    <div>
                                        @if($pack->is_primary)
                                            <span class="badge badge-danger text-uppercase px-2 py-1" style="font-size: 10px;">Primary</span>
                                        @elseif($pack->is_installed)
                                            <span class="badge badge-success text-uppercase px-2 py-1" style="font-size: 10px;">Installed</span>
                                        @else
                                            <span class="badge badge-light border text-muted text-uppercase px-2 py-1" style="font-size: 10px;">Available</span>
                                        @endif
                                    </div>
                                </div>
                                <p class="text-muted small mb-4" style="line-height: 1.6;">
                                    {{ $pack->description }}
                                </p>
                            </div>
                            <div class="d-flex align-items-center justify-content-end gap-2 pt-3 border-top">
                                @if(!$pack->is_installed)
                                    <form action="{{ route('compliance.packs.install') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="pack_key" value="{{ $pack->pack_key }}">
                                        <button type="submit" class="btn btn-primary btn-sm px-3 font-weight-bold">
                                            <i class="fas fa-download mr-1"></i> Install
                                        </button>
                                    </form>
                                @else
                                    @if(!$pack->is_primary)
                                        <form action="{{ route('compliance.packs.primary') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="pack_key" value="{{ $pack->pack_key }}">
                                            <button type="submit" class="btn btn-outline-primary btn-sm px-3 font-weight-bold">
                                                <i class="fas fa-star mr-1"></i> Make primary
                                            </button>
                                        </form>
                                        <form action="{{ route('compliance.packs.uninstall') }}" method="POST" onsubmit="return confirm('Uninstall [{{ $pack->name }}]?')">
                                            @csrf
                                            <input type="hidden" name="pack_key" value="{{ $pack->pack_key }}">
                                            <button type="submit" class="btn btn-outline-danger btn-sm px-3">
                                                Uninstall
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted small italic">Active Default Standard</span>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection

