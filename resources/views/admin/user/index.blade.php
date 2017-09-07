@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @component('admin.navpill',[ 'user' => 'class=active'])

            @endcomponent

            <div class="col-md-9">
                <ul class="nav nav-tabs">
                    <li role="presentation" class="active"><a href="{{ url('admin/user') }}">列表</a></li>
                    <li role="presentation" ><a href="{{ url('admin/user/create') }}">新增</a></li>
                </ul>
            </div>
            <div class="col-md-9 col-md-offset-0">
                <div class="panel panel-info">
                    <!--<div class="panel-heading">概况</div>-->

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
                                        <button type="submit" class="btn btn-primary">search</button>
                                    </div>
                                </form>
                            </div>

                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>姓名</th>
                                    <th>用户名</th>
                                    <th>所属部门</th>
                                    <th>餐费标准</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>兰帅</td>
                                    <td>lans</td>
                                    <td>飞人部</td>
                                    <td>早餐：午餐：晚餐：</td>
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
