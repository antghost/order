@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @component('admin.navpill',[ '$report' => 'class=active'])

        @endcomponent

        <div class="col-md-9 col-md-offset-0">
            <div class="panel panel-info">
                <div class="panel-heading">报表数据生成</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="col-md-12">
                       <form class="navbar-form navbar-left" action="{{ url('admin/report/setData') }}" method="post">
                           {{ csrf_field() }}
                           <div class="form-group">
                               <label class="control-label">年份</label>
                               <select class="form-control" name="year">
                                   @for($i = \Carbon\Carbon::today()->year; $i >= 2010; $i--)
                                       <option>{{ $i }}</option>
                                   @endfor
                               </select>
                           </div>
                           <div class="form-group">
                               <label class="control-label">月份</label>
                               <select class="form-control" name="month">
                                   @for($i = 12; $i >=1; $i--)
                                       @if($i == \Carbon\Carbon::today()->month)
                                           <option selected>{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                       @else
                                           <option>{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                       @endif
                                   @endfor
                               </select>
                           </div>
                           <div class="form-group">
                               <button type="submit" class="btn btn-info">生成</button>
                           </div>
                       </form>
                    </div>

                    <div class="col-md-12">

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script src="{{asset('My97DatePicker/WdatePicker.js')}}"></script>
@endsection