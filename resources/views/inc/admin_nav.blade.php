<!--NAVBAR-->
<!--===================================================-->
<header id="navbar">
    <div id="navbar-container" class="boxed">

        @php
        $generalsetting = \App\GeneralSetting::first();
        @endphp

        <!--Brand logo & name-->
        <!--================================-->
        <div class="navbar-header-logo">
            <a href="{{route('admin.dashboard')}}" class="navbar-brand">
                @if($generalsetting->logo != null)
                <img loading="lazy" src="{{ asset($generalsetting->admin_logo) }}" class="brand-icon" alt="{{ $generalsetting->site_name }}">
                @else
                <img loading="lazy" src="{{ asset('img/logo_shop.png') }}" class="brand-icon" alt="{{ $generalsetting->site_name }}">
                @endif
                <div class="brand-title d-none">
                    <span class="brand-text">{{ $generalsetting->site_name }}</span>
                </div>
            </a>
        </div>
        <!--================================-->
        <!--End brand logo & name-->


        <!--Navbar Dropdown-->
        <!--================================-->
        <div class="navbar-content">

        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar1" aria-expanded="false"> <span class="sr-only">Toggle navigation</span>
 <span class="icon-bar"></span>
 <span class="icon-bar"></span>
 <span class="icon-bar"></span>
            </button>

        </div>

        <div class="navbar-collapse collapse" id="navbar1">
        <ul id="mainnav-menu1" class="nav navbar-nav list-group topbar-list">

<!--Category name-->
{{-- <li class="list-header">Navigation</li> --}}

<!--Menu list item-->
<li class="{{ areActiveRoutes(['admin.dashboard'])}}">
    <a class="nav-link" href="{{route('admin.dashboard')}}">
        <i class="fa fa-home"></i>
        <span class="menu-title">{{__('Dashboard')}}</span>
    </a>
</li>

<!-- Product Menu -->
@if(Auth::user()->user_type == 'admin' || in_array('1', json_decode(Auth::user()->staff->role->permissions)))
<li class="parent">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-shopping-cart"></i>
        <span class="menu-title">{{__('Products')}}</span>
        <i class="arrow"></i>
    </a>

    <!--Submenu-->
    <ul class="collapse child dropdown-menu">
        <li class="{{ areActiveRoutes(['brands.index', 'brands.create', 'brands.edit'])}}">
            <a class="nav-link" href="{{route('brands.index')}}">{{__('Brand')}}</a>
        </li>
        <li class="{{ areActiveRoutes(['categories.index', 'categories.create', 'categories.edit'])}}">
            <a class="nav-link" href="{{route('categories.index')}}">{{__('Category')}}</a>
        </li>
        <li class="{{ areActiveRoutes(['subcategories.index', 'subcategories.create', 'subcategories.edit'])}}">
            <a class="nav-link" href="{{route('subcategories.index')}}">{{__('Subcategory')}}</a>
        </li>
        <li class="{{ areActiveRoutes(['subsubcategories.index', 'subsubcategories.create', 'subsubcategories.edit'])}}">
            <a class="nav-link" href="{{route('subsubcategories.index')}}">{{__('Sub Subcategory')}}</a>
        </li>
        <li class="{{ areActiveRoutes(['products.admin', 'products.create', 'products.admin.edit'])}}">
            <a class="nav-link" href="{{route('products.admin')}}">{{__('In House Products')}}</a>
        </li>
        @if(\App\BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1)
        <li class="{{ areActiveRoutes(['products.seller', 'products.seller.edit'])}}">
            <a class="nav-link" href="{{route('products.seller')}}">{{__('Seller Products')}}</a>
        </li>
        @endif
        @php
        $review_count = DB::table('reviews')
        ->orderBy('code', 'desc')
        ->join('products', 'products.id', '=', 'reviews.product_id')
        ->where('products.user_id', Auth::user()->id)
        ->where('reviews.viewed', 0)
        ->select('reviews.id')
        ->distinct()
        ->count();
        @endphp
        <li class="{{ areActiveRoutes(['reviews.index'])}}">
            <a class="nav-link" href="{{route('reviews.index')}}">{{__('Product Reviews')}}@if($review_count > 0)<span class="pull-right badge badge-info">{{ $review_count }}</span>@endif</a>
        </li>
    </ul>
</li>
@endif

