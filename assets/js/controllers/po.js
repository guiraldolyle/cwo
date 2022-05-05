window.myApp.controller('po-controller', function($scope, $http, $window) {
    const warningTitle = "<i class='fas fa-exclamation-triangle fa-lg' style='color:#e65c00'></i>";
    const infoTitle = "<i class='fas fa-info-circle fa-lg' style='color:#005ce6'></i>";
    const successTitle = "<i class='fas fa-check-circle fa-lg' style='color:#28a745'></i>";
    $scope.loadSupplier = function() {

        $http({
            method: 'get',
            url: $base_url + 'getSuppliersForPO'
        }).then(function successCallback(response) {
            $scope.suppliers = response.data;
        });
    }

    $scope.loadCustomer = function() {
        $http({
            method: 'get',
            url: $base_url + 'getCustomersForPO'
        }).then(function successCallback(response) {
            $scope.customers = response.data;
        });
    }

    $scope.uploadPo = function(ev) {
        ev.preventDefault();

        var formData = new FormData(ev.target);

        $.ajax({
            type: "POST",
            url: $base_url + 'uploadPo',
            data: formData,
            async: false,
            cache: false,
            processData: false,
            contentType: false,
            success: function(response) {

                console.log(response);
                if( response.info == "Error-ext" ) {
                    Swal.fire({
                        title: warningTitle,
                        html: "<b> " + response.message + " [" + response.ext + "] </b>"
                    }).then(function() {
                        location.reload();
                    })
                } else if( response.info == "Error-item"){
                    var items = Object.values(response.item);
                    var itemNotFound = "";
                    for (let i = 0; i < items.length; i++) {
                        itemNotFound = items;
                    }
                    Swal.fire({
                        title: warningTitle,
                        html: "<b> " +  response.message +" </b> <br> " + "<i>" + itemNotFound + "</i>"
                    }).then(function() {
                        location.reload();
                    })
                } else if( response.info == "Error" ){
                    Swal.fire({
                        title: warningTitle,
                        html: "<b> " + response.message + " </b>"
                    }).then(function() {
                        location.reload();
                    })
                } else if( response.info == "Success" ){
                    Swal.fire({
                        title: successTitle,
                        html: "<b> " + response.message + " </b>"
                    }).then(function() {
                        location.reload();
                    })
                } else if( response == "duplicate" ){
                    Swal.fire({
                        title: successTitle,
                        html: "<b> PO already exists! </b>"
                    }).then(function() {
                        location.reload();
                    })
                }
            }
        });

    }

    $scope.closePoForm = function() {
        $("#uploadPoForm").trigger("reset");
    }

    $scope.poTable = function() {
        $http({
            method: 'POST',
            url: $base_url + 'getPOs',
            data: { supId: $scope.supplierName, cusId: $scope.locationName }
        }).then(function successCallback(response) {
            if (response.data != '') {
                $(document).ready(function() { $('#poTable').DataTable(); });
                $scope.po = response.data;
                $scope.poList = true;
            } else {
                swal.fire({
                    title: infoTitle,
                    html: "<b> No Pending Matches for this supplier and location! </b>"
                })
                $scope.poList = false;
            }


        });
    }

    $scope.poExt = function(element) {
        $scope.pofile = element.files[0];
        var filename = $scope.pofile.name;
        var index = filename.lastIndexOf(".");
        var strsubstring = filename.substring(index, filename.length);
        if (strsubstring == ".ICM-SA-PST" ||
            strsubstring == ".CENT-DC-PST" ||
            strsubstring == ".CENT-DC" ||
            strsubstring == ".ICM-SA" ||
            strsubstring == "txt" ||
            strsubstring == "TXT") {
            console.log("allowed");
        } else {
            Swal.fire({
                title: warningTitle,
                html: "<b> Invalid file extension! </b>"
            }).then(function() {
                location.reload();
            })
        }
    }

    $scope.viewPoDetails = function(data) {
        var poId = data.po_header_id;
        var supId = data.supplier_id;
        var total = 0;
        $scope.poNo = data.poNo;
        $scope.poRef = data.ref;

        $http({
            method: 'post',
            url: '../transactionControllers/pocontroller/getPoDetails/' + poId
        }).then(function successCallback(response) {
            $scope.poDetails = response.data;
            angular.forEach($scope.poDetails, function(value, key) {
                total += parseInt(value.qty) * parseFloat(value.direct_unit_cost);
            });
            $scope.poAmt = total.toFixed(2);

        });
    }

});