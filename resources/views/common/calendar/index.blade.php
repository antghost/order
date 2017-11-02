@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @component('user.navpill',['common_holiday' => 'class=active'])

            @endcomponent
            <div class="col-md-9 col-md-offset-0">
                <div class="panel">
                    <div class="col-md-12">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <div class="panel-body">
                        <div class="col-md-12">
                            <form class="form-horizontal" action="" method="post">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label class="control-label col-md-2">名称</label>
                                    <div class="col-md-5">
                                        <input name="name" type="text" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">开始日期</label>
                                    <div class="col-md-3">
                                        <input name="begin_date" id="begin_date" type="text" class="form-control" required
                                               onclick="WdatePicker({
                                                       dateFmt:'yyyy-MM-dd',
                                                       maxDate:'#F{$dp.$D(\'end_date\')}'
                                                       })">
                                    </div>
                                    <label class="control-label col-md-2">结束日期</label>
                                    <div class="col-md-3">
                                        <input name="end_date" id="end_date" type="text" class="form-control" required
                                               onclick="WdatePicker({
                                                       dateFmt:'yyyy-MM-dd',
                                                       minDate:'#F{$dp.$D(\'begin_date\')}'
                                                       })">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">类型</label>
                                    {{--<div class="col-md-2">--}}
                                        {{--<select name="type" class="form-control">--}}
                                            {{--<option value="0" selected>假期</option>--}}
                                            {{--<option value="1">上班</option>--}}
                                        {{--</select>--}}
                                    {{--</div>--}}
                                    <div class="col-md-2 radio">
                                        <label>
                                            <input type="radio" name="type" value="0" checked>假期
                                        </label>
                                        <label>
                                            <input type="radio" name="type" value="1">上班
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="submit" class="form-control btn btn-info" value="提交">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-12" style="background-color: white; margin-top: 10px">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>名称</th>
                            <th>开始日期</th>
                            <th>结束日期</th>
                            <th>类型</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($calendars as $calendar)
                            <tr>
                                <td>{{ $calendar->name or '' }}</td>
                                <td>{{ $calendar->begin_date or '' }}</td>
                                <td>{{ $calendar->end_date or '' }}</td>
                                <td>{{ $calendar->type or '' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{ $calendars->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('My97DatePicker/WdatePicker.js')}}"></script>
@endsection