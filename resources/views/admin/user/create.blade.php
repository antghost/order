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
                            <form class="form-horizontal" action="{{ url('admin/user') }}" method="post">
                                {{ csrf_field() }}
                                <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                                    <label class="control-label col-md-2">用户名</label>
                                    <div class="col-md-5">
                                        <input type="text" name="username" class="form-control" required value="{{ old('username') }}">
                                    </div>
                                    @if($errors->has('username'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('username') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group {{$errors->has('name') ? 'has-error' : ''}}">
                                    <label class="control-label col-md-2">姓名</label>
                                    <div class="col-md-5">
                                        <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                                    </div>
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group {{$errors->has('nickname') ? 'has-error' : ''}}">
                                    <label class="control-label col-md-2">呢称</label>
                                    <div class="col-md-5">
                                        <input type="text" name="nickname" class="form-control" required value="{{ old('nickname') }}">
                                    </div>
                                    @if ($errors->has('nickname'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('nickname') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">所属部门</label>
                                    <div class="col-md-5">
                                        <select name="dept" class="form-control">
                                            @foreach($depts as $dept)
                                                @if ($dept->id == old('dept'))
                                                    <option value="{{ $dept->id }}" selected>{{ $dept->name }}</option>
                                                @else
                                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">餐费标准</label>
                                    <div class="col-md-5">
                                        <select name="price" class="form-control">
                                            @foreach($prices as $price)
                                                @if ($price->id == old('price'))
                                                    <option value="{{ $price->id }}" selected>{{ $price->name }}</option>
                                                @else
                                                    <option value="{{ $price->id }}">{{ $price->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('email' ? 'has-error' : '') }}">
                                    <label class="control-label col-md-2">E-mail</label>
                                    <div class="col-md-5">
                                        <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                                    </div>
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                                    <label class="control-label col-md-2">密码</label>
                                    <div class="col-md-5">
                                        <input type="password" name="password" class="form-control" required value="">
                                    </div>
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">密码确认</label>
                                    <div class="col-md-5">
                                        <input type="password" name="password_confirmation" class="form-control" required value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-2 text-right">
                                        <button type="submit" class="btn btn-info">提交</button>
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
