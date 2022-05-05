window.myApp.controller('povspro-controller', function($scope, $http, $window, $sce) {
    var index = 0;
    var targetModal = '';
    var getData = '';
    var currentModal = '';
    $scope.dataContainer = {};
    $scope.reportData = {};

    $('.nav-tabs a').click(function(e) {
        e.preventDefault();
        index = $($(this).attr('href')).index();
    });

    const customSweet = Swal.mixin({
        customClass: {
            confirmButton: 'btn bg-gradient-primary btn-flat mr-3',
            cancelButton: 'btn bg-gradient-danger btn-flat'
        },
        buttonsStyling: false
    });

    $scope.getPendingMatches = function() {

        var matchesData = {
            supplier_id: $scope.supplierName,
            customer_code: $scope.locationName
        }

        $http({
            headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' },
            method: 'POST',
            url: `${$base_url}getPendingMatchesPRF`,
            data: $.param(matchesData),
            responseType: 'json'
        }).then(function successCallback(response) {
            if (response.data.info == 'Does Not Exist') {
                customSweet.fire({
                    title: `<i class='fas fa-info-circle fa-lg' style='color:#005ce6'></i>`,
                    html: `<strong>${response.data.Message}</strong>`,
                    allowOutsideClick: false,
                    confirmButtonText: 'OK'
                })
                $scope.pendingMatchesTable = false;
            } else if (response.data != '') {
                $scope.pendingMatchesTable = true;
                $scope.pendingMatches = response.data;
                $(document).ready(function() { $('#proformaTable').DataTable(); });
            } else {
                customSweet.fire({
                    title: `<i class='fas fa-info-circle fa-lg' style='color:#005ce6'></i>`,
                    html: `<strong>No Pending Matches Found.</strong>`,
                    allowOutsideClick: false,
                    confirmButtonText: 'OK'
                })
                $scope.pendingMatchesTable = false;

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
            url: `${$base_url}getCustomers`
        }).then(function successCallback(response) {
            $scope.customers = response.data;
        });
    }

    $scope.getPurchaseOrder = function() {
        $http({
            method: 'post',
            url: '../transactionControllers/povsproformacontroller/getPurchaseOrder/' + $scope.supplierSelect + '/' + $scope.customerSelect
        }).then(function successCallback(response) {

            console.log(response.data);
            $scope.po = response.data;
        });
    }

    $scope.uploadProforma = function(e) {
        e.preventDefault();

        var formData = new FormData(e.target);
        // var files = $('#proforma')[0].files;
        // formData.append('file', files[0]);

        $.ajax({
            type: 'POST',
            url: `${$base_url}uploadProforma`,
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

                if (response.info == 'Invalid Format') {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top',
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
                } else if (response.info == 'No Data') {
                    customSweet.fire({
                        icon: 'error',
                        title: response.info,
                        text: response.message
                    })
                } else if (response.info == 'Error') {
                    customSweet.fire({
                        icon: 'error',
                        title: response.info,
                        text: response.message
                    })
                } else if (response.info == 'Uploaded') {
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

    $scope.getItems = function(data) {

        console.log(data);
        $scope.reportData = {
            po: data.po_no,
            rep_stat_id: data.rep_stat_id,
            po_header_id: data.po_header_id,
            proforma_header_id: data.proforma_header_id,
            proforma_code: data.proforma_code,
            supplier_code: data.supplier_code,
            customer_code: data.customer_code,
            acroname: data.acroname
        };

        $http({
            headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' },
            method: 'POST',
            url: $base_url + 'getMatchItems',
            data: $.param(data),
            responseType: 'json'
        }).then(function successCallback(response) {
            $scope.items = response.data;
        });
    }

    $scope.match = function(e, data) {
        e.preventDefault();

        var items = data;

        for (let i = 0; i < items.length; i++) {

            if (items[i].po_desc == undefined || items[i].po_desc == 'NO SET UP' || items[i].po_desc == '') {
                customSweet.fire({
                    title: `<i class="fas fa-exclamation-triangle" style="color: orange;"></i> ` + 'WARNING!',
                    html: `<strong>There are missing Item codes/Description detected please setup those missing item codes to proceed.</strong>`,
                    allowOutsideClick: false
                })

                return;
            }
        }

        $scope.items = data;
        var formData = new FormData(e.target);
        var container1 = JSON.parse(angular.toJson($scope.items));
        var container2 = JSON.parse(angular.toJson($scope.reportData));
        formData = convertModelToFormData(container1, formData, 'container1');
        formData = convertModelToFormData(container2, formData, 'container2');


        customSweet.fire({
            html: `<strong>Are you sure to match this<br> PO : <i>${$scope.reportData.po}</i><br>Proforma : <i>${$scope.reportData.proforma_code}</i>?</strong>`,
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: 'Match',
            cancelButtonText: 'Cancel',
            allowOutsideClick: false,
        }).then((result) => {

            if (result.dismiss !== 'cancel') {

                $.ajax({
                    type: 'POST',
                    url: $base_url + 'matchPOandProforma',
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

                        response = JSON.parse(response);

                        var itemArray1 = [];
                        var itemArray2 = [];

                        if (response.info == 'Matching Failed') {
                            customSweet.fire({
                                icon: 'error',
                                title: response.info,
                                text: response.message
                            })
                        } else if (response.info == 'Served and Not served') {
                            for (let i = 0; i < response.item_codes.length; i++) {
                                itemArray1.push(response.item_codes[i]);
                            }

                            customSweet.fire({
                                icon: 'success',
                                html: `<label>Matched Succesfully!</label> <br>${response.message}<br><br> <strong>PO ITEMS : <i>${itemArray1}</i></strong>`,
                                width: 600,
                            }).then((result) => {
                                window.open($base_url + 'files/Reports/POvsProforma/' + response.file);
                                location.reload();
                            });
                        } else if (response.info == 'Served and Overserved') {
                            for (let i = 0; i < response.item_codes.length; i++) {
                                itemArray1.push(response.item_codes[i]);
                            }

                            customSweet.fire({
                                icon: 'success',
                                title: 'Matched Succesfully',
                                html: `${response.message}<br><br> <strong><i>${itemArray1}</i></strong>`,
                            }).then((result) => {
                                window.open($base_url + 'files/Reports/POvsProforma/' + response.file);
                                location.reload();
                            });
                        } else if (response.info == 'Served, Not served , and Overserved') {
                            if (response.po_items != null) {
                                for (let i = 0; i < response.po_items.length; i++) {
                                    itemArray1.push(response.po_items[i]);
                                }
                            }

                            if (response.pr_items != null) {
                                for (let i = 0; i < response.pr_items.length; i++) {
                                    itemArray2.push(response.pr_items[i]);
                                }
                            }

                            customSweet.fire({
                                icon: 'success',
                                title: 'Matched Succesfully',
                                html: `${response.message}<br><br> <strong>PO Items: <i>${itemArray1}</i></strong> <br> <strong>Proforma Items: <i>${itemArray2}</i></strong>`,
                            }).then((result) => {
                                window.open($base_url + 'files/Reports/POvsProforma/' + response.file);
                                location.reload();
                            });
                        } else if (response.info == 'Matched') {
                            customSweet.fire({
                                icon: 'success',
                                title: 'Matched Succesfully',
                                html: `${response.message}`,
                            }).then((result) => {
                                window.open($base_url + 'files/Reports/POvsProforma/' + response.file);
                                location.reload();
                            });
                        }
                    }
                });
            } else {
                Swal.close();
            }
        })
    }

    $scope.view = function(data) {
        const newLocal = '../transactionControllers/povsproformacontroller/getProforma/';
        $http({
            method: 'POST',
            url: `${newLocal + data.acroname}/${data.po_header_id}/${data.proforma_header_id}`,
        }).then(function successCallback(response) {
            $scope.po_no = response.data[0].po_no + "/" + response.data[0].po_reference;
            $scope.so_no = response.data[0].so_no;
            $scope.pro_code = response.data[0].proforma_code;
            $scope.proforma_line = response.data;
            $scope.acroname_edit = data.acroname;
            $scope.proforma_header_id = data.proforma_header_id;

            $scope.tableRow = true;
            $scope.uploadRow = false;
            $scope.buttonNAme = 'Replace Proforma';
        });

        $scope.dataContainer = {
            poNo: data.po_no,
            supplierCode: data.supplier_code,
            customerCode: data.customer_code,
            po_reference: data.po_reference
        }
    }

    $scope.editProforma = function(e) {

        if (e.target != '') {
            e.preventDefault();
        }

        if ($scope.tableRow == true) {
            var formData1 = [];

            $scope.proforma_line.forEach(function(data) {
                if (data.checkBoxEdit == true) {
                    formArray = {
                        id: data.proforma_line_id,
                        item_code: data.item_code,
                        description: data.description,
                        qty: data.qty,
                        uom: data.uom,
                        price: data.price
                    };

                    formData1.push(formArray);
                }
            });

            if (e.target != '') {
                var formData = new FormData(e.target);
            } else {
                var formData = new FormData(e);
            }
            var proforma_line = JSON.parse(angular.toJson(formData1));
            formData = convertModelToFormData(proforma_line, formData, 'proforma_line');

            customSweet.fire({
                title: 'Update Proforma',
                text: 'Are you sure to update Proforma Line?',
                showCancelButton: true,
                showConfirmButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {

                if (result.dismiss !== 'cancel') {
                    $.ajax({
                        type: 'POST',
                        url: `${$base_url}updateProformaLine`,
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

                            if (response.info == 'Updated') {
                                customSweet.fire({
                                    icon: 'success',
                                    title: response.info,
                                    text: response.message
                                }).then((result) => {
                                    location.reload();
                                });
                            } else if (response.info == 'Error') {
                                customSweet.fire({
                                    icon: 'success',
                                    title: response.info,
                                    text: response.message
                                })
                            } else if (response.info == 'Empty') {
                                customSweet.fire({
                                    icon: 'success',
                                    title: response.info,
                                    text: response.message
                                })
                            }
                        }
                    });
                } else {
                    Swal.close();
                }
            })
        } else {
            var formData = new FormData(e.target);
            // var files = $('#new_proforma')[0].files;
            var container = JSON.parse(angular.toJson($scope.dataContainer));
            formData = convertModelToFormData(container, formData, 'container');
            // formData.append('file', files[0]);

            customSweet.fire({
                title: 'Replace Proforma',
                text: 'Are you sure to replace Proforma Line?',
                showCancelButton: true,
                showConfirmButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {

                if (result.dismiss !== 'cancel') {
                    $.ajax({
                        type: 'POST',
                        url: $base_url + 'replaceProforma',
                        data: formData,
                        enctype: 'multipart/form-data',
                        async: true,
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function() {
                            $('#loading_modal').modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                        },
                        success: function(response) {
                            $('#loading_modal').modal('toggle');

                            if (response.info == 'No File') {
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    didOpen: (toast) => {
                                        toast.addEventListener('mouseenter', Swal.stopTimer)
                                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                                    }
                                })

                                Toast.fire({
                                    icon: 'warning',
                                    title: response.message
                                })
                            } else if (response.info == 'Invalid Format') {
                                customSweet.fire({
                                    icon: 'error',
                                    title: response.info,
                                    text: response.message
                                });
                            } else if (response.info == 'Error') {
                                customSweet.fire({
                                    icon: 'error',
                                    title: response.info,
                                    text: response.message
                                });
                            } else if (response.info == 'Replaced') {
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
                } else {
                    Swal.close();
                }
            })
        }

    }

    $scope.addDiscountVAT = function(e) {
        e.preventDefault();

        var formData = new FormData(e.target);
        var discountData = JSON.parse(angular.toJson($scope.discountData));
        formData = convertModelToFormData(discountData, formData, 'discountData');

        customSweet.fire({
            text: `Are you sure to Additionals/Deductions to ${$scope.pro_code}?`,
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {

            if (result.dismiss !== 'cancel') {
                $.ajax({
                    type: 'POST',
                    url: $base_url + 'addDiscount',
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

                        if (response.info == 'Added') {
                            customSweet.fire({
                                icon: 'success',
                                title: response.info,
                                text: response.message
                            }).then((result) => {
                                location.reload();
                            });
                        } else if (response.info == 'Error') {
                            customSweet.fire({
                                icon: 'error',
                                title: response.info,
                                text: response.message
                            })
                        } else if (response.info == 'No Data') {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            })

                            Toast.fire({
                                icon: 'warning',
                                title: response.message
                            })
                        }
                    }
                });
            } else {
                Swal.close();
            }
        })
    }

    $scope.authenticate = () => {
        $('#managersKey').modal('toggle')
    }

    $scope.managersKey = (data, mkey, targerModal) => {
        targetModal = targerModal;
        getData = data;
        currentModal = mkey;

        $('#' + currentModal).modal('toggle')
    }

    $scope.authorizeKey = (e) => {
        e.preventDefault();
        var formData = new FormData(e.target);

        $.ajax({
            type: 'POST',
            url: $base_url + 'authorize',
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

                if (response.info == 'Auth') {

                    if (targetModal == '') {
                        $('#managersKey').modal('hide');
                        clearModal('managersKey');
                        $scope.editProforma(document.getElementById("viewForm"));
                    } else {
                        $('#' + currentModal).modal('hide');
                        clearModal(currentModal);
                        $scope.view(getData);
                        $('#' + targetModal).modal('toggle')
                        toastAlert(response.message, 'success');
                    }

                } else if (response.info == 'Denied' || response.info == 'Error' || response.info == 'Not Found') {

                    toastAlert(response.message, 'error');

                }
            }
        });

    }

    $scope.setButtonEnabled = function() {
        var isButtonEnabled = true;

        if (index == 0) {
            angular.forEach($scope.proforma_line, function(item) {
                if (item && item.checkBoxEdit)
                    isButtonEnabled = false;
            });
        } else {
            isButtonEnabled = false;
        }

        return isButtonEnabled;
    }

    $scope.history = function(data) {
        $http({
            method: 'post',
            url: '../transactionControllers/povsproformacontroller/getHistory/' + data.proforma_header_id,
        }).then(function successCallback(response) {
            if (response.data != '') {
                $scope.proforma_history = response.data;
                $(document).ready(function() { $('#historyTable').DataTable(); });
            } else {
                $scope.proforma_history = '';
            }
        });
    }

    $scope.clearHistory = function() {
        $scope.proforma_history = '';
    }

    $scope.replaceProforma = function() {
        if ($scope.buttonNAme == 'Replace Proforma') {
            $scope.tableRow = false;
            $scope.uploadRow = true;
            $scope.buttonNAme = 'Return';
        } else {
            $scope.tableRow = true;
            $scope.uploadRow = false;
            $scope.buttonNAme = 'Replace Proforma';
        }
    }

    $scope.tabs = () => {
        if (index == 1) {
            $scope.tabIndex = true;
            $scope.discount_tab = true;
            $scope.tableRow = true;
            $scope.uploadRow = false;
            $scope.buttonNAme = 'Replace Proforma';
            $scope.getDiscount($scope.proforma_header_id);
        } else {
            $scope.discount_tab = false;
            $scope.tabIndex = false;
        }
    }

    $scope.getDiscount = function(id) {
        if (id !== undefined) {
            $http({
                method: 'post',
                url: '../transactionControllers/povsproformacontroller/getDiscount/' + id,
            }).then(function successCallback(response) {
                $scope.discount = response.data;
            });
        }
    }
});