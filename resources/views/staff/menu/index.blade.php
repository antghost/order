@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @component('user.navpill',['staff_menus' => 'class=active'])

            @endcomponent

            <div class="col-md-9 col-md-offset-0">
                <div class="panel panel-default">

                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="col-md-12">
                            <h4>早餐</h4>
                        </div>

                        <div class="col-md-12">
                            <h4>中餐</h4>
                        </div>

                        <div class="col-md-12">
                            <h4>晚餐</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
