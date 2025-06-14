<nav class="navbar navbar-expand-lg navbar-light bg-light navbar-static-top">
    <div class="container">
        <!-- Branding Image -->
        <a class="navbar-brand" href="{{ url('/') }}">
            育才中学管理系统
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav flex-grow-1">
                <li class="nav-item {{ active_class(if_route('reports.index')) }}"><a class="nav-link" href="{{ route('reports.index') }}">数据汇总</a></li>
                 <li class="nav-item {{ active_class(if_route('group_quantify.display')) }}"><a class="nav-link" href="{{ route('group_quantify.display') }}">小组量化</a></li>
                <li class="nav-item {{ active_class(if_route('quantify.display')) }}"><a class="nav-link" href="{{ route('quantify.display') }}">班级量化</a></li>
                <li class="nav-item {{ category_nav_active([1,2,3]) }} dropdown">
                    <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        学生出勤
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item {{ category_nav_active(1) }}" href="{{ route('reports.summary.grade', 1) }}">七年级</a></li>
                        <li><a class="dropdown-item {{ category_nav_active(2) }}" href="{{ route('reports.summary.grade', 2) }}">八年级</a></li>
                        <li><a class="dropdown-item {{ category_nav_active(3) }}" href="{{ route('reports.summary.grade', 3) }}">九年级</a></li>
                    </ul>
                </li>
                {{-- 新增量化记录添加入口 --}}
                @canany(['manage_reports', 'manage_contents'])
                <li class="nav-item {{ active_class(if_route('quantify_records.create')) }}">
                    <a class="nav-link" href="{{ route('quantify_records.create') }}">添加记录</a>
                </li>
                @endcanany
                {{-- 新增通知公告菜单项 --}}
                <li class="nav-item {{ active_class(if_route('categories.show')) }}">
                    <a class="nav-link" href="{{ route('categories.show', 4) }}">通知公告</a>
                </li>
                {{-- 新增规章制度菜单项 --}}
                <li class="nav-item {{ active_class(if_route('categories.show')) }}">
                    <a class="nav-link" href="{{ route('categories.show', 2) }}">规章制度</a>
                </li>
                <li class="nav-item {{ category_nav_active(4) }}"><a class="nav-link" href="{{ route('reports.create') }}">每日上报</a></li>
                <li class="nav-item {{ category_nav_active(5) }}">
                    <a class="nav-link" href="{{ route('banjis.assignments', ['banji' => Auth::user()->banji_id ?? 1]) }}">作业清单</a>
                </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav navbar-right">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">登录</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">注册</a></li>
                @else
                    <li class="nav-item">
                        <a class="nav-link mt-1 mr-3 font-weight-bold" href="{{ route('reports.create') }}">
                            <i class="fa fa-plus"></i>
                        </a>
                    </li>
                    <li class="nav-item notification-badge">
                        <a class="nav-link mr-3 badge badge-pill badge-{{ Auth::user()->notification_count > 0 ? 'hint' : 'secondary' }} text-white" href="{{ route('notifications.index') }}">
                            {{ Auth::user()->notification_count }}
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ Auth::user()->avatar }}" class="img-responsive img-circle" width="30px" height="30px">
                            {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            @can('manage_contents')
                                <a class="dropdown-item" href="{{ url(config('administrator.uri')) }}">
                                    <i class="fas fa-tachometer-alt mr-2"></i>
                                    管理后台
                                </a>
                                <div class="dropdown-divider"></div>
                            @endcan
                            <a class="dropdown-item" href="{{ route('users.show', Auth::id()) }}">
                                <i class="far fa-user mr-2"></i>
                                个人中心
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('quantify_records.index') }}">
                                <i class="far fa-user mr-2"></i>
                                量化管理
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('users.edit', Auth::id()) }}">
                                <i class="far fa-edit mr-2"></i>
                                编辑资料
                            </a>
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-link">退出</button>
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

