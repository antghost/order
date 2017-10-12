@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @component('user.navpill',['breakfast'=> 'class=active'])

            @endcomponent

            <div class="col-md-9 col-md-offset-0">
                <div class="panel panel-info">
                    <div class="panel-body">
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

                        <div class="col-md-12">
                            <ul class="nav nav-tabs">
                                <li role="presentation" class="active"><a href="#">早餐</a></li>
                                <li role="presentation" ><a href="{{ url('user/lunch/create') }}">中餐</a></li>
                                <li role="presentation" ><a href="{{ url('user/dinner/create') }}">晚餐</a></li>
                            </ul>
                        </div>

                        <div class="col-md-12">
                            <div class="alert alert-info" style="margin-top: 10px">
                                <p>早餐需提前一天设置。（长期开餐或停餐请联系餐厅负责部门设置）</p>
                            </div>
                            <span>开餐设置</span>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>开始日期</th>
                                    <th>结束日期</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (Auth::user()->userOrderStatuses->breakfast)
                                    <tr>
                                        <td>{{ $bookBreakfast->begin_date or '' }}</td>
                                        <td>{{ $bookBreakfast->end_date or '长期' }}</td>
                                        <td>
                                            <p>长期开餐或停餐请联系餐厅负责部门设置</p>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>
                                            <input type="text" name="book_time" required
                                                   value=""
                                                   onclick="WdatePicker({dateFmt:'yyyy-MM-dd', minDate:'%y-%M-{%d+1}'})">
                                        </td>
                                        <td>
                                            <input type="text" name="cancel_time" required
                                                   value=""
                                                   onclick="WdatePicker({dateFmt:'yyyy-MM-dd', minDate:'%y-%M-{%d+1}'})">
                                        </td>
                                        <td>
                                            <button type="submit" class="btn btn-info">保存</button>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-12">
                            <span>停餐设置</span>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>开始日期</th>
                                    <th>结束日期</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (isset($cancelBreakfast))
                                    <tr>
                                        <td>{{ $cancelBreakfast->begin_date or '' }}</td>
                                        <td>{{ $cancelBreakfast->end_date or '长期' }}</td>
                                        <td>
                                            <p>长期开餐或停餐请联系餐厅负责部门设置</p>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>
                                            <input type="text" name="book_time" required
                                                   value=""
                                                   onclick="WdatePicker({dateFmt:'yyyy-MM-dd', minDate:'%y-%M-{%d+1}'})">
                                        </td>
                                        <td>
                                            <input type="text" name="cancel_time" required
                                                   value=""
                                                   onclick="WdatePicker({dateFmt:'yyyy-MM-dd', minDate:'%y-%M-{%d+1}'})">
                                        </td>
                                        <td>
                                            <button type="submit" class="btn btn-info">保存</button>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
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