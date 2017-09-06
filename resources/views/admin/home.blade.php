@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @component('admin.navpill',[ 'index' => 'class=active'])

        @endcomponent

        <div class="col-md-9 col-md-offset-0">
            <div class="panel panel-info">
                <div class="panel-heading">概况</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="col-md-12">
                        <h1>暂无内容展示</h1>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
