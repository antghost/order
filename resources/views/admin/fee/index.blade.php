@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @component('admin.navpill',[ 'fee' => 'class=active'])

            @endcomponent

            <div class="col-md-9">
                <ul class="nav nav-tabs">
                    <li role="presentation" class="active"><a href="{{ url('admin/fee') }}">列表</a></li>
                    <li role="presentation" ><a href="{{ url('admin/fee/create') }}">新增</a></li>
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
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>费用名称</th>
                                    <th>费用标准</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($prices as $price)
                                <tr>
                                    <td>{{ $price->name }}</td>
                                    <td>{{ '早餐：'.$price->breakfast.' 午餐：'.$price->lunch.' 晚餐：'.$price->dinner}}</td>
                                    <td>
                                        <a href="#" class="btn btn-info">编辑</a>
                                        <a href="#" class="btn btn-danger">删除</a>
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
