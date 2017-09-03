@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @component('user.navpill',[])

        @endcomponent

        <div class="col-md-8 col-md-offset-0">
            <div class="panel panel-default">
                <div class="panel-heading">个人信息</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="col-md-10">
                        <form class="form-horizontal" action="#" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="">
                            <div class="form-group">
                                <label class="control-label col-md-2">呢称</label>
                                <div class="col-md-6">
                                    <input type="text" name="nickname" class="form-control" required value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2">E-mail</label>
                                <div class="col-md-6">
                                    <input type="email" name="" class="form-control" required value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2">手机</label>
                                <div class="col-md-6">
                                    <input type="text" name="nickname" class="form-control" required value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2">办公电话</label>
                                <div class="col-md-6">
                                    <input type="text" name="nickname" class="form-control" required value="">
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
                            <button type="submit" class="btn btn-default">submit</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
