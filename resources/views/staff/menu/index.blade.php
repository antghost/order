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
                                    <input id="input_name" name="name" type="text" class="form-control" value="{{ old('name') }}">
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
                    <a href="{{ url('staff/menu/create') }}" class="btn btn-primary menu_add">添加菜式</a>
                    <form id="form_menu" action="" method="post">
                        {{ csrf_field() }}
                    </form>
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
                                <td><div id="div_show">
                                        <label for="{{ $menu->id }}"> {{ $menu->name or '' }} </label>
                                        @if($menu->active)
                                            <span class="badge">今日菜单</span>
                                        @endif
                                    </div>
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
        var url = "{{ url('staff/menu/today/') }}";
        //ajax全局设置
        $.ajaxSetup({
            headers: {
                //设置csrf防止ajax call返回internal 500错误
                'X-CSRF-TOKEN': $('#form_menu input[name="_token"]').val()
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
        //将事件绑定到parent的.on()函数，解决无法点击触发事件的问题
        $('#div_table').on('click', 'button[name="menu_today"]', function () {
            var id = $(this).val();
            $.post(url+ "/"+ id, function (data) {
                console.log(data.name);

            });
        });

        //批量今日菜单设置

        //删除
        $('#div_table').on('click', 'button[name="menu_delete"]', function () {
            layer.confirm('确认删除？',function (index) {
                var id = $(this).val();
                $.ajax({
                    method: 'DELETE',
                    url: url+ "/"+ id + 'delete',
                    success: function (data) {
                        layer.msg(data);
                    }
                });
                layer.close(index);
                layer.load(1);
            });
        });

        //新增菜单
        $('.menu_add').on('click', function () {
            var _href = $(this).attr('href');
            layer.open({
                type: 2,
                //skin: 'layui-layer-lan',
                title: '新增菜式',
                //skin: 'layui-layer-rim', //加上边框
                area: ['400px', '500px'], //宽高
                content: _href,
                success: function(layero, index){
                },
                end: function(){
                    history.go(0);
                },

            });

            return false;
        });
    </script>
@endsection
