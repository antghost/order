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

                        <form class="navbar-form navbar-left" action="" method="get">
                            <div class="form-group">
                                <label class="control-label">年份</label>
                                <select class="form-control" name="year" id="year">
                                    @for($i = \Carbon\Carbon::today()->year; $i >= 2010; $i--)
                                        <option>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="form-group">
                                <button type="button" onclick="getEchart()" class="btn btn-info">查询</button>
                            </div>
                        </form>

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
        function getEchart() {
            myChart.showLoading();
            var year = $('#year').val();
            var url = "{{ url('staff/report/data') }}";
            url = url+"?year="+year;
            $.get(url).done(function (data) {
                option = {
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {            // 坐标轴指示器，坐标轴触发有效
                            type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                        }
                    },
                    legend: {
                        data: ['早餐人数', '早餐餐数', '午餐人数', '午餐餐数', '晚餐人数', '晚餐餐数', '早餐总金额', '午餐总金额', '晚餐总金额']
                    },
                    grid: {
                        left: '3%',
                        right: '5%',
                        bottom: '3%',
                        containLabel: true
                    },
                    toolbox: {
                        feature: {
                            dataView: {show: true, readOnly: true},
                            restore: {show: true},
                            saveAsImage: {show: true}
                        }
                    },
                    xAxis: {
                        type: 'category',
                        axisTick: {
                            alignWithLabel: true
                        },
                        data: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月']
                    },
                    yAxis: [{
                        type: 'value'
                    },
                        {
                            type: 'value',
                            name: '金额',
                            min: 0,
                            max: 60000,
                            interval: 10000,
                            axisLabel: {
                                formatter: '{value}元'
                            }
                        }
                    ],
                    series: [
                        {
                            name: '早餐餐数',
                            type: 'bar',
                            stack: '总餐数',    //数据堆叠，同个类目轴上系列配置相同的stack值可以堆叠放置。
                            label: {
                                normal: {
                                    show: true,
                                    position: 'insideRight'
                                }
                            },
                            data: data.breakfasts
                        },
                        {
                            name: '午餐餐数',
                            type: 'bar',
                            stack: '总餐数',
                            label: {
                                normal: {
                                    show: true,
                                    position: 'insideRight'
                                }
                            },
                            data: data.lunches
                        },
                        {
                            name: '晚餐餐数',
                            type: 'bar',
                            stack: '总餐数',
                            label: {
                                normal: {
                                    show: true,
                                    position: 'insideRight'
                                }
                            },
                            data: data.dinners
                        },
//                {
//                    name: '早餐人数',
//                    type: 'bar',
//                    stack: '总人数',    //数据堆叠，同个类目轴上系列配置相同的stack值可以堆叠放置。
//                    label: {
//                        normal: {
//                            show: true,
//                            position: 'insideRight'
//                        }
//                    },
//                    data: data.breakfast_users
//                },
//                {
//                    name: '午餐人数',
//                    type: 'bar',
//                    stack: '总人数',
//                    label: {
//                        normal: {
//                            show: true,
//                            position: 'insideRight'
//                        }
//                    },
//                    data: data.lunche_users
//                },
//                {
//                    name: '晚餐人数',
//                    type: 'bar',
//                    stack: '总人数',
//                    label: {
//                        normal: {
//                            show: true,
//                            position: 'insideRight'
//                        }
//                    },
//                    data: data.dinner_users
//                },
                        {
                            name: '早餐总金额',
                            type: 'line',
                            yAxisIndex: 1,
                            data: data.breakfast_amount
                        },
                        {
                            name: '午餐总金额',
                            type: 'line',
                            yAxisIndex: 1,
                            data: data.lunch_amount
                        },
                        {
                            name: '晚餐总金额',
                            type: 'line',
                            yAxisIndex: 1,
                            data: data.dinner_amount
                        }
                    ]
                };
                myChart.clear();
                myChart.hideLoading();
                myChart.setOption(option);
            });
        }
    </script>
@endsection