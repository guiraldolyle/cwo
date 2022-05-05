window.myApp.controller('vendorsDeal-controller', function($scope, $http, $window, $sce) {
    const customSweet = Swal.mixin({
        customClass: {
            confirmButton: 'btn bg-gradient-primary btn-flat mr-3',
            cancelButton: 'btn bg-gradient-danger btn-flat'
        },
        buttonsStyling: false
    });

    var url = '';
    $scope.label = `Upload New Deals`;
    $scope.toggleSwitch = function() {
        if ($scope.switch) {
            $scope.label = `Update Deals`;
        } else {
            $scope.label = `Upload New Deals`;
        }
    }

    $scope.getVendorsDeal = function() {
        var formData = {
            supplierID: $scope.supplierName
        };

        $scope.items = '';
        $scope.itemsTableToggle = false;

        $http({
            headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' },
            method: 'POST',
            url: `${$base_url}getDeals`,
            data: $.param(formData),
            responseType: 'json'
        }).then(function successCallback(response) {
            if (response.data != '') {
                $(document).ready(function() { $('#vendorsDealTable').DataTable(); });

                $scope.vendorsDealTables = true;
                $scope.deals = response.data;
            } else {
                $scope.deals = '';
                $scope.vendorsDealTables = false;
                Swal.fire({
                    title: 'No Data Found'
                });
            }
        });
    }

    $scope.getSuppliers = function() {
        $http({
            method: 'get',
            url: `${$base_url}getSuppliers`
        }).then(function successCallback(response) {
            $scope.suppliers = response.data;
        });
    }

    $scope.getCustomers = function() {
        $http({
            method: 'get',
            url: $base_url + 'getCustomers'
        }).then(function successCallback(response) {
            $scope.customers = response.data;
        });
    }

    $scope.uploadDeal = function(e) {

        e.preventDefault();

        var formData = new FormData(e.target);

        if ($scope.switch) {
            url = `${$base_url}updateDeals`;
        } else {
            url = `${$base_url}uploadDeals`;
        }

        customSweet.fire({
            html: `<strong>Proceed Uploading?</strong>`,
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: 'Ok',
            cancelButtonText: 'Cancel',
            allowOutsideClick: false,
        }).then((result) => {
            $.ajax({
                type: 'POST',
                url: url,
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

                    if (response.info == 'Format' ||
                        response.info == 'Duplicate' ||
                        response.info == 'Incorrect-Supplier' ||
                        response.info == 'Failed' ||
                        response.info == 'Error-Uploading') {
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
                    } else if (response.info == 'Uploaded') {
                        customSweet.fire({
                            icon: 'success',
                            title: response.info,
                            text: response.message
                        }).then((result) => {
                            location.reload();
                        });
                    }
                }
            });
        })
    }

    $scope.loadSupplierItemDeptCode = function(supplier)
    {
        $http({
            method: 'post',
            url: $base_url + 'loadItemDeptCode',
            data: { supId: supplier  }
        }).then(function successCallback(response) 
        {
            $scope.itemDeptCodes = response.data;
        });
    }
    $scope.count = 0;
    $scope.addNewDiscToTable = function(e, selected, selection)
    {
        e.preventDefault();
        var checkDupes = $scope.deductions.find(({ itemcode }) => itemcode === selected);
        var find = selection.find(({ item_department_code }) => item_department_code === selected);

        if( find !== undefined){
            if(checkDupes == undefined){
                $scope.deductions.push({'itemcode' : find.item_department_code,
                                        'desc'     : find.description,
                                        'disc1'    : $scope.disc1,
                                        'disc2'    : $scope.disc2,
                                        'disc3'    : $scope.disc3,
                                        'disc4'    : $scope.disc4,
                                        'disc5'    : $scope.disc5});
                                       
                $scope.count  ++;
            } else {
                customSweet.fire({
                    icon: 'error',
                    title: "Duplicate",
                    text: "Item discount is already added!"
                })
            }
        }
        $scope.resetNewItemDisc();
        $("#itemDiscount").modal('hide');
    }

    $scope.resetNewItemDisc = function()
    {
        $scope.itemDepartment = null;
        $scope.disc1          = null;
        $scope.disc2          = null;
        $scope.disc3          = null;
        $scope.disc4          = null;
        $scope.disc5          = null;
    }

    $scope.submitManualSetup = function(e)
    {
        e.preventDefault();

        var discounts = JSON.parse(angular.toJson($scope.deductions));
        if($scope.count > 0){
            customSweet.fire({
                html: `<strong>Are you sure you want to save the manual setup?</strong>`,
                showCancelButton: true,
                showConfirmButton: true,
                confirmButtonText: 'Ok',
                cancelButtonText: 'Cancel',
                allowOutsideClick: false,
            }).then((result) => {
                $.ajax({
                    type: 'POST',
                    url: $base_url + 'submitManualSetup',
                    data: {supId: $scope.selectSupplier,from: $scope.mFrom,to: $scope.mTo,discount: discounts},
                    async: false,
                    cache: false,
                    beforeSend: function() {
                        $('#modal_loading').modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                    },
                    success: function(response) {
                        $('#modal_loading').modal('toggle');
                        if(response.info == "Success"){
                            customSweet.fire({
                                icon: 'success',
                                title: response.info,
                                text: response.message
                            }).then((result) => {
                                location.reload();
                            });
                        } else {
                            customSweet.fire({
                                icon: 'error',
                                title: response.info,
                                text: response.message
                            }).then((result) => {
                                location.reload();
                            });
                        }
                       
                    }
                });
            })
        } else {
            customSweet.fire({
                icon: 'error',
                title: "No Data",
                text: "No Data added in the table!"
            })
        }
    }
});