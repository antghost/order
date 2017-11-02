@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @component('user.navpill',['staff_menus' => 'class=active'])

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
                            <form class="form-horizontal" action="" method="post">
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
                                    <div class="col-md-offset-2 col-md-2">
                                        <input type="submit" class="form-control btn btn-info" value="添加">
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
                            <th>名称</th>
                            <th>类型</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach( $menus as $menu)
                            <tr>
                                <td>{{ $menu->name or '' }}</td>
                                <td>{{ $menu->type or '' }}</td>
                                <td>
                                    <input type="button" class="btn btn-info" name="editBtn" value="编辑">
                                    <input type="button" class="btn btn-danger" name="deleteBtn" value="删除">
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>


                </div>
            </div>
        </div>
    </div>
@endsection