@if(Auth::user()->user_type == 'admin' || in_array('2', json_decode(Auth::user()->staff->role->permissions)))
<li class="{{ areActiveRoutes(['flash_deals.index', 'flash_deals.create', 'flash_deals.edit'])}}">
    <a class="nav-link" href="{{ route('flash_deals.index') }}">
        <i class="fa fa-bolt"></i>
        <span class="menu-title">{{__('Deals')}}</span>
    </a>
</li>
@endif




<!-- Orders -->


<!-- Inhouse Orders -->
@if(Auth::user()->user_type == 'admin' || in_array('3', json_decode(Auth::user()->staff->role->permissions)))
@php
$orders = DB::table('orders')
->orderBy('code', 'desc')
->join('order_details', 'orders.id', '=', 'order_details.order_id')
->where('order_details.seller_id', Auth::user()->id)
->where('orders.viewed', 0)
->select('orders.id')
->distinct()
->count();
@endphp


<li class=" parent {{ areActiveRoutes(['orders.index.admin', 'orders.show'])}}">
    <a href="#">
        <i class="fa fa-shopping-basket"></i>
        <span class="menu-title">{{__('Orders')}} @if($orders > 0)<span class="pull-right badge badge-info">{{ $orders }}</span>@endif</span>
    </a>


    <ul class="collapse child dropdown-menu">
        <li class="{{ areActiveRoutes(['orders.index.admin', 'orders.show'])}}">
            <a class="nav-link" href="{{ route('orders.index.admin') }}">
                {{__('Inhouse orders')}} @if($orders > 0)
                <span class="pull-right badge badge-info">{{ $orders }}</span>@endif
            </a>
        </li>
        <!-- Pickup Point Orders -->
        @if(Auth::user()->user_type == 'admin' || in_array('14', json_decode(Auth::user()->staff->role->permissions)))
        <li class="{{ areActiveRoutes(['pick_up_point.order_index'])}}">
            <a class="nav-link" href="{{ route('pick_up_point.order_index') }}">
                {{__('Pick-up Point Order')}}
            </a>
        </li>
        @endif
        <!-- // Pickup Point Orders -->
    </ul>

</li>
@endif
<!-- // Inhouse Orders -->


<!-- // Orders -->

@if(Auth::user()->user_type == 'admin' || in_array('4', json_decode(Auth::user()->staff->role->permissions)))
<li class="{{ areActiveRoutes(['sales.index', 'sales.show'])}}">
    <a class="nav-link" href="{{ route('sales.index') }}">
        <i class="fa fa-money"></i>
        <span class="menu-title">{{__('Sales')}}</span>
    </a>
</li>

@endif

@if((Auth::user()->user_type == 'admin' || in_array('5', json_decode(Auth::user()->staff->role->permissions))) && \App\BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1)
<li class="parent">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-user-plus"></i>
        <span class="menu-title">{{__('Sellers')}}</span>
        <i class="arrow"></i>
    </a>

    <!--Submenu-->
    <ul class="collapse child dropdown-menu">
        <li class="{{ areActiveRoutes(['sellers.index', 'sellers.create', 'sellers.edit', 'sellers.payment_history'])}}">
            @php
            $sellers = \App\Seller::where('verification_status', 0)->where('verification_info', '!=', null)->count();
            //$withdraw_req = \App\SellerWithdrawRequest::where('viewed', '0')->get();
            @endphp
            <a class="nav-link" href="{{route('sellers.index')}}">{{__('Seller List')}} @if($sellers > 0)<span class="pull-right badge badge-info">{{ $sellers }}</span> @endif</a>
        </li>
        <li class="{{ areActiveRoutes(['withdraw_requests_all'])}}">
            <a class="nav-link" href="{{ route('withdraw_requests_all') }}">{{__('Seller Withdraw Requests')}}</a>
        </li>
        <li class="{{ areActiveRoutes(['sellers.payment_histories'])}}">
            <a class="nav-link" href="{{ route('sellers.payment_histories') }}">{{__('Seller Payments')}}</a>
        </li>
        <li class="{{ areActiveRoutes(['business_settings.vendor_commission'])}}">
            <a class="nav-link" href="{{ route('business_settings.vendor_commission') }}">{{__('Seller Commission')}}</a>
        </li>
        <li class="{{ areActiveRoutes(['seller_verification_form.index'])}}">
            <a class="nav-link" href="{{route('seller_verification_form.index')}}">{{__('Seller Verification Form')}}</a>
        </li>
    </ul>
</li>
@endif

