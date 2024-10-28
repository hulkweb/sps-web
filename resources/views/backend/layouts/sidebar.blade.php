<div class="sidebar-wrapper sidebar-theme">

    <nav id="sidebar">

        <div class="navbar-nav theme-brand flex-row  text-center">
            <div class="nav-logo">
                <div class="nav-item theme-logo">
                    <a href="{{route('dashboard')}}">
                        <img src="{{ uploads(getSetting('logo')) }}" style="width: 50px;" class="navbar-" alt="logo">
                    </a>
                </div>
                <div class="nav-item theme-text">
                    <a href="{{route('dashboard')}}" class="nav-link"> {{ getSetting('sitename') }} </a>
                </div>
            </div>
            <div class="nav-item sidebar-toggle">
                <div class="btn-toggle sidebarCollapse">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>
                </div>
            </div>
        </div>
        <div class="shadow-bottom"></div>
        <ul class="list-unstyled menu-categories" id="accordionExample">
            <li class="menu {{ $currentRouteName == 'dashboard' ? 'active' : '' }}">
                <a href="{{route('dashboard')}}" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        <span>Dashboard</span>
                    </div>

                </a>

            </li>



            <li class="menu {{ $currentRouteName == 'setting.index' ? 'active' : '' }}">
                <a href="{{route('setting.index')}}" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                            <path fill="none" d="M0 0h24v24H0z"/>
                            <path fill="currentColor" d="M19.43 12.98c.04-.32.07-.66.07-1 0-.34-.03-.68-.07-1l2.11-1.65c.19-.15.24-.42.12-.64l-2-3.46a.5.5 0 0 0-.6-.22l-2.49 1a7.99 7.99 0 0 0-1.72-.99l-.38-2.65a.5.5 0 0 0-.49-.42h-4a.5.5 0 0 0-.49.42l-.38 2.65a7.99 7.99 0 0 0-1.72.99l-2.49-1a.5.5 0 0 0-.6.22l-2 3.46a.5.5 0 0 0 .12.64l2.11 1.65c-.04.32-.07.66-.07 1 0 .34.03.68.07 1l-2.11 1.65a.5.5 0 0 0-.12.64l2 3.46c.12.22.39.3.6.22l2.49-1a7.99 7.99 0 0 0 1.72.99l.38 2.65a.5.5 0 0 0 .49.42h4a.5.5 0 0 0 .49-.42l.38-2.65a7.99 7.99 0 0 0 1.72-.99l2.49 1a.5.5 0 0 0 .6-.22l2-3.46a.5.5 0 0 0-.12-.64l-2.11-1.65zM12 15.5a3.5 3.5 0 1 1 0-7 3.5 3.5 0 0 1 0 7z"/>
                          </svg>
                        <span>Settings</span>
                    </div>
                </a>
            </li>


            <li class="menu @if($currentRouteName == 'permission.index' || $currentRouteName == 'role.index' ||$currentRouteName == 'staff.index') active @endif">
                <a href="#role" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                            <path fill="none" d="M0 0h24v24H0z"/>
                            <path fill="currentColor" d="M4 5h4v4H4V5zm6 0h4v4h-4V5zm6 0h4v4h-4V5zM4 11h4v4H4v-4zm6 0h4v4h-4v-4zm6 0h4v4h-4v-4zM4 17h4v4H4v-4zm6 0h4v4h-4v-4zm6 0h4v4h-4v-4z"/>
                          </svg>
                           <span>Role Menu</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="role" data-bs-parent="#accordionExample">
                    <li>
                        <a href="{{route('permission.index')}}"> Permissions </a>
                    </li>
                    <li>
                        <a href="{{route('role.index')}}"> Roles </a>
                    </li>

                    <li>
                        <a href="{{route('staff.index')}}"> Staffs </a>
                    </li>

                </ul>
            </li>


            <li class="menu {{ $currentRouteName == 'charge.index' ? 'active' : '' }}">
                <a href="{{ route('charge.index') }}" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                            <path fill="none" d="M0 0h24v24H0z"/>
                            <path fill="currentColor" d="M13 3c-1.66 0-3 1.34-3 3 0 1.24.73 2.29 1.77 2.83C9.74 10.2 8 12.02 8 14c0 1.66 1.34 3 3 3 1.24 0 2.29-.73 2.83-1.77C15.26 15.8 17 13.98 17 12c0-1.66-1.34-3-3-3-1.24 0-2.29.73-2.83 1.77C10.74 10.8 12 9.98 12 8c0-1.66-1.34-3-3-3zM12 11c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm0-8c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zM7 20c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm10 0c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1z"/>
                          </svg>
                            <span>Charges</span>
                    </div>
                </a>
            </li>

           

            <li class="menu {{ $currentRouteName == 'category.index' ? 'active' : '' }} {{ $currentRouteName == 'subcategory.index' ? 'active' : '' }}">
                <a href="#category" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                            <path fill="none" d="M0 0h24v24H0z"/>
                            <path fill="currentColor" d="M4 5h4v4H4V5zm6 0h4v4h-4V5zm6 0h4v4h-4V5zM4 11h4v4H4v-4zm6 0h4v4h-4v-4zm6 0h4v4h-4v-4zM4 17h4v4H4v-4zm6 0h4v4h-4v-4zm6 0h4v4h-4v-4z"/>
                          </svg>
                           <span>Catgeory Menu</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="category" data-bs-parent="#accordionExample">
                    <li>
                        <a href="{{route('category.index')}}"> Catgeories </a>
                    </li>
                    <li>
                        <a href="{{route('subcategory.index')}}"> Sub Catgeories </a>
                    </li>

                </ul>
            </li>

            <li class="menu {{ $currentRouteName == 'variation_type.index' ? 'active' : '' }} {{ $currentRouteName == 'variation_value.index' ? 'active' : '' }}">
                <a href="#variation" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                            <path fill="none" d="M0 0h24v24H0z"/>
                            <path fill="currentColor" d="M4 5h4v4H4V5zm6 0h4v4h-4V5zm6 0h4v4h-4V5zM4 11h4v4H4v-4zm6 0h4v4h-4v-4zm6 0h4v4h-4v-4zM4 17h4v4H4v-4zm6 0h4v4h-4v-4zm6 0h4v4h-4v-4z"/>
                          </svg>
                           <span>Variation Menu</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="variation" data-bs-parent="#accordionExample">
                    <li>
                        <a href="{{route('variation_type.index')}}"> Variation Type</a>
                    </li>
                    <li>
                        <a href="{{route('variation_value.index')}}"> Variation Value </a>
                    </li>

                </ul>
            </li>



            <li class="menu {{ $currentRouteName == 'product.index' ? 'active' : '' }}">
                <a href="{{route('product.index')}}" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                            <path fill="none" d="M0 0h24v24H0z"/>
                            <path fill="currentColor" d="M16 6V4c0-2.21-1.79-4-4-4s-4 1.79-4 4v2H4v14c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V6h-4zm-6-2c0-1.1.9-2 2-2s2 .9 2 2v2h-4V4zm6 16H6V8h12v12zm-6-9c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                          </svg>
                          <span>Products</span>
                    </div>
                </a>
            </li>

            <li class="menu {{ $currentRouteName == 'driver.index' ? 'active' : '' }}">
                <a href="{{route('driver.index')}}" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                            <path fill="none" d="M0 0h24v24H0z"/>
                            <path fill="currentColor" d="M19 5h-2c0-1.1-.9-2-2-2H9C7.9 3 7 3.9 7 5H5c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h1.26c.36 1.18 1.46 2 2.74 2 1.28 0 2.38-.82 2.74-2h3.52c.36 1.18 1.46 2 2.74 2 1.66 0 3-1.34 3-3v-4c0-1.1-.9-2-2-2h-1V7c0-1.1-.9-2-2-2zM9 5h6v2H9V5zm-4 8V7h2v4h2.17L12 13H5zm3 4c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm8-2h-4v-2h6v3c0 .55-.45 1-1 1s-1-.45-1-1v-1zm1-4h3v2h-3v-2zm-1 6c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1z"/>
                          </svg>
                           <span>Delivery Boys</span>
                    </div>
                </a>
            </li>


            <li class="menu {{ $currentRouteName == 'seller.index' ? 'active' : '' }}">
                <a href="{{route('seller.index')}}" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                            <path fill="none" d="M0 0h24v24H0z"/>
                            <path fill="currentColor" d="M16 11c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm8 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4zM8 13c-2.67 0-8 1.34-8 4v2h4v-2c0-2.66 1.34-4 4-4H8z"/>
                          </svg>
                          <span>Sales Persons</span>
                    </div>
                </a>
            </li>

            <li class="menu {{ $currentRouteName == 'customer.index' ? 'active' : '' }}">
                <a href="{{route('customer.index')}}" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                            <path fill="none" d="M0 0h24v24H0z"/>
                            <path fill="currentColor" d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm4 2h-1.26c-.84 0-1.58.33-2.12.88C16.58 14.67 15.37 15 14 15s-2.58-.33-3.62-.88c-.54-.55-1.28-.88-2.12-.88H7c-2.21 0-4 1.79-4 4v2h16v-2c0-2.21-1.79-4-4-4zm-8-2c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-.85 0-1.58.33-2.12.88C5.58 14.67 4.37 15 3 15H2c-1.1 0-2 .9-2 2v2h8v-2c0-2.21-1.79-4-4-4z"/>
                          </svg>
                           <span>Customers</span>
                    </div>
                </a>
            </li>

            <li class="menu {{ $currentRouteName == 'orders.index' ? 'active' : '' }}">
                <a href="{{ route('orders.index') }}" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                            <path fill="none" d="M0 0h24v24H0z"/>
                            <path fill="currentColor" d="M19 3h-4.18C14.4 1.84 13.3 1 12 1s-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM12 3c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm7 18H5V5h2v2h10V5h2v16zm-9-8h8v2H10v-2zm0 4h8v2H10v-2zm-2-4v2H6v-2h2zm0 4v2H6v-2h2z"/>
                          </svg>
                            <span>Orders</span>
                    </div>
                </a>
            </li>

            <li class="menu {{ $currentRouteName == 'transaction' ? 'active' : '' }}">
                <a href="{{ route('transaction') }}" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                            <path fill="none" d="M0 0h24v24H0z"/>
                            <path fill="currentColor" d="M12 1C6.48 1 2 5.48 2 11c0 5.51 4.48 10 10 10s10-4.49 10-10S17.52 1 12 1zm-1 17h-2v-2h2v2zm0-4h-2v-4h2v4zm6 4h-2v-2h2v2zm0-4h-2v-4h2v4zm4-4h-2v-4h2v4zm0-6h-2V5h2v2zm-6-2h-2V5h2v2zm-4 0H8V5h2v2zm-4 0H4V5h2v2z"/>
                          </svg>

                            <span>Transactions</span>
                    </div>
                </a>
            </li>




        </ul>

    </nav>

</div>
