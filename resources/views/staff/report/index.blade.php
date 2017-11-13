@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @component('user.navpill',['common_report' => 'class=active'])

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
                            <div id="echarts" style="width: auto; height: 450px"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('js/echarts.min.js')}}"></script>
    <script>
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
                data: ['早餐', '午餐','晚餐','总金额']
            },
            grid: {
                left: '3%',
                right: '5%',
                bottom: '3%',
                containLabel: true
            },
            toolbox: {
                feature: {
                    dataView: {show: true, readOnly: false},
                    restore: {show: true},
                    saveAsImage: {show: true}
                }
            },
            xAxis:  {
                type: 'category',
                axisTick: {
                    alignWithLabel: true
                },
                data: ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月']
            },
            yAxis: {
                type: 'value'
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
                },
//                {
//                    name: '总金额',
//                    type: 'line',
//                    yAxisIndex: 2,
//                    data: [55.22, 33.2, 544.22, 663.33]
//                }
            ]
        };
        myChart.clear();
        myChart.hideLoading();
        myChart.setOption(option);
        });
    </script>
@endsection