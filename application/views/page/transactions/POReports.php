<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper body-bg" ng-controller="po-controller">
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-style-1">
                        <div class="card-header bg-dark rounded-0">
                            <div class="content-header" style="padding: 0px">
                                <div class="panel panel-default">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel-body"><i class="fas fa-file-alt"></i> <strong>PURCHASE ORDER</strong></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php if ($this->session->userdata('userType') == 'Buyer-Purchaser' || $this->session->userdata('userType') == 'Admin') : ?>
                                    <div class="col-md-12 mb-4">
                                        <button class="btn bg-gradient-primary btn-flat" data-target="#addPOReport" data-toggle="modal"><i class="fas fa-upload"></i> Upload PO </button>
                                    </div>
                                <?php endif; ?>
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
                                        <button type="button" class="btn bg-gradient-primary btn-block btn-flat" ng-click="poTable()" ng-disabled="!supplierName && !locationName">GET PENDING MATCHES</button>
                                    </div>
                                </div>
                            </div>

                            <div ng-if="poList">
                                <table id="poTable" class="table table-bordered table-sm table-hover">
                                    <thead class="bg-dark">
                                        <tr>
                                            <th scope="col" class="text-center">PO No - Reference</th>
                                            <th scope="col" class="text-center">Order Date</th>
                                            <th scope="col" class="text-center">Business Unit Matched</th>
                                            <th scope="col" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="p in po" ng-cloak>
                                            <td class="text-center">{{p.poNo}} - {{p.ref}}</td>
                                            <td class="text-center">{{p.orderDate | date:'mediumDate'}}</td>
                                            <td class="text-center">{{p.cusName}} vs. {{p.supName}}</td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn bg-gradient-info btn-flat btn-sm dropdown-toggle" data-toggle="dropdown">Action
                                                    </button>
                                                    <div class="dropdown-menu" style="margin-right: 50px;">
                                                        <a class="dropdown-item" title="View Item" href="#" data-toggle="modal" data-target="#viewDetails" ng-click="viewPoDetails(p)">
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

    <!-- MODAL UPLOAD PO -->
    <div class="modal fade" id="addPOReport" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header bg-dark rounded-0">
                    <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-upload"></i> Upload New PO </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" id="uploadPoForm" ng-submit="uploadPo($event)" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group" ng-init="loadSupplier()">
                                    <label for="selectSupplier">Supplier Name: </label>
                                    <select ng-model="selectSupplier" name="selectSupplier" class="form-control rounded-0" required>
                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                        <option ng-repeat="supplier in suppliers" value="{{supplier.supplier_id}}">{{supplier.supplier_name}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group" ng-init="loadCustomer()">
                                    <label for="selectCustomer">Location Name: </label>
                                    <select ng-model="selectCustomer" name="selectCustomer" class="form-control rounded-0" required>
                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                        <option ng-repeat="customer in customers" value="{{customer.customer_code}}">{{customer.customer_name}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="pofile">Purchase Order: </label>
                                    <input type="file" class="form-control rounded-0" style="height:45px" id="pofile" ng-model="pofile" name="pofile[]" required multiple>
                                </div>
                            </div>                            
                        </div>
                        
                        <div class="modal-footer">
                            <button type="submit" class="btn bg-gradient-primary btn-flat"><i class="fas fa-upload"></i> Upload</button>
                            <button type="button" class="btn bg-gradient-danger btn-flat" data-dismiss="modal" ng-click="closePoForm()">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL VIEW PO DETAILS  -->
    <div class="modal fade" id="viewDetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header bg-dark rounded-0">
                    <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-info-circle"></i> Purchase Order Details </h5>
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
                            </table>

                            <table id="viewPiLine" class="table table-bordered table-sm table-hover">
                                <thead class="bg-dark">
                                    <tr>
                                        <th scope="col" style="width: 100px" class="text-center">#</th>
                                        <th scope="col" style="width: 100px" class="text-center">Item Code</th>
                                        <th scope="col" class="text-center">Description</th>
                                        <th scope="col" class="text-center">Quantity</th>
                                        <th scope="col" class="text-center">UOM</th>
                                        <th scope="col" class="text-center">Direct Unit Cost</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="d in poDetails">
                                        <td class="text-center">{{$index + 1}}</td>
                                        <td class="text-center">{{d.item_code}}</td>
                                        <td class="text-center">
                                            <span class="no-item" ng-if="!d.description">
                                                UNKNOWN ITEM
                                            </span>
                                            <span ng-if="d.description">
                                                {{d.description}}
                                            </span>
                                        </td>
                                        <td class="text-center">{{d.qty}}</td>
                                        <td class="text-center">{{d.uom}}</td>
                                        <td class="text-center">{{d.direct_unit_cost | currency:'₱ '}}</td>
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
    <!-- MODAL VIEW PI DETAILS -->



</div>
<!-- /.content-wrapper -->