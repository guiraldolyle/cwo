<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper body-bg" ng-controller="povspro-controller">
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
                                            <div class="panel-body"><i class="fas fa-file-alt"></i> <strong>PO VS PROFORMA</strong></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn bg-gradient-primary btn-flat" data-target="#addPOReport" data-toggle="modal"><i class="fas fa-file-upload"></i> Upload Proforma</button>
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
                                        <button type="button" class="btn bg-gradient-primary btn-block btn-flat" ng-click="getPendingMatches()" ng-disabled="!supplierName && !locationName">GET PENDING MATCHES</button>
                                    </div>
                                </div>
                            </div>

                            <div ng-if="pendingMatchesTable">
                                <table id="proformaTable" class="table table-bordered table-sm table-hover">
                                    <thead class="bg-dark">
                                        <tr>
                                            <th scope="col" class="text-center">Business Unit Matched</th>
                                            <th scope="col" class="text-center">Purchase Order</th>
                                            <th scope="col" class="text-center">Proforma</th>
                                            <th scope="col" style="width: 50px;" class="text-center">Status</th>
                                            <th scope="col" style="width: 100px;" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="p in pendingMatches" ng-cloak>
                                            <td class="text-center"><i class="far fa-dot-circle"></i> {{p.supplier_name}} vs {{p.customer_name}}</th>
                                            <td class="text-center">{{p.po_no}}</th>
                                            <td class="text-center">{{p.proforma_code}}</td>
                                            <td class="text-center">
                                                <button ng-if="p.proforma_stat == '0' || p.proforma_stat == '3'" class="btn bg-gradient-danger btn-flat btn-sm">PENDING</button>
                                                <!-- <button ng-if="p.proforma_stat == '1'" class="btn bg-gradient-danger btn-flat btn-sm" disabled>PENDING</button> -->
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn bg-gradient-info btn-flat btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action
                                                    </button>
                                                    <div class="dropdown-menu rounded-0" style="margin-right: 80px;">
                                                        <a class="dropdown-item" href="#" data-target="#matchingModal" data-toggle="modal" ng-click="getItems(p)">
                                                            <i class="fas fa-link" style="color: green;"></i> Match
                                                        </a>
                                                        <a class="dropdown-item" href="#" data-target="#viewProforma" data-toggle="modal" ng-click="view(p)">
                                                            <i class="fas fa-pen-square" style="color: green;"></i> View/Edit
                                                        </a>

                                                        <?php if ($this->session->userdata('authorize_id') == '' || $this->session->userdata('authorize_id') == null) : ?>
                                                            <a class="dropdown-item" href="#" ng-click="managersKey(p, 'managersKey', 'addDiscountsAddition')">
                                                                <i class="fas fa-percentage" style="color: green;"></i> Discounts/VAT
                                                            </a>
                                                        <?php else : ?>
                                                            <a class="dropdown-item" href="#" data-target="#addDiscountsAddition" data-toggle="modal" ng-click="view(p)">
                                                                <i class="fas fa-percentage" style="color: green;"></i> Discounts/VATs
                                                            </a>
                                                        <?php endif; ?>

                                                        <a class="dropdown-item" href="#" data-target="#viewHistory" data-toggle="modal" ng-click="history(p)">
                                                            <i class="fas fa-history" style="color: red;"></i> History
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

    <!-- MODAL UPLOAD PO VS PROFORMA -->
    <div class="modal fade" id="addPOReport" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-md" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header bg-dark rounded-0">
                    <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-file-upload"></i> Upload Proforma</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post" enctype="multipart/form-data" ng-submit="uploadProforma($event)" name="addporeport" class="needs-validation">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group" ng-init="getSuppliers()">
                                    <label for="#"><i class="fab fa-slack required-icon"></i> Supplier Name: </label>
                                    <select ng-change="getPurchaseOrder()" class="form-control rounded-0" ng-model="supplierSelect" name="supplierSelect" required>
                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                        <option ng-repeat="s in suppliers" value="{{s.supplier_id}}">{{s.supplier_name}}</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please choose a supplier.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group" ng-init="getCustomers()">
                                    <label for="#"><i class="fab fa-slack required-icon"></i> Location Name: </label>
                                    <select ng-change="getPurchaseOrder()" class="form-control rounded-0" ng-model="customerSelect" name="customerSelect" required>
                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                        <option ng-repeat="c in customers" value="{{c.customer_code}}">{{c.customer_name}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="#"><i class="fab fa-slack required-icon"></i> Purchase Order: </label>
                                    <select class="form-control rounded-0" ng-model="poSelect" name="poSelect" required>
                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                        <option ng-repeat="p in po" value="{{p.po_header_id}}">{{p.po_no}}</option>
                                        <option value="" disabled="" ng-if="po == ''">No PO Available</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="#"><i class="fab fa-slack required-icon"></i> Proforma: </label>
                                    <input type="file" name="proforma[]" id="proforma" class="form-control rounded-0" style="height: 45px" required multiple>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn bg-gradient-primary btn-flat" ng-disabled="addporeport.$invalid"><i class="fas fa-upload"></i> Upload</button>
                        <button type="button" class="btn bg-gradient-danger btn-flat" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- VIEW PROFORMA -->
    <div class="modal fade" id="viewProforma" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-xl" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header bg-dark rounded-0">
                    <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-search-plus"></i> View Proforma</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post" enctype="multipart/form-data" ng-submit="editProforma($event)" name="viewForm" id="viewForm">
                    <!-- <form action="" method="post" enctype="multipart/form-data" name="viewForm" id="viewForm"> -->
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" class="form-control rounded-0" name="proforma_header_id" ng-value="proforma_header_id">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="acroname_edit">Acroname:</label>
                                    <input type="text" class="form-control rounded-0 read-only-color" name="acroname_edit" autocomplete="off" ng-model="acroname_edit" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="po_no">PO Number/PO Reference:</label>
                                    <input type="text" class="form-control rounded-0 read-only-color" name="po_no" autocomplete="off" ng-model="po_no" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="so_no">SO Number: </label>
                                    <input type="text" class="form-control rounded-0 read-only-color" name="so_no" autocomplete="off" ng-model="so_no" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="pro_code">Proforma Code: </label>
                                    <input type="text" class="form-control rounded-0 read-only-color" name="pro_code" autocomplete="off" ng-model="pro_code" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="container-fluid">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active rounded-0" id="proforma-line-tab" data-toggle="tab" href="#proforma-line" role="tab" aria-controls="home" aria-selected="true" style="color: black;" ng-click="tabs()">Proforma Line</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link rounded-0" id="discounts-tab" data-toggle="tab" href="#discounts" role="tab" aria-controls="profile" aria-selected="false" style="color: black;" ng-click="tabs()">Discounts and VAT</a>
                                </li>
                            </ul>

                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="proforma-line" role="tabpanel" aria-labelledby="proforma-line-tab">
                                    <div class="row mt-2" ng-if="tableRow">
                                        <table class="table-sm table table-bordered table-hover">
                                            <thead class="bg-dark">
                                                <tr>
                                                    <th class="text-center">Item Code</th>
                                                    <th class="text-center">Description</th>
                                                    <th class="text-right" style="width: 5%;">QTY</th>
                                                    <th class="text-center">UOM</th>
                                                    <th class="text-right" style="width: 15%;">Price</th>
                                                    <th class="text-right" style="width: 15%;">Amount</th>
                                                    <th class="text-center" style="width: 4%;;">Edit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr ng-repeat="pl in proforma_line">
                                                    <td>{{ pl.item_code }}</td>
                                                    <td>{{ pl.description}}</td>
                                                    <td>
                                                        <div class="input-group input-group-sm rounded-0">
                                                            <input type="text" class="form-control rounded-0 text-right" ng-model="pl.qty" style="border: none;" ng-disabled="!pl.checkBoxEdit">
                                                        </div>
                                                    </td>
                                                    <td>{{ pl.uom }}</td>
                                                    <td>
                                                        <div class="input-group input-group-sm rounded-0">
                                                            <div class="input-group-prepend rounded-0">
                                                                <span class="input-group-text rounded-0" style="border: 0; background-color: white;"><strong>₱</strong></span>
                                                            </div>
                                                            <input type="text" class="form-control rounded-0 text-right currency" ng-model="pl.price" style="border: none;" ng-disabled="!pl.checkBoxEdit">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm rounded-0">
                                                            <div class="input-group-prepend rounded-0">
                                                                <span class="input-group-text rounded-0" style="border: 0; background-color: white;"><strong>₱</strong></span>
                                                            </div>
                                                            <input type="text" class="form-control rounded-0 text-right currency" name="amount" id="amount" ng-value="pl.qty * pl.price | currency : ''" style="border: none;" readonly>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="col-lg-12">
                                                            <div class="input-group">
                                                                <input type="checkbox" style="width: 20px; height: 20px;" ng-model="pl.checkBoxEdit" ng-changed="setButtonEnabled()" class="rounded-0">
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="row mt-2" ng-if="uploadRow">
                                        <div class="col-md-12" style="padding-left: 200px; padding-right: 200px;">
                                            <div class="form-group">
                                                <label for="#">New Proforma: </label>
                                                <input type="file" name="new_proforma" id="new_proforma" class="form-control rounded-0" style="height: 45px">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="discounts" role="tabpanel" aria-labelledby="discounts-tab">
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="container-fluid" style="padding-left: 200px; padding-right: 200px;">
                                                <table ng-init="getDiscount()" class="table table-bordered table-sm table-hover">
                                                    <thead class="bg-dark">
                                                        <tr>
                                                            <th class="text-center">Discount/VAT</th>
                                                            <th class="text-center">Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr ng-repeat="d in discount" ng-cloak>
                                                            <th>{{ d.discount }}</th>
                                                            <td class="text-right">{{ d.total_discount | currency : ''}}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">

                        <?php if ($this->session->userdata('authorize_id') == '' || $this->session->userdata('authorize_id') == null) : ?>
                            <button type="button" class="btn bg-gradient-primary btn-flat" ng-click="authenticate()" ng-disabled="setButtonEnabled() || tabIndex" ng-if="tableRow">
                                <i class="fas fa-pen-square"></i> Update
                            </button>
                        <?php else : ?>
                            <button type="submit" class="btn bg-gradient-primary btn-flat" ng-disabled="setButtonEnabled() || tabIndex" ng-if="tableRow">
                                <i class="fas fa-pen-square"></i> Update
                            </button>
                        <?php endif; ?>

                        <button type="submit" class="btn bg-gradient-success btn-flat" ng-if="uploadRow">
                            <i class="fas fa-upload"></i> Replace Proforma
                        </button>

                        <button type="button" class="btn bg-gradient-info btn-flat" ng-click="replaceProforma()">
                            <i class="fas fa-undo-alt"></i>
                            {{buttonNAme}}
                        </button>

                        <button type="button" class="btn bg-gradient-danger btn-flat" data-dismiss="modal">
                            <i class="fas fa-times"></i> Close
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ADD Discounts/VAT -->
    <div class="modal fade" id="addDiscountsAddition" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-xl" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header bg-dark rounded-0">
                    <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-percentage"></i> Discounts/VAT</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post" enctype="multipart/form-data" ng-submit="addDiscountVAT($event)" name="discountVATForm" id="discountVATForm">
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" class="form-control rounded-0" name="proforma_header_id" ng-value="proforma_header_id">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="acroname_edit">Acroname:</label>
                                    <input type="text" class="form-control rounded-0 read-only-color" name="acroname_edit" autocomplete="off" ng-model="acroname_edit" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="po_no">PO Number/PO Reference:</label>
                                    <input type="text" class="form-control rounded-0 read-only-color" name="po_no" autocomplete="off" ng-model="po_no" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="so_no">SO Number: </label>
                                    <input type="text" class="form-control rounded-0 read-only-color" name="so_no" autocomplete="off" ng-model="so_no" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="pro_code">Proforma Code: </label>
                                    <input type="text" class="form-control rounded-0 read-only-color" name="pro_code" autocomplete="off" ng-model="pro_code" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="container-fluid">
                                <div class="row mt-1">
                                    <label for="discount" style="margin-left: 25px;">Discount/VAT:</label>
                                    <label for="amount" style="margin-left: 240px;">Amount:</label>
                                    <div class="col-md-12" ng-init="discountData = [{}];">
                                        <div ng-repeat="data in discountData" class="row">
                                            <div class="col-md-6">
                                                <div class="form-group ml-3">
                                                    <input type="text" class="form-control rounded-0" ng-model="data.discount" id="discount" required autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <input step=".01" type="number" class="form-control rounded-0 text-right currency" id="amount" ng-model="data.amount" required autocomplete="off" placeholder="0.00">
                                                </div>
                                            </div>
                                            <div class="col-md-1 ml-3">
                                                <div class="row">
                                                    <div class="container">
                                                        <div class="row">
                                                            <button type="button" ng-if="$index == 0" class="btn btn-default btn-flat" ng-click="discountData.push({})">
                                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                                            </button>
                                                            <button class="btn btn-danger btn-flat" ng-if="$index > 0" ng-click="discountData.splice($index, 1)">
                                                                <i class="fa fa-minus" aria-hidden="true"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn bg-gradient-primary btn-flat" ng-disabled="discountVATForm.$invalid">
                            <i class="fas fa-pen-square"></i> Save
                        </button>

                        <button type="button" class="btn bg-gradient-danger btn-flat" data-dismiss="modal">
                            <i class="fas fa-times"></i> Close
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- PROFORMA LINE HISTORY -->
    <div class="modal fade" id="viewHistory" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-xl" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header bg-dark rounded-0">
                    <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-history"></i> Proforma Line History</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post">
                    <div class="modal-body">
                        <table class="table table-bordered table-sm" id="historyTable" style="font-size: 12px;">
                            <thead class="bg-dark">
                                <tr>
                                    <th class="text-center">Item Code</th>
                                    <th class="text-center" style="width: 200px;">Description</th>
                                    <th class="text-center" style="width: 5%;">QTY</th>
                                    <th class="text-center">UOM</th>
                                    <th class="text-center" style="width: 15%;">Price</th>
                                    <th class="text-center" style="width: 15%;">Amount</th>
                                    <th class="text-center">Editted By</th>
                                    <th class="text-center">Approved By</th>
                                    <th>Date Edited</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="h in proforma_history">
                                    <td>{{ h.item_code }}</td>
                                    <td class="text-left">{{ h.description }}</td>
                                    <td>{{ h.qty }}</td>
                                    <td>{{ h.uom }}</td>
                                    <td class="text-right">₱ {{ h.price | currency: ''}}</td>
                                    <td class="text-right">₱ {{ h.amount | currency: ''}}</td>
                                    <td class="text-center">{{ h.editted_by }}</td>
                                    <td class="text-center">{{ h.approved }}</td>
                                    <td class="text-center">{{ h.date_edited }}</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-danger btn-flat" data-dismiss="modal" ng-click="clearHistory()"><i class="fas fa-times"></i> Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MATCH PROFORMA -->
    <div class="modal fade" id="matchingModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-xl" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header bg-dark rounded-0">
                    <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-link"></i> PO vs Proforma Matching</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post" enctype="multipart/form-data" ng-submit="match($event, items)">
                    <div class="modal-body">
                        <table class="table table-bordered table-sm table-hover" id="historyTable">
                            <thead class="bg-dark">
                                <tr>
                                    <th class="text-center">PO Item Code</th>
                                    <th class="text-center">PO Description</th>
                                    <th class="text-center">Proforma Description</th>
                                    <th class="text-center">Proforma Item Code</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="i in items">
                                    <td class="text-center">
                                        <span class="no-item" ng-if="i.po_item == null">OVER SERVED</span>
                                        <span class="no-item" ng-if="i.po_item == 'NO SET UP'">NO SET UP</span>
                                        <span ng-if="i.po_item != null">{{ i.po_item }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="no-item" ng-if="i.po_desc == null || i.po_desc == ''">NO SET UP</span>
                                        <span class="no-item" ng-if="i.po_desc == 'NO SET UP'">NO SET UP</span>
                                        <span ng-if="i.po_desc != null || i.po_desc != ''">{{ i.po_desc }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="no-item" ng-if="i.prof_desc == null">NOT SERVED</span>
                                        <span class="no-item" ng-if="i.prof_desc == 'NO SET UP'">NO SET UP</span>
                                        <span ng-if="i.prof_desc != null">{{ i.prof_desc }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="no-item" ng-if="i.pr_item == null">NOT SERVED</span>
                                        <span class="no-item" ng-if="i.pr_item == 'NO SET UP'">NO SET UP</span>
                                        <span ng-if="i.pr_item != null">{{ i.pr_item }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn bg-gradient-primary btn-flat"><i class="fas fa-link"></i> Match</button>
                        <button type="button" class="btn bg-gradient-danger btn-flat" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include './application/views/components/managersKey.php'; ?>
</div>
<!-- /.content-wrapper -->