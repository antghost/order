@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="container">
        <div class="row">
            @component('admin.navpill',[ 'role' => 'class=active'])

            @endcomponent

            <div class="col-md-9">
                <ul class="nav nav-tabs">
                    <li role="presentation" ><a href="{{ url('admin/role') }}">列表</a></li>
                    <li role="presentation" class="active"><a href="{{ url('admin/role/create') }}">新增</a></li>
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
                            <form class="form-horizontal" action="{{ url('admin/role') }}" method="post">
                                {{ csrf_field() }}
                                <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                                    <label class="control-label col-md-2">角色</label>
                                    <div class="col-md-5">
                                        <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                                    </div>
                                    @if($errors->has('username'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('username') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">权限</label>
                                    <div class="col-md-5">
                                        <select name="permissions[]" class="form-control" multiple="multiple">
                                            @foreach($permissions as $permission)
                                                <option>{{ $permission->name }}</option>
                                            @endforeach
                                        </select>
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
@section('script')
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script>
        $('select').select2();
        $('form').submit(function () {
            $("button:submit").prop('disabled', true).text('提交中');
        });
    </script>
@endsection