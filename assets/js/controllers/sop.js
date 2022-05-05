window.myApp.controller('sop-controller', function($scope, $http, $window) {


    const warningTitle = "<i class='fas fa-exclamation-triangle fa-lg' style='color:#e65c00'></i>";
    const successTitle = "<i class='fas fa-check-circle fa-lg' style='color:#28a745'></i>";
    const infoTitle = "<i class='fas fa-info-circle fa-lg' style='color:#005ce6'></i>";
    const confirmButtonIcon = "<i class='fas fa-thumbs-up'></i> ";
    const cancelButtonIcon = "<i class='fas fa-thumbs-down'></i>";
    const confirmButtonClass = "btn btn-outline-success";
    const cancelButtonClass = "btn btn-light";

    $scope.crfId = "";
    $scope.sopNo = "";
    $scope.sopId = "";
    $scope.supplierName = "";
    $scope.userType = "";
    $scope.proceedApply = 0;

    $scope.totalInvoiceAmount = 0;
    $scope.totalDeductionAmount = 0;
    $scope.totalChargesAmount = 0;
    $scope.totalNetPayableAmount = 0;
    $scope.amountToBeDeducted = 0;
    $scope.inputted = false;
    $scope.deductionAmountInputted = 0;

    $scope.sopTotalInvoiceAmount = 0;
    $scope.sopTotalDeductionAmount = 0;
    $scope.sopTotalChargesAmount = 0;
    $scope.sopTotalNetPayableAmount = 0;

    $scope.mes = function()
    {
        swal.fire({
            title: infoTitle,
            html: "<b>This transaction is not available at the moment!</b>"
        })
    }

    $scope.checkUserType = function() 
    {
        $http({
            method: 'get',
            url: $base_url + 'checkUserTypeSOP'
        }).then(function successCallback(response) {
            $scope.userType = response.data;
        });
    }

    $scope.getSuppliers = function() 
    {
        $http({
            method: 'get',
            url: $base_url + 'getSuppliersSop'
        }).then(function successCallback(response) {
            $scope.suppliers = response.data;
        });
    }

    $scope.getSupplierName = function() 
    {
        $http({
            method: 'post',
            url: $base_url + 'getSupplierName',
            data: { supId: $scope.selectSupplier }
        }).then(function successCallback(response) {
            $scope.supplierName = response.data;
        });
    }

    $scope.getCustomers = function() 
    {
        $http({
            method: 'get',
            url: $base_url + 'getCustomersSop'
        }).then(function successCallback(response) {

            $scope.customers = response.data;
        });
    }

  
    $scope.sopHead = [];
    $scope.sopDeduction = [];
    $scope.viewSopDeductions = [];


    $scope.isChecked = function(data, ev) 
    {
        if (ev.target.checked == true) {
            $scope.sopDeductionToUpload.push(data);
        } else {
            $scope.sopDeductionToUpload.splice(data, 1);
        }

        if ($scope.sopDeductionToUpload.length > 0) {
            $scope.proceedUpload = true;
        } else {
            $scope.proceedUpload = false;
        }
    }

    $scope.getDetail = function(selectedSupplier, suppliers)
    {
        var result     = suppliers.find(({ supplier_id }) => supplier_id === selectedSupplier);
        $scope.hasDeal = result.has_deal;
        $scope.vendorsDeal= "";
        $scope.periodFrom = "";
        $scope.periodTo   = "";

        if($scope.hasDeal == "0"){ //no deals
            $scope.loadSONos(0);
        } else if($scope.hasDeal == "1"){
            $scope.loadDeals(selectedSupplier);
        }
    }

    $scope.loadDeals = function(supplier)
    {
        $http({
            method: 'post',
            url: $base_url + 'loadVendorsDeal',
            data: { supId: supplier }
        }).then(function successCallback(response) {
            $scope.deals = response.data;
            $scope.resetNewInvoice();
            $scope.selectCustomerNewSop = null;
            $scope.SOPInvoiceData = [{}];
            $scope.DeductionData = [{}];
            $scope.ChargesData = [{}];
            $scope.SONOs = [{}];
            $scope.deductionType = [{}];
            $scope.deductionNames = [{}];
            $scope.chargesType = [{}];
            $scope.totalInvoiceAmount = 0;
            $scope.totalDeductionAmount = 0;
            $scope.totalChargesAmount = 0;
            $scope.totalNetPayableAmount = 0;
            $scope.checkDeduction = false;
            $scope.checkCharges = false;
        });
    }

    $scope.displayToInputDeal = function(selectedDeal, deals)
    {
        // console.log(deals);
        var result = deals.find(({ vendor_deal_head_id }) => vendor_deal_head_id === selectedDeal);
        if(result !== undefined){
            $scope.periodFrom = result.period_from;
            $scope.periodTo   = result.period_to;            
            $scope.loadSONos(selectedDeal);
        }
       
    }

    $scope.loadSONos = function(selectedDeal) 
    {
        $http({
            method: 'post',
            url: $base_url + 'loadSONos',
            data: { supId: $scope.selectSupplierNewSop, dealId: selectedDeal } 
        }).then(function successCallback(response) {
            $scope.SONOs = response.data.SONOs;
            $scope.itemMapping = response.data.items;
        });
    }

    $scope.closemyModal6 = function()
    {
        // $scope.SONOs =  null;
        // $scope.itemMapping = null;
    }

    $scope.displayToInput = function(profId, soData) 
    {
        var result = soData.find(({ proforma_header_id }) => proforma_header_id === profId);
        if(result !== undefined){
            $scope.invoiceDate = result.order_date;
            $scope.invoiceAmount = result.amount;
        }
    }

    $scope.addNewInvoiceToTable = function(ev, profIdd, SOData) 
    {
        ev.preventDefault();

        var findSOData = SOData.find(({ proforma_header_id }) => proforma_header_id === profIdd);
        var checkInvoiceDupes = $scope.SOPInvoiceData.find(({ profId }) => profId === profIdd);

        if (checkInvoiceDupes == undefined) {
            $scope.SOPInvoiceData.push({
                'profId': findSOData.proforma_header_id,
                'invoiceNo': findSOData.so_no,
                'invoiceDate': findSOData.order_date,
                'poNo': findSOData.po_no,
                'poDate': findSOData.poDate,
                'invoiceAmount': findSOData.amount
            });
            
            $scope.invoiceNo     = null;
            $scope.invoiceDate   = null;
            $scope.invoiceAmount = null;
            $('#myModal2').modal('hide')
            $scope.calculateTotals();

        } else {
            swal.fire({
                title: warningTitle,
                html: "<b>Invoice is already added!</b>"
            })
        }


    }

    $scope.calculateTotals = function() 
    {
        var totalInvoice = 0;
        var totalDed = 0;
        var totalCharge = 0;
        angular.forEach($scope.SOPInvoiceData, function(value, key) {
            if (!isNaN(value.invoiceAmount)) {
                totalInvoice += value.invoiceAmount * 1;
            }
        });
        $scope.totalInvoiceAmount = totalInvoice;

        angular.forEach($scope.DeductionData, function(value, key) {
            if (!isNaN(value.dedAmount)) {
                totalDed += value.dedAmount;
            }
        });
        $scope.totalDeductionAmount = totalDed;

        angular.forEach($scope.ChargesData, function(value, key) {
            if (!isNaN(value.chargeAmount)) {
                totalCharge += value.chargeAmount * 1;
            }

        });
        $scope.totalChargesAmount = totalCharge;

        $scope.totalNetPayableAmount = totalInvoice + totalCharge + totalDed;
        return $scope.totalInvoiceAmount, $scope.totalDeductionAmount, $scope.totalChargesAmount, $scope.totalNetPayableAmount;

    }

    $scope.calculateToBeDeductedAmount = function() 
    {

        $scope.amountToBeDeducted = $scope.totalInvoiceAmount + $scope.totalChargesAmount + $scope.totalDeductionAmount;
        return $scope.amountToBeDeducted;
    }

    $scope.loadDeductionType = function() 
    {
        $http({
            method: 'get',
            url: $base_url + 'loadDeductionType'
        }).then(function successCallback(response) {
            $scope.deductionType = response.data;
        });
    }

    $scope.loadDeductionNames = function(type) 
    {
        $scope.deductionAmount = 0;
        $http({
            method: 'post',
            url: $base_url + 'loadDeduction',
            data: { typeId: type, supId: $scope.selectSupplierNewSop  }
        }).then(function successCallback(response) {
            $scope.deductionNames = response.data;
            $('input[name="customRadio"]').prop('checked', false);
            $scope.deductionAmount    = 0;
            $scope.amountToBeDeducted = 0;
        });
    }

    $scope.loadDeductionDetails = function(selected,selection)
    {        
        var findDeductionData = selection.find(({ deduction_id }) => deduction_id === selected);
        if(findDeductionData !== undefined){
            $scope.useForDisplay  = findDeductionData.name_used_for_display;
            $scope.inputted       = findDeductionData.inputted == 0 ? false : true ;      
            $('input[name="customRadio"]').prop('checked', false);
            $scope.deductionAmount    = 0;
            $scope.amountToBeDeducted = 0; 
        }         
    }   

    $scope.searchSOP = function(ev)
    {
        ev.preventDefault();
        var string = $("#sopInvoice").val();
        $scope.searchResult = {};
        if(string == '') 
        {
            $(".search-results").hide();
        }else
        {
            $http({
                method: 'post',
                url: $base_url + 'searchSOP',
                data: { str: string, supId: $scope.selectSupplierNewSop  }
            }).then(function successCallback(response) {
                $scope.searchResult = response.data;
                if($scope.searchResult.length == 0)
                {
                    $scope.hasResults = 0 ;
                    $scope.searchResult.push( { id: "No Results Found" } );
                }else
                {
                    $scope.hasResults = 1 ;
                    $scope.searchResult = response.data;                    
                }                
            });            
        }
    }

    $scope.sopInvLineId = 0;
    $scope.getSOPInv = function(data)
    {
        $("#sopInvoice").val(data.sop_no + "-" + data.so_no );
        $(".search-results").hide();
        $scope.sopInvLineId = data.id;
    }

    $scope.calculateDeduction = function(selectedType) 
    {

        // console.log(selectedType);
        // console.log($scope.inputted);
        if(!$scope.inputted ){
            if(selectedType == 1){ //net
                var invoiceData = JSON.parse(angular.toJson($scope.SOPInvoiceData));
                $http({
                    method: 'post',
                    url: $base_url + 'forRegDiscount',
                    data: { invoice: invoiceData, supId: $scope.selectSupplierNewSop, dedId: $scope.selectedDeductionName, dealId: $scope.vendorsDeal }
                }).then(function successCallback(response) {
                    $scope.amountToBeDeducted = response.data;
                    $scope.getDeductionAmount($scope.amountToBeDeducted, $scope.selectedDeductionName);
                });

            } else if(selectedType == 2){ //gross not diminishing
                $scope.amountToBeDeducted = $scope.totalInvoiceAmount ;
                $scope.getDeductionAmount($scope.amountToBeDeducted, $scope.selectedDeductionName);

            } else if(selectedType == 3){ //gross diminishing
                $scope.amountToBeDeducted = $scope.totalInvoiceAmount + $scope.totalChargesAmount + $scope.totalDeductionAmount;
                $scope.getDeductionAmount($scope.amountToBeDeducted, $scope.selectedDeductionName);
            }
        }
    }

    $scope.getDeductionAmount = function(toBeDeducted, dedId) 
    {
        $http({
            method: 'post',
            url: $base_url + 'calculateDeduction',
            data: { amount: toBeDeducted, discountId: dedId }
        }).then(function successCallback(response) {
            $scope.deductionAmount = response.data;
        });
    }

    $scope.addNewDeductionToTable = function(ev) 
    {
        ev.preventDefault();
        var selected = $scope.deductionNames.find(({ deduction_id }) => deduction_id === $scope.selectedDeductionName);
        var inv = $("#sopInvoice").val();
        var newDeduction = {};

        newDeduction.dedId     = selected.deduction_id;
        // newDeduction.sopInvId  = $scope.sopInvoice === null? 0 : $scope.sopInvoice ;
        // newDeduction.dedName   = selected.name_used_for_display + ' ' + ($scope.remarksDed === null ? '(' + $scope.numberWithCommas($scope.amountToBeDeducted.toFixed(2)) + ')' : $scope.remarksDed) 
        newDeduction.dedAmount = selected.inputted == 1 ? $scope.deductionAmountInputted.toFixed(2) * -1 : $scope.deductionAmount.toFixed(2) * -1;
        if( ($scope.sopInvoice === undefined || $scope.sopInvoice === null) && ($scope.remarksDed === undefined || $scope.remarksDed === null) ){
            newDeduction.sopInvId  =  0  ;
            newDeduction.dedName   = selected.name_used_for_display + ' ' +  '(' + $scope.numberWithCommas($scope.amountToBeDeducted.toFixed(2)) + ')'
        } else if( ($scope.sopInvoice === undefined || $scope.sopInvoice === null) && ($scope.remarksDed !== undefined || $scope.remarksDed !== null)  ) {
            newDeduction.sopInvId  =  0  ;
            newDeduction.dedName   = selected.name_used_for_display + ' ' +  $scope.remarksDed
        } else if( ($scope.sopInvoice !== undefined || $scope.sopInvoice !== null) && ($scope.remarksDed === undefined || $scope.remarksDed === null) ){
            newDeduction.sopInvId  = $scope.sopInvLineId   ;
            newDeduction.dedName   = selected.name_used_for_display + ' ' + inv  
        } else if( ($scope.sopInvoice !== undefined || $scope.sopInvoice !== null) && ($scope.remarksDed !== undefined || $scope.remarksDed !== null) ){
            newDeduction.sopInvId  = $scope.sopInvLineId   ;
            newDeduction.dedName   = selected.name_used_for_display + ' ' + inv + ' '  +  $scope.remarksDed
        }
        if ($scope.deductionAmountInputted != 0 || $scope.deductionAmount != 0) {
            if (selected.repeat == 1) {

                $scope.DeductionData.push(newDeduction);

            } else {
                var checkDupes = $scope.DeductionData.find(({ dedId }) => dedId === $scope.selectedDeductionName);

                if (checkDupes === undefined) {
                    $scope.DeductionData.push(newDeduction);
                } else {
                    swal.fire({
                        title: warningTitle,
                        html: "<b>Deduction is already added!</b>"
                    })
                }
            }
            $scope.resetNewDeduction();
            $('#myModal3').modal('hide');
        } else {
            swal.fire({
                title: warningTitle,
                html: "<b>Amount is empty!</b>"
            })
        }

        $scope.calculateTotals();
    }

    $scope.numberWithCommas = function(string) 
    {
        return string.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    $scope.resetNewInvoice = function()
    {    
        $scope.invoiceNo     = null;
        $scope.invoiceDate   = null;
        $scope.invoiceAmount = null;
        
    }

    $scope.resetNewDeduction = function()
     {
        $scope.selectDeductionType     = null;
        $scope.selectedDeductionName   = null;
        $scope.remarksDed              = null;
        $scope.deductionAmount         = null;
        $scope.deductionAmountInputted = null;
        $scope.amountToBeDeducted      = null;
        $scope.useForDisplay           = null;
        $scope.sopInvoice              = null;
        $("#sopInvoice").val("");
        $('input[name="customRadio"]').prop('checked', false);
    }

    $scope.loadChargesType = function() 
    {
        $http({
            method: 'get',
            url: $base_url + 'loadChargesType'
        }).then(function successCallback(response) {
            $scope.chargesType = response.data;
        });
    }

    $scope.displayToInputCharge = function()
    {
        $scope.chargeRemarks = null;
        $scope.chargeAmountInputted = null;
    }

    $scope.addNewChargeToTable = function(ev) 
    {
        ev.preventDefault();

        var findSelectedCharge = $scope.chargesType.find(({ charges_id }) => charges_id === $scope.selectChargeType);
        $scope.ChargesData.push({
            'chargeId': $scope.selectChargeType,
            'description': findSelectedCharge.charges_type + ' - ' + $scope.chargeRemarks,
            'chargeAmount': $scope.chargeAmountInputted
        });

        $scope.resetNewCharge();
        $("#myModal4").modal('hide');
        $scope.calculateTotals();
    }

    $scope.resetNewCharge = function() 
    {
        $scope.selectChargeType = null;
        $scope.chargeRemarks = null;
        $scope.chargeAmountInputted = null;
    }

    $scope.submitNewSop = function(ev) 
    {
        ev.preventDefault();
        var InvoiceData = JSON.parse(angular.toJson($scope.SOPInvoiceData));
        var ChargeData = JSON.parse(angular.toJson($scope.ChargesData));
        var DeductionsData = JSON.parse(angular.toJson($scope.DeductionData));;

        if ($scope.totalInvoiceAmount != 0.00 && $scope.totalDeductionAmount != 0.00 && $scope.totalNetPayableAmount != 0.00) {
            Swal.fire({
                title: warningTitle,
                html: "<b>Are you sure to submit SOP ? </b>",
                buttonsStyling: false,
                showCancelButton: true,
                confirmButtonText: confirmButtonIcon,
                cancelButtonText: cancelButtonIcon,
                customClass: { confirmButton: confirmButtonClass, cancelButton: cancelButtonClass }
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        type: "POST",
                        url: $base_url + 'submitSOP',
                        data: {
                            supId: $scope.selectSupplierNewSop,
                            cusId: $scope.selectCustomerNewSop,
                            invoiceAmount: $scope.totalInvoiceAmount,
                            chargesAmount: $scope.totalChargesAmount,
                            dedAmount: $scope.totalDeductionAmount,
                            netAmount: $scope.totalNetPayableAmount,
                            invoice: InvoiceData,
                            deduction: DeductionsData,
                            charges: ChargeData
                        },
                        cache: false,
                        success: function(response) {
                            if (response.info == "success") {
                                Swal.fire({
                                    title: successTitle,
                                    html: '<b> ' + response.message + ' </b>'
                                }).then(function() {
                                    window.open($base_url + 'files/Reports/SOP/' + response.file);
                                    location.reload();
                                })
                            } else if (response.data == "incomplete") {
                                Swal.fire({
                                    title: warningTitle,
                                    html: '<b> Failed to save SOP! </b>'
                                })
                            }
                        }
                    });
                }
            })

        } else {
            Swal.fire({
                title: warningTitle,
                html: '<b> No Data to Save! </b>'
            })
        }
    }

    $scope.resetNewSOP = function() 
    {
        $scope.selectSupplierNewSop = null;
        $scope.selectCustomerNewSop = null;
        $scope.vendorsDeal          = null;
        $scope.periodFrom           = null;
        $scope.periodTo             = null;
        $scope.SOPInvoiceData       = [{}];
        $scope.DeductionData        = [{}];
        $scope.ChargesData          = [{}];
        $scope.SONOs                = [{}];
        $scope.deductionType        = [{}];
        $scope.deductionNames       = [{}];
        $scope.chargesType          = [{}];
        $scope.totalInvoiceAmount   = 0;
        $scope.totalDeductionAmount = 0;
        $scope.totalChargesAmount   = 0;
        $scope.totalNetPayableAmount= 0;
        $scope.checkDeduction       = false;
        $scope.checkCharges         = false;
        $scope.deals                = null;
    }

    $scope.loadCwoSop = function() 
    {
        $http({
            method: 'post',
            url: $base_url + 'loadCwoSop',
            data: { supId: $scope.selectSupplier, cusId: $scope.selectCustomer }
        }).then(function successCallback(response) {
            if (response.data != '') {
                $(document).ready(function() { $('#cwoSopTable').DataTable(); });
                $scope.cwoSopList = true;
                $scope.cwoSopHead = response.data;
            } else {
                swal.fire({
                    title: infoTitle,
                    html: "<b> No SOP Transactions for this supplier and location! </b>"
                })
                $scope.cwoSopList = false;
            }
        });
    }

    $scope.viewSopDetails = function(data) 
    {
        $scope.sopId       = data.sop_id;
        $scope.sopSupplier = data.supplier_name;
        $scope.sopCustomer = data.customer_name;
        $scope.sopDate     = data.sop_date;
        $scope.sopNumber   = data.sop_no;
        $scope.status      = data.statuss;

        $http({
            method: 'post',
            url: $base_url + 'loadSopDetails',
            data: { sopId: data.sop_id }
        }).then(function successCallback(response) {

            if (response.data.invoice.length != 0) {
                $scope.sopInvoice = response.data.invoice;
                var invoiceTotal = 0;
                angular.forEach($scope.sopInvoice, function(value, key) {
                    if (!isNaN(value.invoice_amount)) {

                        invoiceTotal += value.invoice_amount * 1;
                    }
                });
                $scope.sopTotalInvoiceAmount = invoiceTotal;
            }

            if (response.data.deduction.length != 0) {
                $scope.sopDeduction = response.data.deduction;
                var deductionTotal = 0;
                angular.forEach($scope.sopDeduction, function(value, key) {
                    if (!isNaN(value.deduction_amount)) {

                        deductionTotal += value.deduction_amount * 1;
                    }
                });
                $scope.sopTotalDeductionAmount = deductionTotal;
            }

            if (response.data.charges.length != 0) {
                $scope.sopCharges = response.data.charges;
                var chargesTotal = 0;
                angular.forEach($scope.sopCharges, function(value, key) {
                    if (!isNaN(value.charge_amount)) {

                        chargesTotal += value.charge_amount * 1;
                    }
                });
                $scope.sopTotalChargesAmount = chargesTotal;
            }

            $scope.sopTotalNetPayableAmount = $scope.sopTotalInvoiceAmount + $scope.sopTotalDeductionAmount + $scope.sopTotalChargesAmount;

        });
    }

    $scope.tagAsAudited = function(ev)
    {
        ev.preventDefault();

        Swal.fire({
            title: warningTitle,
            html: "<b>Are you sure you want to tag this as AUDITED ? </b>",
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: "<i class='fas fa-thumbs-up'></i> Yes",
            cancelButtonText: "<i class='fas fa-thumbs-down'></i> No",
            customClass: { confirmButton: "btn btn-outline-success",cancelButton: "btn btn-light" }
        }).then((result) => {
            if (result.isConfirmed) 
            {
                $http({
                    method: 'post',
                    url: $base_url + 'tagAsAudited',
                    data: { sopId: $scope.sopId }
                }).then(function successCallback(response) {
                   var icon = "";
                   if(response.data.info == "Success"){
                       icon = successTitle;
                   } else if(response.data.info == "Error"){
                       icon = warningTitle;
                   }
                   Swal.fire({
                        title: icon,
                        html: '<b> ' + response.data.message +  ' </b>'
                    }).then(function(){
                        location.reload();
                    })
                });
            }
        })
    }


    $(document).ready(function() {

        $('#openBtn').click(() => $('#myModal').modal({
            show: true
        }));

        $(document).on('show.bs.modal', '.modal', function() {
            const zIndex = 1040 + 10 * $('.modal:visible').length;
            $(this).css('z-index', zIndex);
            setTimeout(() => $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack'));
        });

    });


});