<?php
defined('BASEPATH') or exit('No direct script access allowed');

// ============ DEFAULT CONTROLLERS ============ //
$route['default_controller'] = 'baseController/login';
$route['home'] = 'baseController/home';
$route['login'] = 'baseController/checkCredentials';
$route['logout'] = 'baseController/endSession';
// ============ DEFAULT CONTROLLERS ============ //

// ============ TESTING CONTROLLERS ============ //
$route['testing'] = 'baseController/testing';
$route['testPDF'] = 'Testingcontroller/testPDF';
$route['cwoSlip'] = 'Testingcontroller/cwoSlip';
$route['authorize'] = 'baseController/authorize';
$route['getSOP'] = 'Testingcontroller/getSOP';
$route['printProfVSCRF'] = 'Testingcontroller/printProfVSCRF';
$route['printSOP'] = 'Testingcontroller/printSOP';
$route['exportExcel'] = 'Testingcontroller/exportExcel';
// ============ TESTING CONTROLLERS ============ //

// ============ MASTER FILES ============ //
$route['masterfiles/(:any)'] = 'baseController/masterfile/$1';
$route['addCustomer']        = 'masterfileController/customercontroller/addCustomer';
$route['updateCustomer']     = 'masterfileController/customercontroller/updateCustomer';
$route['deactivateCustomer'] = 'masterfileController/customercontroller/deactivateCustomer';
$route['fetchCustomers']     = 'masterfileController/customercontroller/fetchCustomers';

$route['fetchSuppliers']         = 'masterfileController/suppliercontroller/fetchSuppliers';
$route['addSupplier']['POST']    = 'masterfileController/suppliercontroller/addSupplier';
$route['uploadSupplier']          = 'masterfileController/suppliercontroller/uploadSupplier';
$route['updateSupplier']['POST'] = 'masterfileController/suppliercontroller/updateSupplier';
$route['deactivateSupplier']     = 'masterfileController/suppliercontroller/deactivateSupplier';

$route['generateItems']  = 'masterfileController/itemCodeController/generateItems';
$route['uploadItems']    = 'masterfileController/itemCodeController/uploadItems';
$route['updateItemCodes']    = 'masterfileController/itemCodeController/updateItemCodes';
$route['getNoMapItems']    = 'masterfileController/itemCodeController/getNoMapItems';
$route['saveMapItems']    = 'masterfileController/itemCodeController/saveMapItems';
$route['updateNewItems'] = 'masterfileController/itemCodeController/updateNewItems';
$route['deleteItem']     = 'masterfileController/itemCodeController/deleteItem';
$route['uploadMapping'] = 'masterfileController/itemCodeController/uploadMapping';

$route['getDeals'] = 'masterfileController/vendorsdealcontroller/getDeals';
$route['uploadDeals'] = 'masterfileController/vendorsdealcontroller/uploadDeals';
$route['updateDeals'] = 'masterfileController/vendorsdealcontroller/updateDeals';
$route['loadItemDeptCode'] = 'masterfileController/vendorsdealcontroller/loadItemDeptCode';
$route['submitManualSetup'] = 'masterfileController/vendorsdealcontroller/submitManualSetup';

$route['addType'] = 'masterfileController/deductioncontroller/addType';
$route['saveDeduction'] = 'masterfileController/deductioncontroller/saveDeduction';
$route['loadDeductions'] = 'masterfileController/deductioncontroller/loadDeductions';
$route['editDeduction'] = 'masterfileController/deductioncontroller/editDeduction';
$route['deactivateDeduction'] = 'masterfileController/deductioncontroller/deactivateDeduction';

$route['loadVAT']       = 'masterfileController/vatcontroller/loadVAT';
$route['addVAT']        = 'masterfileController/vatcontroller/addVAT';
$route['updateVAT']     = 'masterfileController/vatcontroller/updateVAT';
$route['deactivateVAT'] = 'masterfileController/vatcontroller/deactivateVAT';
// ============ MASTER FILES ============ //

$route['getUsers'] = 'masterfileController/usercontroller/getUsers';
$route['addUser']  = 'masterfileController/usercontroller/addUser';
$route['updateUser'] = 'masterfileController/usercontroller/updateUser';
$route['deactivate'] = 'masterfileController/usercontroller/deactivate';

