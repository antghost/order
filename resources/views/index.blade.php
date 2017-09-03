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
<body>
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
                    <ul class="nav navbar-nav">
                        <li class="active">
                            <a href="#">Link</a>
                        </li>
                        <li>
                            <a href="#">Link</a>
                        </li>

                    </ul>
                    <form class="nav navbar-form navbar-left" role="search">
                        <div class="form-group">
                            <input type="text" class="form-control" />
                        </div>
                        <button type="submit" class="btn btn-default">Submit</button>
                    </form>

                    @if(Route::has('login'))
                        <ul class="nav navbar-nav navbar-right">
                            @if(Auth::check())
                                <li><a href="{{ url('user') }}">Home</a></li>
                            @else
                                <li><a href="{{ url('/login') }}">Login</a></li>
                                <li><a href="{{ url('/register') }}">Register</a></li>
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
        <div class="col-md-12">
            <label class="col-md-2 col-md-offset-1">breakfast numbers:</label>
            <div class="col-md-8">
                <h5>123</h5>
            </div>
            <label class="col-md-2 col-md-offset-1">lunch numbers:</label>
            <div class="col-md-8">
                <h5>123</h5>
            </div>
            <label class="col-md-2 col-md-offset-1">dinner numbers:</label>
            <div class="col-md-8">
                <h5>123</h5>
            </div>
        </div>

        <div class="col-md-12">
            <label>菜单</label>
        </div>
    </div>
</div>
<!-- javascript -->
<script src="{{ asset('js/app.js') }}"></script>

</body>
</html>
