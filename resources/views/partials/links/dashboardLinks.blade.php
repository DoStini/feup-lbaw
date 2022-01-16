<ul class="nav nav-tabs my-5">
    <li class="nav-item">
        <a class="nav-link{{($page == 'orderDashboard' ? ' active' : '')}}" href={{route('getOrderDashboard')}}> Manage Orders </a>
    </li>
    <li class="nav-item">
        <a class="nav-link{{($page == 'userDashboard' ? ' active' : '')}}" href={{route('getUserDashboard')}}>  Manage Users </a>
    </li>
    <li class="nav-item">
        <a class="nav-link{{($page == 'couponDashboard' ? ' active' : '')}}" href={{route('getCouponDashboard')}}>  Manage Coupons </a>
    </li>
</ul>