@if(Auth::user()->user_type == 'admin' || in_array('6', json_decode(Auth::user()->staff->role->permissions)))
<li class="parent">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-user-plus"></i>
        <span class="menu-title">{{__('Customers')}}</span>
        <i class="arrow"></i>
    </a>

    <!--Submenu-->
    <ul class="collapse child dropdown-menu">
        <li class="{{ areActiveRoutes(['customers.index'])}}">
            <a class="nav-link" href="{{ route('customers.index') }}">{{__('Customer list')}}</a>
        </li>
    </ul>
</li>
@endif

<li class="parent">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-file"></i>
        <span class="menu-title">{{__('Reports')}}</span>
        <i class="arrow"></i>
    </a>

    <!--Submenu-->
    <ul class="collapse child dropdown-menu">
        <li class="{{ areActiveRoutes(['stock_report.index'])}}">
            <a class="nav-link" href="{{ route('stock_report.index') }}">{{__('Stock Report')}}</a>
        </li>
        <li class="{{ areActiveRoutes(['in_house_sale_report.index'])}}">
            <a class="nav-link" href="{{ route('in_house_sale_report.index') }}">{{__('In House Sale Report')}}</a>
        </li>
        <li class="{{ areActiveRoutes(['seller_report.index'])}}">
            <a class="nav-link" href="{{ route('seller_report.index') }}">{{__('Seller Report')}}</a>
        </li>
        <li class="{{ areActiveRoutes(['seller_sale_report.index'])}}">
            <a class="nav-link" href="{{ route('seller_sale_report.index') }}">{{__('Seller Based Selling Report')}}</a>
        </li>
        <li class="{{ areActiveRoutes(['wish_report.index'])}}">
            <a class="nav-link" href="{{ route('wish_report.index') }}">{{__('Product Wish Report')}}</a>
        </li>
    </ul>
</li>

@if(Auth::user()->user_type == 'admin' || in_array('7', json_decode(Auth::user()->staff->role->permissions)))
<li class="parent">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-envelope"></i>
        <span class="menu-title">{{__('Messaging')}}</span>
        <i class="arrow"></i>
    </a>

    <!--Submenu-->
    <ul class="collapse child dropdown-menu">
        <li class="{{ areActiveRoutes(['newsletters.index'])}}">
            <a class="nav-link" href="{{route('newsletters.index')}}">{{__('Newsletters')}}</a>
        </li>
    </ul>
</li>
@endif

