@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked ">
                <li role="presentation" class="active"><a href="{{ url('user') }}">概况</a></li>
                <li role="presentation"><a href="{{ url('user/setting') }}">用餐设置</a></li>
                <li role="presentation"><a href="{{ url('user/info') }}">用餐流水</a></li>
                <li role="presentation"><a href="{{ url('user/comment') }}">管理</a></li>
            </ul>
        </div>

        <div class="col-md-8 col-md-offset-0">
            <div class="panel panel-default">
                <div class="panel-heading">概况</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
