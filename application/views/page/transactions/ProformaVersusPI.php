<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper body-bg" ng-controller="proformavspi-controller">
    
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
                                            <div class="panel-body"><i class="fas fa-file-alt"></i> <strong>PROFORMA VS PI</strong></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                  <button 
                                        class="btn bg-gradient-primary btn-flat"
                                        data-target="#addProformaVSPI"
                                        data-toggle="modal"><i class="fas fa-upload"></i> Upload PI 
                                    </button>
                                    
                                    
                                </div>
                            </div>

                            <hr>

                            <div class="container" style="padding-left: 220px; padding-right: 220px;">
                                <div class="row col-lg-12">
                                    <div class="col-lg-6">
                                        <div class="form-group" ng-init="loadSupplier()">
                                            <label for="supplierName">Supplier Name</label>
                                            <select class="form-control rounded-0" ng-model="supplierName" name="supplierName" required>
                                                <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                <option ng-repeat="s in suppliers" value="{{s.supplier_id}}">{{s.supplier_name}}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group" ng-init="loadCustomer()">
                                            <label for="locationName">Location Name </label>
                                            <select class="form-control rounded-0" ng-model="locationName" name="locationName" required>
                                                <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                <option ng-repeat="c in customers" value="{{c.customer_code}}">{{c.customer_name}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3 col-lg-8">
                                    <div class="col-lg-6"></div>
                                    <div class="col-lg-6">
                                        <button type="button" class="btn btn-primary btn-block btn-flat" ng-click="loadPi(supplierName,locationName)" ng-disabled="!supplierName && !locationName">GET PENDING MATCHES</button>
                                    </div>
                                    <!-- <div class="col-lg-6">
                                        <button type="button" class="btn btn-success btn-flat" ng-click="loadPi()">yowwww</button>
                                    </div> -->
                                </div>
                            </div>

                            <!-- PI TABLE  -->
                            <div ng-if="pendingPi">
                                
                                <table id="proformaVspiTable" class="table table-bordered table-hover table-sm">
                                    <thead class="bg-dark">
                                        <tr>                                            
                                            <th scope="col" class="text-center">Location</th>
                                            <th scope="col" class="text-center">PI No.</th>
                                            <th scope="col" class="text-center">Posting Date</th>
                                            <th scope="col" class="text-center">PO</th>
                                            <th scope="col" class="text-center">Credit Memo</th>
                                            <th scope="col" style="width: 100px;" class="text-center">Status</th>
                                            <th scope="col" style="width: 100px;" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="p in pi" ng-cloak>                                            
                                            <td class="text-center">{{p.customer_name}}</td>
                                            <td class="text-center">{{p.pi_no}}</td>
                                            <td class="text-center">{{p.date | date:'mediumDate' }}</td>
                                            <td class="text-center">{{p.po_no}}</td>  
                                            <td class="text-center" ng-if="p.cm_no" >
                                                <a  
                                                    style="color:red;font-weight:bold"                                                                         
                                                    href="#"
                                                    title="View CM Details"
                                                    data-toggle="modal"                                                   
                                                    data-target="#viewCM"
                                                    ng-click="viewCmDetails(p)">
                                                    {{p.cm_no}}
                                                </a>  
                                            </td>
                                            <td class="text-center" ng-if="!p.cm_no">NO APPLIED CM</td>
                                            <td class="text-center" ng-if="p.status == 'PENDING' "> 
                                                <span class="badge badge-pill badge-danger">{{p.status}}</span>
                                            </td>   
                                            <td class="text-center" ng-if="p.status != 'PENDING' "> 
                                                <span class="badge badge-pill badge-success">{{p.status}}</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <button 
                                                        type="button" 
                                                        class="btn btn-success btn-flat btn-sm dropdown-toggle" 
                                                        data-toggle="dropdown">Action
                                                    </button>
                                                    <div class="dropdown-menu" style="margin-right: 50px;">
                                                        <?php if ($this->session->userdata('userType') == 'Accounting' || $this->session->userdata('userType') == 'Admin') : ?>
                                                            <a                                                            
                                                                class="dropdown-item"                                                             
                                                                href="#"
                                                                data-toggle="modal"
                                                                title="View Item"
                                                                data-target="#viewPiDetails"
                                                                ng-click="viewPiDetails(p)">
                                                                <i class="fas fa-search" style="color: green;"></i> View
                                                            </a>                                                         
                                                            <a                                                            
                                                                class="dropdown-item"
                                                                title="Tag this PI"
                                                                href="#"
                                                                data-toggle="modal" 
                                                                data-target="#"
                                                                ng-click="tag(p)">
                                                                <i class="fas fa-tasks" style="color: green;"></i> Tag/Match
                                                            </a>
                                                            <a                                                            
                                                                class="dropdown-item"
                                                                title="Upload CM for this PI"
                                                                href="#"
                                                                data-toggle="modal" 
                                                                data-target="#uploadCMForm"
                                                                ng-if="!p.cm_no"
                                                                ng-click="applyCm(p)">
                                                                <i class="fas fa-upload" style="color: green;"></i> Upload CM
                                                            </a>
                                                            <a                                                            
                                                                class="dropdown-item"
                                                                title="Change Status"
                                                                href="#"
                                                                data-toggle="modal" 
                                                                data-target="#" 
                                                                ng-click="changeStatus(p)">
                                                                <i class="fas fa-highlighter" style="color: red;"></i> Change Status
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>                                            
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- PI TABLE  -->
                        </div>
                    </div>
                </div>
                <!-- /.col-md-6 -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

    <!-- MODAL UPLOAD PROFORMA VS PI -->
    <div class="modal fade" id="addProformaVSPI"  data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-lg" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header bg-dark">
                    <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-upload"></i> Upload New PI </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" id="uploadProformaPi" ng-submit="uploadPi($event)" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group" ng-init="loadSupplier()">
                                    <label for="selectSupplier">Supplier Name: </label>
                                    <select name= "selectSupplier" ng-model="selectSupplier" class="form-control rounded-0" required>
                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                        <option ng-repeat="supplier in suppliers" value="{{supplier.supplier_id}}">{{supplier.supplier_name}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group" ng-init="loadCustomer()">
                                    <label for="selectCustomer">Location Name: </label>
                                    <select name="selectCustomer" ng-model="selectCustomer" class="form-control rounded-0" required>
                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                        <option ng-repeat="customer in customers" value="{{customer.customer_code}}">{{customer.customer_name}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="piFile">Purchase Invoice : </label>
                                    <input type="file" class="form-control rounded-0" style="height:45px" id="piFile" ng-model="piFile" name="piFile[]" required multiple>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn bg-gradient-primary btn-flat" ><i class="fas fa-upload"></i> Upload</button>
                            <button type="button" class="btn btn-dark btn-flat" data-dismiss="modal" ng-click="closeProformaPi()">Close</button>
                        </div>
                    </form>
                </div>           
            </div>
        </div>
    </div>

    <!-- MODAL VIEW PI DETAILS  -->
    <div class="modal fade" id="viewPiDetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header bg-dark">
                    <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-search"></i> View Purchase Invoice Details </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body modal-xl">
                    <!-- <form action=""> -->
                        <div class="row">                                         
                            <div class="col-md-12" style="overflow-y: scroll; height: 500px;"> 
                                <!-- <table id="viewPiLine" class="table table-bordered table-hover table-sm">
                                    <thead class="bg-dark">
                                        <tr>                                        
                                            <th scope="col" style="width: 8%"  class="text-center">Item</th>
                                            <th scope="col" style="width: 20%" class="text-center">Description</th>
                                            <th scope="col" style="width: 8%"  class="text-center">Qty</th>
                                            <th scope="col" style="width: 8%"  class="text-center">UOM</th>
                                            <th scope="col" style="width: 15%" class="text-center">Unit Cost</th>
                                            <th scope="col" style="width: 15%" class="text-center">Amount</th>
                                            <th scope="col" style="width: 17%" class="text-center">Remarks</th>
                                            <th scope="col" style="width: 8%"  class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="d in details">
                                            <td class="text-center" style="color:red; font-weight: bold;">                                               
                                                 <button 
                                                    type="button"
                                                    title="View Price History"
                                                    class="btn btn-success btn-flat btn-sm"
                                                    data-toggle="modal"
                                                    data-target="#viewItemPriceLog"
                                                    ng-click="itemPricelog(d)">
                                                    {{d.item_code}}
                                                </button>
                                            </td>
                                            <td class="text-center">{{d.description}}</td>
                                            <td class="text-center">
                                                <input type="text" id="qty" class="form-control" ng-model="d.qty" readonly>
                                            </td>
                                            
                                            <td class="text-center">{{d.uom}}</td> 
                                            <td class="text-center">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">₱</span>
                                                    </div>                                             
                                                    <input type="text" id="unitCost" class="form-control" ng-model="d.direct_unit_cost  " ng-disabled="!d.chk">
                                                </div>
                                            </td> 
                                            <td class="text-center">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">₱</span>
                                                    </div>                                             
                                                    <input type="text" id="amount" class="form-control"  ng-value="d.amt_including_vat | currency: '' " ng-disabled="!d.chk">
                                                </div>
                                            </td> 
                                            <td class="text-center">
                                                <textarea ng-model="d.remarks" cols="20" rows="2" maxlength="75" ng-disabled="!d.chk"></textarea>
                                            </td> 
                                            <td>
                                                <div class="col-lg-3">
                                                    <input type="checkbox" ng-model="d.chk" style="width: 50px; height: 30px" class="form-control rounded-0" ng-change="isChecked()">
                                                </div>
                                            </td> 
                                        </tr>
                                    </tbody>
                                </table> -->
                                <table id="viewPiLine" class="table table-bordered table-hover table-sm">
                                    <thead class="bg-dark">
                                        <tr>                                        
                                            <th scope="col" style="width: 100px" class="text-center">Item Code</th>
                                            <th scope="col" class="text-center">Description</th>
                                            <th scope="col" class="text-center">Qty</th>
                                            <th scope="col" class="text-center">UOM</th>
                                            <th scope="col" class="text-center">Direct Unit Cost</th>
                                            <th scope="col" class="text-center">Amount</th>
                                            <th scope="col" class="text-center">Remarks</th>
                                            <th scope="col" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>               
                                        <tr ng-repeat="d in details" ng-cloak>
                                            <td class="text-center" style="color:red; font-weight: bold;">
                                                <button
                                                    title="Edit Item Details"
                                                    class="btn btn-info btn-flat btn-sm"
                                                    ng-disabled="!canEdit"
                                                    ng-click="fetchItemPrice(d)">
                                                    {{d.item_code}}
                                                </button>
                                            </td>
                                            <td class="text-center">{{d.description}}</td>
                                            <td class="text-center">{{d.qty}}</td>
                                            <td class="text-center">{{d.uom}}</td> 
                                            <td class="text-center">{{d.direct_unit_cost | currency: '₱ '}}</td> 
                                            <td class="text-center">{{d.amt_including_vat | currency:'₱ '}}</td> 
                                            <td class="text-center">{{d.remarks}}</td> 
                                            <td class="text-center">
                                                <button 
                                                    type="button"
                                                    title="View History"
                                                    class="btn btn-success btn-flat btn-sm"
                                                    data-toggle="modal"
                                                    data-target="#viewItemPriceLog"
                                                    ng-click="itemPricelog(d)">
                                                    <i class="fas fa-history"></i>
                                                </button>
                                            </td> 
                                        </tr>    
                                    </tbody>
                                </table>                               
                            </div>                      
                        </div>  
                        <div class="modal-footer">
                            <button type="button" id="updateItemBtn" class="btn btn-danger btn-flat" ng-click="managersKey($event)"><i class="fas fa-pen-square"></i> Update</button>
                            <button type="button" class="btn btn-dark btn-flat" data-dismiss="modal" ng-click="closeViewPi()"> Close</button>
                        </div>  
                    <!-- </form>                 -->
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL VIEW PI DETAILS -->

    <!-- EDIT PRICE MODAL -->
    <div class="modal fade" id="updatePrice" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content rounded-0 modal-md">
                <div class="modal-header bg-dark">
                    <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-edit"></i> Edit Item Details</h5>                      
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">                       
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="itemDesc" class="col-sm-4 col-form-label">Item Description : </label>
                            <input type="text" ng-model="itemDesc" value="" class="form-control rounded-0" readonly>
                        </div>
                    </div>                    
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="itemQty" class="col-sm-6 col-form-label">Quantity : </label>
                            <input type="text" ng-model="itemQty" value="" class="form-control rounded-0" readonly>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="newPrice" class="col-sm-12 col-form-label">New Price (incl.VAT) : </label>
                                    <input type="text" ng-model="newPrice" value="" ng-disabled="itemQty == 0" class="form-control rounded-0" ng-change="calculate()" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="newAmount" class="col-sm-12 col-form-label">New Amount (incl.VAT) : </label>
                                    <input type="text" ng-model="newAmount" value="" class="form-control rounded-0" readonly>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="itemDesc" class="col-sm-4 col-form-label">Remarks : </label>
                            <textarea ng-model="itemRemarks" cols="53" rows="3" maxlength="75"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-flat" ng-click="updatePrice($event)"><i class="fas fa-save"></i> Save</button>
                        <button type="button" class="btn btn-dark btn-flat" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                    </div>
                </div>                
            </div>
        </div>
    </div>
     <!-- EDIT PRICE MODAL -->

    <!-- MODAL ITEM PRICE LOG -->
    <div class="modal fade" id="viewItemPriceLog" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header bg-dark">
                    <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-history"></i> Item Price Log </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">     
                    <form id="itemPriceLog">            
                        <div class="col-md-12">
                            <div class="row">
                                <table class="table table-bordered table-sm">
                                    <thead class="bg-dark">
                                        <tr> 
                                            <th scope="col" class="text-center">Item</th>
                                            <th scope="col" class="text-center">Quantity</th>
                                            <th scope="col" class="text-center">UOM</th>
                                        </tr>
                                    </thead>
                                    <tbody>                                    
                                        <tr>
                                            <td class="text-center">{{ itemCode }}</td>
                                            <td class="text-center">{{ quantity }}</td>
                                            <td class="text-center">{{ uom }}</td>                                    
                                        </tr>    
                                    </tbody> 
                                </table>
                            
                                <table id="priceLogTable" class="table table-bordered table-sm">
                                    <thead class="bg-dark">
                                        <tr> 
                                            <th scope="col" class="text-center">Price(Old)</th>
                                            <th scope="col" class="text-center">Amount(Old)</th>
                                            <th scope="col" class="text-center">Date Edited</th>
                                            <th scope="col" class="text-center">Changed By</th>
                                        </tr>
                                    </thead>
                                    <tbody>                                    
                                        <tr ng-repeat="pl in pricelog">
                                            <td class="text-center">{{ pl.old_price | currency:'₱ ': '5'}}</td>
                                            <td class="text-center">{{ pl.old_amt | currency:'₱ ' }}</td>
                                            <td class="text-center">{{ pl.changed_date | date:'mediumDate'}}</td> 
                                            <td class="text-center">{{ pl.username}}</td>                                    
                                        </tr>    
                                    </tbody> 
                                </table> 
                            </div> 
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-dark btn-flat" data-dismiss="modal">Close</button>
                        </div>  
                    </form>                           
                </div>                  
            </div>
        </div>
    </div>
    <!-- MODAL ITEM PRICE LOG -->

     <!-- TAG PI -->
    <div class="modal fade" id="tagPi" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content rounded-0 modal-md">
                <div class="modal-header bg-dark">
                    <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-file-alt"></i> PROFORMA vs PI</h5>                      
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" id="applyPiForm" ng-submit="applyPi($event)" enctype="multipart/form-data">  
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group" >
                                        <label for="crf" class="col-sm-12 col-form-label">CRF/CV : </label>
                                        <select id="crf" name="crf" ng-model="crf" class="form-control rounded-0" ng-change="loadProfPi(crf,crfs)" required>
                                            <option value="" selected="" style="display:none">Please Select One</option>
                                            <option ng-repeat="crf in crfs" value="{{crf.crf_id}}">{{crf.crf_no}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="crfDate" class="col-sm-12 col-form-label">Date : </label>
                                        <input type="text" style="border:none" id="crfDate" value="{{crfDate | date:'mediumDate'}}" value="" class="form-control rounded-0" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="crfAmount" class="col-sm-12 col-form-label">CRF/CV Amount : </label>
                                        <input type="text" style="border:none" id="crfAmount" value="{{crfAmount | currency:'₱ '}}" value="" class="form-control rounded-0" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="sopNo" class="col-sm-12 col-form-label">SOP No : </label>
                                        <input type="text" style="border:none" id="sopNo" ng-model="sopNo " value="" class="form-control rounded-0" readonly>
                                        <input type="hidden" name="" ng-model="sopId" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group" >
                                        <label for="selectVendorsDeal" class="col-sm-12 col-form-label">Vendor's Deal</label>
                                        <select id="vendorsdeal" name="vendorsdeal" ng-model="selectVendorsDeal" class="form-control rounded-0" ng-change="displayVendorsdDealToInput(selectVendorsDeal,vendorsDeal)">
                                            <option ng-if="vendorsDeal ==''" value="" disabled="" selected="" style="display:none">No Data Found</option>
                                            <option value="" selected="" style="display:none">Please Select One</option>
                                            <option ng-repeat="deal in vendorsDeal" value="{{deal.vendor_deal_head_id}}">{{deal.vendor_deal_code}}</option>
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
                        </div>  
                   
                        <div class="col-md-12" style="overflow-y: scroll; height: 300px;">
                            <div class="row">
                                <!-- PROF TABLE   -->
                                <div class="col-md-6">                                  
                                    <!-- <button type="button" class="btn btn-dark btn-flat btn-block"> PROFORMA</button> -->
                                    <strong>PROFORMA</strong>
                                    <table id="profTable"  class="table table-bordered table-sm">
                                        <thead class="bg-dark">
                                            <tr>
                                                <th scope="col" class="text-center">Location</th>
                                                <th scope="col" class="text-center">Proforma</th>
                                                <th scope="col" class="text-center">Date</th>
                                                <th scope="col" class="text-center">PO No</th>
                                                <th scope="col" class="text-center">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>   
                                            <tr ng-repeat="f in profInCrf" ng-cloak>
                                                <td class="text-center">{{f.loc}}</td>
                                                <td class="text-center">{{f.profCode}}</td>
                                                <td class="text-center">{{f.delivery}}</td>
                                                <td class="text-center">{{f.po}}</td>
                                                <td class="text-center">{{f.total | currency :''}}</td>                                                    
                                            </tr> 
                                        </tbody> 
                                    </table> 
                                </div> 
                                 <!-- PROF TABLE   -->
                                  <!-- PI TABLE   -->
                                <div class="col-md-6">
                                    <!-- <button type="button" class="btn btn-dark btn-flat btn-block"> PURCHASE INVOICE</button> -->
                                    <strong>PURCHASE INVOICE</strong>
                                    <table id="piTable" class="table table-bordered table-sm">
                                        <thead class="bg-dark">
                                            <tr> 
                                                <th scope="col" class="text-center">Location</th>
                                                <th scope="col" class="text-center">PI No</th>
                                                <th scope="col" class="text-center">Date</th>
                                                <th scope="col" class="text-center">PO No</th>
                                                <th scope="col" class="text-center">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>                                    
                                            <tr ng-repeat="pc in piInCrf" ng-cloak> 
                                                <td class="text-center">{{pc.loc}}</td>
                                                <td class="text-center">{{pc.piNo}}</td>
                                                <td class="text-center">{{pc.postDate}}</td>
                                                <td class="text-center">{{pc.po}}</td> 
                                                <td class="text-center">{{pc.total_amount | currency :''}}</td>          
                                            </tr>    
                                        </tbody> 
                                    </table>
                                </div>
                                <!-- PI TABLE   -->
                            </div>                                                 
                        </div> 
                        <?php if ($this->session->userdata('userType') == 'Admin' || $this->session->userdata('userType') == 'Accounting'): ?>
                        <div class="">
                            <button                                
                                type="button"   
                                id="btnMatch"                             
                                class="btn btn-success btn-flat btn-block"                                
                                ng-disabled="proceedMatchPi == 0 || proceedMatchProf == 0"
                                ng-click="matchProformaVsPi(crf,$event)">
                                <i class="fas fa-link"></i> Match PROFORMA VS PI 
                            </button>      
                        </div>
                        <?php endif; ?>
                        <div class="modal-footer">
                            <?php if ($this->session->userdata('userType') == 'Admin' || $this->session->userdata('userType') == 'Accounting'): ?>
                                <button type="submit"  id="btnTag" title="Apply PI under this CRF" class="btn btn-danger btn-flat"><i class="fas fa-tag"></i> TAG PI</button>
                                <button type="button"  id="btnUntag" title="Apply PI under this CRF" class="btn btn-danger btn-flat" ng-click="untagPi($event)"><i class="fas fa-unlink"></i> UNTAG PI</button>
                                <!-- <button type="button"  id="" title="Apply PI under this CRF" class="btn btn-danger btn-flat" data-toggle="modal" data-target="#viewMatchItems" ng-click="matchItems(crf)">View Match Items</button> -->
                            <?php endif; ?>
                            <button type="button" id="btnClose" class="btn btn-dark btn-flat" data-dismiss="modal" > Close</button>
                            <!-- <a                                                            
                                class="dropdown-item"                                                             
                                href="#"
                                data-toggle="modal"
                                title="Match Items"
                                data-target="#viewMatchItems"
                                ng-click="matchItems(p)">
                                <i class="fas fa-search" style="color: green;"></i> Match Items
                            </a>  -->
                        </div>
                    </form>                   
                </div>                
            </div>
        </div>
    </div>
    <!-- TAG PI -->

    <!-- MANAGER'S KEY -->
    <div class="modal fade" id="managersKey" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header bg-dark rounded-0">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-key"></i> Manager's Key</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post" enctype="multipart/form-data" ng-submit="updateItem($event)">
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">  
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"> <i class="fa fa-user"></i></span>
                                    </div>
                                    <input type="text" id="user" ng-model="user" name="user"  placeholder="Username" class="form-control rounded-0" autocomplete="off" autofocus>  
                                </div>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-unlock-alt"></i></span>
                                    </div>
                                    <input type="password" id="pass" ng-model="pass" name="pass" placeholder="Password" class="form-control rounded-0" autocomplete="off">
                                </div> 
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-flat"><i class="fas fa-key"></i> Authorize</button>
                        <button type="button" class="btn btn-dark btn-flat" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- MANAGER'S KEY -->

     <!-- MODAL VIEW PI DETAILS MATCH ITEMS  -->
    <div class="modal fade" id="viewMatchItems" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header bg-dark rounded-0">
                    <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-info-circle"></i> View Matched/Unmatched Items </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body modal-xl">
                    <form action="">
                        <div class="row">
                            <!-- <table class="table table-bordered table-sm">
                                <thead class="bg-dark">
                                    <tr>
                                        <th scope="col" class="text-center">PO No</th>
                                        <th scope="col" class="text-center">Reference</th>
                                        <th scope="col" class="text-center">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">{{ poNo  }}</td>
                                        <td class="text-center">{{ poRef }}</td>
                                        <td class="text-center">{{poAmt | currency:'₱ '}}</td>
                                    </tr>
                                </tbody>
                            </table> -->

                            <table id="matchProfPiItems" class="table table-bordered table-sm table-hover">
                                <thead class="bg-dark">
                                    <tr>
                                        <th scope="col" style="width:5%"  class="text-center">#</th>
                                        <th scope="col" style="width:25%"  class="text-center">PSI Item Code</th>
                                        <th scope="col" style="width:25%"  class="text-center">PI Item Code</th>
                                        <th scope="col" style="width:35%"  class="text-center">Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="same in sameItemSamePo">
                                        <td class="text-center">{{$index + 1}}</td>
                                        <td class="text-center">{{same.itemProf}}</td>                                        
                                        <td class="text-center">{{same.itemPi}}</td>
                                        <td class="text-center">{{same.desc}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-dark btn-flat" data-dismiss="modal" ng-click="closeViewPi()"> Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
     <!-- MODAL VIEW PI DETAILS MATCH ITEMS  -->

     <!-- MODAL UPLOAD CM -->
    <div class="modal fade" id="uploadCMForm"  data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-md" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header bg-dark">
                    <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-upload"></i> Upload Credit Memo </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" name="uploadCM" id="uploadCM" ng-submit="uploadCm($event)" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="piNo">Purchase Invoice(PI) ID: </label>                                    
                                    <input type="text" style="border:none;text-align:left;font-weight:bold" ng-model="piId" name="piId" class="form-control rounded-0" readonly>                                    
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="piNo">Purchase Invoice(PI) No: </label>                                    
                                    <input type="text" style="border:none;text-align:left;font-weight:bold" ng-model="piNo" name="piNo" class="form-control rounded-0" readonly>                                    
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="cmFile">Credit Memo : </label>
                                    <input type="file" class="form-control rounded-0" style="height:45px" id="cmFile" ng-model="cmFile" name="cmFile" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn bg-gradient-primary btn-flat"><i class="fas fa-upload"></i> Upload</button>
                            <button type="button" class="btn btn-dark btn-flat" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>           
            </div>
        </div>
    </div>
    <!-- MODAL UPLOAD CM -->

     <!-- MODAL CM DETAILS  -->
     <div class="modal fade" id="viewCM" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header bg-dark rounded-0">
                    <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-info-circle"></i> Credit Memo Details </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body modal-xl">
                    <form action="">
                        <div class="row">
                            <table class="table table-bordered table-sm">
                                <thead class="bg-dark">
                                    <tr>
                                        <th scope="col" class="text-center">CM No</th>
                                        <th scope="col" class="text-center">Posting Date</th>
                                        <th scope="col" class="text-center">PI Applied</th>
                                        <th scope="col" class="text-center">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">{{ cmNo  }}</td>
                                        <td class="text-center">{{ cmPostingDate }}</td>
                                        <td class="text-center">{{ cmPI }}</td>
                                        <td class="text-center">{{ cmAmount | currency:'₱ '}}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <table id="viewCmLine" class="table table-bordered table-sm table-hover">
                                <thead class="bg-dark">
                                    <tr>
                                        <th scope="col" style="width: 100px" class="text-center">#</th>
                                        <th scope="col" style="width: 100px" class="text-center">Item Code</th>
                                        <th scope="col" class="text-center">Description</th>
                                        <th scope="col" class="text-center">Quantity</th>
                                        <th scope="col" class="text-center">UOM</th>
                                        <th scope="col" class="text-center">Unit Cost</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="cm in cmDetails">
                                        <td class="text-center">{{$index + 1}}</td>
                                        <td class="text-center">{{cm.item_code}}</td>
                                        <td class="text-center">{{cm.description}}</td>
                                        <td class="text-center">{{cm.qty}}</td>
                                        <td class="text-center">{{cm.uom}}</td>
                                        <td class="text-center">{{cm.price | currency:'₱ '}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-dark btn-flat" data-dismiss="modal" ng-click="closeViewPi()"> Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL CM DETAILS -->
    
</div>
<!-- /.content-wrapper -->




 