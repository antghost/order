@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @component('user.navpill',['common_notice' => 'class=active'])

            @endcomponent
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
                            <form class="form-horizontal" action="{{ url('common/notice') }}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                <div class="form-group">
                                    <label for="title" class="col-md-2 control-label">标题</label>
                                    <div class="col-md-8">
                                        <input name="title" id="title" type="text" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="valid_date" class="col-md-2 control-label">有效日期</label>
                                    <div class="col-md-3">
                                        <input name="valid_date" id="valid_date" type="text" class="form-control"
                                               onclick="WdatePicker({
                                                       dateFmt:'yyyy-MM-dd HH:mm:ss',
                                                       })">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="content" class="col-md-2 control-label">内容</label>
                                    <div class="col-md-10">
                                        <textarea name="content" id="content" class="form-control" rows="10" required></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-offset-2 col-md-2">
                                        <button type="submit" name="submit" class="btn btn-primary form-control">提交</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-12" style="background-color: white; margin-top: 10px">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>标题</th>
                            <th>内容</th>
                            <th>创建日期</th>
                            <th>有效日期</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($notices))
                            @foreach($notices as $notice)
                                <tr>
                                    <td><a href="">{{ $notice->title or '' }}</a></td>
                                    <td>{{ str_limit($notice->content,100) }}</td>
                                    <td>{{ $notice->created_at or '' }}</td>
                                    <td>{{ $notice->valid_date or '长期' }}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>

                    {{ $notices->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('My97DatePicker/WdatePicker.js')}}"></script>
@endsection