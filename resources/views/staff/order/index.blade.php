@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @component('user.navpill',['staff_order' => 'class=active'])

            @endcomponent

            <div class="col-md-9 col-md-offset-0">
                <div class="panel panel-info">

                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <!--搜索功能-->
                        <div class="col-md-12">
                            <form action="{{ url('staff/order/search') }}" class="navbar-form navbar-left" role="search">
                                <div class="form-group">
                                    <label for="dept">部门</label>
                                    <select name="dept" id="dept" class="form-control">
                                        <option value=""></option>
                                        @foreach($depts as $dept)
                                            @if($dept->id == old('dept'))
                                                <option value="{{ $dept->id }}" selected>{{ $dept->name }}</option>
                                            @else
                                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                            @endif
                                        @endforeach
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
                                        <th>姓名</th>
                                        <th>所属部门</th>
                                        <th>餐费标准</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach( $users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->dept->name or '' }}</td>
                                            <td>早餐：{{$user->price->breakfast or ''}} 午餐：{{$user->price->lunch or ''}} 晚餐：{{$user->price->dinner or ''}}</td>
                                            <td>
                                                <a href="{{ url('staff/order/'.$user->id.'/edit') }}" class="btn btn-info">状态设置</a>
                                                <a href="{{ url('staff/order/'.$user->id.'/edit') }}" class="btn btn-info">暂时开停</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {{ $users->links() }}
                            </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
