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
                    <form class="form-horizontal">
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
                            <div class="col-md-3">
                                <div class="switch" data-on-label="SI" data-off-label="NO">
                                    <input type="checkbox" checked />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">午餐：</label>
                            <div class="col-md-3">
                                <input class="mySwitch" type="checkbox" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">开始日期：</label>
                            <div class="col-md-3">
                                <input type="datetime" class="form-control" name="begin_date" id="begin_date" required value="{{ \Carbon\Carbon::tomorrow() }}" disabled>
                            </div>
                            <div class="col-md-5">
                                <button type="submit" name="submit" class="btn btn-primary" disabled>修改</button>
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
        $('.mySwitch').bootstrapSwitch({
            onText: "开",
            offText: "停",
            onSwitchChange: function (event, state) {
                $('#begin_date').prop('disabled', !state);
            }
        });
    </script>
@endsection
