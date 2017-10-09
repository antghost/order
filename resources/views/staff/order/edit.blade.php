@extends('layouts.app')

@section('css')
    <link href="{{ asset('bootstrap-switch\css\bootstrap-switch.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container">
        <div class="row">
            @component('user.navpill',['staff_order' => 'class=active'])
            @endcomponent

            <div class="col-md-9 col-md-offset-0">

                <div class="col-md-12">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if(count($errors) > 0)
                            <div class="alert alert-warning">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                </div>

                <div class="col-md-12" style="background-color: white">
                    <form class="form-horizontal" action="{{ url('staff/order/'.$user->id) }}" method="post">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="form-group">
                            <label class="control-label col-md-2">姓名：</label>
                            <div class="col-md-5">
                                <label class="control-label">{{ $user->name }}</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">所属部门：</label>
                            <div class="col-md-5">
                                <label class="control-label">{{ $user->dept->name }}</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">早餐：</label>
                            <div class="col-md-2">
                                <input id="breakfast_switch" name="breakfast_chk" type="checkbox"
                                    @if (isset($user->userorderstatuses) && $user->userorderstatuses->breakfast)
                                        checked
                                    @endif
                                >
                            </div>
                            <label class="control-label col-md-2">开始日期：</label>
                            <div class="col-md-3">
                                <input type="datetime" class="form-control" name="breakfast_begin_date" id="breakfast_begin_date" required value="{{ \Carbon\Carbon::today() }}" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">午餐：</label>
                            <div class="col-md-2">
                                <input id="lunch_switch" name="lunch_chk" type="checkbox"
                                    @if (isset($user->userorderstatuses) && $user->userorderstatuses->lunch)
                                        checked
                                    @endif
                                >
                            </div>
                            <label class="control-label col-md-2">开始日期：</label>
                            <div class="col-md-3">
                                <input type="datetime" class="form-control" name="lunch_begin_date" id="lunch_begin_date" required value="{{ \Carbon\Carbon::today() }}" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">晚餐：</label>
                            <div class="col-md-2">
                                <input id="dinner_switch" name="dinner_chk" type="checkbox"
                                    @if (isset($user->userorderstatuses) && $user->userorderstatuses->dinner)
                                        checked
                                    @endif
                                >
                            </div>
                            <label class="control-label col-md-2">开始日期：</label>
                            <div class="col-md-3">
                                <input type="datetime" class="form-control" name="dinner_begin_date" id="dinner_begin_date" required value="{{ \Carbon\Carbon::today() }}" >
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-2 text-right">
                                <button type="submit" name="submit" class="btn btn-primary">提交</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!--早餐流水 -->
                <div class="col-md-12" style="background-color: white; margin-top: 10px">
                    <span>早餐流水</span>
                    <table class="table">
                        <thead>
                        <th>开始日期</th>
                        <th>结束日期</th>
                        <th>类型</th>
                        </thead>
                        <tbody>
                        @foreach($breakfasts as $breakfast)
                            <tr>
                                <td>{{ $breakfast->begin_date or '' }}</td>
                                <td>{{ $breakfast->end_date or '长期' }}</td>
                                <td>{{ $breakfast->type or '' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <!--午餐流水 -->
                <div class="col-md-12" style="background-color: white; margin-top: 10px">
                    <span>午餐流水</span>
                    <table class="table">
                        <thead>
                        <th>开始日期</th>
                        <th>结束日期</th>
                        <th>类型</th>
                        </thead>
                        <tbody>
                        @foreach($lunches as $lunch)
                            <tr>
                                <td>{{ $lunch->begin_date or '' }}</td>
                                <td>{{ $lunch->end_date or '长期' }}</td>
                                <td>{{ $lunch->type or '' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <!--晚餐流水 -->
                <div class="col-md-12" style="background-color: white; margin-top: 10px">
                    <span>晚餐流水</span>
                    <table class="table">
                        <thead>
                        <th>开始日期</th>
                        <th>结束日期</th>
                        <th>类型</th>
                        </thead>
                        <tbody>
                        @foreach($dinners as $dinner)
                            <tr>
                                <td>{{ $dinner->begin_date or '' }}</td>
                                <td>{{ $dinner->end_date or '长期' }}</td>
                                <td>{{ $dinner->type or '' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('bootstrap-switch\js\bootstrap-switch.min.js') }}"></script>
    <script>
        $('#breakfast_switch, #lunch_switch, #dinner_switch').bootstrapSwitch({
            onText: "开",
            offText: "停",
            onSwitchChange: function (event, state) {

            }
        });
//        $('#lunch_switch').bootstrapSwitch({
//            onText: "开",
//            offText: "停",
//            onSwitchChange: function (event, state) {
//
//            }
//        });
//        $('#dinner_switch').bootstrapSwitch({
//            onText: "开",
//            offText: "停",
//            onSwitchChange: function (event, state) {
//
//            }
//        });
    </script>
@endsection
