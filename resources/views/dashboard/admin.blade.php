@extends('layouts.app')

@section('content')
<div class="container dashboard-wrapper">
    <div class="row">
        <div class="col-12">
            <h2>Buffs Administration</h2>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-4 col-sm-6 col-12">
            <div class="card bg-primary mb-4">
                <div class="card-header">
                    <h5 class="my-0">Chatbots</h5>
                </div>
                <div class="card-body">

                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('admin.chatbots') }}" class="btn btn-secondary">View More</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <div class="card bg-success mb-4">
                <div class="card-header">
                    <h5 class="my-0">BetaList</h5>
                </div>
                <div class="card-body">

                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('admin.betalist') }}" class="btn btn-secondary">View More</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <div class="card bg-info mb-4">
                <div class="card-header">
                    <h5 class="my-0">Some Other Card...</h5>
                </div>
                <div class="card-body">

                </div>
                <div class="card-footer text-right">
                    <a href="#" class="btn btn-secondary">Something...</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
