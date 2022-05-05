window.myApp.controller('users-controller', function($scope, $http) {

    $scope.userID = 0;
    $scope.getUsers = () => {
        $http({
            method: 'GET',
            url: $base_url + 'getUsers'
        }).then(function successCallback(response) {
            $(document).ready(function() { $('#usersTable').DataTable(); });
            $scope.users = response.data;
        });
    }

    $scope.saveUser = (e) => {
        e.preventDefault();

        var formData = {
            firstname: $scope.firstname,
            middlename: $scope.middlename,
            lastname: $scope.lastname,
            position: $scope.position,
            department: $scope.department,
            subsidiary: $scope.subsidiary,
            usertype: $scope.usertype,
            username: $scope.username,
            password: $scope.password

        }

        Swal.fire({
            title: 'Are you sure to proceed?',
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            allowOutsideClick: false,
        }).then((result) => {

            if (result.dismiss != 'cancel') {
                $http({
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' },
                    method: 'POST',
                    url: $base_url + 'addUser',
                    data: $.param(formData),
                    responseType: 'json'
                }).then(function successCallback(response) {
                    if (response.data.info == 'Success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.data.message
                        }).then((result) => {
                            location.reload();
                        });
                    } else if (response.data.info == 'Error Saving') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.data.message
                        });
                    } else if (response.data.info == 'No Data') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.data.message
                        });
                    } else if (response.info == 'Duplicate') {
                        Swal.fire({
                            icon: 'info',
                            title: 'Duplicate',
                            text: response.data.message
                        });
                    }
                });
            } else {
                Swal.close();
            }
        })
    }

    $scope.editUser = (data) => {
        $scope.userID = data.user_id;
        $scope.name_u = data.name;
        $scope.position_u = data.position;
        $scope.department_u = data.department;
        $scope.subsidiary_u = data.subsidiary;
        $scope.usertype_u = data.userType;
    }

    $scope.updateUser = (e) => {
        e.preventDefault();

        var formData = {
            ID: $scope.userID,
            name: $scope.name_u,
            position: $scope.position_u,
            department: $scope.department_u,
            subsidiary: $scope.subsidiary_u,
            usertype: $scope.usertype_u
        }

        Swal.fire({
            title: 'Are you sure to update user?',
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            allowOutsideClick: false,
        }).then((result) => {

            if (result.dismiss != 'cancel') {
                $http({
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' },
                    method: 'POST',
                    url: $base_url + 'updateUser',
                    data: $.param(formData),
                    responseType: 'json'
                }).then(function successCallback(response) {

                    if (response.data.info == 'Updated') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.data.message
                        }).then((result) => {
                            location.reload();
                        });
                    } else if (response.data.info == 'Error Saving') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.data.message
                        });
                    } else if (response.data.info == 'No Data') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.data.message
                        });
                    }
                });
            } else {
                Swal.close();
            }
        })

    }

    $scope.deactivate = function(data) {

        var formData = { ID: data.user_id }
        Swal.fire({
            title: 'Are you sure to deactivate user?',
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            allowOutsideClick: false,
        }).then((result) => {

            if (result.dismiss != 'cancel') {
                $http({
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' },
                    method: 'POST',
                    url: $base_url + 'deactivate',
                    data: $.param(formData),
                    responseType: 'json'
                }).then(function successCallback(response) {

                    if (response.data.info == 'Deactivated') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.data.message
                        }).then((result) => {
                            location.reload();
                        });
                    } else if (response.data.info == 'Error Deactivating') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.data.message
                        });
                    } else if (response.data.info == 'No ID') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.data.message
                        });
                    }
                });
            } else {
                Swal.close();
            }
        })

    }

});