@if(Auth::user()->user_type == 'admin' || in_array('9', json_decode(Auth::user()->staff->role->permissions)))
<li class="parent">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-desktop"></i>
        <span class="menu-title">{{__('Settings')}}</span>
        <i class="arrow"></i>
    </a>

    <!--Submenu-->
    <ul class="collapse child dropdown-menu">
        <li class="{{ areActiveRoutes(['home_settings.index', 'home_banners.index', 'sliders.index', 'home_categories.index', 'home_banners.create', 'home_categories.create', 'home_categories.edit', 'sliders.create'])}}">
            <a class="nav-link" href="{{route('home_settings.index')}}">{{__('Home')}}</a>
        </li>

        <!-- Bussines Settings -->
        <li class="{{ areActiveRoutes(['activation.index'])}}">
            <a class="nav-link" href="{{route('activation.index')}}">{{__('Activation')}}</a>
        </li>
        <li class="{{ areActiveRoutes(['payment_method.index'])}}">
            <a class="nav-link" href="{{ route('payment_method.index') }}">{{__('Payment method')}}</a>
        </li>
        <li class="{{ areActiveRoutes(['smtp_settings.index'])}}">
            <a class="nav-link" href="{{ route('smtp_settings.index') }}">{{__('SMTP Settings')}}</a>
        </li>
        <li class="{{ areActiveRoutes(['google_analytics.index'])}}">
            <a class="nav-link" href="{{ route('google_analytics.index') }}">{{__('Google Analytics')}}</a>
        </li>
        <li class="{{ areActiveRoutes(['facebook_chat.index'])}}">
            <a class="nav-link" href="{{ route('facebook_chat.index') }}">{{__('Facebook Chat')}}</a>
        </li>
        <li class="{{ areActiveRoutes(['social_login.index'])}}">
            <a class="nav-link" href="{{ route('social_login.index') }}">{{__('Social Media Login')}}</a>
        </li>
        <li class="{{ areActiveRoutes(['currency.index'])}}">
            <a class="nav-link" href="{{route('currency.index')}}">{{__('Currency')}}</a>
        </li>
        <li class="{{ areActiveRoutes(['languages.index', 'languages.create', 'languages.store', 'languages.show', 'languages.edit'])}}">
            <a class="nav-link" href="{{route('languages.index')}}">{{__('Languages')}}</a>
        </li>

        <!-- // Bussiness Settings -->

        <li class="{{ areActiveRoutes(['sellerpolicy.index'])}}">
            <a class="nav-link" href="{{route('sellerpolicy.index', 'seller_policy')}}">{{__('Seller Policy')}}</a>
        </li>
        <li class="{{ areActiveRoutes(['returnpolicy.index'])}}">
            <a class="nav-link" href="{{route('returnpolicy.index', 'return_policy')}}">{{__('Return Policy')}}</a>
        </li>
        <li class="{{ areActiveRoutes(['supportpolicy.index'])}}">
            <a class="nav-link" href="{{route('supportpolicy.index', 'support_policy')}}">{{__('Support Policy')}}</a>
        </li>
        <li class="{{ areActiveRoutes(['terms.index'])}}">
            <a class="nav-link" href="{{route('terms.index', 'terms')}}">{{__('Terms & Conditions')}}</a>
        </li>
        <li class="{{ areActiveRoutes(['privacypolicy.index'])}}">
            <a class="nav-link" href="{{route('privacypolicy.index', 'privacy_policy')}}">{{__('Privacy Policy')}}</a>
        </li>


        <!-- ecommerce setup -->
        @if(Auth::user()->user_type == 'admin' || in_array('12', json_decode(Auth::user()->staff->role->permissions)))

        <li class="{{ areActiveRoutes(['coupon.index','coupon.create','coupon.edit',])}}">
            <a class="nav-link" href="{{route('coupon.index')}}">{{__('Coupon')}}</a>
        </li>

        <li class="{{ areActiveRoutes(['pick_up_points.index','pick_up_points.create','pick_up_points.edit',])}}">
            <a class="nav-link" href="{{route('pick_up_points.index')}}">{{__('Pickup Point')}}</a>
        </li>

        @endif

        <!-- Seo Settings -->

        @if(Auth::user()->user_type == 'admin' || in_array('11', json_decode(Auth::user()->staff->role->permissions)))
        <li class="{{ areActiveRoutes(['seosetting.index'])}}">
            <a class="nav-link" href="{{ route('seosetting.index') }}">
                {{__('SEO Setting')}}
            </a>
        </li>
        @endif

        <li class="{{ areActiveRoutes(['links.index', 'links.create', 'links.edit'])}}">
            <a class="nav-link" href="{{route('links.index')}}">{{__('Useful Link')}}</a>
        </li>
        <li class="{{ areActiveRoutes(['generalsettings.index'])}}">
            <a class="nav-link" href="{{route('generalsettings.index')}}">{{__('General Settings')}}</a>
        </li>
        <li class="{{ areActiveRoutes(['generalsettings.logo'])}}">
            <a class="nav-link" href="{{route('generalsettings.logo')}}">{{__('Logo Settings')}}</a>
        </li>
        <li class="{{ areActiveRoutes(['generalsettings.color'])}}">
            <a class="nav-link" href="{{route('generalsettings.color')}}">{{__('Color Settings')}}</a>
        </li>
        <!-- Support Ticket -->
        @if(Auth::user()->user_type == 'admin' || in_array('13', json_decode(Auth::user()->staff->role->permissions)))
        @php
        $support_ticket = DB::table('tickets')
        ->where('viewed', 0)
        ->select('id')
        ->count();
        @endphp
        <li class="{{ areActiveRoutes(['support_ticket.admin_index', 'support_ticket.admin_show'])}}">
            <a class="nav-link" href="{{ route('support_ticket.admin_index') }}">
                {{__('Support Ticket')}} @if($support_ticket > 0)
                {{ $support_ticket }}@endif
            </a>
        </li>
        @endif
    </ul>
</li>
@endif


@if(Auth::user()->user_type == 'admin' || in_array('10', json_decode(Auth::user()->staff->role->permissions)))
<li class="parent">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-user"></i>
        <span class="menu-title">{{__('Staffs')}}</span>
        <i class="arrow"></i>
    </a>

    <!--Submenu-->
    <ul class="collapse child dropdown-menu">
        <li class="{{ areActiveRoutes(['staffs.index', 'staffs.create', 'staffs.edit'])}}">
            <a class="nav-link" href="{{ route('staffs.index') }}">{{__('All staffs')}}</a>
        </li>
        <li class="{{ areActiveRoutes(['roles.index', 'roles.create', 'roles.edit'])}}">
            <a class="nav-link" href="{{route('roles.index')}}">{{__('Staff permissions')}}</a>
        </li>
    </ul>
