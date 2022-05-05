<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper body-bg" ng-controller="vendorsDeal-controller">
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
                                            <div class="panel-body"><i class="fas fa-percent"></i> <strong>VENDORS DEALS</strong></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <button class="btn bg-gradient-primary btn-flat" data-target="#newVendorDeal" data-toggle="modal"><i class="fas fa-file-upload"></i> Upload Deals</button>
                                    <button class="btn bg-gradient-primary btn-flat" data-target="#setupNoDeal" data-toggle="modal"><i class="fas fa-file-upload"></i> Manual Setup</button>
                                </div>
                            </div>
                            <hr>
                            <div class="container" style="padding-left: 220px; padding-right: 220px;">
                                <div class="row col-lg-12">
                                    <div class="col-lg-12">
                                        <div class="form-group" ng-init="getSuppliers()">
                                            <label for="supplierName">Supplier Name</label>
                                            <select class="form-control rounded-0" ng-model="supplierName" name="supplierName" required>
                                                <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                <option ng-repeat="s in suppliers" value="{{s.supplier_id}}">{{s.supplier_name}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3 col-lg-8">
                                    <div class="col-lg-6"></div>
                                    <div class="col-lg-6">
                                        <button type="button" class="btn bg-gradient-primary btn-block btn-flat" ng-click="getVendorsDeal()" ng-disabled="!supplierName && !locationName">GET DEALS</button>
                                    </div>
                                </div>
                            </div>

                            <div ng-if="vendorsDealTables">
                                <table id="vendorsDealTable" class="table table-sm table-bordered font-small table-hover" ng-init="getVendorsDeal()" style="font-size: 14px;">
                                    <thead class="bg-dark">
                                        <tr>
                                            <th scope="col" class="text-center">Vendor Deal Code</th>
                                            <th scope="col" class="text-center">Line No.</th>
                                            <th scope="col" class="text-center">Type</th>
                                            <th scope="col" class="text-center">Description</th>
                                            <th scope="col" class="text-center">Disc %1</th>
                                            <th scope="col" class="text-center">Disc %2</th>
                                            <th scope="col" class="text-center">Disc %3</th>
                                            <th scope="col" class="text-center">Disc %4</th>
                                            <th scope="col" class="text-center">Disc %5</th>
                                            <th scope="col" class="text-center">Period From</th>
                                            <th scope="col" class="text-center">Period To</th>
                                            <th scope="col" style="width: 160px;" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="d in deals" ng-cloak>
                                            <td class="text-center">{{ d.vendor_deal_code}}</td>
                                            <td class="text-center">{{ d.line_no}}</td>
                                            <td class="text-center">{{ d.type}}</td>
                                            <td class="text-center">{{ d.description}}</td>
                                            <td class="text-center">{{ d.disc_1}}</td>
                                            <td class="text-center">{{ d.disc_2}}</td>
                                            <td class="text-center">{{ d.disc_3}}</td>
                                            <td class="text-center">{{ d.disc_4}}</td>
                                            <td class="text-center">{{ d.disc_5}}</td>
                                            <td class="text-center">{{ d.period_from}}</td>
                                            <td class="text-center">{{ d.period_to}}</td>
                                            <td class="text-center">
                                                <button title="Edit" class="btn bg-gradient-info btn-flat btn-sm" data-toggle="modal" data-target="#updateSupplier" ng-click="fetchSupplierData(s)"><i class="fas fa-pen-square"></i> Edit
                                                </button>

                                                <!-- <button class="btn bg-gradient-danger btn-flat btn-sm" ng-click="deactivateSupplier(s)"><i class="fas fa-ban"></i> Deactivate</button> -->
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

    <div class="modal fade" id="newVendorDeal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-md" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header bg-dark rounded-0">
                    <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-file-upload"></i> Upload Vendor's Deal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post" enctype="multipart/form-data" ng-submit="uploadDeal($event)" name="uploadVendorDealsForm" class="needs-validation">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="customSwitch1" ng-model="switch" ng-change="toggleSwitch()">
                                <label class="custom-control-label" for="customSwitch1" ng-bind="label" style="user-select: none"></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group" ng-init="getSuppliers()">
                                    <label for="supplierSelect"><i class="fab fa-slack required-icon"></i> Supplier Name: </label>
                                    <select ng-change="getPurchaseOrder()" class="form-control rounded-0" ng-model="supplierSelect" name="supplierSelect" required>
                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                        <option ng-repeat="s in suppliers" value="{{s.supplier_id}}">{{s.supplier_name}}</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please choose a supplier.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="vendorsDeal"><i class="fab fa-slack required-icon"></i> Vendor's Deal: </label>
                                    <input type="file" name="vendorsDeal" id="vendorsDeal" class="form-control rounded-0" style="height: 45px" required multiple>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn bg-gradient-primary btn-flat" ng-disabled="uploadVendorDealsForm.$invalid"><i class="fas fa-upload"></i> Upload</button>
                        <button type="button" class="btn bg-gradient-danger btn-flat" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateVendorDeal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-md" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header bg-dark rounded-0">
                    <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-file-upload"></i> Update Vendor's Deal</h5>
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
                                    <label for="#"><i class="fab fa-slack required-icon"></i> Vendor's Deal: </label>
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

     <!-- MANUAL SETUP FOR NO DEALS SUPPLIER -->
    <div class="modal fade" id="setupNoDeal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
            <div class="modal-content rounded-0 modal-l">
                <div class="modal-header bg-dark rounded-0">
                    <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-edit"></i>Manual Setup</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="manual" name="manual" >
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="selectSupplier" ng-init="getSuppliers()"><i class="fab fa-slack required-icon"></i> Supplier Name: </label>
                                    <select name="selectSupplier" ng-model="selectSupplier" class="form-control rounded-0">
                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                        <option ng-repeat="supplier in suppliers" value="{{supplier.supplier_id}}">{{supplier.supplier_name}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mFrom"><i class="fab fa-slack required-icon"></i> Period From: </label>
                                    <input type="text" class="form-control rounded-0" ng-model="mFrom" name="mFrom" required autocomplete="off">    
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mTo"><i class="fab fa-slack required-icon"></i> Period To: </label>
                                    <input type="text" class="form-control rounded-0" ng-model="mTo" name="mTo" required autocomplete="off">    
                                </div>
                            </div>
                            <table class="table table-bordered table-sm" ng-init="deductions=[{}]">
                                <thead class="bg-dark">
                                    <tr>
                                        <th scope="col" style="width:15%" class="text-center">Item Dep't Code</th>
                                        <th scope="col" style="width:30%" class="text-center">Description</th>
                                        <th scope="col" style="width:10%" class="text-center">Discount 1</th>
                                        <th scope="col" style="width:10%" class="text-center">Discount 2</th>
                                        <th scope="col" style="width:10%" class="text-center">Discount 3</th>
                                        <th scope="col" style="width:10%" class="text-center">Discount 4</th>
                                        <th scope="col" style="width:10%" class="text-center">Discount 5</th>
                                        <th scope="col" style="width:5%" class="text-center"><i class="fas fa-bars"></i</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="data in deductions" ng-cloak>
                                        <td ng-if="$index > 0">
                                            <div class="input-group input-group-sm rounded-0 ">
                                                <input type="text" class="form-control rounded-0 text-center text-bold" value="{{data.itemcode}}" style="border: none;" readonly>
                                            </div>
                                        </td>
                                        <td ng-if="$index > 0">
                                            <div class="input-group input-group-sm rounded-0 ">
                                                <input type="text" class="form-control rounded-0 text-center text-bold" value="{{data.desc}}" style="border: none;" readonly>
                                            </div>
                                        </td>
                                        <td ng-if="$index > 0">
                                            <div class="input-group input-group-sm rounded-0 ">
                                                <input type="text" class="form-control rounded-0 text-center text-bold" value="{{data.disc1}}" style="border: none;" readonly>
                                            </div>
                                        </td>
                                        <td ng-if="$index > 0">
                                            <div class="input-group input-group-sm rounded-0 ">
                                                <input type="text" class="form-control rounded-0 text-center text-bold" value="{{data.disc2}}" style="border: none;" readonly>
                                            </div>
                                        </td>
                                        <td ng-if="$index > 0">
                                            <div class="input-group input-group-sm rounded-0 ">
                                                <input type="text" class="form-control rounded-0 text-center text-bold" value="{{data.disc3}}" style="border: none;" readonly>
                                            </div>
                                        </td>
                                        <td ng-if="$index > 0">
                                            <div class="input-group input-group-sm rounded-0 ">
                                                <input type="text" class="form-control rounded-0 text-center text-bold" value="{{data.disc4}}" style="border: none;" readonly>
                                            </div>
                                        </td>
                                        <td ng-if="$index > 0">
                                            <div class="input-group input-group-sm rounded-0 ">
                                                <input type="text" class="form-control rounded-0 text-center text-bold" value="{{data.disc5}}" style="border: none;" readonly>
                                            </div>
                                        </td>
                                        <td ng-if="$index > 0">                                           
                                            <div class="col-lg-12">
                                                <div class="input-group input-group-sm rounded-0">
                                                    <a href="#" style="color:red; padding-right: 10px; padding-left: 10px;" ng-click="deductions.splice($index, 1)">
                                                        <i class="fa fa-minus"></i>
                                                    </a>                                                   
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>  
                            <div class="col-md-12"> 
                                <div class="row">
                                    <div class="col-md-10"></div>
                                    <div class="col-md-2">
                                        <button 
                                            type="button" 
                                            class="btn btn-danger btn-flat float-right" 
                                            title="Add New Item Discount" 
                                            data-toggle="modal" 
                                            href="#itemDiscount" 
                                            ng-disabled="!selectSupplier"
                                            ng-click="loadSupplierItemDeptCode(selectSupplier)">
                                            <i class="fas fa-plus-circle"></i>
                                            Item Discount
                                        </button>
                                    </div>
                                </div> 
                            </div>                        
                        </div>
                        <div class="modal-footer">
                            <button 
                                type="button" 
                                class="btn bg-gradient-primary btn-flat" 
                                ng-click="submitManualSetup($event)"
                                ng-disabled="manual.mFrom.$invalid || manual.mTo.$invalid">
                                <i class="fas fa-save"></i> 
                                Save
                            </button>
                            <button type="button" class="btn bg-gradient-danger btn-flat" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- MANUAL SETUP FOR NO DEALS SUPPLIER -->

     <!-- ADD NEW ITEM DISCOUNT -->
    <div class="modal fade" id="itemDiscount">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header bg-dark">
                    <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-percent"></i> New Item Discount </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <div class="container"></div>
                <div class="modal-body">
                    <!-- <form id="" ng-submit="addNewDiscToTable($event,itemDepartment,itemDeptCodes)"> -->
                        <div class="row">                                                      
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="itemDepartment">Item Department :</label>
                                    <select name="itemDepartment" ng-model="itemDepartment" ng-change="displayToInput(invoiceNo,SONOs)" class="form-control rounded-0" required>
                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                        <option ng-if="itemDeptCodes ==''" value="" disabled="" selected="" style="display:none">No Data Found</option>
                                        <option ng-repeat="item in itemDeptCodes"  value="{{item.item_department_code}}">{{item.description}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="disc1"><i class="fab fa-slack required-icon"></i> Discount 1 :</label>
                                    <input type="number" ng-model="disc1" value="" class="form-control rounded-0" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="disc2">Discount 2 :</label>
                                    <input type="number" ng-model="disc2" value="" class="form-control rounded-0">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="disc3">Discount 3 :</label>
                                    <input type="number" ng-model="disc3" value="" class="form-control rounded-0">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="disc4">Discount 4 :</label>
                                    <input type="number" ng-model="disc4" value="" class="form-control rounded-0">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="disc5">Discount 5 :</label>
                                    <input type="number" ng-model="disc5" value="" class="form-control rounded-0">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">                            
                            <button 
                                type="button" 
                                class="btn btn-success btn-flat" 
                                ng-disabled="!itemDepartment || !disc1"
                                ng-click= "addNewDiscToTable($event,itemDepartment,itemDeptCodes)">
                                Add
                            </button>
                            <button type="button" class="btn btn-dark btn-flat" ng-click="resetNewItemDisc()" data-dismiss="modal">Close</button>
                        </div>
                    <!-- </form> -->
                </div>
            </div>
        </div>
    </div>
   <!-- ADD NEW ITEM DISCOUNT -->

</div>