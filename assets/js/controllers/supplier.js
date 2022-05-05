window.myApp.controller('supplier-controller', function($scope, $http, $window) {

    const customSweet = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-primary btn-flat mr-3',
            cancelButton: 'btn btn-danger btn-flat'
        },
        buttonsStyling: false
    });

    $scope.suppliersTable = function() {
        var request = $http({
            method: 'get',
            url: '../masterfileController/suppliercontroller/fetchSuppliers'
        }).then(function successCallback(response) {
            $(document).ready(function() { $('#suppliersTable').DataTable(); });
            $scope.suppliers = response.data;
        });
    }

    $scope.saveSupplier = function(e) {
        e.preventDefault();

        var formData = new FormData(e.target);
        // var proformaHeader = JSON.parse(angular.toJson($scope.proformaHeader));
        // var proformaLine = JSON.parse(angular.toJson($scope.proformaLine));
        // formData = convertModelToFormData(proformaHeader, formData, 'proformaHeader');
        // var formData2 = convertModelToFormData(proformaLine, formData, 'proformaLine');

        Swal.fire({
            title: 'Are you sure to proceed?',
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            allowOutsideClick: false,
        }).then((result) => {

            if (result.dismiss != 'cancel') {
                $.ajax({
                    type: 'POST',
                    url: '../masterfileController/suppliercontroller/addSupplier',
                    data: formData,
                    enctype: 'multipart/form-data',
                    async: true,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#modal_loading').modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                    },
                    success: function(response) {
                        $('#modal_loading').modal('toggle');

                        if (response.info == 'Success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message
                            }).then((result) => {
                                location.reload();
                            });
                        } else if (response.info == 'Error Saving') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        } else if (response.info == 'No Data') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        } else if (response.info == 'Supplier Exist') {
                            Swal.fire({
                                icon: 'info',
                                title: 'Duplicate',
                                text: response.message
                            });
                        }
                    }
                });
            } else {
                Swal.close();
            }
        })
    }

    $scope.uploadSupplier = function(e) {
        e.preventDefault();

        var formData = new FormData(e.target);
        var files = $('#supplierFile')[0].files;
        formData.append('file', files[0]);

        $.ajax({
            type: 'POST',
            url: `${$base_url}uploadSupplier`,
            data: formData,
            enctype: 'multipart/form-data',
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $('#modal_loading').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function(response) {
                $('#modal_loading').modal('toggle');

                if (response.info == 'Format') {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-right',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        icon: 'error',
                        title: response.message
                    })
                } else if (response.info == 'Failed') {
                    customSweet.fire({
                        icon: 'error',
                        title: response.info,
                        text: response.message
                    })
                } else if (response.info == 'Error Saving') {
                    customSweet.fire({
                        icon: 'error',
                        title: response.info,
                        text: response.message
                    })
                } else if (response.info == 'Success') {
                    customSweet.fire({
                        icon: 'success',
                        title: response.info,
                        text: response.message
                    }).then((result) => {
                        location.reload();
                    });
                } else if (response.info == 'Duplicate') {
                    customSweet.fire({
                        icon: 'info',
                        title: response.info,
                        text: response.message
                    })
                }
            }
        });

    }

    $scope.fetchSupplierData = function(data) {
        $scope.suppliersID = data.supplier_id;
        $scope.vendorsCodeU = data.supplier_code;
        $scope.supplierNameU = data.supplier_name;
        $scope.supplierAcronameU = data.acroname;
        $scope.supplierAddressU = data.address;
        $scope.supplierContactU = data.contact_no;
    }

    $scope.updateSupplierData = function(e) {
        e.preventDefault();

        var formData = new FormData(e.target);
        // var proformaHeader = JSON.parse(angular.toJson($scope.proformaHeaderU));
        // var proformaLine = JSON.parse(angular.toJson($scope.proformaLineU));
        // formData = convertModelToFormData(proformaHeader, formData, 'proformaHeader');
        // var formData2 = convertModelToFormData(proformaLine, formData, 'proformaLine');
        formData.append('ID', $scope.suppliersID);

        Swal.fire({
            title: 'Are you sure to update or add new columns?',
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            allowOutsideClick: false,
        }).then((result) => {

            if (result.dismiss != 'cancel') {
                $.ajax({
                    type: 'POST',
                    url: '../masterfileController/suppliercontroller/updateSupplier',
                    data: formData,
                    enctype: 'multipart/form-data',
                    async: true,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#modal_loading').modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                    },
                    success: function(response) {
                        $('#modal_loading').modal('toggle');

                        if (response.info == 'Success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message
                            }).then((result) => {
                                location.reload();
                            });
                        } else if (response.info == 'Error Saving') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        } else if (response.info == 'No Data') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        } else if (response.info == 'Exist') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    }
                });
            }
        })
    }

    $scope.deactivateSupplier = function(data) {
        var supplier_code = data.supplier_code;

        Swal.fire({
            title: 'Are you sure to deactivate this supplier?',
            icon: 'warning',
            text: data.supplier_name,
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            allowOutsideClick: false,
        }).then((result) => {

            if (result.dismiss !== 'cancel') {
                $.ajax({
                    type: 'POST',
                    url: '../masterfileController/suppliercontroller/deactivateSupplier',
                    data: { ID: supplier_code },
                    beforeSend: function() {
                        $('#modal_loading').modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                    },
                    success: function(response) {
                        $('#modal_loading').modal('toggle');
                        if (response.info == 'Success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message
                            }).then((result) => {
                                location.reload();
                            });
                        } else if (response.info == 'Error Deactivating') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        } else if (response.info == 'Error') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    }
                });
            } else {
                Swal.close();
            }
        })
    }
});