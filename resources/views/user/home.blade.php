@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @component('user.navpill',[ 'index' => 'class=active'])

        @endcomponent

        <div class="col-md-9 col-md-offset-0">
            <div class="panel panel-info">
                <div class="panel-heading">概况</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="col-md-12">
                        <p>
                            <strong>本人餐费标准</strong>
                            <label> 早餐：</label>{{ Auth::user()->price->breakfast }}
                            <label> 午餐：</label>{{ Auth::user()->price->lunch }}
                            <label> 晚餐：</label>{{ Auth::user()->price->dinner }}
                        </p>
                        <hr>
                    </div>

                    <div class="col-md-12">
                        <label>月份：</label>
                        <input type="text" name="selectMonth" onclick="WdatePicker({dateFmt:'yyyy-MM'})">
                    </div>

                    <div class="col-md-12">
                        <h4>天数</h4>
                        <div class="col-md-4 text-center"><h2>{{ $breakfastDays or '' }}</h2></div>
                        <div class="col-md-4 text-center"><h2>4</h2></div>
                        <div class="col-md-4 text-center"><h2>8</h2></div>
                        <div class="col-md-4 text-center"><label>早餐</label></div>
                        <div class="col-md-4 text-center"><label>午餐</label></div>
                        <div class="col-md-4 text-center"><label>晚餐</label></div>
                    </div>

                    <div class="col-md-12">
                        <h4>费用</h4>
                        <div class="col-md-4 text-center"><h2>{{ $breakfastAmount or '' }}</h2></div>
                        <div class="col-md-4 text-center"><h2>285.00</h2></div>
                        <div class="col-md-4 text-center"><h2>60.00</h2></div>
                        <div class="col-md-4 text-center"><label>早餐</label></div>
                        <div class="col-md-4 text-center"><label>午餐</label></div>
                        <div class="col-md-4 text-center"><label>晚餐</label></div>
                        <label>费用合计：</label>385.00 元
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script src="{{asset('My97DatePicker/WdatePicker.js')}}"></script>
@endsection