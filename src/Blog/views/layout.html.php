<?php

$activeIfRoute = function ($item) use (&$route) {
    return $route['_name'] === $item?'class="active"':'';
};
?>
<!DOCTYPE html>
<html lang="en-us">

<head>
    <title><?php echo gettext("Education")?></title>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <link href="/css/bootstrap.min.css" type="text/css" rel="stylesheet"/>
    <link href="/css/bootstrap-theme.min.css" type="text/css" rel="stylesheet"/>
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome.css" rel="stylesheet">

    <link href='//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700' rel='stylesheet' type='text/css'>
    <link href="/css/theme.css" rel="stylesheet">

</head>
<body role="document">

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="http://mindk.com"><img class="brand-logo" src="/images/img-logo-mindk-white.png"
                                                                 alt="Education"></a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li <?php echo $activeIfRoute('home') ?>><a href="<?php echo $getRoute('home')?>"><?php echo _("Home") ?></a></li>
                <li <?php echo $activeIfRoute('add_post') ?>><a href="<?php echo $getRoute('add_post')?>"><?php echo _("Add Post")?></a></li>
                <li <?php echo $activeIfRoute('user_info') ?>><a href="<?php echo $getRoute('user_info')?>"><?php echo _("User Info")?></a></li>

            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown"><?php echo $curLocale?><span class="caret"/></a>
                    <ul class="dropdown-menu">
                        <?php foreach ($avalLocale as $key=>$value) { ?>
                            <li><a href="/change_options/location/<?php echo $key.$currentURN?>"><?php echo $key?></a></li>
                            <li class = "divider"></li>
                        <?php }?>
                    </ul>
                </li>
                <?php if (is_null($user)) { ?>
                    <li <?php echo $activeIfRoute('signin') ?>><a href="<?php echo $getRoute('signin')?>"><?php echo _("Sign in")?></a></li>
                    <li <?php echo $activeIfRoute('login') ?>><a href="<?php echo $getRoute('login')?>"><?php echo _("Login")?></a></li>
                <?php } else { ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="glyphicon glyphicon-user"></i>
                            <?php echo _('Hello, ').$user->email ?> <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li <?php echo $activeIfRoute('profile') ?>><a href="<?php echo $getRoute('profile')?>"><?php echo _("Profile")?></a></li>
                            <li class="divider"></li>
                            <li><a href="<?php echo $getRoute('logout')?>"><?php echo _("Logout")?></a></li>
                        </ul>
                    </li>

                <?php } ?>
            </ul>
        </div>
    </div>
</div>

<div class="container theme-showcase" role="main">
    <div class="row">
        <?php foreach($flush as $type=>$msgs) {
            foreach($msgs as $msg) {?>
            <div class="alert alert-<?php echo $type==='error'?'danger':$type?> alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>
                <?php echo $msg; ?>
            </div>
        <?php } ?>
        <?php } ?>

        <?php echo $content; ?>
    </div>
</div>

<script type="application/javascript" src="/js/jquery.min.js"></script>
<script type="application/javascript" src="/js/bootstrap.min.js"></script>
<script type="application/javascript" src="/js/jquery.hotkeys.js"></script>
<script type="application/javascript" src="/js/bootstrap-wysiwyg.js"></script>
<script type="application/javascript">
    $(document).ready(function () {
        $('#editor').wysiwyg();
        $('#post-form').submit(function (e) {
            $('#post-content').val($('#editor').html());
        })
    });
</script>

</body>
</html>