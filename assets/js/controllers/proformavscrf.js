window.myApp.controller('proformavscrf-controller', function($scope, $http, $window) {
    const warningTitle = "<i class='fas fa-exclamation-triangle fa-lg' style='color:#e65c00'></i>";
    const successTitle = "<i class='fas fa-check-circle fa-lg' style='color:#28a745'></i>";
    const infoTitle    = "<i class='fas fa-info-circle fa-lg' style='color:#005ce6'></i>";
    const confirmButtonIcon = "<i class='fas fa-thumbs-up'></i>";
    const cancelButtonIcon  = "<i class='fas fa-thumbs-down'></i>";

    const ToastSuccess = Swal.mixin({
                            toast: true,
                            position: 'top-right',
                            iconColor: 'green',
                            customClass: { popup: 'toasts-top-right' },
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true })
    const ToastError = Swal.mixin({
                            toast: true,
                            position: 'top-right',
                            iconColor: 'red',
                            customClass: { popup: 'toasts-top-right'},
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true})

    $scope.userType = "";
    $scope.hiddenSupplier = "";
    $scope.crfId = "";
    $scope.crfNo = "";
    $scope.crfAmount = "";
    $scope.proformaId = "";
    $scope.proceedMatch = 0; /* for matching    */
    $scope.proceedApply = 0; /* for tagging proforma    */
    $scope.hasResults = 0;   /* for searching untagged proforma  */

    $scope.checkUserType = function()
    {
        $http({
            method : 'get',
            url    : $base_url + 'checkUserTypeCrf'
        }).then(function successCallback(response){
            $scope.userType = response.data ;
        });       
    }

    $scope.getSuppliers = function() 
    {
        $http({
            method: 'get',
            url: $base_url + 'getSuppliersForCRF'
        }).then(function successCallback(response) {
            $scope.suppliers = response.data;
        });
    }

    $scope.getSop = function(supplier, customer)
    {
        $http({
            method: 'post',
            url: '../transactionControllers/proformavscrfcontroller/getSop/' + supplier + '/' + customer
        }).then(function successCallback(response) {
            $scope.sops = response.data;
        });
    }

    $scope.getCustomers = function() 
    {
        $http({
            method: 'get',
            url: $base_url +  'getCustomersForCRF'
        }).then(function successCallback(response) {
            $scope.customers = response.data;
        });
    }

    $scope.getCrfs = function() 
    {
        $http({
            method: 'POST',
            url: $base_url + 'getCrfs',
            data   : { supId: $scope.supplierName, cusId: $scope.locationName }
        }).then(function successCallback(response) {
            if(response.data != '')
            {
                $(document).ready(function() { $('#crfTable').DataTable(); });
                $scope.crf = response.data;
                $scope.pendingCrf = true;
            }else
            {
                swal.fire({
                    title: infoTitle,
                    html: "<b> No Pending Matches for this supplier and location! </b>"
                   })
               $scope.pendingCrf = false;
              
            }
           
        });
    }

    $scope.checkExt = function(element) {
        $scope.crfFile = element.files[0];
        var filename = $scope.crfFile.name;
        var index = filename.lastIndexOf(".");
        var strsubstring = filename.substring(index, filename.length);
        if (strsubstring == ".txt" || strsubstring == ".TXT") {
            console.log("allowed");

        } else {

            Swal.fire({
                title: warningTitle,
                html: "<b> Invalid file extension! </b>"
            }).then(function(){
                location.reload();
            })
        }
    }

    $scope.uploadCrf = function(ev) {

        if($scope.userType == "Admin" || $scope.userType == "Accounting")
        {
            ev.preventDefault();

            var form = $("#uploadProCrf")[0];
            var formData = new FormData(form);

            $.ajax({
                type: "POST",
                url: $base_url + 'uploadCrf',
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                success: function(response) {
                
                    if (response == "success") {
                        Swal.fire({
                            title: successTitle,
                            html: '<b> CRF is uploaded successfully! </b>'               
                        }).then(function(){
                            location.reload();
                        })

                    } else if (response == "exists") {
                        Swal.fire({
                            title: warningTitle,
                            html: "<b> CRF already exists!</b>"
                        }).then(function(){
                            location.reload();
                        })
                        
                    } else if (response == "incomplete"){
                        Swal.fire({
                            title: warningTitle,
                            html: "<b> Uploading is incomplete!</b>"
                        }).then(function(){
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

    $scope.applyProforma = function(data) 
    {
        if($scope.userType == "Admin" || $scope.userType == "Accounting")
        {
                $scope.hiddenSupplier = data.supplier_id;
                $scope.crfId     = data.crf_id;
                $scope.crfNo     = data.crf_no;
                $scope.crfDate   = data.crf_date;
                $scope.crfAmount = data.crf_amt;
                $scope.sopNo = data.sop_no
                $scope.sName = data.supplier_name;
                $scope.hasDeal = data.has_deal ;
                $scope.loadApplied();
                $("#applyProformaToCrf").modal('show');
        }else {
            swal.fire({
                title: warningTitle,
                html: "<b>Unathorized Account!</b>"                                           
            })
        }       
    }

    $scope.searchProf = function(ev)
    {
        ev.preventDefault();
        var string = $("#searchProforma").val();
        if(string == '') 
        {
            $(".search-results").hide();
            $scope.proceedApply = 0;
        }else
        {
            $http({
                method: 'post',
                url:'../transactionControllers/proformavscrfcontroller/getUnAppliedProforma/' + $scope.hiddenSupplier + '/' + $scope.crfId,
                data: { str: string }
            }).then(function successCallback(response) {
                $scope.searchResult = response.data;
                if($scope.searchResult.length == 0)
                {
                    $scope.hasResults = 0 ;
                    $scope.proceedApply = 0;
                    $scope.searchResult.push( { proforma_header_id: "No Results Found" } );
                }else
                {
                    $scope.hasResults = 1 ;
                    $scope.searchResult = response.data;                    
                }                
            });            
        }
        
    }

    $scope.getProf = function(prof) 
    {
        $("#searchProforma").val(prof.proforma_header_id + "-" + prof.proforma_code + "-" + prof.delivery_date + "-" + prof.so_no + "-" + prof.order_no + "-" + prof.po_no + "-" + prof.po_reference);
        $(".search-results").hide();
        $scope.proformaId = prof.proforma_header_id;
        $scope.proceedApply = 1;
    }

 
    $scope.loadApplied = function() 
    {
        $http({
            method: 'post',
            url: '../transactionControllers/proformavscrfcontroller/getAppliedProforma/' + $scope.crfId + '/' + $scope.hiddenSupplier
        }).then(function successCallback(response) {
    
            if (response.data.profs.length > 0) {
                $scope.applied = response.data.profs;
                $scope.proceedMatch = 1;

            } else {
                $scope.applied = [];
                $scope.applied.push( { proforma_code: "No Data", delivery_date: "No Data", po_no: "No Data", item_total : '0.00', add_less : '0.00', total: '0.00' } );
                $scope.proceedMatch = 0;
            }
            $scope.vendorsDeal = response.data.deal;
            $scope.resetProfVsCrf();
        });
    }

    $scope.displayVendorsdDealToInput = function(dealId, deals)
    {
        var result        = deals.find(({ vendor_deal_head_id }) => vendor_deal_head_id === dealId);
        $scope.periodFrom = result.period_from ;
        $scope.periodTo   = result.period_to ;        
    }

    $scope.applyProf = function() 
    {      
        var id = $scope.proformaId; /* from getProf function */
        if (angular.isUndefined(id) || !id) {
            Swal.fire({
                title: warningTitle,
                html: '<b>' + "No Data to Tag!" + '</b>'
            })
        } else {
            Swal.fire({
                title: warningTitle,
                html: "<b>Are you sure to <strong>TAG </strong> this proforma ? </b>",
                buttonsStyling: false,            
                showCancelButton: true,
                confirmButtonText: confirmButtonIcon + " Yes",
                cancelButtonText: cancelButtonIcon + " No",
                customClass:{ confirmButton: "btn btn-outline-success", cancelButton: "btn btn-light" }  
            }).then((result) => {
                if (result.isConfirmed) 
                {
                    $http({
                        method: 'post',
                        url: '../transactionControllers/proformavscrfcontroller/applyProforma/' + $scope.crfId + '/' + $scope.hiddenSupplier,
                        data: { id: id }
                    }).then(function successCallback(response) {
                        if (response.data == "success") {                           
                            ToastSuccess.fire({
                                icon: 'success',
                                title: 'Proforma is tagged under this CRF!'
                            })
        
                        } else {                           
                            ToastError.fire({
                                icon: 'error',
                                title: 'Proforma is already tagged!'
                            })
                        }
                        $scope.loadApplied();
                        $("#searchProforma").val("");
                        $scope.proceedApply = 0;
                    });                
                }
            });
        }
    }

    $scope.untagProforma = function(data)
    {
        Swal.fire({
            title: warningTitle,
            html: "<b>Are you sure to <strong> UNTAG " + data.proforma_code +" </strong> ? </b>",
            buttonsStyling: false,            
            showCancelButton: true,
            confirmButtonText: confirmButtonIcon + " Proceed",
            cancelButtonText: cancelButtonIcon + " Cancel",
            customClass:{ confirmButton: "btn btn-outline-success", cancelButton: "btn btn-light" }  
        }).then((result) => {
            if (result.isConfirmed) 
            {
                $http({
                    method: 'post',
                    url: $base_url + 'untagProforma',
                    data: { profId: data.proforma_header_id, supId: data.supplier_id, crfId: $scope.crfId }
                }).then(function successCallback(response) 
                {
                    if (response.data == "success") {
                        ToastSuccess.fire({
                            icon: 'success',
                            title: 'Proforma is untagged under this CRF!'
                        })
    
                    } else if (data.response == "failed") {
                        ToastError.fire({
                            icon: 'error',
                            title: 'Failed to untag proforma!'
                        })
                    }
                    $scope.loadApplied();                          
                });    
            }
        })
    }

    $scope.matchProformaVsCrf = function(ev) 
    {
        if($scope.userType == "Admin" || $scope.userType == "Accounting")
        {
            ev.preventDefault();
            if ($scope.proceedMatch == 1) {
                Swal.fire({
                    title: warningTitle,
                    html: "<b>Match PROFORMA vs CRF ? </b>",
                    buttonsStyling: false,            
                    showCancelButton: true,
                    confirmButtonText: confirmButtonIcon + " Yes",
                    cancelButtonText: cancelButtonIcon + " No",
                    customClass:{ confirmButton: "btn btn-outline-success", cancelButton: "btn btn-light"}  
                }).then((result) => {
                    if (result.isConfirmed) 
                    {
                        $("#btnTag").prop('disabled', true);
                        $("#btnMatch").prop('disabled', true);
                        $("#btnClose").prop('disabled', true);
                        $('#selectProforma').attr("disabled", true);
                        $.ajax({
                            type: "POST",
                            url: $base_url + 'matchProformaVsCrf',
                            data: { crf: $scope.crfId, dealId: $scope.vendorsdeal },
                            cache: false,
                            beforeSend: function() {
                                $("#btnMatch").html(`<span class="spinner-grow spinner-grow-sm" role="status"></span>  Matching ... `);
                            },
                            success: function(response) 
                            {
                                if (response.info == "incomplete") {
                                    Swal.fire({
                                        title: warningTitle,
                                        html: '<b>' + response.message + '</b>'               
                                    })
                                } else if (response == "itemsetup-error") {
                                    Swal.fire({
                                        title: warningTitle,
                                        html: '<b> Division/Dept/Group Code is missing in the item setup!  </b>'               
                                    })
                                
                                } else if (response.info == "success") {
                                    Swal.fire({
                                        title: successTitle,
                                        html: '<b>' + response.message + '</b>'
                                    }).then(function() {
                                        window.open($base_url + 'files/Reports/ProformaVsCrf/' + response.file);
                                        $("#applyProformaToCrf").modal('hide');
                                        // location.reload();
                                    })
                                }
                            },
                            complete: function() {
                                $("#btnMatch").html(` <i class="fas fa-link"></i> Match PROFORMA VS CRF  `);
                                $("#btnTag").prop('disabled', false);
                                $("#btnMatch").prop('disabled', false);
                                $("#btnClose").prop('disabled', false);
                                $scope.proceedApply = 0 ;
                            }
                        });
                    }
                })
            } else {
                Swal.fire({
                    title: warningTitle,
                    html: '<b>' + "No Data to Match!" + '</b>'
                })
            } 
        } else {
            swal.fire({
                title: warningTitle,
                html: "<b>Unathorized Account!</b>"                                           
            }) 
        }
    }

    $scope.resetProfVsCrf = function()
    {
        $scope.vendorsdeal = null;
        $scope.periodFrom  = null;
        $scope.periodTo    = null;

    }

    $scope.changeStatus = function(crf)
    {   
        if($scope.userType == "Admin" || $scope.userType == "Accounting")
        {
            var crfId = crf.crf_id ;
            Swal.fire({
                title: warningTitle,
                html: "<b>Change Status to <strong>MATCHED </strong> ? <br> You won't be able to revert this! </b>",
                buttonsStyling: false,            
                showCancelButton: true,
                confirmButtonText: confirmButtonIcon + " Yes",
                cancelButtonText: cancelButtonIcon + " Cancel",
                customClass:{ confirmButton: "btn btn-outline-success", cancelButton: "btn btn-light"}  
            }).then((result) => {
                if (result.isConfirmed) 
                {
                    $http({
                        method: 'post',
                        url: $base_url + 'changeStatusToMatched',
                        data: { crfId: crfId  }
                    }).then(function successCallback(response) 
                    {
                        if(response.data == "success")
                        {
                            Swal.fire({
                                title: successTitle,
                                html: '<b> CRF status changed to MATCHED! </b>'               
                            })                       
                            
                        }  else {
                            Swal.fire({
                                title: warningTitle,
                                html: "<b> Failed to change status!</b>"
                            })                        
                        }      
                        $scope.getCrfs();           
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

    $scope.closeCrf = function() {
        // $("#uploadProCrf").trigger("reset");
        $scope.selectSupplier   = null;
        $scope.selectCustomer   = null;
        $scope.selectSop        = null;
        $scope.sops             = null;        
        $("#crfFile").val('');
      
    }

    $scope.closeApplyProforma = function() {
        $("#applyProforma").trigger("reset");
    }


});