@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @component('user.navpill',['staff_order' => 'class=active'])

            @endcomponent

            <div class="col-md-9 col-md-offset-0">
                <div class="panel panel-info">

                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                            <div class="col-md-12">
                                <form action="#" class="navbar-form navbar-left" role="search">
                                    <div class="form-group">
                                        <label for="startdate">部门</label>
                                        <input type="text" id="startdate" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="enddate">姓名</label>
                                        <input type="text" id="enddate" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-info">search</button>
                                    </div>
                                </form>
                            </div>

                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>部门</th>
                                    <th>用户名</th>
                                    <th>姓名</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>飞人部</td>
                                    <td>tan</td>
                                    <td>兰帅</td>
                                    <td>
                                        <button class="btn btn-info">早餐</button>
                                        <button class="btn btn-info">午餐</button>
                                        <button class="btn btn-info">晚餐</button>
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
