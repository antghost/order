@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @component('admin.navpill',[ 'permit' => 'class=active'])

            @endcomponent

            <div class="col-md-9">
                <ul class="nav nav-tabs">
                    <li role="presentation" class="active"><a href="{{ url('admin/permit') }}">列表</a></li>
                    <li role="presentation" ><a href="{{ url('admin/permit/create') }}">新增</a></li>
                </ul>
            </div>
            <div class="col-md-9 col-md-offset-0">
                <div class="panel panel-info">
                    <!--<div class="panel-heading">概况</div>-->

                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                            <div class="col-md-12">
                                <form action="{{ url('') }}" class="navbar-form navbar-left" role="search">
                                    <div class="form-group">
                                        <label for="dept">部门</label>
                                        <select name="dept" id="dept" class="form-control">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">姓名</label>
                                        <input type="text" id="name" name="name" class="form-control" value="{{old('name')}}">
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">搜索</button>
                                    </div>
                                </form>
                            </div>

                        <div class="col-md-12">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>权限名称</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($permissions))
                                    @foreach($permissions as $permission)
                                        <td>{{ $permission->name or ''}}</td>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>

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
        //删除单条记录提示确认
        function __del(obj) {
            layer.confirm('确认删除？',function (index) {
                obj.parentNode.submit();
                layer.close(index);
                layer.load(1);
            });
        }
    </script>
@endsection