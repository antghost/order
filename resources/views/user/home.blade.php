@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked ">
                <li role="presentation" class="active"><a href="{{ url('user') }}">概况</a></li>
                <li role="presentation"><a href="{{ url('user/setting') }}">用餐设置</a></li>
                <li role="presentation"><a href="{{ url('user/info') }}">用餐流水</a></li>
                <li role="presentation"><a href="{{ url('user/comment') }}">管理</a></li>
            </ul>
        </div>

        <div class="col-md-8 col-md-offset-0">
            <div class="panel panel-default">
                <div class="panel-heading">概况</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div>
                        <p>
                            <strong>本人餐费标准</strong>
                            <label> 早餐：</label>10.00
                            <label> 午餐：</label>15.00
                            <label> 晚餐：</label>10.00
                        </p>
                    </div>
                    <div>
                        <h3>本月用餐天数</h3>
                        <label> 早餐：</label>3
                        <label> 午餐：</label>4
                        <label> 晚餐：</label>8
                        <h3>本月用餐费用</h3>
                        <label> 早餐：</label>30.00
                        <label> 午餐：</label>95.00
                        <label> 晚餐：</label>50.00
                        <p><label>费用合计：</label>385.00</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
