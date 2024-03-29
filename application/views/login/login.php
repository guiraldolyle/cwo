<!DOCTYPE html>
<html lang="en" ng-app="login">

<head>
    <meta charset="UTF-8">
    <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CWO | Log In</title>
    <link rel="icon" type="image/gif" href="<?php echo base_url(); ?>assets/img/CWO-LOGO-2.png">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style.css">
</head>

<body id="login">
    <a href="<?php echo base_url(); ?>admin_login" class="ml-1" title="Admin Dashboard Login"><i class="fas fa-user-shield"></i> ADMIN LOGIN</a>
    <a href="<?php echo base_url(); ?>portal_login" class="ml-1" title="Portal Dashboard Login"><i class="fas fa-globe"></i> CWO PORTAL</a>
    <div class="container container-style" ng-controller="login-controller">
        <div class="card rounded-0 card-style">
            <div class="card-body">
                <img src="<?php echo base_url(); ?>assets/img/CWO-BLUE.png" alt="CWO" class="mb-3">
                <form action="" method="POST" enctype="multipart/form-data" ng-submit="loginSubmit($event)">
                    <div class="form-group row">
                        <label for="usernameTxtBox" class="col-sm-3 col-form-label text-right">Username: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control rounded-0" ng-model="login_user" name="login_user" placeholder="Username" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="passwordTxtBox" class="col-sm-3 col-form-label text-right">Password: </label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control rounded-0" ng-model="login_pass" name="login_pass" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-11">
                            <button type="submit" class="btn btn-primary button-style float-right rounded-0" style="width: 320px">Log In</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- SCRIPTS HERE -->
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-3.6.0.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/angular.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/controllers/login.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>plugins/sweetalert2/sweetalert2.min.js"></script>
</body>

</html>