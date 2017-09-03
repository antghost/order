@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @component('user.navpill',['info' => 'class=active'])

            @endcomponent

            <div class="col-md-9 col-md-offset-0">
                <div class="panel panel-default">
                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="col-md-12">
                            <ul class="nav nav-tabs">
                                <li role="presentation" class="active"><a href="#">breakfast</a></li>
                                <li role="presentation" ><a href="{{ url('user/lunch') }}">lunch</a></li>
                                <li role="presentation" ><a href="{{ url('user/dinner') }}">dinner</a></li>
                            </ul>
                        </div>

                        <div class="col-md-12">
                            <p></p>
                            <form action="#" class="navbar-form navbar-left" role="search">
                                <div class="form-group">
                                    <label for="startdate">开始日期</label>
                                    <input type="text" id="startdate" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="enddate">结束日期</label>
                                    <input type="text" id="enddate" class="form-control">
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" id="book" value="">
                                    <label for="book">book</label>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" id="cancel" value="">
                                    <label for="cancel">cancel</label>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-info">search</button>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-12">
                            <p></p>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>开始日期</th>
                                    <th>结束日期</th>
                                    <th>类型</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>2017-01-01</td>
                                    <td>2017-11-05</td>
                                    <td>book</td>
                                </tr>
                                <tr>
                                    <td>2017-09-01</td>
                                    <td>2017-09-05</td>
                                    <td>cancel</td>
                                </tr>
                                <tr>
                                    <td>2017-09-11</td>
                                    <td>2017-09-15</td>
                                    <td>cancel</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
