<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper body-bg" ng-controller="proformavscrf-controller">
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
                                            <div class="panel-body"><i class="fas fa-file-alt"></i> <strong>PROFORMA VS CRF/CV</strong></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <button class="btn bg-gradient-primary btn-flat" data-target="#addProformaVsCRFReport" data-toggle="modal"><i class="fas fa-upload"></i> Upload CRF/CV</button>
                                </div>
                            </div>

                            <hr>

                            <div class="container" style="padding-left: 220px; padding-right: 220px;">
                                <div class="row col-lg-12">
                                    <div class="col-lg-6">
                                        <div class="form-group" ng-init="getSuppliers()">
                                            <label for="supplierName">Supplier Name</label>
                                            <select class="form-control rounded-0" ng-model="supplierName" name="supplierName" required>
                                                <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                <option ng-repeat="s in suppliers" value="{{s.supplier_id}}">{{s.supplier_name}}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group" ng-init="getCustomers()">
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
                                        <button type="button" class="btn btn-primary btn-block btn-flat" ng-click="getCrfs()" ng-disabled="!supplierName && !locationName">GET PENDING MATCHES</button>
                                    </div>
                                </div>
                            </div>

                            <div ng-if="pendingCrf">
                                <table id="crfTable" class="table table-bordered table-hover table-sm">
                                    <thead class="bg-dark">
                                        <tr>
                                            <th scope="col" class="text-center">Supplier</th>
                                            <th scope="col" class="text-center">CRF/CV No.</th>
                                            <th scope="col" class="text-center">Date</th>
                                            <th scope="col" style="width: 100px;" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="cf in crf" ng-cloak>
                                            <td class="text-center">{{cf.supplier_name}}</td>
                                            <td class="text-center">{{cf.crf_no}}</td>
                                            <td class="text-center">{{cf.crf_date | date:'mediumDate'}}</td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-success btn-flat btn-sm dropdown-toggle" data-toggle="dropdown">Action
                                                    </button>
                                                    <div class="dropdown-menu" style="margin-right: 50px;">
                                                        <?php if ($this->session->userdata('userType') == 'Accounting' || $this->session->userdata('userType') == 'Admin') : ?>
                                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#" ng-click="applyProforma(cf)">
                                                                <i class="fas fa-list" style="color: green;"></i> Tag/Match
                                                            </a>
                                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#" ng-click="changeStatus(cf)">
                                                                <i class="fas fa-highlighter" style="color: red;"></i> Change Status
                                                            </a>
                                                        <?php endif;?>
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
    <!-- MODAL UPLOAD PROFORMA VS CRF -->
    <div class="modal fade" id="addProformaVsCRFReport" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-lg" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header bg-dark">
                    <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-upload"></i> Upload New CRF</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" name="uploadProCrf" id="uploadProCrf" ng-submit="uploadCrf($event)" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group" ng-init="getSuppliers()">
                                    <label for="selectSupplier">Supplier Name: </label>
                                    <select name="selectSupplier" ng-model="selectSupplier" class="form-control rounded-0" required>
                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                        <option ng-repeat="supplier in suppliers" value="{{supplier.supplier_id}}">{{supplier.supplier_name}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group" ng-init="getCustomers()">
                                    <label for="selectCustomer">Customer Name: </label>
                                    <select name="selectCustomer" ng-model="selectCustomer" ng-change="getSop(selectSupplier,selectCustomer)" class="form-control rounded-0" required>
                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                        <option ng-repeat="customer in customers" value="{{customer.customer_code}}">{{customer.customer_name}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="selectSop">SOP No: </label>
                                    <select name="selectSop" ng-model="selectSop" class="form-control rounded-0" required>
                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                        <option ng-repeat="s in sops" value="{{s.sop_id}}">{{s.sop_no}}-{{s.net_amount | currency:''}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="crfFile">CRF/CV : </label>
                                    <input type="file" class="form-control rounded-0" style="height:45px" id="crfFile" ng-model="crfFile" name="crfFile" onchange="angular.element(this).scope().checkExt(this)" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn bg-gradient-primary btn-flat"><i class="fas fa-upload"></i> Upload</button>
                            <button type="button" class="btn btn-dark btn-flat" data-dismiss="modal" ng-click="closeCrf()">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL UPLOAD PROFORMA VS CRF -->

    <!-- EDIT APPLY PROFORMA TO CRF -->
    <div class="modal fade" id="applyProformaToCrf" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content rounded-0 modal-md">
                <div class="modal-header bg-dark">
                    <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-file-alt"></i> PROFORMA vs CRF</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- <form id="applyMatchForm">                -->
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="crfNo" class="col-sm-12 col-form-label">CRF/CV No</label>
                                    <input type="text" style="border:none" ng-model="crfNo" value="" class="form-control rounded-0" readonly>
                                    <input type="hidden" ng-model="hiddenSupplier">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="crfDate" class="col-sm-12 col-form-label">Date</label>
                                    <input type="text" style="border:none" value="{{crfDate | date:'mediumDate' }}" value="" class="form-control rounded-0" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="crfAmount" class="col-sm-12 col-form-label">Amount</label>
                                    <input type="text" style="border:none" value="{{crfAmount | currency:'₱ '}}" class="form-control rounded-0" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sopNo" class="col-sm-12 col-form-label">SOP No</label>
                                    <input type="text" style="border:none" ng-model="sopNo" class="form-control rounded-0" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" >
                                    <label for="vendorsdeal" class="col-sm-12 col-form-label">Vendor's Deal</label>
                                    <select id="vendorsdeal" name="vendorsdeal" class="form-control rounded-0" ng-disabled="hasDeal == '0' " ng-model="vendorsdeal"  ng-change="displayVendorsdDealToInput(vendorsdeal,vendorsDeal)" required>
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
                            <div class="col-md-12" style="overflow-y: scroll; height: 200px;">
                                <table id="appliedProforma" class="table table-bordered table-sm">
                                    <thead class="bg-dark">
                                        <tr>
                                            <th scope="col" class="text-center">Proforma Code</th>
                                            <th scope="col" class="text-center">Date</th>
                                            <th scope="col" class="text-center">PO No</th>
                                            <th scope="col" class="text-center">Item</th>
                                            <th scope="col" class="text-center">Add'l/Less</th>
                                            <th scope="col" class="text-center">Total</th>
                                            <th scope="col" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="a in applied" ng-cloak>
                                            <td class="text-center">{{a.proforma_code}}</td>
                                            <td class="text-center">{{a.delivery_date}}</td>
                                            <td class="text-center">{{a.po_no}}</td>
                                            <td class="text-center">{{a.item_total | currency : ''}}</td>
                                            <td class="text-center">{{a.add_less | currency : ''}}</td>
                                            <td class="text-center">{{a.total | currency : ''}}</td>
                                            <td class="text-center" >
                                                <a       
                                                   href="#"                                            
                                                   style="color:red"
                                                   title= "Untag Proforma"
                                                   ng-disabled="!proceedMatch"
                                                   ng-click="untagProforma(a)">
                                                   <i class="fas fa-unlink"></i>                                                
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-10">
                                <label for="searchProforma">Search PROFORMA under Supplier : {{sName}} </label>
                                <input type="text" id="searchProforma" ng-keyup="searchProf($event)" placeholder="Search by Proforma Code or Invoice No or Order No or PO No or PO Reference" class="form-control rounded-0" autocomplete="off" >
                                <div class="search-results" ng-repeat="s in searchResult " ng-if="hasResults == 1">
                                    <a 
                                        href="#" 
                                        ng-repeat="s in searchResult track by $index"                                        
                                        ng-click="getProf(s)">
                                        {{s.proforma_header_id}} - {{s.proforma_code}} - {{s.delivery_date}} - {{s.so_no}} - {{s.order_no}} -  {{s.po_no}} - {{s.po_reference}}<br>
                                    </a>                                  
                                </div>
                                <div class="search-results" ng-repeat="s in searchResult " ng-if="hasResults == 0">
                                    <a 
                                        href="#" 
                                        ng-repeat="s in searchResult">
                                        {{s.proforma_header_id}} <br>
                                    </a>                                  
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="btnTag" style="color:white">Proforma</label>
                                    <div class="button-group">
                                        <button type="button" id="btnTag" title="Apply Proforma under this CRF" class="btn btn-danger btn-flat" ng-disabled="proceedApply == 0" ng-click="applyProf()">
                                            <i class="fas fa-tag"></i> Tag
                                        </button>
                                    </div>
                                    <div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <button 
                                    type="button" 
                                    id="btnMatch" 
                                    class="btn btn-success btn-flat btn-block" 
                                    ng-disabled="proceedMatch == 0 || !vendorsdeal" 
                                    ng-click="matchProformaVsCrf($event)">
                                    <i class="fas fa-link"></i> Match PROFORMA VS CRF
                                </button>
                            </div>
                            <div class="col-md-12">
                                <div class="modal-footer">
                                    <button type="button" id="btnClose" class="btn btn-dark btn-flat" ng-click="resetProfVsCrf()" data-dismiss="modal"> Close</button>
                                </div>
                            </div>
                            <!-- </form>                   -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- EDIT APPLY PROFORMA TO CRF -->
        </div>
    </div>


</div>
<!-- /.content-wrapper -->