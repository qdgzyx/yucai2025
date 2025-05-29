<nav class="navbar navbar-expand-lg navbar-light bg-light navbar-static-top">
  <div class="container">
      <!-- Branding Image -->
      <a class="navbar-brand " href="{{ url('/') }}">
      育才中学管理系统
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- Left Side Of Navbar -->
        <ul class="navbar-nav flex-grow-1">
          <li class="nav-item {{ active_class(if_route('reports.index')) }}"><a class="nav-link" href="{{ route('reports.index') }}">数据汇总</a></li>
          <li class="nav-item {{ active_class(if_route('quantify.display')) }}"><a class="nav-link" href="{{ route('quantify.display') }}">量化公示</a></li>
          <li class="nav-item {{ category_nav_active(1) }}"><a class="nav-link" href="{{ route('reports.summary.grade', 1) }}">七年级出勤</a></li>
          <li class="nav-item {{ category_nav_active(2) }}"><a class="nav-link" href="{{ route('reports.summary.grade', 2) }}">八年级出勤</a></li>
          <li class="nav-item {{ category_nav_active(3) }}"><a class="nav-link" href="{{ route('reports.summary.grade', 3) }}">九年级出勤</a></li>
          {{-- 新增量化记录添加入口 --}}
          <li class="nav-item {{ active_class(if_route('quantify_records.create')) }}">
            <a class="nav-link" href="{{ route('quantify_records.create') }}">添加记录</a>
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
            <li class="nav-item ">
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
                <a class="dropdown-item" href="{{ route('users.edit', Auth::id()) }}">
                  <i class="far fa-edit mr-2"></i>
                  编辑资料
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="return confirm('您确定要退出吗？')">
                  <i class="fas fa-sign-out-alt mr-2"></i>
                  退出
                </a>
              </div>
          </li>
          @endguest
      </ul>
      </div>
  </div>
</nav>