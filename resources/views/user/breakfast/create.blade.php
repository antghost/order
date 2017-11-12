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
                            <form name="bookForm" action="{{ url('user/breakfast') }}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="type" value="book">
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
                                    {{--当没有有效开餐记录时生成输入--}}
                                    @if(!isset($bookFirst) && !isset($bookSecond))
                                        <tr>
                                            <td>
                                                <input type="text" id="book_begin_date" name="book_begin_date" required
                                                       value="{{ $bookFirst->begin_date or '' }}"
                                                       onclick="WdatePicker({
                                                               dateFmt:'yyyy-MM-dd',
                                                               minDate:'{{ $bookMinDate }}',
                                                               maxDate:'#F{$dp.$D(\'book_end_date\')}'
                                                               })">
                                            </td>
                                            <td>
                                                <input type="text" id="book_end_date" name="book_end_date"
                                                       value="{{ $bookFirst->end_date or '' }}"
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
                                    {{--开餐记录结束日期不为空且为有效日期处理--}}
                                    @if(isset($bookFirst))
                                        <tr>
                                            @if(isset($bookSecond))
                                                <td>
                                                    <input type="hidden" name="first_id" value="{{ $bookFirst->id or '' }}">
                                                    {{--开始日期--}}
                                                    <input type="text" id="book_first_begin_date" name="first_begin_date" required
                                                           readonly value="{{ $bookFirst->begin_date or '' }}" >
                                                </td>
                                                <td>
                                                    {{--结束日期--}}
                                                    <input type="text" id="book_first_end_date" name="first_end_date" required
                                                           readonly value="{{ $bookFirst->end_date or '' }}" >
                                                </td>
                                                <td></td>
                                            @else
                                                <td>
                                                    <input type="hidden" name="first_id" value="{{ $bookFirst->id or '' }}">
                                                    {{--开始日期--}}
                                                    @if ($bookFirstReadonly)
                                                        <input type="text" id="book_first_begin_date" name="first_begin_date" required
                                                               readonly value="{{ $bookFirst->begin_date or '' }}" >
                                                    @else
                                                        <input type="text" id="book_first_begin_date" name="first_begin_date" required
                                                               value="{{ $bookFirst->begin_date or '' }}"
                                                               onclick="WdatePicker({
                                                                       dateFmt:'yyyy-MM-dd',
                                                                       minDate:'{{ $bookMinDate }}',
                                                                       maxDate:'#F{$dp.$D(\'book_first_end_date\')}'
                                                                       })">
                                                    @endif
                                                </td>
                                                <td>
                                                    {{--结束日期--}}
                                                    <input type="text" id="book_first_end_date" name="first_end_date"
                                                           value="{{ $bookFirst->end_date or '' }}"
                                                           onclick="WdatePicker({
                                                                   dateFmt:'yyyy-MM-dd',
                                                                   minDate:'#F{\'{{ $bookMinDate }}\' || $dp.$D(\'book_first_begin_date\')}'
                                                                   })">
                                                </td>
                                                <td>
                                                    <button type="submit" class="btn btn-info">保存</button>
                                                </td>
                                            @endif
                                        </tr>
                                    @endif
                                    {{--开餐记录结束日期为空处理--}}
                                    @if(isset($bookSecond))
                                        <tr>
                                            <td>
                                                <input type="hidden" name="second_id" value="{{ $bookSecond->id or '' }}">
                                                {{--开始日期--}}
                                                @if ($bookSecondReadonly)
                                                    <input type="text" id="book_second_begin_date" name="second_begin_date" required
                                                           readonly value="{{ $bookSecond->begin_date or '' }}" >
                                                @else
                                                    <input type="text" id="book_second_begin_date" name="second_begin_date" required
                                                           value="{{ $bookSecond->begin_date or '' }}"
                                                           onclick="WdatePicker({
                                                       dateFmt:'yyyy-MM-dd',
                                                       minDate:'{{ $bookMinDate }}',
                                                       maxDate:'#F{$dp.$D(\'book_second_end_date\')}'
                                                       })">
                                                @endif
                                            </td>
                                            <td>
                                                {{--结束日期--}}
                                                <input type="text" id="book_second_end_date" name="second_end_date"
                                                       value="{{ $bookSecond->end_date or '' }}"
                                                       onclick="WdatePicker({
                                                       dateFmt:'yyyy-MM-dd',
                                                       minDate:'#F{$dp.$D(\'book_second_begin_date\')}'
                                                       {{--minDate:'#F{\'{{ $bookMinDate }}\' || $dp.$D(\'book_second_begin_date\')}'--}}
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
                            <form name="cancelForm" action="{{ url('user/breakfast') }}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="type" value="cancel">
                                @if (isset($bookFirst))
                                    <input type="hidden" name="first_id" value="{{ $bookFirst->id }}">
                                    <input type="hidden" name="first_begin_date" value="{{ $bookFirst->begin_date }}">
                                    <input type="hidden" name="first_end_date" value="{{ $bookFirst->end_date }}">
                                @endif
                                @if (isset($bookSecond))
                                    <input type="hidden" name="second_id" value="{{ $bookSecond->id }}">
                                    <input type="hidden" name="second_begin_date" value="{{ $bookSecond->begin_date }}">
                                    <input type="hidden" name="second_end_date" value="{{ $bookSecond->end_date }}">
                                @endif
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
                                                <input type="text" id="cancel_end_date" name="end_date"
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