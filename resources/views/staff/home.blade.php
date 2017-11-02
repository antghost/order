@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @component('user.navpill',['staff_index' => 'class=active'])

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
                            <h4>当天用餐人数</h4>
                            <div class="col-md-4 text-center"><h2>{{ $userOfDayInBreakfasts or 0 }}</h2></div>
                            <div class="col-md-4 text-center"><h2>{{ $userOfDayInLunches or 0 }}</h2></div>
                            <div class="col-md-4 text-center"><h2>{{ $userOfDayInDinners or 0 }}</h2></div>
                            <div class="col-md-4 text-center"><label>早餐</label></div>
                            <div class="col-md-4 text-center"><label>午餐</label></div>
                            <div class="col-md-4 text-center"><label>晚餐</label></div>
                            <hr>
                        </div>

                        <div class="col-md-12">
                            <h4>明天用餐人数</h4>
                            <div class="col-md-4 text-center"><h2>{{ $userOfTomorrowInBreakfasts or 0 }}</h2></div>
                            <div class="col-md-4 text-center"><h2>{{ $userOfTomorrowInLunches or 0 }}</h2></div>
                            <div class="col-md-4 text-center"><h2>{{ $userOfTomorrowInDinners or 0 }}</h2></div>
                            <div class="col-md-4 text-center"><label>早餐</label></div>
                            <div class="col-md-4 text-center"><label>午餐</label></div>
                            <div class="col-md-4 text-center"><label>晚餐</label></div>
                            <hr>
                        </div>

                        <div class="col-md-12">
                            <h4>本周用餐人数</h4>
                            <div class="col-md-4 text-center"><h2>{{ $userOfWeekInBreakfasts or 0 }}</h2></div>
                            <div class="col-md-4 text-center"><h2>{{ $userOfWeekInLunches or 0 }}</h2></div>
                            <div class="col-md-4 text-center"><h2>{{ $userOfWeekInDinners or 0 }}</h2></div>
                            <div class="col-md-4 text-center"><label>早餐</label></div>
                            <div class="col-md-4 text-center"><label>午餐</label></div>
                            <div class="col-md-4 text-center"><label>晚餐</label></div>
                        </div>

                        <div class="col-md-12">
                            <h4>本月用餐人数</h4>
                            <div class="col-md-4 text-center"><h2>{{ $userOfMonthInBreakfasts or 0 }}</h2></div>
                            <div class="col-md-4 text-center"><h2>{{ $userOfMonthInLunches or 0 }}</h2></div>
                            <div class="col-md-4 text-center"><h2>{{ $userOfMonthInDinners or 0 }}</h2></div>
                            <div class="col-md-4 text-center"><label>早餐</label></div>
                            <div class="col-md-4 text-center"><label>午餐</label></div>
                            <div class="col-md-4 text-center"><label>晚餐</label></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
