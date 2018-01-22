@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @component('user.navpill',['staff_index' => 'class=active'])

            @endcomponent

            <div class="col-md-9 col-md-offset-0">
                <div class="panel panel-default">

                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="col-md-12">
                            <div id="echarts" style="width: auto; height: 500px"></div>
                        </div>

                        {{--<div class="col-md-12">--}}
                            {{--<h4>当天用餐人数</h4>--}}
                            {{--<div class="col-md-4 text-center"><h2>{{ $userOfDayInBreakfasts or 0 }}</h2></div>--}}
                            {{--<div class="col-md-4 text-center"><h2>{{ $userOfDayInLunches or 0 }}</h2></div>--}}
                            {{--<div class="col-md-4 text-center"><h2>{{ $userOfDayInDinners or 0 }}</h2></div>--}}
                            {{--<div class="col-md-4 text-center"><label>早餐</label></div>--}}
                            {{--<div class="col-md-4 text-center"><label>午餐</label></div>--}}
                            {{--<div class="col-md-4 text-center"><label>晚餐</label></div>--}}
                            {{--<hr>--}}
                        {{--</div>--}}

                        {{--<div class="col-md-12">--}}
                            {{--<h4>明天用餐人数</h4>--}}
                            {{--<div class="col-md-4 text-center"><h2>{{ $userOfTomorrowInBreakfasts or 0 }}</h2></div>--}}
                            {{--<div class="col-md-4 text-center"><h2>{{ $userOfTomorrowInLunches or 0 }}</h2></div>--}}
                            {{--<div class="col-md-4 text-center"><h2>{{ $userOfTomorrowInDinners or 0 }}</h2></div>--}}
                            {{--<div class="col-md-4 text-center"><label>早餐</label></div>--}}
                            {{--<div class="col-md-4 text-center"><label>午餐</label></div>--}}
                            {{--<div class="col-md-4 text-center"><label>晚餐</label></div>--}}
                            {{--<hr>--}}
                        {{--</div>--}}

                        {{--<div class="col-md-12">--}}
                            {{--<h4>本周用餐人数</h4>--}}
                            {{--<div class="col-md-4 text-center"><h2>{{ $userOfWeekInBreakfasts or 0 }}</h2></div>--}}
                            {{--<div class="col-md-4 text-center"><h2>{{ $userOfWeekInLunches or 0 }}</h2></div>--}}
                            {{--<div class="col-md-4 text-center"><h2>{{ $userOfWeekInDinners or 0 }}</h2></div>--}}
                            {{--<div class="col-md-4 text-center"><label>早餐</label></div>--}}
                            {{--<div class="col-md-4 text-center"><label>午餐</label></div>--}}
                            {{--<div class="col-md-4 text-center"><label>晚餐</label></div>--}}
                        {{--</div>--}}

                        {{--<div class="col-md-12">--}}
                            {{--<h4>本月用餐人数</h4>--}}
                            {{--<div class="col-md-4 text-center"><h2>{{ $userOfMonthInBreakfasts or 0 }}</h2></div>--}}
                            {{--<div class="col-md-4 text-center"><h2>{{ $userOfMonthInLunches or 0 }}</h2></div>--}}
                            {{--<div class="col-md-4 text-center"><h2>{{ $userOfMonthInDinners or 0 }}</h2></div>--}}
                            {{--<div class="col-md-4 text-center"><label>早餐</label></div>--}}
                            {{--<div class="col-md-4 text-center"><label>午餐</label></div>--}}
                            {{--<div class="col-md-4 text-center"><label>晚餐</label></div>--}}
                        {{--</div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('js/echarts.min.js')}}"></script>
    <script src="{{ asset('layer/layer.js') }}"></script>
    <script>
        var d = new Date();
        var begin_time = d.getTime();
        //初始化echarts实例
        myChart = echarts.init(document.getElementById('echarts'));
        myChart.showLoading();
        $.get("{{ url('staff/data') }}").done(function (data) {
        option = {
            tooltip : {
                trigger: 'axis',
                axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                    type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                }
            },
            legend: {
                data: ['早餐', '午餐','晚餐']
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis:  {
                type: 'value'
            },
            yAxis: {
                type: 'category',
                data: ['本月用餐人数','本周用餐人数','明天用餐人数','当天用餐人数']
            },
            series: [
                {
                    name: '早餐',
                    type: 'bar',
                    stack: '总人数',    //数据堆叠，同个类目轴上系列配置相同的stack值可以堆叠放置。
                    label: {
                        normal: {
                            show: true,
                            position: 'insideRight'
                        }
                    },
                    data: data.breakfasts
                },
                {
                    name: '午餐',
                    type: 'bar',
                    stack: '总人数',
                    label: {
                        normal: {
                            show: true,
                            position: 'insideRight'
                        }
                    },
                    data: data.lunches
                },
                {
                    name: '晚餐',
                    type: 'bar',
                    stack: '总人数',
                    label: {
                        normal: {
                            show: true,
                            position: 'insideRight'
                        }
                    },
                    data: data.dinners
                }
            ]
        };
        myChart.clear();
        myChart.hideLoading();
        myChart.setOption(option);

        var d = new Date();
        var end_time = d.getTime();
        var use_time = end_time - begin_time;
        layer.msg('用时：'+(use_time)+'毫秒');
        });
    </script>
@endsection