@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @component('admin.navpill',[ 'dept' => 'class=active'])

            @endcomponent

            <div class="col-md-9">
                <ul class="nav nav-tabs">
                    <li role="presentation" ><a href="{{ url('admin/dept') }}">列表</a></li>
                    <li role="presentation" ><a href="{{ url('admin/dept/create') }}">新增</a></li>
                    <li role="presentation" class="active"><a href="#">编辑</a></li>
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
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                {!! implode('<br>', $errors->all()) !!}
                            </div>
                        @endif

                        <div class="col-md-12">
                            <form class="form-horizontal" action="{{ url('admin/dept/'.$dept->id) }}" method="post">
                                {{ csrf_field() }}
                                {{ method_field('PUT') }}
                                <div class="form-group">
                                    <label class="control-label col-md-2">部门名称</label>
                                    <div class="col-md-5">
                                        <input type="text" name="name" class="form-control" required value="{{ $dept->name }}">
                                    </div>
                                </div>
                                <div class="form-group text-right">
                                    <div class="col-md-2">
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