// ============ PO ============ /
$route['getSuppliersForPO'] = 'transactionControllers/pocontroller/getSuppliersForPO';
$route['getCustomersForPO'] = 'transactionControllers/pocontroller/getCustomersForPO';
$route['uploadPo'] = 'transactionControllers/pocontroller/uploadPo';
$route['getPOs'] = 'transactionControllers/pocontroller/getPOs';
$route['getPoDetails/(:any)'] =  'transactionControllers/pocontroller/getPoDetails/$1';

// ============ PO VS PROFORMA ============ //
$route['getSuppliers'] = 'transactionControllers/povsproformacontroller/getSuppliers';
$route['getCustomers'] = 'transactionControllers/povsproformacontroller/getCustomers';
$route['getPurchaseOrder/(:any)/(:any)'] = 'transactionControllers/povsproformacontroller/getPurchaseOrder/$1/$2';
$route['uploadProforma'] = 'transactionControllers/povsproformacontroller/uploadProforma';
$route['getPendingMatchesPRF'] = 'transactionControllers/povsproformacontroller/getPendingMatches';
$route['matchPOandProforma'] = 'transactionControllers/povsproformacontroller/matchPOandProforma';
$route['getProforma'] = 'transactionControllers/povsproformacontroller/getProforma';
$route['getProforma/(:any)/(:any)/(:any)'] = 'transactionControllers/povsproformacontroller/getProforma/$1/$2/$3';
$route['updateProformaLine'] = 'transactionControllers/povsproformacontroller/updateProformaLine';
$route['getHistory/(:any)'] = 'transactionControllers/povsproformacontroller/getHistory/($1)';
$route['replaceProforma'] = 'transactionControllers/povsproformacontroller/replaceProforma';
$route['addDiscount'] = 'transactionControllers/povsproformacontroller/addDiscount';
$route['getDiscount/(:any)'] = 'transactionControllers/povsproformacontroller/getDiscount/$1';

// NEW PROFORMA ROUTES ---- ONGOING
$route['getMatchItems'] = 'transactionControllers/povsproformacontroller/getMatchItems';


// ============ PROFORMA VS PI ============ //
$route['getSuppliersForPI'] = 'transactionControllers/proformavspicontroller/getSuppliersForPI';
$route['getCustomersForPI'] = 'transactionControllers/proformavspicontroller/getCustomersForPI';
$route['uploadPi'] = 'transactionControllers/proformavspicontroller/uploadPi';
$route['getPIs'] = 'transactionControllers/proformavspicontroller/getPIs';
$route['getPiDetails'] = 'transactionControllers/proformavspicontroller/getPiDetails';
$route['updatePrice'] = 'transactionControllers/proformavspicontroller/updatePrice';
$route['getItemPriceLog'] = 'transactionControllers/proformavspicontroller/getItemPriceLog';
$route['getCrfInPI'] = 'transactionControllers/proformavspicontroller/getCrfInPI';
$route['getProfPiInCrf'] = 'transactionControllers/proformavspicontroller/getProfPiInCrf';
$route['applyPiToCrf']  = 'transactionControllers/proformavspicontroller/applyPiToCrf';
$route['untagPiFromCrf'] = 'transactionControllers/proformavspicontroller/untagPiFromCrf';
$route['managersKey'] = 'transactionControllers/proformavspicontroller/managersKey';
$route['checkUserType'] = 'transactionControllers/proformavspicontroller/checkUserType';
$route['changeStatus'] = 'transactionControllers/proformavspicontroller/changeStatus';
$route['matchProformaVsPi'] = 'transactionControllers/proformavspicontroller/matchProformaVsPi';
$route['viewMatchedUnmatchedItems'] = 'transactionControllers/proformavspicontroller/viewMatchedUnmatchedItems';
$route['uploadCm'] = 'transactionControllers/proformavspicontroller/uploadCm';
$route['viewCMDetails'] = 'transactionControllers/proformavspicontroller/viewCMDetails';



