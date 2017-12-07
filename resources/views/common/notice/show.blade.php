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
                            <form id="form" class="form-horizontal" action="" method="post">
                                {{ csrf_field() }}
                                {{ method_field('PUT') }}
                                <div class="form-group">
                                    <label for="title" class="col-md-2 control-label">标题</label>
                                    <div class="col-md-8">
                                        <input name="title" id="title" type="text" class="form-control" value="{{ $notice->title }}" required readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="valid_date" class="col-md-2 control-label">有效日期</label>
                                    <div class="col-md-3">
                                        <input name="valid_date" id="valid_date" type="text" class="form-control" readonly
                                               value="{{ $notice->valid_date or '长期' }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="content" class="col-md-2 control-label">内容</label>
                                    <div class="col-md-10">
                                        <textarea name="content" id="content" class="form-control" rows="10" required readonly>{{ $notice->content }}</textarea>
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
</body>
</html>