<!doctype html>
<html lang="en">
    <head>
        <title>Home</title>
        <link rel="stylesheet" type="text/css" href="{{asset('assets/admin')}}/css/style.css">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/admin')}}/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/admin')}}/css/font-awesome.css">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/admin')}}/css/responsive.css">
        <link rel="stylesheet" href="{{asset('assets/admin')}}/css/alertify/themes/default.css">
        <link rel="stylesheet" href="{{asset('assets/admin')}}/css/alertify/alertify.rtl.css">
        <script type="text/javascript" src="{{asset('assets/admin')}}/js/jquery.js"></script>
        <script type="text/javascript" src="{{asset('assets/admin')}}/js/functions.js"></script>
        <script src="{{asset('assets/admin')}}/js/alertify.js"></script>

    </head>
    <body>
        <!--Wrapper Start-->
        <div class="wrapper">
            <div class="home-login">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-section">
                                <div class="form-logo-section">
                                    <img src="{{asset('assets/admin')}}/images/logo.png"/>
                                </div>
                                <form method="post" action="">
                                    <div class="form-group">
                                        <input required type="text" class="form-control user" placeholder="Username"/>
                                    </div>
                                    <div class="form-group">
                                        <input required type="password" class="form-control pass" placeholder="Password"/>
                                    </div>
                                    <input type="submit" class="btn btn-default login-submit" value="Sign In">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Wrapper End-->
    <script type="text/javascript" src="{{asset('assets')}}/admin/js/app.js"></script>
    </body>
</html>