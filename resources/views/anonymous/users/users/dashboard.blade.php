@extends('anonymous.default')

@section('title', $metadata['title'])

@section('content')
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0 text-dark text-center">{{ trans('users.baseline') }}</h1>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        Home
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <div class="info-box bg-light">
                    <div class="info-box-content">
                        <span class="info-box-text text-center text-muted">{{ trans('global.our_news') }}</span>
                        <span class="info-box-number text-center text-muted mb-0"><a href="{{ config('services.twitter.url') }}" target="_blank" rel="noopener" title="twitter.com"><i class="fab fa-twitter mr-2"></i>Twitter</a></span>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="info-box bg-light">
                    <div class="info-box-content">
                        <span class="info-box-text text-center text-muted">{{ trans('global.next_features') }}</span>
                        <span class="info-box-number text-center text-muted mb-0"><a href="{{ config('services.github.nextgen') }}" target="_blank" rel="noopener" title="github.com"><i class="fab fa-github mr-2"></i>Github</a></span>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="info-box bg-light">
                    <div class="info-box-content">
                        <span class="info-box-text text-center text-muted">{{ trans('global.bugs_reported') }}</span>
                        <span class="info-box-number text-center text-muted mb-0"><a href="{{ config('services.github.issues') }}" target="_blank" rel="noopener" title="github.com"><i class="fab fa-github mr-2"></i>Github</a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
