<div class="col-md-2" style="background-color: white">
    <ul class="nav nav-pills nav-stacked ">
        <li role="presentation" {{ $index or '' }}><a href="{{ url('user') }}">概况</a></li>
        <li role="presentation" {{ $breakfast or '' }}><a href="{{ url('user/breakfast/create') }}">用餐设置</a></li>
        <li role="presentation" {{ $info or '' }}><a href="{{ url('user/breakfast') }}">用餐详情</a></li>
        <li role="presentation" {{ $staff_index or '' }}><a href="{{ url('staff') }}">用餐情况</a></li>
        <li role="presentation" {{ $staff_menus or '' }}><a href="{{ url('staff/menu') }}">菜式设置</a></li>
        <li role="presentation" {{ $staff_limit or '' }}><a href="{{ url('staff/limit') }}">时限设置</a></li>
        <li role="presentation" {{ $staff_order or '' }}><a href="{{ url('staff/order') }}">员工停开餐</a></li>
        <li role="presentation" {{ $staff_price or '' }}><a href="{{ url('staff') }}">缴费设置</a></li>
        <li role="presentation" {{ $common_holiday or '' }}><a href="{{ url('common/calendar') }}">假期设置</a></li>
        <li role="presentation" {{ $common_notice or '' }}><a href="{{ url('staff') }}">发布通知</a></li>
        <li role="presentation" {{ $common_report or '' }}><a href="{{ url('staff/report') }}">报表</a></li>
    </ul>
</div>