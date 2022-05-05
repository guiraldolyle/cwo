<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper body-bg" ng-controller="users-controller">
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-style-1">
                        <div class="card-header bg-dark rounded-0">
                            <div class="content-header" style="padding: 0px">
                                <div class="panel panel-default">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel-body"><i class="fas fa-users"></i> <strong>USERS</strong></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <button class="btn bg-gradient-primary btn-flat" data-target="#newUser" data-toggle="modal"><i class="fas fa-plus-circle"></i> New Users</button>
                                </div>
                            </div>
                            <div>
                                <table id="usersTable" class="table table-bordered table-sm table-hover" ng-init="getUsers()">
                                    <thead class="bg-dark">
                                        <tr>
                                            <th scope="col" class="text-center" style="display:none;">ID</th>
                                            <th scope="col" class="text-center">Name</th>
                                            <th scope="col" class="text-center">Username</th>
                                            <th scope="col" class="text-center">Position</th>
                                            <th scope="col" class="text-center">User Type</th>
                                            <th scope="col" class="text-center">Status</th>
                                            <th scope="col" class="text-center" style="width: 100px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="u in users" ng-cloak>
                                            <td class="text-center" style="display:none;">{{ u.user_id }}</td>
                                            <td class="text-center">{{ u.name }}</td>
                                            <td class="text-center">{{ u.username }}</th>
                                            <td class="text-center">{{ u.position }}</th>
                                            <td class="text-center">{{ u.userType }}</th>
                                            <td class="text-center">{{ u.status }}</th>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn bg-gradient-info btn-flat btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action
                                                    </button>
                                                    <div class="dropdown-menu rounded-0" style="margin-right: 50px;">
                                                        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#updateUserModal" ng-click="editUser(u)"><i class="fas fa-pen-square"></i> Edit</a>
                                                        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#updateUserModal"><i class="fas fa-key"></i> Change Password</a>
                                                        <a href="#" class="dropdown-item" ng-click="deactivate(u)" style="color: red;"><i class="fas fa-ban"></i> Deactivate</a>
                                                        <a href="#" class="dropdown-item" ng-click="deactivate(u)" style="color: red;"><i class="fas fa-ban"></i> Reset Password</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.col-md-6 -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <!-- MODAL ADD USER -->
    <div class="modal fade" id="newUser" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-xl" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header bg-dark rounded-0">
                    <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-pen-square"></i> New User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="POST" name="newUserForm" ng-submit="saveUser($event)" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="inputPassword" class="col-sm-4 col-form-label text-right"><i class="fab fa-slack required-icon"></i> First Name:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control rounded-0" name="firstname" ng-model="firstname" required autocomplete="off">
                                        <div class="validation-Error">
                                            <span ng-show="newUserForm.firstname.$dirty && newUserForm.firstname.$error.required">
                                                <p class="error-display">This field is required.</p>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword" class="col-sm-4 col-form-label text-right">Middle Name:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control rounded-0" name="middlename" ng-model="middlename">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword" class="col-sm-4 col-form-label text-right"><i class="fab fa-slack required-icon"></i> Last Name:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control rounded-0" name="lastname" ng-model="lastname" required autocomplete="off">
                                        <div class="validation-Error">
                                            <span ng-show="newUserForm.lastname.$dirty && newUserForm.lastname.$error.required">
                                                <p class="error-display">This field is required.</p>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword" class="col-sm-4 col-form-label text-right"><i class="fab fa-slack required-icon"></i> Position:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control rounded-0" name="position" ng-model="position" required>
                                            <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                            <option>Admin</option>
                                            <option>Accounting Clerk I</option>
                                            <option>Accounting Clerk II</option>
                                            <option>Accounting Clerk III</option>
                                            <option>CDC Acctg Section Head</option>
                                            <option>Programmer</option>
                                            <option>Manager</option>
                                            <option>Supervisor</option>
                                            <option>LDI</option>
                                            <option>Buyer-Purchaser</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword" class="col-sm-4 col-form-label text-right">Department:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control rounded-0" name="department" ng-model="department">
                                            <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                            <option>IT</option>
                                            <option>Accounting</option>
                                            <option>LDI</option>
                                            <option>Purchasing</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="inputPassword" class="col-sm-4 col-form-label text-right">Subsidiary:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control rounded-0" name="subsidiary" ng-model="subsidiary">
                                            <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                            <option>Corporate IT</option>
                                            <option>IT</option>
                                            <option>Corporate Accounting</option>
                                            <option>Corporate</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword" class="col-sm-4 col-form-label text-right"><i class="fab fa-slack required-icon"></i> User Type:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control rounded-0" name="usertype" ng-model="usertype" required>
                                            <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                            <option>Admin</option>
                                            <option>Accounting</option>
                                            <option>Manager</option>
                                            <option>Section Head</option>
                                            <option>Supervisor</option>
                                            <option>Buyer-Purchaser</option>
                                            <option>LDI</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword" class="col-sm-4 col-form-label text-right"><i class="fab fa-slack required-icon"></i> User Name:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control rounded-0" name="username" ng-model="username" required autocomplete="off">
                                        <div class="validation-Error">
                                            <span ng-show="newUserForm.username.$dirty && newUserForm.username.$error.required">
                                                <p class="error-display">This field is required.</p>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword" class="col-sm-4 col-form-label text-right"><i class="fab fa-slack required-icon"></i> Password:</label>
                                    <div class="col-sm-8">
                                        <input type="password" class="form-control rounded-0" name="password" ng-model="password" required autocomplete="off">
                                        <div class="validation-Error">
                                            <span ng-show="newUserForm.password.$dirty && newUserForm.password.$error.required">
                                                <p class="error-display">This field is required.</p>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword" class="col-sm-4 col-form-label text-right"><i class="fab fa-slack required-icon"></i> Confirm Password:</label>
                                    <div class="col-sm-8">
                                        <input type="password" class="form-control rounded-0" name="confirmpassword" ng-model="confirmpassword" required password-confirm match-target="password" autocomplete="off">
                                        <div class="validation-Error">
                                            <!-- <span ng-show="newUserForm.confirmpassword.$dirty && newUserForm.confirmpassword.$error.required"> -->
                                            <span ng-show="newUserForm.confirmpassword.$dirty && newUserForm.confirmpassword.$error.required" class="error-display">This field is required.</span>
                                            <!-- </span>
                                            <span ng-show="newUserForm.confirmpassword.$error.match"> -->
                                            <span ng-show="newUserForm.confirmpassword.$error.match" class="error-display">Password does not match.</span>
                                            <!-- </span> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn bg-gradient-primary btn-flat" ng-disabled="newUserForm.$invalid || newUserForm.confirmpassword.$error.match"><i class="fas fa-save"></i> Save</button>
                        <button type="button" class="btn bg-gradient-danger btn-flat" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- /.content -->

    <!-- MODAL EDIT USER -->
    <div class="modal fade" id="updateUserModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-xl" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header bg-dark rounded-0">
                    <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-pen-square"></i> Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="POST" name="updateUserForm" ng-submit="updateUser($event)" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="name" class="col-sm-4 col-form-label text-right"><i class="fab fa-slack required-icon"></i>Name:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control rounded-0" name="name" ng-model="name_u" required autocomplete="off">
                                        <div class="validation-Error">
                                            <span ng-show="updateUserForm.firstname.$dirty && updateUserForm.firstname.$error.required">
                                                <p class="error-display">This field is required.</p>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="position" class="col-sm-4 col-form-label text-right"><i class="fab fa-slack required-icon"></i> Position:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control rounded-0" name="position" ng-model="position_u" required>
                                            <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                            <option>Admin</option>
                                            <option>Accounting Clerk I</option>
                                            <option>Accounting Clerk II</option>
                                            <option>Accounting Clerk III</option>
                                            <option>CDC Acctg Section Head</option>
                                            <option>Programmer</option>
                                            <option>Sr. Programmer</option>
                                            <option>Jr. Programmer</option>
                                            <option>Manager</option>
                                            <option>Supervisor</option>
                                            <option>Jr. Supervisor</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="department" class="col-sm-4 col-form-label text-right">Department:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control rounded-0" name="department" ng-model="department_u">
                                            <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                            <option>IT</option>
                                            <option>Accounting</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="subsidiary" class="col-sm-4 col-form-label text-right">Subsidiary:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control rounded-0" name="subsidiary" ng-model="subsidiary_u">
                                            <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                            <option>Corporate IT</option>
                                            <option>IT</option>
                                            <option>Corporate Accounting</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="usertype" class="col-sm-4 col-form-label text-right"><i class="fab fa-slack required-icon"></i> User Type:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control rounded-0" name="usertype" ng-model="usertype_u" required>
                                            <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                            <option>Admin</option>
                                            <option>Accounting</option>
                                            <option>Manager</option>
                                            <option>Section Head</option>
                                            <option>Supervisor</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn bg-gradient-success btn-flat" ng-disabled="updateUserForm.$invalid"><i class="fas fa-save"></i> Save</button>
                        <button type="button" class="btn bg-gradient-danger btn-flat" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- /.content -->
</div>