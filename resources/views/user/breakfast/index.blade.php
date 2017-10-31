@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @component('user.navpill',['info' => 'class=active'])

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
                            <ul class="nav nav-tabs">
                                <li role="presentation" class="active"><a href="#">早餐</a></li>
                                <li role="presentation" ><a href="{{ url('user/lunch') }}">午餐</a></li>
                                <li role="presentation" ><a href="{{ url('user/dinner') }}">晚餐</a></li>
                            </ul>
                        </div>

                        <div class="col-md-12">

                            <form action="{{ url('user/breakfast/s') }}" class="navbar-form navbar-left" role="search">
                                <div class="form-group">
                                    <label for="startdate">开始日期</label>
                                    <input type="text" id="start_date" name="begin_date" class="form-control"
                                           value="{{ \Carbon\Carbon::today()->startOfMonth()->toDateString() }}"
                                           onclick="WdatePicker({
                                           dateFmt:'yyyy-MM-dd',
                                           maxDate:'#F{$dp.$D(\'end_date\')}' })">
                                </div>
                                <div class="form-group">
                                    <label for="enddate">结束日期</label>
                                    <input type="text" id="end_date" name="end_date" class="form-control"
                                           value="{{ \Carbon\Carbon::today()->endOfMonth()->toDateString() }}"
                                           onclick="WdatePicker({
                                           dateFmt:'yyyy-MM-dd',
                                           minDate:'#F{$dp.$D(\'start_date\')}'})">
                                </div>
                                {{--<div class="form-group">--}}
                                    {{--<input type="checkbox" checked id="book" name="book">--}}
                                    {{--<label for="book">开餐 </label>--}}
                                {{--</div>--}}
                                {{--<div class="form-group">--}}
                                    {{--<input type="checkbox" checked id="cancel" name="cancel">--}}
                                    {{--<label for="cancel">停餐 </label>--}}
                                {{--</div>--}}
                                <div class="form-group">
                                    <button type="submit" class="btn btn-info">查询</button>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-12">
                            <p></p>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>开始日期</th>
                                    <th>结束日期</th>
                                    <th>用餐天数</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($breakfasts))
                                    @foreach($breakfasts as $breakfast)
                                        <tr>
                                            <td>{{ $breakfast->begin_date or '' }}</td>
                                            <td>{{ $breakfast->end_date or '长期' }}</td>
                                            <td>{{ $breakfast->days or '' }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                            @if (isset($breakfasts))
                                {{ $breakfasts->links() }}
                            @endif
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