</li>
@endif
<!--User dropdown-->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<li id="dropdown-user" class="dropdown">
    <a href="#" data-toggle="dropdown" class="dropdown-toggle text-right">
        <span class="ic-user">

            <i class="demo-pli-male"></i>
        </span>
    </a>

    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right panel-default">
        <ul class="head-list">
            <li>
                <a href="{{ route('profile.index') }}"><i class="demo-pli-male icon-lg icon-fw"></i> {{__('Profile')}}</a>
            </li>
            <li>
                <a href="{{ route('logout')}}"><i class="demo-pli-unlock icon-lg icon-fw"></i> {{__('Logout')}}</a>
            </li>
        </ul>
    </div>
</li>
</ul>
        </div>



            <ul class="nav navbar-top-links d-none">

                <!--Navigation toogle button-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <li class="tgl-menu-btn">
                    <a class="mainnav-toggle" href="#">
                        <i class="demo-pli-list-view"></i>
                    </a>
                </li>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End Navigation toogle button-->



                {{-- <!--Search-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <li>
                    <div class="custom-search-form">
                        <label class="btn btn-trans" for="search-input" data-toggle="collapse" data-target="#nav-searchbox">
                            <i class="demo-pli-magnifi-glass"></i>
                        </label>
                    </div>
                </li>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End Search--> --}}

            </ul>

            <ul class="nav navbar-top-links d-none">

                @php
                $orders = DB::table('orders')
                ->orderBy('code', 'desc')
                ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                ->where('order_details.seller_id', Auth::user()->id)
                ->where('orders.viewed', 0)
                ->select('orders.id')
                ->distinct()
                ->count();
                $sellers = \App\Seller::where('verification_status', 0)->where('verification_info', '!=', null)->count();
                @endphp

                <li class="dropdown d-none" id="lang-change">
                    @php
                    if(Session::has('locale')){
                    $locale = Session::get('locale', Config::get('app.locale'));
                    }
                    else{
                    $locale = 'en';
                    }
                    @endphp
                    <a href="" class="dropdown-toggle top-bar-item" data-toggle="dropdown">
                        <img loading="lazy" src="{{ asset('frontend/images/icons/flags/'.$locale.'.png') }}" class="flag" style="margin-right:6px;"><span class="language">{{ \App\Language::where('code', $locale)->first()->name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @foreach (\App\Language::all() as $key => $language)
                        <li class="dropdown-item @if($locale == $language) active @endif">
                            <a href="#" data-flag="{{ $language->code }}"><img loading="lazy" src="{{ asset('frontend/images/icons/flags/'.$language->code.'.png') }}" class="flag" style="margin-right:6px;"><span class="language">{{ $language->name }}</span></a>
                        </li>
                        @endforeach
                    </ul>
                </li>


                <li class="dropdown d-none">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle" aria-expanded="true">
                        <i class="demo-pli-bell"></i>
                        @if($orders > 0 || $sellers > 0)
                        <span class="badge badge-header badge-danger"></span>
                        @endif
                    </a>

                    <!--Notification dropdown menu-->
                    <div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="opacity: 1;">
                        <div class="nano scrollable has-scrollbar" style="height: 265px;">
                            <div class="nano-content" tabindex="0" style="right: -17px;">
                                <ul class="head-list">
                                    @if($orders > 0)
                                    <li>
                                        <a class="media" href="{{ route('orders.index.admin') }}" style="position:relative">
                                            <span class="badge badge-header badge-info" style="right:auto;left:3px;"></span>
                                            <div class="media-body">
                                                <p class="mar-no text-nowrap text-main text-semibold">{{ $orders }} new order(s)</p>
                                            </div>
                                        </a>
                                    </li>
                                    @endif
                                    @if($sellers > 0)
                                    <li>
                                        <a class="media" href="{{ route('sellers.index') }}">
                                            <div class="media-body">
                                                <p class="mar-no text-nowrap text-main text-semibold">{{__('New verification request(s)')}}</p>
                                            </div>
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                            <div class="nano-pane">
                                <div class="nano-slider" style="height: 170px; transform: translate(0px, 0px);"></div>
                            </div>
                        </div>
                    </div>
                </li>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End user dropdown-->
            </ul>
        </div>
        <!--================================-->
        <!--End Navbar Dropdown-->

    </div>
</header>