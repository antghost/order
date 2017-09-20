@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @component('admin.navpill',[ 'user' => 'class=active'])

            @endcomponent

            <div class="col-md-9">
                <ul class="nav nav-tabs">
                    <li role="presentation" ><a href="{{ url('admin/user') }}">列表</a></li>
                    <li role="presentation" ><a href="{{ url('admin/user/create') }}">新增</a></li>
                    <li role="presentation" class="active"><a href="#">费用标准编辑</a></li>
                </ul>
            </div>
            <div class="col-md-9 col-md-offset-0">
                <div class="panel panel-info">
                    <!--<div class="panel-heading">概况</div>-->

                    <div class="panel-body">
                        @if (count($errors) >0 )
                            <div class="alert alert-warning">
                                {!! implode('<br>',$errors->all()) !!}
                            </div>
                        @endif
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="col-md-12">
                            <form id="form" class="form-horizontal" action="{{ url('admin/user/price/'.$user->id) }}" method="post">
                                {{ csrf_field() }}
                                {{ method_field('PUT') }}
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <input type="hidden" name="price_id" value="{{ $user->price_id }}">

                                <div class="form-group">
                                    <label class="control-label col-md-2">姓名：</label>
                                    <div class="col-md-5">
                                        <label class="control-label">{{ $user->name }}</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-2">所属部门：</label>
                                    <div class="col-md-5">
                                        <label class="control-label">{{ $user->dept->name }}</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-2">餐费标准：</label>
                                    <div class="col-md-5">
                                        <select name="price" class="form-control">
                                            @foreach($prices as $price)
                                                @if (empty($user->price_id) or $price->id <> $user->price_id)
                                                    <option value="{{ $price->id }}">{{ $price->name.'（早'.$price->breakfast.' 午'.$price->lunch.' 晚'.$price->dinner.'）' }}</option>
                                                @else
                                                    <option value="{{ $price->id }}" selected>{{ $price->name.'（早'.$price->breakfast.' 午'.$price->lunch.' 晚'.$price->dinner.'）' }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" name="submit" class="btn btn-primary" disabled>修改</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-body">
                        <div class="col-md-12">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>开始日期</th>
                                    <th>失效日期</th>
                                    <th>餐费标准</th>
                                    <th>状态</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach( $priceUsers as $priceUser)
                                    <tr>
                                        <td>{{ $priceUser->begin_date or '' }}</td>
                                        <td>{{ $priceUser->valid_date or '长期' }}</td>
                                        <td>早餐：{{$priceUser->breakfast or ''}} 午餐：{{$priceUser->lunch or ''}} 晚餐：{{$priceUser->dinner or ''}}</td>
                                        <td>
                                            {{ is_null($priceUser->valid_date) ? '生效' : '失效' }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $priceUsers->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('layer/layer.js') }}"></script>
    <script>
//        $('button[name=submit]').click(function () {
//            layer.confirm('修改后原收费标准将失效,确认修改？', function (index) {
//                $('form').submit();
//                $('button[name=submit]').prop('disabled', true).text('正在提交');
//                layer.close(index);
//            });
//        });


        $('select[name=price]').change(function () {
            if ($(this).val() == $('input[name=price_id]').val()){
                $('button[name=submit]').prop('disabled', true);
            } else {
                $('button[name=submit]').prop('disabled', false);
            }
        });
    </script>
@endsection