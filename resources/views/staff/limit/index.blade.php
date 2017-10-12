@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @component('user.navpill',['staff_limit' => 'class=active'])

            @endcomponent

            <div class="col-md-9 col-md-offset-0">
                <div class="panel panel-info">
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
                    <div class="panel panel-heading">早餐</div>
                    <div class="panel-body">
                        <div class="col-md-12">
                            <form name="breakfast_form" action="{{ url('staff/limit') }}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="type" value="1">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>开餐时限</th>
                                        <th>停餐时限</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <input type="text" name="book_time" required
                                                   value="{{ $breakfastTime->book_time or '' }}"
                                                   onclick="WdatePicker({dateFmt:'H:mm:ss', minTime:'08:30:00', maxTime:'17:30:00'})">
                                        </td>
                                        <td>
                                            <input type="text" name="cancel_time" required
                                                   value="{{ $breakfastTime->cancel_time or '' }}"
                                                   onclick="WdatePicker({dateFmt:'H:mm:ss', minTime:'08:30:00', maxTime:'17:30:00'})">
                                        </td>
                                        <td>
                                            <button type="submit" class="btn btn-info">保存</button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>

                    <div class="panel-heading">午餐</div>
                    <div class="panel-body">
                        <div class="col-md-12">
                            <form name="lunch_form" action="{{ url('staff/limit') }}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="type" value="2">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>开餐时限</th>
                                        <th>停餐时限</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <input type="text" name="book_time" required
                                                   value="{{ $lunchTime->book_time or '' }}"
                                                   onclick="WdatePicker({dateFmt:'H:mm:ss', minTime:'08:30:00', maxTime:'17:30:00'})">
                                        </td>
                                        <td>
                                            <input type="text" name="cancel_time" required
                                                   value="{{ $lunchTime->cancel_time or '' }}"
                                                   onclick="WdatePicker({dateFmt:'H:mm:ss', minTime:'08:30:00', maxTime:'17:30:00'})">
                                        </td>
                                        <td>
                                            <button type="submit" class="btn btn-info">保存</button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>

                    <div class="panel-heading">晚餐</div>
                    <div class="panel-body">
                        <div class="col-md-12">
                            <form name="dinner_form" action="{{ url('staff/limit') }}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="type" value="3">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>开餐时限</th>
                                        <th>停餐时限</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <input type="text" name="book_time" required
                                                   value="{{ $dinnerTime->book_time or '' }}"
                                                   onclick="WdatePicker({dateFmt:'H:mm:ss', minTime:'08:30:00', maxTime:'17:30:00'})">
                                        </td>
                                        <td>
                                            <input type="text" name="cancel_time" required
                                                   value="{{ $dinnerTime->cancel_time or '' }}"
                                                   onclick="WdatePicker({dateFmt:'H:mm:ss', minTime:'08:30:00', maxTime:'17:30:00'})">
                                        </td>
                                        <td>
                                            <button type="submit" class="btn btn-info">保存</button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </form>
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