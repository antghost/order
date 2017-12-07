<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Meal-System') }}</title>

    <!-- Styles -->
    {{--    <link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}
    <link href="{{ asset('bootstrap-3.3.7/css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body style="background-color:whitesmoke">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-md-offset-0">
                <div class="panel">
                    <div class="col-md-12">
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
                    </div>

                    <div class="panel-body">
                        <div class="col-md-12">
                            <form id="form_menu_create" class="form-horizontal" action="{{ url('staff/menu') }}" method="post">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label class="control-label col-md-2">名称</label>
                                    <div class="col-md-5">
                                        <input name="name" type="text" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">类型</label>
                                    <div class="radio col-md-5">
                                        <label>
                                            <input type="radio" name="type" value="1" checked>早餐
                                        </label>
                                        <label>
                                            <input type="radio" name="type" value="2">午餐
                                        </label>
                                        <label>
                                            <input type="radio" name="type" value="3">晚餐
                                        </label>
                                        <label>
                                            <input type="radio" name="type" value="4">下午茶
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">今日菜单</label>
                                    <div class="radio col-md-5">
                                        <label>
                                            <input type="radio" name="active" value="1" checked>是
                                        </label>
                                        <label>
                                            <input type="radio" name="active" value="0">否
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-offset-2 col-md-2">
                                        <input id="submit" type="submit" class="form-control btn btn-info" value="添加">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- javascript -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('layer/layer.js') }}"></script>
<script>
    $("#form_menu_create").submit(function () {
        $("#submit").prop('disabled', true).text('提交中');
    });
</script>
</body>
</html>