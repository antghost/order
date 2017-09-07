@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @component('admin.navpill',[ 'user' => 'class=active'])

            @endcomponent

            <div class="col-md-9">
                <ul class="nav nav-tabs">
                    <li role="presentation" ><a href="{{ url('admin/user') }}">列表</a></li>
                    <li role="presentation" class="active"><a href="{{ url('admin/user/create') }}">新增</a></li>
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
                            <form class="form-horizontal" action="{{ url('admin/dept') }}" method="post">
                                <div class="form-group">
                                    <label class="control-label col-md-2">用户名</label>
                                    <div class="col-md-5">
                                        <input type="text" name="username" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">姓名</label>
                                    <div class="col-md-5">
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">所属部门</label>
                                    <div class="col-md-5">
                                        <input type="text" name="dept" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">E-mail</label>
                                    <div class="col-md-5">
                                        <input type="email" name="email" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">密码</label>
                                    <div class="col-md-6">
                                        <input type="password" name="password" class="form-control" required value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">密码确认</label>
                                    <div class="col-md-6">
                                        <input type="password" name="password_confirmation" class="form-control" required value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-2 text-right">
                                        <button type="submit" class="btn btn-info">submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
