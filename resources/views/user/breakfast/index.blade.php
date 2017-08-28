@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @component('user.navpill',[ 'index' => '', 'breakfast'=> 'class=active', 'info' => ''])

            @endcomponent

            <div class="col-md-8 col-md-offset-0">
                <div class="panel panel-default">
                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="col-md-12">
                            <ul class="nav nav-tabs">
                                <li role="presentation" class="active"><a href="#">breakfast</a></li>
                                <li role="presentation" ><a href="{{ url('user/lunch') }}">lunch</a></li>
                                <li role="presentation" ><a href="{{ url('user/dinner') }}">dinner</a></li>
                            </ul>
                        </div>

                        <div class="col-md-3">
                            <label>开始日期</label><input type="button" class="btn btn-info" value="select">
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
