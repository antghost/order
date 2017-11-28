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
                <div class="col-md-4" style="background-color: lightblue;height: 100px">
                    <div class="text-center">
                        <h1><span class="glyphicon glyphicon-hand-right"></span></h1>
                    </div>
                </div>
                <div class="col-md-8" style="background-color:white; height: 100px">
                    <h5>早餐人数</h5>
                    <h1>123</h1>
                </div>
            </div>

            <div class="col-md-4">
                <div class="col-md-4" style="background-color: lightblue;height: 100px">
                    <div class="text-center">
                        <h1><span class="glyphicon glyphicon-hand-right"></span></h1>
                    </div>
                </div>
                <div class="col-md-8" style="background-color:white;height: 100px">
                    <h5>午餐人数</h5>
                    <h1>123</h1>
                </div>
            </div>

            <div class="col-md-4">
                <div class="col-md-4" style="background-color: lightblue;height: 100px">
                    <div class="text-center">
                        <h1><span class="glyphicon glyphicon-hand-right"></span></h1>
                    </div>
                </div>
                <div class="col-md-8" style="background-color:white;height: 100px">
                    <h5>午餐人数</h5>
                    <h1>123</h1>
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
                    <h5>菜名</h5>
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
                    <h5>菜名</h5>
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
                    <h5>菜名</h5>
                </div>
            </div>
        </div>

        {{--右侧通知栏--}}
        <div class="col-md-3 col-md-offset-0" style="background-color: white">
            <h3>通知:</h3>
            <p>sdfsfdfs</p>
            <p>sdfsfdfs</p>
            <p>sdfsfdfs</p>
            <p>sdfsfdfs</p>
            <p>sdfsfdfs</p>
            <p>sdfsfdfs</p>
            <p>sdfsfdfs</p>
            <p>sdfsfdfs</p>
        </div>

    </div>
</div>

<!-- javascript -->
<script src="{{ asset('js/app.js') }}"></script>

</body>
</html>
