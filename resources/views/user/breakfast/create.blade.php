@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @component('user.navpill',['breakfast'=> 'class=active'])

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
                            <ul class="nav nav-tabs">
                                <li role="presentation" class="active"><a href="#">breakfast</a></li>
                                <li role="presentation" ><a href="{{ url('user/lunch/create') }}">lunch</a></li>
                                <li role="presentation" ><a href="{{ url('user/dinner/create') }}">dinner</a></li>
                            </ul>
                        </div>

                        <div class="col-md-12">
                            <p>breakfast book</p>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>开始日期</th>
                                    <th>结束日期</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>2017-02-01</td>
                                    <td>2017-12-31</td>
                                    <td>
                                        <a href="#" class="btn btn-info">编辑</a>
                                        <a href="#" class="btn btn-danger">删除</a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-12">
                            <p>breakfast cancel</p>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>开始日期</th>
                                    <th>结束日期</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>2017-09-01</td>
                                    <td>2017-09-05</td>
                                    <td>
                                        <a href="#" class="btn btn-info">编辑</a>
                                        <a href="#" class="btn btn-danger">删除</a>
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
