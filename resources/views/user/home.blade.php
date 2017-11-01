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
                    </div>

                    <div class="col-md-12">
                       <form class="navbar-form navbar-left" action="{{ url('user/home/s') }}" method="post">
                           {{ csrf_field() }}
                           <div class="form-group">
                               <label class="control-label">年份</label>
                               <select class="form-control" name="year">
                                   @for($i = \Carbon\Carbon::today()->year; $i >= 2010; $i--)
                                       <option>{{ $i }}</option>
                                   @endfor
                               </select>
                           </div>
                           <div class="form-group">
                               <label class="control-label">月份</label>
                               <select class="form-control" name="month">
                                   @for($i = 12; $i >=1; $i--)
                                       <option>{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                   @endfor
                               </select>
                           </div>
                           <div class="form-group">
                               <button type="submit" class="btn btn-info">查询</button>
                           </div>
                       </form>
                    </div>

                    <div class="col-md-12">
                        <hr>
                        <p class="text-info"><b>当前月份：{{ $year or '' }}年{{ $month or '' }}月</b></p>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-4 text-center"><h2>{{ $breakfastDays or 0 }}天</h2></div>
                        <div class="col-md-4 text-center"><h2>{{ $lunchDays or 0 }}天</h2></div>
                        <div class="col-md-4 text-center"><h2>{{ $dinnerDays or 0 }}天</h2></div>
                        <div class="col-md-4 text-center"><label>早餐</label></div>
                        <div class="col-md-4 text-center"><label>午餐</label></div>
                        <div class="col-md-4 text-center"><label>晚餐</label></div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-4 text-center"><h2>{{ $breakfastAmount or 0 }}元</h2></div>
                        <div class="col-md-4 text-center"><h2>{{ $lunchAmount or 0 }}元</h2></div>
                        <div class="col-md-4 text-center"><h2>{{ $dinnerAmount or 0 }}元</h2></div>
                        <div class="col-md-4 text-center"><label>早餐</label></div>
                        <div class="col-md-4 text-center"><label>午餐</label></div>
                        <div class="col-md-4 text-center"><label>晚餐</label></div>
                    </div>
                    <div class="col-md-12">
                        <h3>费用合计：{{ $totalAmount or '' }} 元</h3>
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