<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper body-bg" ng-controller="sop-controller">
    <!-- Main content -->
    <div class="content" ng-init="checkUserType()">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-style-1">
                        <div class="card-header bg-dark rounded-0">
                            <div class="content-header" style="padding: 0px">
                                <div class="panel panel-default">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel-body"><i class="fas fa-money-check"></i> <strong> Summary of Payments</strong></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <?php if ($this->session->userdata('userType') == 'SOP' || $this->session->userdata('userType') == 'Admin') : ?>
                                        <button class="btn bg-gradient-primary btn-flat" data-target="#myModal" data-toggle="modal"><i class="fas fa-plus-circle"></i> CWO</button>
                                        <button class="btn bg-gradient-primary btn-flat" data-target="#" data-toggle="modal" ng-click="mes()"><i class="fas fa-plus-circle"></i> OUTRIGHT</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <hr>
                            <div class="container" style="padding-left: 220px; padding-right: 220px;">
                                <div class="row col-lg-12">
                                    <div class="col-lg-6">
                                        <div class="form-group" ng-init="getSuppliers()">
                                            <label for="selectSupplier">Supplier Name</label>
                                            <select id="selectSupplier" class="form-control rounded-0" ng-model="selectSupplier" name="selectSupplier" ng-change="getSupplierName()" required>
                                                <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                <option ng-repeat="s in suppliers" value="{{s.supplier_id}}">{{s.supplier_name}}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group" ng-init="getCustomers()">
                                            <label for="selectCustomer">Location Name </label>
                                            <select id="selectCustomer" class="form-control rounded-0" ng-model="selectCustomer" name="selectCustomer" required>
                                                <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                <option ng-repeat="c in customers" value="{{c.customer_code}}">{{c.customer_name}}</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <div class="row mb-3 col-lg-8">
                                    <div class="col-lg-6"></div>
                                    <div class="col-lg-6">
                                        <button type="button" class="btn btn-primary btn-block btn-flat" ng-click="loadCwoSop()" ng-disabled="!selectSupplier && !selectCustomer">
                                            GENERATE
                                        </button>
                                    </div>

                                </div>
                            </div>

                            <div ng-if="cwoSopList">
                                <table id="cwoSopTable" class="table table-bordered table-sm">
                                    <thead class="bg-dark">
                                        <tr>
                                            <th scope="col" class="text-center">#</th>
                                            <th scope="col" class="text-center">SOP #</th>
                                            <th scope="col" class="text-center">DATE</th>
                                            <th scope="col" class="text-center">SUPPLIER</th>
                                            <th scope="col" class="text-center">LOCATION</th>
                                            <th scope="col" class="text-center">AMOUNT</th>
                                            <th scope="col" class="text-center">STATUS</th>
                                            <th scope="col" class="text-center">ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-cloak ng-repeat="cwo in cwoSopHead">
                                            <td class="text-center">{{$index + 1}}</td>
                                            <td class="text-center">{{cwo.sop_no}}</td>
                                            <td class="text-center">{{cwo.sop_date | date:'mediumDate'}}</td>
                                            <td class="text-center">{{cwo.supplier_name}}</td>
                                            <td class="text-center">{{cwo.customer_name}}</td>
                                            <td class="text-center">{{cwo.net_amount | currency:'₱ '}}</td>
                                            <td class="text-center">{{cwo.statuss}}</td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-success btn-flat btn-sm dropdown-toggle" data-toggle="dropdown">Action
                                                    </button>
                                                    <div class="dropdown-menu" style="margin-right: 50px;">
                                                        <a class="dropdown-item" href="#" data-toggle="modal" title="View Details" data-target="#myModal5" ng-click="viewSopDetails(cwo)">
                                                            <i class="fas fa-search" style="color: green;"></i> View
                                                        </a>
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
    <!-- /.content -->

    <!-- NEW SOP -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header bg-dark rounded-0">
                    <h5 class="modal-title" id="exampleModalCenterTitle"><i class="fas fa-file-alt"></i> NEW CWO - SOP</h5>
                    <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> -->
                </div>
                <div class="container"></div>
                <div class="modal-body ">
                    <form action="" method="POST" id="newSopForm" ng-submit="submitNewSop($event)" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" ng-init="getSuppliers()">
                                    <label for="selectSupplier">Supplier Name: </label>   <!-- ng-change="loadDeals(selectSupplierNewSop)" -->
                                    <select name="selectSupplier" ng-model="selectSupplierNewSop" ng-change="getDetail(selectSupplierNewSop,suppliers)"  class="form-control rounded-0" required>
                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                        <option ng-repeat="supplier in suppliers" value="{{supplier.supplier_id}}">{{supplier.supplier_name}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" ng-init="getCustomers()">
                                    <label for="selectCustomer">Location Name: </label>
                                    <select name="selectCustomer" ng-model="selectCustomerNewSop" class="form-control rounded-0" required>
                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                        <option ng-repeat="customer in customers" value="{{customer.customer_code}}">{{customer.customer_name}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="vendorsDeal">Vendor's Deal :</label>
                                    <select name="vendorsDeal" ng-model="vendorsDeal" ng-change="displayToInputDeal(vendorsDeal,deals)" ng-disabled="hasDeal=='0'" class="form-control rounded-0">
                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                        <option ng-if="deals ==''" value="" disabled="" selected="" style="display:none">No Data Found</option>
                                        <option ng-repeat="d in deals" value="{{d.vendor_deal_head_id}}">{{d.vendor_deal_code}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="periodFrom" class="col-sm-12 col-form-label">Period From</label>
                                    <input type="text" style="border:none" ng-model="periodFrom" value="" class="form-control rounded-0" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="periodTo" class="col-sm-12 col-form-label">Period To</label>
                                    <input type="text" style="border:none" ng-model="periodTo" class="form-control rounded-0" readonly>
                                </div>
                            </div>   
                        </div>

                        <hr>

                        <div class="col-md-12" ng-init="SOPInvoiceData = [{}]; DeductionData = [{}]; ChargesData = [{}]; ">
                            <!-- <div class="col-md-12"> -->
                            <div class="row">
                                <table class="table table-bordered table-sm">
                                    <thead class="bg-dark">
                                        <tr>
                                            <th scope="col" style="width:20%" class="text-center">PO #</th>
                                            <th scope="col" style="width:15%" class="text-center">PO DATE</th>
                                            <th scope="col" style="width:25%" class="text-center">INVOICE #</th>
                                            <th scope="col" style="width:15%" class="text-center">INVOICE DATE</th>
                                            <th scope="col" style="width:20%" class="text-center">AMOUNT</th>
                                            <th scope="col" style="width:5%" class="text-center"><i class="fas fa-bars"></i></th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="data in SOPInvoiceData track by data.profId" ng-cloak>
                                            <td ng-if="$index > 0">
                                                <div class="input-group input-group-sm rounded-0 ">
                                                    <input type="text" class="form-control rounded-0 text-center text-bold" value="{{data.poNo}}" style="border: none; " readonly>
                                                </div>
                                            </td>
                                            <td ng-if="$index > 0">
                                                <div class="input-group input-group-sm rounded-0 ">
                                                    <input type="text" class="form-control rounded-0 text-center text-bold" value="{{data.poDate}}" style="border: none; " readonly>
                                                </div>
                                            </td>
                                            <td ng-if="$index > 0">
                                                <div class="input-group input-group-sm rounded-0 ">
                                                    <input type="text" class="form-control rounded-0 text-center text-bold" value="{{data.invoiceNo}}" style="border: none; " readonly>
                                                </div>
                                            </td>

                                            <td ng-if="$index > 0">
                                                <div class="input-group input-group-sm rounded-0 ">
                                                    <input type="text" class="form-control rounded-0 text-center text-bold" value="{{data.invoiceDate}}" style="border: none;  " readonly>
                                                </div>
                                            </td>
                                            <td ng-if="$index > 0">
                                                <div class="input-group input-group-sm rounded-0 ">
                                                    <input type="text" class="form-control rounded-0 text-center text-bold" value="{{data.invoiceAmount | currency:' ' }}" style="border: none;  " readonly>
                                                </div>
                                            </td>
                                            <td ng-if="$index > 0">
                                                <div class="col-lg-12">
                                                    <div class="input-group input-group-sm rounded-0">
                                                        <a href="#" style="color:red; padding-right: 10px; padding-left: 10px;" title="Remove Invoice" ng-click="SOPInvoiceData.splice($index, 1); calculateTotals() ">
                                                            <i class="fa fa-minus"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="font-weight:bold">
                                            <td>TOTAL INVOICE</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-center">{{totalInvoiceAmount | currency :' '}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            <input type="checkbox" style="width: 15px;height: 15px;" class="form-check-input" ng-model="checkDeduction" ng-disabled="totalInvoiceAmount == 0" value="">Deduction
                                        </label>
                                    </div>
                                    <div class="col-sm-10 form-check-inline">
                                        <label class="form-check-label">
                                            <input type="checkbox" style="width: 15px;height: 15px;" class="form-check-input" ng-model="checkCharges" ng-disabled="totalInvoiceAmount == 0 " value="">Charges
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger btn-flat float-right" title="Add New Invoice" data-toggle="modal" href="#myModal2" ng-disabled="!selectSupplierNewSop || (hasDeal == '1' && !vendorsDeal)">
                                        <i class="fas fa-plus-circle"></i>
                                        Invoice
                                    </button>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="col-md-12" ng-if="checkDeduction || checkCharges"> 
                            <div class="row">
                                <div class="col-md-6" ng-if="checkDeduction">
                                    <h5>Less: DEDUCTION</h5>
                                    <table class="table table-bordered table-sm">
                                        <thead class="bg-dark">
                                            <tr>
                                                <th scope="col" style="width:45%" class="text-center">DEDUCTION</th>
                                                <th scope="col" style="width:45%" class="text-center">AMOUNT</th>
                                                <th scope="col" style="width:10%" class="text-center"><i class="fas fa-bars"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="ded in DeductionData" ng-cloak>
                                                <td ng-if="$index > 0">
                                                    <div class="input-group input-group-sm rounded-0 ">
                                                        <input type="text" class="form-control rounded-0 text-center text-bold" value="{{ded.dedName}}" style="border: none; " readonly>
                                                    </div>
                                                <td ng-if="$index > 0">
                                                    <div class="input-group input-group-sm rounded-0 ">
                                                        <input type="text" class="form-control rounded-0 text-center text-bold" value="{{ded.dedAmount | currency:' '}}" style="border: none;  " readonly>
                                                    </div>
                                                </td>
                                                <td ng-if="$index > 0">
                                                    <div class="input-group input-group-sm rounded-0">
                                                        <a href="#" style="color:red; padding-right: 10px; padding-left: 10px;" title="Remove Deduction" ng-click="DeductionData.splice($index, 1); calculateTotals() ">
                                                            <i class="fa fa-minus"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr style="font-weight:bold">
                                                <td>TOTAL DEDUCTION</td>
                                                <td class="text-center">{{totalDeductionAmount | currency :' '}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-6" ng-if="checkCharges">
                                    <h5>Add: CHARGES</h5>
                                    <table class="table table-bordered table-sm">
                                        <thead class="bg-dark">
                                            <tr>
                                                <th scope="col" style="width:45%" class="text-center">DESCRIPTION</th>
                                                <th scope="col" style="width:45%" class="text-center">AMOUNT</th>
                                                <th scope="col" style="width:10%" class="text-center"><i class="fas fa-bars"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="charge in ChargesData" ng-cloak>
                                                <td ng-if="$index > 0">
                                                    <div class="input-group input-group-sm rounded-0 ">
                                                        <input type="text" class="form-control rounded-0 text-center text-bold" value="{{charge.description}}" style="border: none; " readonly>
                                                    </div>
                                                <td ng-if="$index > 0">
                                                    <div class="input-group input-group-sm rounded-0 ">
                                                        <input type="text" class="form-control rounded-0 text-center text-bold" value="{{charge.chargeAmount | currency:' '}}" style="border: none;  " readonly>
                                                    </div>
                                                </td>
                                                <td ng-if="$index > 0">
                                                    <div class="input-group input-group-sm rounded-0">
                                                        <a href="#" style="color:red; padding-right: 10px; padding-left: 10px;" title="Remove Charges" ng-click="ChargesData.splice($index, 1); calculateTotals() ">
                                                            <i class="fa fa-minus"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr style="font-weight:bold">
                                                <td>TOTAL CHARGES</td>
                                                <td class="text-center">{{totalChargesAmount | currency :' '}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6" ng-if="checkDeduction">
                                    <button type="button" class="btn btn-danger btn-flat float-left" title="Add New Deduction" data-toggle="modal" href="#myModal3" ng-click="loadDeductionType()" ng-disabled="!selectSupplierNewSop">
                                        <i class="fas fa-plus-circle"></i>
                                        Deduction
                                    </button>
                                </div>
                                <div class="col-md-6" ng-if="checkCharges">
                                    <button type="button" class="btn btn-danger btn-flat float-right" title="Add New Charge" data-toggle="modal" data-target="#myModal4" ng-click="loadChargesType()" ng-disabled="!selectSupplierNewSop">
                                        <i class="fas fa-plus-circle"></i>
                                        Charge
                                    </button>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-sm-6"></div>
                            <div class="col-sm-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend rounded-0">
                                        <span class="input-group-text rounded-0" style="border: 0; background-color: white;"><strong>TOTAL INVOICE :</strong></span>
                                    </div>
                                    <input type="text" style="border:none;text-align:right;font-weight:bold" value="{{totalInvoiceAmount | currency :'₱ '}}" class="form-control rounded-0" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6"></div>
                            <div class="col-sm-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend rounded-0">
                                        <span class="input-group-text rounded-0" style="border: 0; background-color: white;"><strong>TOTAL DEDUCTION :</strong></span>
                                    </div>
                                    <input type="text" style="border:none;text-align:right;font-weight:bold" value="{{totalDeductionAmount | currency :'₱ ' }}" class="form-control rounded-0" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6"></div>
                            <div class="col-sm-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend rounded-0">
                                        <span class="input-group-text rounded-0" style="border: 0; background-color: white;"><strong>TOTAL CHARGES :</strong></span>
                                    </div>
                                    <input type="text" style="border:none;text-align:right;font-weight:bold" value="{{totalChargesAmount | currency :'₱ ' }}" class="form-control rounded-0" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6"></div>
                            <div class="col-sm-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend rounded-0">
                                        <span class="input-group-text rounded-0" style="border: 0; background-color: white; font-weight:bold; font-size: 120%;">NET PAYABLE AMOUNT :</span>
                                    </div>
                                    <input type="text" style="border:none;text-align:right;font-weight:bold; font-size: 180%;" value="{{totalNetPayableAmount | currency :'₱ ' }}" class="form-control rounded-0" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success btn-flat">Save</button>
                            <button type="button" class="btn btn-dark btn-flat" ng-click="resetNewSOP()" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- NEW SOP -->

    <!-- ADD NEW INVOICE -->
    <div class="modal fade" id="myModal2">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header bg-dark">
                    <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-file-invoice"></i> New Invoice </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <div class="container"></div>
                <div class="modal-body">
                    <form id="newInvoice" ng-submit="addNewInvoiceToTable($event,invoiceNo,SONOs)">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <button 
                                        type="button" 
                                        class="btn btn-primary btn-flat btn-block" 
                                        data-toggle="modal" 
                                        href="#myModal6"
                                        ng-disabled="hasDeal == '1' && !vendorsDeal ">
                                        <i class="fas fa-search"></i>
                                        View Item Mapping
                                    </button>
                                </div>    
                                                      
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="invoiceNo">Invoice No :</label>
                                        <select name="invoiceNo" ng-model="invoiceNo" ng-change="displayToInput(invoiceNo,SONOs)" class="form-control rounded-0" required>
                                            <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                            <option ng-if="SONOs ==''" value="" disabled="" selected="" style="display:none">No Data Found</option>
                                            <option ng-repeat="so in SONOs" value="{{so.proforma_header_id}}">{{so.so_no}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="invoiceNo">Invoice Date :</label>
                                        <input type="text" ng-model="invoiceDate" value="" class="form-control rounded-0" readonly>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="invoiceNo">Invoice Amount :</label>
                                        <input type="text" value="{{invoiceAmount | currency:'₱ ' }}" value="" class="form-control rounded-0" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">                            
                            <button type="submit" class="btn btn-success btn-flat" ng-disabled="!invoiceNo">Add</button>
                            <button type="button" class="btn btn-dark btn-flat" ng-click="resetNewInvoice()" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ADD NEW INVOICE -->

    <!-- ADD NEW DEDUCTION -->
    <div class="modal fade" id="myModal3">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header bg-dark">
                    <h5 class="modal-title" id="exampleModalLongTitle"><i class="fab fa-less"></i> New Deduction </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <div class="container"></div>
                <div class="modal-body">
                    <form id="newDeduction" name="newDeduction">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="selectDeductionType">Deduction Type :</label>
                                        <select name="selectDeductionType" ng-model="selectDeductionType" ng-change="loadDeductionNames(selectDeductionType);" class="form-control rounded-0" required>
                                            <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                            <option ng-if="deductionType ==''" value="" disabled="" selected="" style="display:none">No Type Found</option>
                                            <option ng-repeat="type in deductionType" value="{{type.deduction_type_id}}">{{type.type}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="deductionName">Deduction Name :</label>
                                        <select name="selectedDeductionName" ng-model="selectedDeductionName" ng-change="loadDeductionDetails(selectedDeductionName, deductionNames);" class="form-control rounded-0" required>
                                            <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                            <option ng-if="deductionNames ==''" value="" disabled="" selected="" style="display:none">No Data Found</option>
                                            <option ng-repeat="d in deductionNames" value="{{d.deduction_id}}">{{d.name}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="amountToBeDeducted">Name Use For Display</label>
                                        <input type="text" style="border:none;text-align:left;font-weight:bold" ng-model="useForDisplay" class="form-control rounded-0" readonly>
                                    </div>
                                </div>      
                                <div class="col-md-12" >
                                    <fieldset ng-disabled="!selectedDeductionName || selectDeductionType==3 || selectDeductionType==7 ">
                                        <label for="">Basis for Deduction Computation</label>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="customRadio1" name="customRadio" value="1"  ng-click="calculateDeduction(1)" class="custom-control-input">
                                            <label class="custom-control-label" for="customRadio1">Total Invoice(per Item Disc.)</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="customRadio2" name="customRadio" value="2" ng-click="calculateDeduction(2)" class="custom-control-input">
                                            <label class="custom-control-label" for="customRadio2">Gross of Total Invoice</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="customRadio3" name="customRadio" value="3"  ng-click="calculateDeduction(3)" class="custom-control-input">
                                            <label class="custom-control-label" for="customRadio3">Gross of Total Invoice (Diminishing)</label>
                                        </div>
                                    </fieldset>
                                </div>                  
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="amountToBeDeducted">Amount To Be Deducted</label>
                                        <input type="text" style="border:none;text-align:right;font-weight:bold" value="{{amountToBeDeducted | currency:'₱ ' }}" class="form-control rounded-0" readonly>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <!-- <div class="form-group"> -->
                                        <label for="sopInvoice">SOP : </label>
                                        <input type="text" placeholder="Search by SOP No or Invoice No" id="sopInvoice" name="sopInvoice" ng-model="sopInvoice" ng-keyup="searchSOP($event)" ng-disabled="!inputted" value="" class="form-control rounded-0">
                                    <!-- </div> -->
                                    <div class="search-results" ng-repeat="s in searchResult track by $index" ng-if="hasResults == 1">
                                        <a 
                                            href="#" 
                                            ng-repeat="s in searchResult track by $index"                                        
                                            ng-click="getSOPInv(s)">{{s.sop_no}} - {{s.so_no}}<br>
                                        </a>                                  
                                    </div>
                                    <div class="search-results" ng-repeat="d in searchResult track by $index " ng-if="hasResults == 0">
                                        <a 
                                            href="#" 
                                            ng-repeat="d in searchResult track by $index">
                                            {{d.id}} <br>
                                        </a>                                  
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="remarksDed">Remarks : </label>
                                        <input type="text" ng-model="remarksDed" ng-disabled="!inputted" value="" class="form-control rounded-0">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group" ng-if="!inputted">
                                        <label for="invoiceNo">Amount :</label>
                                        <input type="text" style="text-align: right;" value="{{deductionAmount | currency:' ' }}" value="" class="form-control rounded-0" readonly>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="deductionAmountInputted" class="col-sm-6 col-form-label">Amount(Inputted) : </label>
                                        <input type="number" ng-model="deductionAmountInputted" ng-disabled="!inputted" ng-pattern="/^[0-9]+(\.[0-9]{1,2})?$/" value="" class="form-control rounded-0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success btn-flat" ng-disabled="newDeduction.$invalid" ng-click="addNewDeductionToTable($event)">Add</button>
                            <button type="button" class="btn btn-dark btn-flat" ng-click="resetNewDeduction()" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ADD NEW DEDUCTION -->

    <!-- ADD CHARGE -->
    <div class="modal fade" id="myModal4">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header bg-dark">
                    <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-receipt"></i> New Charge </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <div class="container"></div>
                <div class="modal-body">
                    <form id="newCharge" name="newCharge">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="selectChargeType">Charge Type :</label>
                                        <select name="selectChargeType" ng-model="selectChargeType" ng-change="displayToInputCharge()" class="form-control rounded-0" required>
                                            <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                            <option ng-if="chargesType ==''" value="" disabled="" selected="" style="display:none">No Data Found</option>
                                            <option ng-repeat="type in chargesType" value="{{type.charges_id}}">{{type.charges_type}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="chargeRemarks">Remarks :</label>
                                        <input type="text" ng-model="chargeRemarks" value="" class="form-control rounded-0" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="chargeAmountInputted">Amount(Inputted) : </label>
                                        <input type="text" ng-model="chargeAmountInputted" value="" class="form-control rounded-0" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success btn-flat" ng-disabled="newCharge.$invalid" ng-click="addNewChargeToTable($event)">Add</button>
                            <button type="button" class="btn btn-dark btn-flat" data-dismiss="modal" ng-click="resetNewCharge($event)">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ADD CHARGE -->


    <!-- VIEW SOP DETAILS -->
    <div class="modal fade" id="myModal5" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header bg-dark rounded-0">
                    <h5 class="modal-title" id="exampleModalCenterTitle"><i class="fas fa-info-circle"></i> SOP Details </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- <div class="container"></div> -->
                <div class="modal-body ">
                    <div ng-class= "{watermark: status== 'AUDITED'}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="selectSupplier">Supplier Name : </label>
                                    <input type="text" style="border:none;text-align:left;font-weight:bold" ng-model="sopSupplier" class="form-control rounded-0" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="selectSupplier">SOP Number : </label>
                                    <input type="text" style="border:none;text-align:left;font-weight:bold" ng-model="sopNumber" class="form-control rounded-0" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="selectSupplier">Location Name : </label>
                                    <input type="text" style="border:none;text-align:left;font-weight:bold" ng-model="sopCustomer" class="form-control rounded-0" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="selectSupplier">Date : </label>
                                    <input type="text" style="border:none;text-align:left;font-weight:bold" ng-model="sopDate" class="form-control rounded-0" readonly>
                                </div>
                            </div>

                        </div>

                        <hr>

                        <div class="col-md-12">
                            <!-- <div class="col-md-12"> -->
                            <div class="row">
                                <table class="table table-bordered table-sm">
                                    <thead class="bg-dark">
                                        <tr>
                                            <th scope="col" style="width:25%" class="text-center">PO #</th>
                                            <th scope="col" style="width:15%" class="text-center">PO DATE</th>
                                            <th scope="col" style="width:25%" class="text-center">INVOICE #</th>
                                            <th scope="col" style="width:15%" class="text-center">INVOICE DATE</th>
                                            <th scope="col" style="width:20%" class="text-center">AMOUNT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-cloak ng-repeat="inv in sopInvoice">
                                            <td>
                                                <div class="input-group input-group-sm rounded-0 ">
                                                    <input type="text" class="form-control rounded-0 text-center text-bold" value="{{inv.po_no}}" style="border: none; " readonly>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm rounded-0 ">
                                                    <input type="text" class="form-control rounded-0 text-center text-bold" value="{{inv.po_date}}" style="border: none; " readonly>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm rounded-0 ">
                                                    <input type="text" class="form-control rounded-0 text-center text-bold" value="{{inv.so_no}}" style="border: none; " readonly>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="input-group input-group-sm rounded-0 ">
                                                    <input type="text" class="form-control rounded-0 text-center text-bold" value="{{inv.order_date}}" style="border: none;  " readonly>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm rounded-0 ">
                                                    <input type="text" class="form-control rounded-0 text-center text-bold" value="{{inv.invoice_amount | currency:' '}}" style="border: none;  " readonly>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="font-weight:bold">
                                            <td>TOTAL INVOICE </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-center">{{sopTotalInvoiceAmount | currency :' '}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <hr>

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Less: DEDUCTION</h5>
                                    <table class="table table-bordered table-sm">
                                        <thead class="bg-dark">
                                            <tr>
                                                <th scope="col" style="width:55%" class="text-center">DEDUCTION</th>
                                                <th scope="col" style="width:45%" class="text-center">AMOUNT</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="ded in sopDeduction" ng-cloak>
                                                <td>
                                                    <div class="input-group input-group-sm rounded-0 ">
                                                        <input type="text" class="form-control rounded-0 text-center text-bold" value="{{ded.description}}" style="border: none; " readonly>
                                                    </div>
                                                <td>
                                                    <div class="input-group input-group-sm rounded-0 ">
                                                        <input type="text" class="form-control rounded-0 text-center text-bold" value="{{ded.deduction_amount | currency:' '}}" style="border: none;  " readonly>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr style="font-weight:bold">
                                                <td>TOTAL DEDUCTION</td>
                                                <td class="text-center">{{sopTotalDeductionAmount | currency :' '}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h5>Add: CHARGES</h5>
                                    <table class="table table-bordered table-sm">
                                        <thead class="bg-dark">
                                            <tr>
                                                <th scope="col" style="width:45%" class="text-center">DESCRIPTION</th>
                                                <th scope="col" style="width:45%" class="text-center">AMOUNT</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="charge in sopCharges" ng-cloak>
                                                <td>
                                                    <div class="input-group input-group-sm rounded-0 ">
                                                        <input type="text" class="form-control rounded-0 text-center text-bold" ng-model="charge.description" style="border: none; " readonly>
                                                    </div>
                                                <td>
                                                    <div class="input-group input-group-sm rounded-0 ">
                                                        <input type="text" class="form-control rounded-0 text-center text-bold" ng-model="charge.chargeAmount | currency:' '" style="border: none;  " readonly>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr style="font-weight:bold">
                                                <td>TOTAL CHARGES</td>
                                                <td class="text-center">{{sopTotalChargesAmount | currency :' '}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-sm-6"></div>
                            <div class="col-sm-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend rounded-0">
                                        <span class="input-group-text rounded-0" style="border: 0; background-color: white;"><strong>TOTAL INVOICE :</strong></span>
                                    </div>
                                    <input type="text" style="border:none;text-align:right;font-weight:bold" value="{{sopTotalInvoiceAmount | currency :'₱ '}}" class="form-control rounded-0" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6"></div>
                            <div class="col-sm-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend rounded-0">
                                        <span class="input-group-text rounded-0" style="border: 0; background-color: white;"><strong>TOTAL DEDUCTION :</strong></span>
                                    </div>
                                    <input type="text" style="border:none;text-align:right;font-weight:bold" value="{{sopTotalDeductionAmount | currency :'₱' }}" class="form-control rounded-0" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6"></div>
                            <div class="col-sm-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend rounded-0">
                                        <span class="input-group-text rounded-0" style="border: 0; background-color: white;"><strong>TOTAL CHARGES :</strong></span>
                                    </div>
                                    <input type="text" style="border:none;text-align:right;font-weight:bold" value="{{sopTotalChargesAmount | currency :'₱ '}}" class="form-control rounded-0" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6"></div>
                            <div class="col-sm-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend rounded-0">
                                        <span class="input-group-text rounded-0" style="border: 0; background-color: white; font-weight:bold; font-size: 120%;">NET PAYABLE AMOUNT :</span>
                                    </div>
                                    <input type="text" style="border:none;text-align:right;font-weight:bold; font-size: 180%;" value="{{sopTotalNetPayableAmount | currency :'₱ '}}" class="form-control rounded-0" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <?php if ($this->session->userdata('userType') == 'IAD' || $this->session->userdata('userType') == 'Admin') : ?>
                            <button type="button" title="Tag SOP as AUDITED" class="btn btn-danger btn-flat" ng-if="status != 'AUDITED'" ng-click="tagAsAudited($event)"><i class="fas fa-tag"></i> Tag As Audited</button>
                        <?php endif;?>
                        <button type="button" class="btn btn-dark btn-flat" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- VIEW SOP DETAILS -->

     <!-- VIEW ITEM MAPPING -->
     <div class="modal fade" id="myModal6" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header bg-dark rounded-0">
                    <h5 class="modal-title" id="exampleModalCenterTitle"><i class="fas fa-info-circle"></i> Item Mapping of Proforma Transactions </h5>
                </div>

                <div class="container"></div>
                <div class="modal-body ">                   

                    <div class="col-md-12">
                        <!-- <div class="col-md-12"> -->
                        <h4 style="color:red">Note: Please see to it that all items are already mapped and items are in deals so that there won't be any lacking amounts in proforma.</h4>
                        <div class="row">
                            <table class="table table-bordered table-sm">
                                <thead class="bg-dark">
                                    <tr>
                                        <th scope="col" style=""  class="text-center">#</th>                                        
                                        <th scope="col" style=""  class="text-center">SO NO</th>
                                        <th scope="col" style=""  class="text-center">Description</th>
                                        <th scope="col" style=""  class="text-center">Item Code(Supplier)</th>
                                        <th scope="col" style=""  class="text-center">Item Code(Navision)</th>
                                        <th scope="col" style=""  class="text-center">Item Division</th>
                                        <th scope="col" style=""  class="text-center">Item Department</th>
                                        <th scope="col" style=""  class="text-center">Item Group</th> 
                                        <th scope="col" style=""  class="text-center">In Deals</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="item in itemMapping" ng-cloak>
                                        <td class="text-center">{{$index + 1}}</td>                                        
                                        <td class="text-center">{{item.so_no}}</td>
                                        <td class="text-center">{{item.description}}</td>
                                        <td class="text-center">{{item.item_code}}</td>
                                        <td class="text-center">
                                            <span class="no-item" ng-if="!item.itemcode_loc">NO SET UP</span>
                                            <span class="" ng-if="item.itemcode_loc">{{item.itemcode_loc}}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="no-item" ng-if="!item.item_division">NO SET UP</span>
                                            <span class="" ng-if="item.item_division">{{item.item_division}}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="no-item" ng-if="!item.item_department_code">NO SET UP</span>
                                            <span class="" ng-if="item.item_department_code">{{item.item_department_code}}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="no-item" ng-if="!item.item_group_code">NO SET UP</span>
                                            <span class="" ng-if="item.item_group_code">{{item.item_group_code}}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="no-item" ng-if="item.deals == 'No'">{{item.deals}}</span>
                                            <span class="" ng-if="item.deals == 'Yes'">{{item.deals}}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark btn-flat" ng-click="closemyModal6()" data-dismiss="modal">Close</button>
                    </div>
                    <!-- </form> -->
                </div>
            </div>
        </div>
    </div>
    <!-- VIEW ITEM MAPPING -->


</div>
<!-- /.content-wrapper -->