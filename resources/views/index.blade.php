<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Canteen</title>
        <!-- Styles -->
        <link href="{{ asset('bootstrap-3.3.7/css/bootstrap.min.css') }}" rel="stylesheet">
{{--    <link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}
</head>
<body style="background-color:whitesmoke">
<div class="container">
    <div class="row clearfix">
        <div class="col-md-12 column">
            <nav class="navbar navbar-default" role="navigation">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    {{--<ul class="nav navbar-nav">--}}
                        {{--<li class="active">--}}
                            {{--<a href="#">Link</a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<a href="#">Link</a>--}}
                        {{--</li>--}}

                    {{--</ul>--}}
                    {{--<form class="nav navbar-form navbar-left" role="search">--}}
                        {{--<div class="form-group">--}}
                            {{--<input type="text" class="form-control" />--}}
                        {{--</div>--}}
                        {{--<button type="submit" class="btn btn-default">Submit</button>--}}
                    {{--</form>--}}

                    @if(Route::has('login'))
                        <ul class="nav navbar-nav navbar-right">
                            @if(Auth::check())
                                <li><a href="{{ url('user') }}">个人中心</a></li>
                            @else
                                <li><a href="{{ url('/login') }}">登录</a></li>
                                {{--<li><a href="{{ url('/register') }}">Register</a></li>--}}
                            @endif
                            {{--@if(Auth::guard('admin')->check())--}}
                                {{--<li><a href="{{ route('admin.home') }}">admin.home</a></li>--}}
                            {{--@else--}}
                                {{--<li><a href="{{ route('admin.login') }}">admin.login</a></li>--}}
                            {{--@endif--}}
                        </ul>
                    @endif

                </div>
            </nav>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-9 col-md-offset-0">
            {{--左侧栏第一行人数显示--}}
            <div class="col-md-4">
                <div class="col-md-4" style="background-color: skyblue;height: 100px">
                    <div class="text-center">
                        <h1><span class="glyphicon glyphicon-hand-right"></span></h1>
                    </div>
                </div>
                <div class="col-md-8" style="background-color:white; height: 100px">
                    <h5>早餐人数</h5>
                    <h1>{{ $userInBreakfasts }}</h1>
                </div>
            </div>

            <div class="col-md-4">
                <div class="col-md-4" style="background-color:skyblue;height: 100px">
                    <div class="text-center">
                        <h1><span class="glyphicon glyphicon-hand-right"></span></h1>
                    </div>
                </div>
                <div class="col-md-8" style="background-color:white;height: 100px">
                    <h5>午餐人数</h5>
                    <h1>{{ $userInLunches }}</h1>
                </div>
            </div>

            <div class="col-md-4">
                <div class="col-md-4" style="background-color: skyblue;height: 100px">
                    <div class="text-center">
                        <h1><span class="glyphicon glyphicon-hand-right"></span></h1>
                    </div>
                </div>
                <div class="col-md-8" style="background-color:white;height: 100px">
                    <h5>晚餐人数</h5>
                    <h1>{{ $userInDinners }}</h1>
                </div>
            </div>

            {{--左侧第二栏菜名显示--}}
            <div class="col-md-12"  style="padding-top: 30px">
                <div class="col-md-2" style="background-color: lightblue;height: 110px">
                    <div class="text-center">
                        <h1><span class="glyphicon glyphicon-hand-right"></span></h1>
                        <p>早餐</p>
                    </div>
                </div>
                <div class="col-md-10" style="background-color:white;height: 110px">
                    <h3>
                        @foreach($breakfastMenus as $breakfastMenu)
                            {{ $breakfastMenu->name }} ;
                        @endforeach
                    </h3>
                </div>
            </div>

            <div class="col-md-12"  style="padding-top: 30px">
                <div class="col-md-2" style="background-color: lightblue;height: 110px">
                    <div class="text-center">
                        <h1><span class="glyphicon glyphicon-hand-right"></span></h1>
                        <p>午餐</p>
                    </div>
                </div>
                <div class="col-md-10" style="background-color:white;height: 110px">
                    <h3>
                        @foreach($lunchMenus as $lunchMenu)
                            {{ $lunchMenu->name }} ;
                        @endforeach
                    </h3>
                </div>
            </div>

            <div class="col-md-12"  style="padding-top: 30px">
                <div class="col-md-2" style="background-color: lightblue;height: 110px">
                    <div class="text-center">
                        <h1><span class="glyphicon glyphicon-hand-right"></span></h1>
                        <p>晚餐</p>
                    </div>
                </div>
                <div class="col-md-10" style="background-color:white;height: 110px">
                    <h3>
                        @foreach($dinnerMenus as $dinnerMenu)
                            {{ $dinnerMenu->name }} ;
                        @endforeach
                    </h3>
                </div>
            </div>
        </div>

        {{--右侧通知栏--}}
        <div id="notice" class="col-md-3 col-md-offset-0" style="background-color: white">
            <h1 class="page-header">通知</h1>
            @foreach($notices as $notice)
                <div>
                    <li><h4 class="text-primary"><a href="">{{ $notice->title }}</a></h4></li>
                    <div><p style="text-indent:2em">{{ str_limit($notice->content,200) }}<a href="">查看</a></p></div>
                    <line></line>
                </div>
            @endforeach
        </div>

    </div>
</div>

<!-- javascript -->
<script src="{{ asset('js/app.js') }}"></script>
<script>
    {{--$.get("{{ url('/getMenu') }}").done(function (data) {--}}
    {{--});--}}
</script>
</body>
</html>
