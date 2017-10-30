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
                                <li role="presentation" ><a href="{{ url('user/lunch/create') }}">午餐</a></li>
                                <li role="presentation" ><a href="{{ url('user/dinner/create') }}">晚餐</a></li>
                            </ul>
                        </div>

                        <div class="col-md-12">
                            <div class="alert alert-info" style="margin-top: 10px">
                                <p>早餐：当天开餐须在{{ $orderTime->book_time or '' }}前，当天停餐须在{{ $orderTime->cancel_time or '' }}前。</p>
                            </div>
                            <form action="{{ url('user/breakfast') }}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="type" value="book">
                                <input type="hidden" name="status" value="{{ $status or '' }}">
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
                                    @if(isset($bookOne))
                                        <tr>
                                            <td>
                                                <input type="hidden" name="id" value="{{ $bookOne->id or '' }}">
                                                {{--开始日期--}}
                                                @if ($bookOneReadonly)
                                                    <input type="text" id="book_begin_date" name="begin_date" required
                                                           readonly value="{{ $bookOne->begin_date or '' }}" >
                                                @else
                                                    <input type="text" id="book_begin_date" name="begin_date" required
                                                           value="{{ $bookOne->begin_date or '' }}"
                                                           onclick="WdatePicker({
                                                       dateFmt:'yyyy-MM-dd',
                                                       minDate:'{{ $bookMinDate }}',
                                                       maxDate:'#F{$dp.$D(\'book_end_date\')}'
                                                       })">
                                                @endif
                                            </td>
                                            <td>
                                                {{--结束日期--}}
                                                <input type="text" id="book_end_date" name="end_date"
                                                       value="{{ $bookOne->end_date or '' }}"
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
                                    @if(isset($bookSecond))
                                        <tr>
                                            <td>
                                                <input type="hidden" name="id" value="{{ $bookSecond->id or '' }}">
                                                {{--开始日期--}}
                                                @if ($bookSecondReadonly)
                                                    <input type="text" id="book_begin_date" name="begin_date" required
                                                           readonly value="{{ $bookSecond->begin_date or '' }}" >
                                                @else
                                                    <input type="text" id="book_begin_date" name="begin_date" required
                                                           value="{{ $bookSecond->begin_date or '' }}"
                                                           onclick="WdatePicker({
                                                       dateFmt:'yyyy-MM-dd',
                                                       minDate:'{{ $bookMinDate }}',
                                                       maxDate:'#F{$dp.$D(\'book_end_date\')}'
                                                       })">
                                                @endif
                                            </td>
                                            <td>
                                                {{--结束日期--}}
                                                <input type="text" id="book_end_date" name="end_date" required
                                                       value="{{ $bookSecond->end_date or '' }}"
                                                       onclick="WdatePicker({
                                                       dateFmt:'yyyy-MM-dd',
                                                       minDate:'#F{\'%y-%M-{%d+1}\' || $dp.$D(\'book_begin_date\')}'
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
                            <form action="{{ url('user/breakfast') }}" method="post">
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

                                        <tr>
                                            <td>
                                                <input type="hidden" name="id" value="{{ $cancelBreakfast->id or '' }}">
                                                {{--开始日期--}}
                                                @if ($cancelReadonly)
                                                    <input type="text" id="cancel_begin_date" name="begin_date" required
                                                           readonly value="{{ $cancelBeginDate or '' }}">
                                                @else
                                                    <input type="text" id="cancel_begin_date" name="begin_date" required
                                                           value="{{ $cancelBeginDate or '' }}"
                                                           onclick="WdatePicker({
                                                       dateFmt:'yyyy-MM-dd',
                                                       minDate:'{{$cancelMinDate}}',
                                                       maxDate:'#F{$dp.$D(\'cancel_end_date\')}'
                                                       })">
                                                @endif
                                            </td>
                                            <td>
                                                {{--结束日期--}}
                                                <input type="text" id="cancel_end_date" name="end_date" required
                                                       value="{{ $cancelEndDate or '' }}"
                                                       onclick="WdatePicker({
                                                       dateFmt:'yyyy-MM-dd',
                                                       minDate:'#F{\'{{$cancelMinDate}}\' || $dp.$D(\'cancel_begin_date\')}'
                                                       })">
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