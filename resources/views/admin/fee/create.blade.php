@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @component('admin.navpill',[ 'fee' => 'class=active'])

            @endcomponent

            <div class="col-md-9">
                <ul class="nav nav-tabs">
                    <li role="presentation" ><a href="{{ url('admin/fee') }}">列表</a></li>
                    <li role="presentation" class="active"><a href="{{ url('admin/fee/create') }}">新增</a></li>
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
                        @if (count($errors) >0 )
                                <div class="alert alert-warning">
                                    {!! implode('<br>',$errors->all()) !!}
                                </div>
                        @endif

                        <div class="col-md-12">
                            <form class="form-horizontal" action="{{ url('admin/fee') }}" method="post">
                                {{csrf_field()}}
                                <div class="form-group">
                                    <label class="control-label col-md-2">名称</label>
                                    <div class="col-md-5">
                                        <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">早餐</label>
                                    <div class="col-md-5">
                                        <input type="number" name="breakfast" class="form-control" required value="{{ old('breakfast') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">午餐</label>
                                    <div class="col-md-5">
                                        <input type="number" name="lunch" class="form-control" required value="{{ old('lunch') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">晚餐</label>
                                    <div class="col-md-5">
                                        <input type="number" name="dinner" class="form-control" required value="{{ old('dinner') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">生效日期</label>
                                    <div class="col-md-5">
                                        <input type="text" name="begin_date" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-2 text-right">
                                        <button type="submit" class="btn btn-info text-right">提交</button>
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
