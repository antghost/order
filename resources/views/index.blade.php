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
        <style>
            .word{ background-color:white;height: 110px;word-break: break-all;word-wrap: break-word;}
        </style>
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
                    <h1 id="breakfast_count" class="text-info">{{ $userInBreakfasts }}</h1>
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
                    <h1 id="lunch_count" class="text-info">{{ $userInLunches }}</h1>
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
                    <h1 id="dinner_count" class="text-info">{{ $userInDinners }}</h1>
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
                <div class="col-md-10 word">
                    <marquee id="scroll_text" onmouseover="this.stop()" onmouseout="this.start();" height="110px" scrollAmount="2" behavior="scroll" direction="up">
                    <h2 id="breakfast_menu">
                        @foreach($breakfastMenus as $breakfastMenu)
                            {{ $breakfastMenu->name }} ;
                        @endforeach
                    </h2>
                    </marquee>
                </div>
            </div>

            <div class="col-md-12"  style="padding-top: 30px">
                <div class="col-md-2" style="background-color: lightblue;height: 110px">
                    <div class="text-center">
                        <h1><span class="glyphicon glyphicon-hand-right"></span></h1>
                        <p>午餐</p>
                    </div>
                </div>
                <div class="col-md-10 word">
                    <marquee id="scroll_text" onmouseover="this.stop()" onmouseout="this.start();" height="110px" scrollAmount="2" behavior="scroll" direction="up">
                    <h2 id="lunch_menu">
                        @foreach($lunchMenus as $lunchMenu)
                            {{ $lunchMenu->name }} ;
                        @endforeach
                    </h2>
                    </marquee>
                </div>
            </div>

            <div class="col-md-12"  style="padding-top: 30px">
                <div class="col-md-2" style="background-color: lightblue;height: 110px">
                    <div class="text-center">
                        <h1><span class="glyphicon glyphicon-hand-right"></span></h1>
                        <p>晚餐</p>
                    </div>
                </div>
                <div class="col-md-10 word">
                    <marquee id="scroll_text" onmouseover="this.stop()" onmouseout="this.start();" height="110px" scrollAmount="2" behavior="scroll" direction="up">
                    <h2 id="dinner_menu">
                        @foreach($dinnerMenus as $dinnerMenu)
                            {{ $dinnerMenu->name }} ;
                        @endforeach
                    </h2>
                    </marquee>
                </div>
            </div>
        </div>

        {{--右侧通知栏--}}
        <div id="div_notice" class="col-md-3 col-md-offset-0" style="background-color: white">
            <h1 class="page-header">通知</h1>
            <div id="div_notice">
                @foreach($notices as $notice)
                    <li><h4 class="text-primary"><a href="{{ url('/notice/'.$notice->id) }}" class="notice">{{ $notice->title }}</a></h4></li>
                    <p style="text-indent:2em">{{ str_limit($notice->content,200) }}<a href="{{ url('/notice/'.$notice->id) }}" class="notice">查看</a></p>
                @endforeach
            </div>
        </div>

    </div>
</div>

<!-- javascript -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('layer/layer.js') }}"></script>
<script>
    //显示通知详情
    $('#div_notice').on('click', 'a.notice', function () {
        var _href = $(this).attr('href');
        layer.open({
            type: 2,
            title: '通知',
            area: ['600px', '500px'], //宽\高
            content: _href,
            success: function(layero, index){
            },
            end: function(){
            },
        });
        return false;
    });
    //定时刷新
    var t = 5*60*1000;
    setInterval(function () {
        //就餐人数
        $.get("{{ url('/getUserNum') }}").done(function (data) {
            $('#breakfast_count').html(data.breakfast);
            $('#lunch_count').html(data.lunch);
            $('#dinner_count').html(data.dinner);
        });
        //菜单
        $.get("{{ url('/getMenu') }}").done(function (data) {
            var breakfast_html = '';
            var lunch_html = '';
            var dinner_html = '';
            for(i =0; i<data['breakfast'].length; i++){
                breakfast_html += data['breakfast'][i].name +';'
            }
            for(i =0; i<data['lunch'].length; i++){
                lunch_html += data['lunch'][i].name +';'
            }
            for(i =0; i<data['dinner'].length; i++){
                dinner_html += data['dinner'][i].name +';'
            }
            //早餐
            $('#breakfast_menu').html(breakfast_html);
            //午餐
            $('#lunch_menu').html(lunch_html);
            //晚餐
            $('#dinner_menu').html(dinner_html);
//            console.log(breakfast_html);
//            console.log(lunch_html);
//            console.log(dinner_html);
        });
    }, t);

</script>
</body>
</html>
