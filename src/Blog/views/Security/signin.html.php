<div class="container">

    <?php if (!isset($errors)) {
        $errors = array();
    } ?>

    <form class="form-signin" role="form" method="post" action="<?php echo $getRoute('signin')?>">
        <h2 class="form-signin-heading"><?php echo _("Please sign in")?></h2>
        <?php foreach ($errors as $error) { ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>
                <strong><?php echo _("Error!")?></strong> <?php echo $error ?>
            </div>
        <?php } ?>
        <input type="email" class="form-control" placeholder="<?php echo _("Email address")?>" required autofocus name="email">
        <input type="password" class="form-control" placeholder="<?php echo _("Password")?>" required name="password">
        <button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo _("Sign in")?></button>
        <?php $generateToken()?>
    </form>

</div>