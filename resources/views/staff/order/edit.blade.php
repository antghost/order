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
                                <input id="breakfast_switch" name="breakfast_chk" type="checkbox" >
                            </div>
                            <label class="control-label col-md-2">开始日期：</label>
                            <div class="col-md-3">
                                <input type="datetime" class="form-control" name="breakfast_begin_date" id="breakfast_begin_date" required value="{{ \Carbon\Carbon::tomorrow() }}" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">午餐：</label>
                            <div class="col-md-2">
                                <input id="lunch_switch" name="lunch_chk" type="checkbox" >
                            </div>
                            <label class="control-label col-md-2">开始日期：</label>
                            <div class="col-md-3">
                                <input type="datetime" class="form-control" name="lunch_begin_date" id="lunch_begin_date" required value="{{ \Carbon\Carbon::tomorrow() }}" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">晚餐：</label>
                            <div class="col-md-2">
                                <input id="dinner_switch" name="dinner_chk" type="checkbox" >
                            </div>
                            <label class="control-label col-md-2">开始日期：</label>
                            <div class="col-md-3">
                                <input type="datetime" class="form-control" name="dinner_begin_date" id="dinner_begin_date" required value="{{ \Carbon\Carbon::tomorrow() }}" >
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-2 text-right">
                                <button type="submit" name="submit" class="btn btn-primary">提交</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="panel panel-info">
                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('bootstrap-switch\js\bootstrap-switch.min.js') }}"></script>
    <script>
        $('#breakfast_switch').bootstrapSwitch({
            onText: "开",
            offText: "停",
            onSwitchChange: function (event, state) {

            }
        });
        $('#lunch_switch').bootstrapSwitch({
            onText: "开",
            offText: "停",
            onSwitchChange: function (event, state) {

            }
        });
        $('#dinner_switch').bootstrapSwitch({
            onText: "开",
            offText: "停",
            onSwitchChange: function (event, state) {

            }
        });
    </script>
@endsection
