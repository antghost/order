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
                                <li role="presentation" ><a href="{{ url('user/breakfast/create') }}">早餐</a></li>
                                <li role="presentation" ><a href="{{ url('user/lunch/create') }}">中餐</a></li>
                                <li role="presentation" class="active"><a href="#">晚餐</a></li>
                            </ul>
                        </div>

                        <div class="col-md-12">
                            <div class="alert alert-info" style="margin-top: 10px">
                                <p>晚餐：当天开餐须在{{ $orderTime->book_time or '' }}前，当天停餐须在{{ $orderTime->cancel_time or '' }}前。（长期开餐或停餐请联系餐厅负责部门设置）</p>
                            </div>
                            <form action="{{ url('user/dinner') }}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="type" value="book">
                                <input type="hidden" name="status" value="{{ $status or ''}}">
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
                                    @if (Auth::user()->userOrderStatuses->dinner)
                                        <tr>
                                            <td>{{ $bookDinner->begin_date or '' }}</td>
                                            <td>{{ $bookDinner->end_date or '长期' }}</td>
                                            <td>
                                                <p>长期开餐或停餐请联系餐厅负责部门设置</p>
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>
                                                <input type="hidden" name="id" value="{{ $bookDinner->id or '' }}">
                                                {{--开餐开始日期--}}
                                                @if ($readonly)
                                                    <input type="text" id="book_begin_date" name="begin_date" required
                                                           readonly value="{{ $bookDinner->begin_date or '' }}" >
                                                @else
                                                    <input type="text" id="book_begin_date" name="begin_date" required
                                                           value="{{ $bookDinner->begin_date or '' }}"
                                                           onclick="WdatePicker({
                                                       dateFmt:'yyyy-MM-dd',
                                                       minDate:'{{ $bookMinDate }}',
                                                       maxDate:'#F{$dp.$D(\'book_end_date\')}'
                                                       })">
                                                @endif
                                            </td>
                                            <td>
                                                {{--开餐结束日期--}}
                                                <input type="text" id="book_end_date" name="end_date" required
                                                       value="{{ $bookDinner->end_date or '' }}"
                                                       onclick="WdatePicker({
                                                       dateFmt:'yyyy-MM-dd',
                                                       minDate:'#F{\'{{ $bookMinDate }}\' || $dp.$D(\'book_begin_date\')}'
                                                       })">
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-info">保存</button>
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </form>
                        </div>

                        <div class="col-md-12">
                            <form action="{{ url('user/dinner') }}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="type" value="cancel">
                                <input type="hidden" name="status" value="{{ $status or '' }}">
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
                                    @if (!Auth::user()->userOrderStatuses->dinner)
                                        <tr>
                                            <td>{{ $cancelDinner->begin_date or '' }}</td>
                                            <td>{{ $cancelDinner->end_date or '长期' }}</td>
                                            <td>
                                                <p>长期开餐或停餐请联系餐厅负责部门设置</p>
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>
                                                <input type="hidden" name="id" value="{{ $cancelDinner->id or '' }}">
                                                {{--停餐开始日期--}}
                                                @if ($readonly)
                                                    <input type="text" id="cancel_begin_date" name="begin_date" required
                                                           readonly value="{{ $cancelDinner->begin_date or '' }}">
                                                @else
                                                    <input type="text" id="cancel_begin_date" name="begin_date" required
                                                           value="{{ $cancelDinner->begin_date or '' }}"
                                                           onclick="WdatePicker({
                                                       dateFmt:'yyyy-MM-dd',
                                                       minDate:'{{ $cancelMinDate }}',
                                                       maxDate:'#F{$dp.$D(\'cancel_end_date\')}'
                                                       })">
                                                @endif
                                            </td>
                                            <td>
                                                {{--停餐结束日期--}}
                                                <input type="text" id="cancel_end_date" name="end_date" required
                                                       value="{{ $cancelDinner->end_date or '' }}"
                                                       onclick="WdatePicker({
                                                       dateFmt:'yyyy-MM-dd',
                                                       minDate:'#F{\'{{ $cancelMinDate }}\' || $dp.$D(\'cancel_begin_date\')}'
                                                       })">
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-info">保存</button>
                                            </td>
                                        </tr>
                                    @endif
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