<div class="col-md-3">
    <ul class="nav nav-pills nav-stacked ">
        <li role="presentation" {{ $index }}><a href="{{ url('user') }}">概况</a></li>
        <li role="presentation" {{ $breakfast }}><a href="{{ url('user/breakfast') }}">用餐设置</a></li>
        <li role="presentation" {{ $info }}><a href="{{ url('user/info') }}">用餐流水</a></li>
        <li role="presentation"><a href="{{ url('user/comment') }}">管理</a></li>
    </ul>
</div>