<ul class="nav nav-tabs my-5">
    <li class="nav-item">
        <a class="nav-link{{($page == 'orderDashboard' ? ' active' : '')}}" href={{route('getOrderDashboard')}}> Manage Orders </a>
    </li>
    <li class="nav-item">
        <a class="nav-link{{($page == 'createNewAdmin' ? ' active' : '')}}" href={{route('getNewAdminPage')}}>  Create Admin </a>
    </li>
    <li class="nav-item">
        <a class="nav-link{{($page == 'userDashboard' ? ' active' : '')}}" href={{route('getUserDashboard')}}>  Manage Users </a>
    </li>
    <li class="nav-item">
        <a class="nav-link{{($page == 'addProduct' ? ' active' : '')}}" href={{route('addProduct')}}> Create Product </a>
    </li>
    <li class="nav-item">
        <a class="nav-link{{($page == 'productDashboard' ? ' active' : '')}}" href={{route('getProductDashboard')}}>  Manage Products </a>
    </li>
    <li class="nav-item">
        <a class="nav-link{{($page == 'createCouponDashboard' ? ' active' : '')}}" href={{route('getCreateCouponPage')}}>  Create Coupon </a>
    </li>
    <li class="nav-item">
        <a class="nav-link{{($page == 'couponDashboard' ? ' active' : '')}}" href={{route('getCouponDashboard')}}>  Manage Coupons </a>
    </li>
</ul>