// ============ PROFORMA VS CRF ============ //
$route['getSuppliersForCRF'] = 'transactionControllers/proformavscrfcontroller/getSuppliersForCRF';
$route['getSop/(:any)'] = 'transactionControllers/proformavscrfcontroller/getSop/$1';
$route['getCustomersForCRF'] = 'transactionControllers/proformavscrfcontroller/getCustomersForCRF';
$route['getCrfs'] = 'transactionControllers/proformavscrfcontroller/getCrfs';
$route['uploadCrf'] = 'transactionControllers/proformavscrfcontroller/uploadCrf';
$route['matchProformaVsCrf'] = 'transactionControllers/proformavscrfcontroller/matchProformaVsCrf';
$route['getUnAppliedProforma/(:any)/(:any)'] = 'transactionControllers/proformavscrfcontroller/getUnAppliedProforma/$1/$2';
$route['getAppliedProforma/(:any)/(:any)'] = 'transactionControllers/proformavscrfcontroller/getAppliedProforma/$1/$2';
$route['applyProforma/(:any)/(:any)'] = 'transactionControllers/proformavscrfcontroller/applyProforma/$1/$2';
$route['checkUserTypeCrf'] = 'transactionControllers/proformavscrfcontroller/checkUserTypeCrf';
$route['untagProforma'] = 'transactionControllers/proformavscrfcontroller/untagProforma';
$route['changeStatusToMatched'] = 'transactionControllers/proformavscrfcontroller/changeStatusToMatched';


// ============ SOP ============ //
$route['getSuppliersSop'] = 'transactionControllers/sopcontroller/getSuppliersSop';
$route['getSupplierName'] = 'transactionControllers/sopcontroller/getSupplierName';
$route['getCustomersSop'] = 'transactionControllers/sopcontroller/getCustomersSop';
$route['checkUserTypeSOP']  = 'transactionControllers/sopcontroller/checkUserTypeSOP';
$route['loadVendorsDeal'] = 'transactionControllers/sopcontroller/loadVendorsDeal';
$route['loadSONos']           = 'transactionControllers/sopcontroller/loadSONos';
$route['loadSONosWoDeal']     = 'transactionControllers/sopcontroller/loadSONosWithoutDeal';
$route['loadDeductionType']   = 'transactionControllers/sopcontroller/loadDeductionType';
$route['loadDeduction']   = 'transactionControllers/sopcontroller/loadDeduction';
$route['forRegDiscount']   = 'transactionControllers/sopcontroller/calcAmountToBeDeductedForRegDisc';
$route['calculateDeduction']   = 'transactionControllers/sopcontroller/calculateDeduction';
$route['loadChargesType'] = 'transactionControllers/sopcontroller/loadChargesType';
$route['submitSOP']       = 'transactionControllers/sopcontroller/submitSOP';
$route['loadCwoSop']      = 'transactionControllers/sopcontroller/loadCwoSop';
$route['loadSopDetails']  = 'transactionControllers/sopcontroller/loadSopDetails';
$route['tagAsAudited']    = 'transactionControllers/sopcontroller/tagAsAudited';
$route['searchSOP']    = 'transactionControllers/sopcontroller/searchSOP';




$route['getPOForSlip'] = 'transactionControllers/cwoslipcontroller/getPO/$1/$2';
// ============ TRANSACTIONS ============ //
$route['transactions/(:any)'] = 'baseController/transactions/$1';

// ============ REPORTS ============ //
$route['reports/(:any)']       = 'baseController/reports/$1';
$route['getIadReports']        = 'reportsController/iadReportController/getIadReports';
$route['getLedger/(:any)']     = 'reportsController/ledgerController/getLedger/$1';
$route['getDataLedger']        = 'reportsController/ledgerController/getDataLedger';
$route['getProformaHeader']    = 'reportsController/ledgerController/getProformaHeader';
$route['getDataLedgerInvoice'] = 'reportsController/ledgerController/getDataLedgerInvoice';

$route['getTransactionHistory'] = 'reportsController/POvProformaHistoryController/getTransactionHistory';
$route['generateProfvPiHistory'] = 'reportsController/ProformavPiHistoryController/generateProfvPiHistory';
$route['generateProfvCrfHistory'] = 'reportsController/ProformavCrfHistoryController/generateProfvCrfHistory';
$route['generateSopHistory'] = 'transactionControllers/sopcontroller/generateSopHistory';



# ADMIN ROUTES
$route['admin_login'] = 'AdminControllers/AdminController/admin_login';
$route['checkCredentials'] = 'AdminControllers/AdminController/checkCredentials';
$route['admin_home'] = 'AdminControllers/AdminController/admin_home';

# PORTAL ROUTES
$route['portal_login'] = 'PortalController/PortalController/portal_login';



# EXTRA ROUTE TESTING

$route['uploadLeasing'] = 'Testingcontroller/uploadLeasing';
