window.myApp.controller('proformavspi-controller', function($scope, $http, $window) {
    const warningTitle = "<i class='fas fa-exclamation-triangle fa-lg' style='color:#e65c00'></i>";
    const successTitle = "<i class='fas fa-check-circle fa-lg' style='color:#28a745'></i>";
    const infoTitle    = "<i class='fas fa-info-circle fa-lg' style='color:#005ce6'></i>";

    $scope.userType = "";
    $scope.loadData = [];
    $scope.table = {};
    $scope.pricelogTable = {};
    $scope.proceedMatchProf = 0;
    $scope.proceedMatchPi = 0;
    $scope.crfId = 0;
    $scope.crfs = {};
    $scope.canEdit = false;

    $scope.toast = function(color)
    {
        const Toast = Swal.mixin({
                           toast: true,
                           position: 'top-right',
                           iconColor: color,
                           customClass: { popup: 'toasts-top-right' },
                           showConfirmButton: false,
                           timer: 1500,
                           timerProgressBar: true })
        return Toast;
    }

    $scope.checkUserType = function()
    {
        $http({
            method: 'get',
            url: $base_url + 'checkUserType'
        }).then(function successCallback(response) {
            $scope.userType = response.data ;   
        });
    }

    $scope.loadSupplier = function() {
        $http({
            method: 'get',
            url: $base_url + 'getSuppliersForPI'
        }).then(function successCallback(response) {
            $scope.suppliers = response.data;
        });
    }

    $scope.loadCustomer = function() {
        $http({
            method: 'get',
            url: $base_url + 'getCustomersForPI'
        }).then(function successCallback(response) {
            $scope.customers = response.data;
        });
    }

    $scope.loadPi = function(supplier,location) {

        $http({
            method: 'POST',
            url: $base_url + 'getPIs',
            data: { supId: supplier, cusId: location }
        }).then(function successCallback(response) {
            if (response.data != '') {
                $(document).ready(function() { $('#proformaVspiTable').DataTable(); });
                $scope.pi = response.data;
                $scope.pendingPi = true;
            } else {
                swal.fire({
                    title: infoTitle,
                    html: "<b> No Pending Matches for this supplier and location! </b>"
                })
                $scope.pendingPi = false;
            }

        });
        $scope.loadCrf();        
    }

    $scope.uploadPi = function(ev) {

        if($scope.userType == "Admin" || $scope.userType == "Accounting")
        {
            ev.preventDefault();
            var formData = new FormData(ev.target);

            $.ajax({
                type: "POST",
                url: $base_url + 'uploadPi',
                data: formData,
                enctype: 'multipart/form-data',
                cache: false,
                processData: false,
                contentType: false,
                success: function(response) {
                    if(response.info == 'Error-ext'){
                        Swal.fire({
                            title: warningTitle,
                            html: "<b> " + response.message +"<br> [" + response.ext + "] </b>"
                        }).then(function() {
                            location.reload();
                        })
                    } else if(response.info == "Error-item"){
                        var items = Object.values(response.item);                 
                        var itemNotFound = "";
                        for (let i = 0; i < items.length; i++) {
                            itemNotFound = items;                        
                        }
                        Swal.fire({
                            title: warningTitle,
                            html: "<b> " + response.message +" <br> <i> [" + itemNotFound + "] </i> </b>"
                        }).then(function() {
                            location.reload();
                        })
                    } else if(response.info == "Error"){
                        Swal.fire({
                            title: warningTitle,
                            html: "<b> " + response.message + " </b>"
                        }).then(function() {
                            location.reload();
                        })

                    } else if(response.info == "Success"){
                        Swal.fire({
                            title: successTitle,
                            html: "<b> " + response.message + " </b>"
                        }).then(function() {
                            location.reload();
                        })
                    }
                }
            });
        
        } else {
            swal.fire({
                title: warningTitle,
                html: "<b>Unathorized Account!</b>"                                           
            })
        }
    }

    $scope.checkExt = function(element) {
        $scope.piFile = element.files[0];
        var filename = $scope.piFile.name;
        var index = filename.lastIndexOf(".");
        var strsubstring = filename.substring(index, filename.length);
        if (strsubstring == ".txt" || strsubstring == ".TXT") {
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

    $scope.viewPiDetails = function(data) {

        $scope.loadData = data;
        var formData = { pi: data.piId }
        $scope.canUpdate = false;

        if ($.fn.DataTable.isDataTable('#viewPiLine')) {
            $scope.table.destroy();
            $scope.details = [];
        }

        $http({
            method: 'POST',
            url: $base_url + 'getPiDetails',
            data: { pi: data.piId }
        }).then(function successCallback(response) {
            $scope.details = response.data;
            $(document).ready(function() {
                setTimeout(function() {
                    $scope.table = $('#viewPiLine').DataTable({
                        destroy: true,
                        stateSave: true
                    });
                }, 100);
            });
        });
    }

    $scope.managersKey = function(ev) {
        ev.preventDefault();
        $("#managersKey").modal("show");
    }

    $scope.updateItem = function(ev) {
        ev.preventDefault();

        $.ajax({
            type: "POST",
            url: $base_url + 'managersKey',
            data: { user: $scope.user, pass: $scope.pass },
            cache: false,
            success: function(response) {
                if (response != null) {
                    if (response.userType == "Admin" || response.userType == "Supervisor") {
                        $scope.canEdit = true;
                        $("#managersKey").modal("hide");
                        $("#updateItemBtn").prop("disabled", true);                        

                    } else {
                        Swal.fire({
                            title: warningTitle,
                            html: "<b>Unauthorized Account!</b>"
                        }).then(function() {
                            location.reload();
                        })                    
                    }

                } else {                    
                    Swal.fire({
                        title: warningTitle,
                        html: "<b>Unauthorized Account!</b>"
                    }).then(function() {
                        location.reload();
                    })
                }
            }
        });
    }

    $scope.fetchItemPrice = function(data) {
        if ($scope.canEdit) {
            $scope.piLineId = data.pi_line_id;
            $scope.piHeadId = data.pi_head_id;
            $scope.itemCode = data.item_code;
            $scope.itemDesc = data.description;
            $scope.itemRemarks = data.remarks;
            $scope.itemQty = data.qty;
            $scope.newPrice = data.direct_unit_cost.replace(',', '');
            $scope.newAmount = data.amt_including_vat.replace(',', '');
            $scope.oldPrice = data.direct_unit_cost;
            $scope.oldAmount = data.amt_including_vat;
            $("#updatePrice").modal("show");
        }
    }

    $scope.updatePrice = function() {
        var formData = {
            piLineId: $scope.piLineId,
            piHeadId: $scope.piHeadId,
            itemCode: $scope.itemCode,
            itemQty: $scope.itemQty,
            newPrice: $scope.newPrice,
            newAmount: $scope.newAmount,
            oldPrice: $scope.oldPrice,
            oldAmount: $scope.oldAmount,
            remarks: $scope.itemRemarks
        }

        $.ajax({
            type: "POST",
            url: $base_url + 'updatePrice',
            data: formData,
            async: false,
            cache: false,
            success: function(response) {             
                Toast = $scope.toast(response.color);
                Toast.fire({
                      icon: response.info,
                      title: response.message
                })
                $('#updatePrice').modal('hide');
                $scope.viewPiDetails($scope.loadData);
            }
        });
    }

    $scope.calculate = function() {
        $scope.newAmount = ($scope.itemQty * 1) * ($scope.newPrice * 1);
    }

    $scope.closeViewPi = function() {
        var table = $("#viewPiLine").DataTable();
        table.state.clear();
        $("#updateItemBtn").prop("disabled", false);
        $scope.canEdit = false;
    }

    $scope.itemPricelog = function(data) {
        $scope.itemCode = data.item_code + ' - ' + data.description;
        $scope.quantity = data.qty;
        $scope.uom = data.uom;
        var formData = {
            piLineId: data.pi_line_id,
            piHeadId: data.pi_head_id,
            itemCode: data.item_code,
        }

        $http({
            method: 'POST',
            url: $base_url + 'getItemPriceLog',
            data: formData
        }).then(function successCallback(response) {
            if (response.data.length > 0) {
                $scope.pricelog = response.data;

            } else {
                $scope.pricelog = [];
                $scope.pricelog.push({ old_price: "0", old_amt: "0", changed_date: "No Data", username: "No Data" });
            }

        });
    }

    $scope.loadCrf = function() {
        $http({
            method: 'post',
            url: $base_url + 'getCrfInPI',
            data: { supId: $scope.supplierName, cusId: $scope.locationName }
        }).then(function successCallback(response) {

            $scope.crfs = response.data;
            $scope.profInCrf = [];
            $scope.profInCrf.push({ loc: "No Data", profCode: "No Data", delivery: "No Data", po: "No Data", total: "0.00" });
            $scope.proceedMatchProf = 0;
            $scope.piInCrf = [];
            $scope.piInCrf.push({ loc: "No Data", piNo: "No Data", postDate: "No Data", po: "No Data", total_amount: "0.00" });
            $scope.proceedMatchPi = 0;
            $scope.crfDate = "";
            $scope.crfAmount = "";
        });
    }

    $scope.loadProfPi = function(id,crfs) {
        $scope.crfId     = id;
        $scope.crfs      = crfs;
        var result       = crfs.find(({ crf_id }) => crf_id === id);
        $scope.crfDate   = result.crf_date;
        $scope.crfAmount = result.crf_amt;  
        $scope.sopId     = result.sop_id;    
        $scope.sopNo     = result.sop_no;  

        $http({
            method: 'post',
            url: $base_url + 'getProfPiInCrf',
            data: { crfId: $scope.crfId, supId: $scope.supplierName }
        }).then(function successCallback(response) {

            if (response.data.prof.length > 0) {
                $scope.profInCrf = response.data.prof;
                $scope.proceedMatchProf = 1;
            } else {
                $scope.profInCrf = [];
                $scope.profInCrf.push({ loc: "No Data", profCode: "No Data", delivery: "No Data", po: "No Data", total: "0.00" });
                $scope.proceedMatchProf = 0;
            }
            if (response.data.pi.length > 0) {
                $scope.piInCrf = response.data.pi;
                $scope.proceedMatchPi = 1;
            } else {
                $scope.piInCrf = [];
                $scope.piInCrf.push({ loc: "No Data", piNo: "No Data", postDate: "No Data", po: "No Data", total_amount: "0.00" });
                $scope.proceedMatchPi = 0;
            }
        });

        $scope.selectVendorsDeal = null;
        $scope.periodFrom        = null;
        $scope.periodTo          = null;
        $scope.loadDeals($scope.supplierName);
    }

    $scope.loadDeals = function(supplier)
    {   
        $http({
            method: 'post',
            url: `${$base_url}loadVendorsDeal` , 
            data: { supId: supplier }
        }).then(function successCallback(response) {
            $scope.vendorsDeal = response.data;
        });
    }

    $scope.displayVendorsdDealToInput = function(dealId, deals)
    {
        var result        = deals.find(({ vendor_deal_head_id }) => vendor_deal_head_id === dealId);
        $scope.periodFrom = result.period_from ;
        $scope.periodTo   = result.period_to ;        
    }

    $scope.tag = function(data) {

        if($scope.userType == "Admin" || $scope.userType == "Accounting")
        {
            $scope.piId = data.piId;
            $("#tagPi").modal('show');

        } else {
            swal.fire({
                title: warningTitle,
                html: "<b>Unathorized Account!</b>"                                           
            })
        }

    }

    $scope.applyPi = function(ev) {
        ev.preventDefault();

        var form = $("#applyPiForm")[0];
        var formData = new FormData(form);
        formData.append('pi', $scope.piId);
        formData.append('supId', $scope.supplierName);

        $.ajax({
            type: "POST",
            url: $base_url + 'applyPiToCrf',
            data: formData,
            async: false,
            cache: false,
            processData: false,
            contentType: false,
            success: function(response) {                
                Toast = $scope.toast(response.color);
                Toast.fire({
                      icon: response.info,
                      title: response.message
                })
                $scope.loadProfPi($scope.crfId, $scope.crfs);
            }
        });
    }

    $scope.untagPi = function(ev)
    {
        if($scope.piId != undefined && $scope.crf != undefined )
        {
            ev.preventDefault();
            $http({
                method: 'post',
                url: $base_url + 'untagPiFromCrf',
                data: { piId: $scope.piId, crfId: $scope.crf }
            }).then(function successCallback(response) {
                Toast = $scope.toast(response.data.color);
                Toast.fire({
                      icon: response.data.info,
                      title: response.data.message
                })
                $scope.loadProfPi($scope.crfId, $scope.crfs);
            });

        } else {
            swal.fire({
                title: warningTitle,
                html: "<b>Pi No or CRF No is unknown!</b>"                                           
            })
        }
    }

    $('#managersKey').on('hidden.bs.modal', function() {
        $("#user").val('');
        $("#pass").val('');
    });

    $('#tagPi').on('hidden.bs.modal', function(e) {
        $scope.crf = null;
        $scope.loadPi($scope.supplierName, $scope.locationName) 
        // location.reload();
    });
    


    $scope.matchProformaVsPi = function(data, ev) {
        ev.preventDefault();
        var crfId = data;

        Swal.fire({
            title: warningTitle,
            html: "<b>Match PROFORMA vs PI ? </b>",
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: "<i class='fas fa-thumbs-up'></i> Yes",
            cancelButtonText: "<i class='fas fa-thumbs-down'></i> No",
            customClass: { confirmButton: "btn btn-outline-success",cancelButton: "btn btn-light" }
        }).then((result) => {
            if (result.isConfirmed) {                

                (async () => {
                    const { value: type } = await Swal.fire({
                      title: 'Select File To Generate',
                      input: 'select',
                      inputOptions: {
                        pdf: 'PDF',
                        excel: 'Excel'
                      },
                      inputPlaceholder: 'Required',
                      showCancelButton: true,
                      inputValidator: (value) => {
                        return new Promise((resolve) => {
                          if (value !== '') {
                            resolve()
                          } else {
                            resolve('You need to select a file type to generate!')
                          }
                        })
                      }
                    })
                    
                    if (type) {
                        $('button').prop('disabled', true); 
                        $.ajax({
                            type: "POST",
                            url: $base_url + 'matchProformaVsPi',
                            data: { crfId: crfId, supId: $scope.supplierName , cusId: $scope.locationName, dealId: $scope.selectVendorsDeal,type: type },
                            cache: false,
                            beforeSend: function() {
                                $("#btnMatch").html(`<span class="spinner-grow spinner-grow-sm" role="status"></span>  Matching ... `);
                            },
                            success: function(response) {    
                                if (response.info == "incomplete") {
                                    Swal.fire({
                                        title: warningTitle,
                                        html: '<b>' + response.message + '</b>'
                                    })    
                                } else if (response.info == "success") {
                                    var unPairedPos = "";
                                    if( response.wayParesPoProf.length > 0 ){
                                        unPairedPos = "<strong>UNPAIRED PO in :<br> PROFORMA </strong> <br> <i>" + response.wayParesPoProf + "</i>" ;                                
                                    } else if ( response.wayParesPoPi.length > 0) {
                                        unPairedPos = "<strong>UNPAIRED PO in :<br> PI </strong> <br> <i>" + response.wayParesPoPi + "</i>" ;
                                    } else if (response.wayParesPoPi.length > 0 || response.wayParesPoProf.length > 0 ) {
                                        unPairedPos = "<strong>UNPAIRED PO in :<br> PROFORMA </strong> <br> <i>" + response.wayParesPoProf + "</i> <br> <strong>PI </strong> <br> <i>" + response.wayParesPoPi + "</i>" ;
                                    }    
                                    if(response.wayParesPoPi.length > 0 || response.wayParesPoProf.length > 0)  {
                                        
                                        Swal.fire({
                                            title: infoTitle,
                                            html: unPairedPos          
                                        }).then(function(){
                                            Swal.fire({
                                                title: successTitle,
                                                html: '<b>' + response.message + '</b>'
                                            }).then(function() {
                                                window.open($base_url + 'files/Reports/ProformaVsPi/' + response.file);
                                                location.reload();
                                            })
                                        })
                                    } else {
                                        Swal.fire({
                                            title: successTitle,
                                            html: '<b>' + response.message + '</b>'
                                        }).then(function() {
                                            window.open($base_url + 'files/Reports/ProformaVsPi/' + response.file)
                                            location.reload();
                                        })
                                    } 
                                } else if(response == "no data") {
                                    swal.fire({
                                        title: warningTitle,
                                        html: "<b>No Data To Match!</b>"                                           
                                    })
                                } else if(response == "item not found") {
                                    swal.fire({
                                        title: warningTitle,
                                        html: "<b>Item(s) in Proforma not found in Masterfile!</b>"  
                                    }).then(function() {
                                        location.reload();
                                    })
                                }
                            },
                            complete: function() {
                                $("#btnMatch").html(` <i class="fas fa-link"></i> Match PROFORMA VS PI  `);
                                $('button').prop('disabled', false); 
                            }
                        });
                    }                    
                })()
                     
               
            }
        })
    }

    $scope.changeStatus = function(data)
    {
        var piId = data.piId;
        if($scope.userType == "Admin" || $scope.userType == "Accounting")
        {
            Swal.fire({
                title: warningTitle,
                html: "<b>Change Status to <strong>MATCHED </strong> ? <br> You won't be able to revert this! </b>",
                buttonsStyling: false,
                showCancelButton: true,
                confirmButtonText: "<i class='fas fa-thumbs-up'></i> Yes",
                cancelButtonText: "<i class='fas fa-thumbs-down'></i> Cancel",
                customClass: { confirmButton: "btn btn-outline-success",cancelButton: "btn btn-light" }
            }).then((result) => {
                if (result.isConfirmed) 
                {
                    $http({
                        method: 'post',
                        url: $base_url + 'changeStatus',
                        data: { piId: piId  }
                    }).then(function successCallback(response) 
                    {
                        if(response.data == "success")
                        {
                            Swal.fire({
                                title: successTitle,
                                html: '<b> PI status changed to MATCHED! </b>'               
                            })  
                        } else {
                            Swal.fire({
                                title: warningTitle,
                                html: "<b> Failed to change status!</b>"
                            })    
                        } 
                        $scope.loadPi() ;     
                    });
                }
            })
            
        } else {
            swal.fire({
                title: warningTitle,
                html: "<b>Unathorized Account!</b>"                                           
            })
        }
    }

    $scope.matchItems = function(crfId)
    {
        $http({
            method: 'post',
            url: $base_url + 'viewMatchedUnmatchedItems',
            data: { crfId: crfId  }
        }).then(function successCallback(response) 
        {
            console.log(response.data);  
            $scope.sameItemSamePo = response.data;
        });
    }

    $scope.applyCm = function(data)
    {
        $scope.piId = data.piId;
        $scope.piNo = data.pi_no;
    }

    $scope.uploadCm = function(ev)
    {
        ev.preventDefault();
        var form = $("#uploadCM")[0];
        var formData = new FormData(form);

        Swal.fire({
            title: warningTitle,
            html: "<b>Are you sure to apply CM in this PI ? <br> You won't be able to revert this! </b>",
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: "<i class='fas fa-thumbs-up'></i> Yes",
            cancelButtonText: "<i class='fas fa-thumbs-down'></i> No",
            customClass: { confirmButton: "btn btn-outline-success",cancelButton: "btn btn-light" }
        }).then((result) => {
            if (result.isConfirmed) 
            {
                $.ajax({
                    type: "POST",
                    url: $base_url + 'uploadCm',
                    data: formData,
                    async: false,
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        var icon = ""
                        if(response.info == "Error"){
                            icon = warningTitle;
                        } else if(response.info == "Success"){
                            icon = successTitle ;
                        }
    
                        Swal.fire({
                            title: icon,
                            html: '<b> ' + response.message + ' </b>'               
                        }).then(function() {
                            location.reload();
                        })
                    }
                });
            }
        })

           
    }

    $scope.viewCmDetails = function(data)
    {
        $scope.cmNo = data.cm_no;
        $scope.cmPostingDate = data.posting_date;
        $scope.cmPI = data.pi_no ;

        $http({
            method: 'post',
            url: $base_url + 'viewCMDetails',
            data: { cmId: data.cm_head_id  }
        }).then(function successCallback(response) 
        {
            var total = 0;
            $scope.cmDetails = response.data;
            angular.forEach($scope.cmDetails, function(value, key) {
                total += parseInt(value.qty) * parseFloat(value.price);
            });
            $scope.cmAmount = total.toFixed(2);
        });
    }

    

});