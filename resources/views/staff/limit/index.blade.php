@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @component('user.navpill',['staff_limit' => 'class=active'])

            @endcomponent

            <div class="col-md-9 col-md-offset-0">
                <div class="panel panel-info">
                    <div class="panel panel-heading">早餐</div>

                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>定餐时限</th>
                                    <th>停餐时限</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>2017-01-01 10:00</td>
                                    <td>2017-11-05 15:00</td>
                                    <td>
                                        <button class="btn btn-info">编辑</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="panel-heading">午餐</div>
                    <div class="panel-body">
                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>定餐时限</th>
                                    <th>停餐时限</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>2017-01-01 10:00</td>
                                    <td>2017-11-05 15:00</td>
                                    <td>
                                        <button class="btn btn-info">编辑</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-9 col-md-offset-0">
                <div class="panel panel-info">
                    <div class="panel panel-heading">午餐</div>

                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>定餐时限</th>
                                    <th>停餐时限</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>2017-01-01 10:00</td>
                                    <td>2017-11-05 15:00</td>
                                    <td>
                                        <button class="btn btn-info">编辑</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-9 col-md-offset-0">
                <div class="panel panel-info">
                    <div class="panel panel-heading">晚餐</div>

                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>定餐时限</th>
                                    <th>停餐时限</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>2017-01-01 10:00</td>
                                    <td>2017-11-05 15:00</td>
                                    <td>
                                        <button class="btn btn-info">编辑</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
