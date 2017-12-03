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
                            <form id="form_search" class="navbar-form navbar-left" action="{{ route('menu.search') }}" method="get" role="search">
                                <div class="form-group">
                                    <label class="control-label">名称</label>
                                    <input name="name" type="text" class="form-control" value="{{ old('name') }}">
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" id="breakfast" name="breakfast">
                                    <label class="control-label" for="breakfast">早餐</label>
                                    <input type="checkbox" id="lunch" name="lunch">
                                    <label class="control-label" for="lunch">午餐</label>
                                    <input type="checkbox" id="dinner" name="dinner">
                                    <label class="control-label" for="dinner">晚餐</label>
                                    <input type="checkbox" id="hightea" name="hightea">
                                    <label class="control-label" for="hightea">下午茶</label>
                                    <input type="checkbox" id="today_menu" name="today_menu">
                                    <label class="control-label" for="today_menu">今日菜单</label>
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="form-control btn btn-info" value="查询">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div id="div_table" class="col-md-12" style="background-color: white; margin-top: 1px">
                    <button type="button" name="btn_menu_add" class="btn btn-primary">添加菜式</button>
                    <form id="form_menu" action="" method="post"></form>
                    <table class="table">
                        <thead>
                        <tr>
                            <th><input type="checkbox" id="check_all" class="checkbox" name="" value="选择"></th>
                            <th>名称</th>
                            <th>类型</th>
                            <th>今日菜单</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach( $menus as $menu)
                            <tr id="table{{ $menu->id }}">
                                <td><input type="checkbox" id="{{ $menu->id }}" name="ids[]" class="checkbox" value="{{ $menu->id }}"></td>
                                <td><label for="{{ $menu->id }}"> {{ $menu->name or '' }}
                                        @if($menu->active)
                                            </label><span class="badge">今日菜单</span>
                                        @endif
                                </td>
                                <td>{{ $menu->type or '' }}</td>
                                <td>
                                    @if($menu->active)
                                        <button type="button" name="menu_today" class="btn btn-warning" value="{{ $menu->id }}">取消</button>
                                    @else
                                        <button type="button" name="menu_today" class="btn btn-info" value="{{ $menu->id }}">添加</button>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" name="menu_edit" class="btn btn-default" value="{{ $menu->id }}">编辑</button>
                                    <button type="button" name="menu_delete" class="btn btn-danger" value="{{ $menu->id }}">删除</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <input type="button" class="btn btn-info" id="btn_mass_add_menu" value="添加今日菜单" disabled="true">
                    <input type="button" class="btn btn-warning" id="btn_mass_cancel_menu" value="取消今日菜单" disabled="true">
                    <div><label>总量：</label>{{ $menus->total() }} &nbsp;&nbsp;<label>当前页数量：</label>{{ $menus->count() }}</div>
                    {{ $menus->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('layer/layer.js') }}"></script>
    <script>
        //ajax全局设置
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //全选
        $("#check_all").click(function () {
            $(this).parents('table').find("input:checkbox").prop('checked',$(this).prop('checked'));
        });

        //今日菜单按钮生效控制
        $("#div_table input:checkbox").click(function () {
            var count = $("input:checkbox:checked").length;
            $("#btn_mass_add_menu").prop('disabled', count == 0);
            $("#btn_mass_cancel_menu").prop('disabled', count == 0);
        });

        //逐条今日菜单设置

        //批量今日菜单设置
    </script>
@endsection
