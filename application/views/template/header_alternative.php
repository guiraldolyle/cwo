<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Demo</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/bootstrap.min.css">
	<!-- Font Awesome Icons -->
	<link rel="stylesheet" href="<?php echo base_url();?>plugins/fontawesome-free/css/all.min.css">
  	<!-- Theme style -->
  	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/adminlte.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/style.css">
</head>
<body class="hold-transition sidebar-mini">
	<div class="wrapper">
	<!-- Navbar -->
  	<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    	<!-- Left navbar links -->
    	<ul class="navbar-nav">
      		<li class="nav-item">
        		<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      		</li>
      		<li class="nav-item d-none d-sm-inline-block">
        		<a href="index3.html" class="nav-link">Home</a>
      		</li>
      		<li class="nav-item d-none d-sm-inline-block">
        		<a href="#" class="nav-link">Contact</a>
      		</li>
    	</ul>

    	<!-- Right navbar links -->
    	<ul class="navbar-nav ml-auto">
      		<li class="nav-item">
      		  	<a class="nav-link" href="<?php echo base_url();?>baseController/login" role="button">
					Log Out <i class="fas fa-sign-out-alt"></i>
      		  	</a>
      		</li>
    	</ul>
  	</nav>
  	<!-- /.navbar -->

  	<!-- Main Sidebar Container -->
  	<aside class="main-sidebar sidebar-dark-primary elevation-4">
    	<!-- Brand Logo -->
    	<a href="index3.html" class="brand-link">
      		<img src="<?php echo base_url();?>assets/ico/ss1.png" alt="Sample System Logo" class="brand-image">
      		<span class="brand-text font-weight-light">SAMPLE SYSTEM</span>
    	</a>

    	<!-- Sidebar -->
    	<div class="sidebar">
        <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                    <!-- MASTER FILE TAB -->
                    <li class="nav-item">
                        <a href="#" class="nav-link">
			                <i class="fas fa-file nav-icon"></i>
                            <p> Master File<i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="#" class="nav-link">
				                    <i class="fas fa-people-carry nav-icon"></i><p> Suppliers</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-users nav-icon"></i><p> Customer</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- TRANSACTION TAB -->
                    <li class="nav-item">
                        <a href="#" class="nav-link">
			                <i class="fas fa-exchange-alt nav-icon"></i>
                            <p> Transaction<i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="#" class="nav-link">
				                    <i class="fas fa-people-carry"></i><p> Suppliers</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-users"></i><p> Customer</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- REPORTS TAB -->
                    <li class="nav-item">
                        <a href="#" class="nav-link">
			                <i class="fas fa-print nav-icon"></i>
                            <p> Reports<i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="#" class="nav-link">
				                    <i class="fas fa-people-carry"></i><p> Suppliers</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-users"></i><p> Customer</p>
                                </a>
                            </li>
                        </ul>
                    </li>
          
                    <li class="nav-item">
                        <a href="<?php echo base_url();?>baseController/menuSecondary" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>Secondary Menu</p>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Starter Page</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Starter Page</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5 class="m-0">Featured</h5>
                            </div>
                            <div class="card-body">
                                <form action="<?php echo base_url();?>baseController/password" method="post">
                                    <div class="col-md-3">
                                        <input type="password" name="password" class="form-control rounded-0">
                                        <button type="submit" class="btn btn-primary mt-2">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-md-6 -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->