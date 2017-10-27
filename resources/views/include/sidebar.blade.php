<!-- sidebar left start-->
        <div class="sidebar-left">
            <!--responsive view logo start-->
            <div class="logo dark-logo-bg visible-xs-* visible-sm-*">
                <a href="{{ url('/dashboard') }}">
                    <img src="{{ url('/resources/assets/img/logo.png') }}" alt="">
                    <!--<i class="fa fa-maxcdn"></i>-->
                    <!-- <span class="brand-name">Stratus</span> -->
                </a>
            </div>
            <!--responsive view logo end-->

            <div class="sidebar-left-info" id="style-2">
                <!-- visible small devices start-->
                <div class=" search-field">  </div>
                <!-- visible small devices end-->

                <!--sidebar nav start-->
                <ul class="nav nav-pills nav-stacked side-navigation" style="display: none;">
                    <li class="hide_admin">
                        <h3 class="navigation-title">Navigation</h3>
                    </li>
                    <li class="hide_admin"><a href="{{ url('/dashboard') }}"><i class="fa fa-inbox"></i> <span>Projects</span></a></li>

                    <!-- <li>
                        <a href="{{ url('/dashboard/projects') }}""><i class="fa fa-users"></i><span>Projects</span></a>
                        <ul class="child-list">
                            <li><a href="{{ url('/dashboard/projects') }}"><i class="icon-user"></i>&nbsp; View Projects</a></li>
                            <li><a href="{{ url('/dashboard/projects/add') }}"><i class="icon-plus"></i>&nbsp; Add Projects</a></li>
                        </ul>
                    </li> -->
                    <li class="hide_user">
                        <h3 class="navigation-title">Admin Menu</h3>
                    </li>
                    <li class="hide_user">
                        <a href="{{ url('/dashboard/users') }}"><i class="fa fa-users"></i><span>Users</span></a>
                    </li>

                    <li class="hide_user">
                        <a href="{{ url('/dashboard/firms') }}"><i class="fa fa-building"></i><span>Companies</span></a>
                        <!-- <ul class="child-list">
                            <li><a href="{{ url('/dashboard/firms') }}"><i class="icon-user"></i>&nbsp; View Company</a></li>
                            <li><a href="{{ url('/dashboard/firms/add') }}"><i class="icon-plus"></i>&nbsp; Add Company</a></li>
                        </ul> -->
                    </li>
                   <!--  <li class="menu-list hide_user">
                        <a href=""><i class="fa fa-users"></i><span>Document</span></a>
                        <ul class="child-list">
                            <li><a href="{{ url('/dashboard/document') }}"><i class="icon-user"></i>&nbsp; View Document</a></li>
                            <li><a href="{{ url('/dashboard/document/add') }}"><i class="icon-plus"></i>&nbsp; Add Document</a></li>
                        </ul>
                    </li> -->
                    <li class="hide_user hide_admin">
                        <a href="{{ url('/dashboard/improvement') }}"><i class="fa fa-gavel"></i><span>Improvement Types</span></a>
                        <!-- <ul class="child-list">
                            <li><a href="{{ url('/dashboard/improvement') }}"><i class="icon-user"></i>&nbsp; View Improvement</a></li>
                            <li><a href="{{ url('/dashboard/improvement/add') }}"><i class="icon-plus"></i>&nbsp; Add Improvement</a></li>
                        </ul> -->
                    </li>
                    <li class="hide_user">
                        <a href="{{ url('/dashboard/company_type') }}"><i class="fa fa-building"></i><span>Company Types</span></a>
                        <!-- <ul class="child-list">
                            <li><a href="{{ url('/dashboard/company_type') }}"><i class="icon-user"></i>&nbsp; View Company Type</a></li>
                            <li><a href="{{ url('/dashboard/company_type/add') }}"><i class="icon-plus"></i>&nbsp; Add Company Type</a></li>
                        </ul> -->
                    </li>
                    <li class="hide_user hide_admin">
                        <a href="{{ url('/dashboard/currency') }}"><i class="fa fa-usd"></i><span>Currencies</span></a>
                       <!--  <ul class="child-list">
                            <li><a href="{{ url('/dashboard/currency') }}"><i class="icon-user"></i>&nbsp; View Currency</a></li>
                            <li><a href="{{ url('/dashboard/currency/add') }}"><i class="icon-plus"></i>&nbsp; Add Currency</a></li>
                            -->
                        </ul>
                    </li>


                </ul>
                <!--sidebar nav end-->

            </div>
        </div>
        
        <!-- sidebar left end