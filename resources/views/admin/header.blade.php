<!-- Header -->
<header class="header fixed-top clearfix">
    <div class="brand">
        <a href="/" class="logo">
        </a>
    </div>
    <div class="col-md-6">
        @include('flash::message')
    </div>
    <div class="top-nav clearfix">


        <ul class="nav pull-right top-menu">
            <li class="dropdown">
                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                    <span class="username"></span> <b class="caret"></b>
                </a>
                <ul class="dropdown-menu extended logout">
                    <li>
                        <a href="/logout/"><i class="fa fa-sign-out"></i> Log Out</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</header>
<!-- End of Header -->