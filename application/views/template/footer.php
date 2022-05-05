<!-- Main Footer -->
<footer class="main-footer">
    <!-- Default to the left -->
    <strong>&copy; ALTURAS GROUP OF COMPANIES | CWO.</strong> All rights reserved.
</footer>
</div>
<!-- ./wrapper -->
<?php include './application/views/components/myAlert.php'; ?>
<script>
    window.$base_url = `<?= base_url() ?>`
</script>

<!-- REQUIRED SCRIPTS -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-3.6.0.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/adminlte.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jszip.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/pdfmake.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/angular.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/angular-sanitize.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/myplugin.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>plugins/sweetalert2/sweetalert2.min.js"></script>

<!-- MODULE SCRIPTS -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/controllers/root.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/controllers/supplier.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/controllers/customer.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/controllers/po.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/controllers/povsproforma.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/controllers/proformavspi.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/controllers/proformavscrf.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/controllers/sop.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/controllers/itemcodes.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/controllers/supplierledger.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/controllers/iadreport.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/controllers/users.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/controllers/povsproformahistory.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/controllers/proformavscrfhistory.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/controllers/proformavspihistory.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/controllers/sophistory.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/controllers/vendorsdeal.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/controllers/deduction.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/controllers/vat.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/controllers/testing.js"></script>
<!-- MODULE SCRIPTS-->

<!-- BOOTBOX-->
<script type="text/javascript" src="<?php echo base_url() ?>plugins/bootbox/bootbox.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>plugins/bootbox/bootbox.locales.min.js"></script>

<script type="text/javascript" src="<?php echo base_url() ?>plugins/select2/js/select2.full.min.js"></script>

<script>
    $(window).on('load', function() {
        $('#loading').hide();
    })

    $('.select2bs4').select2({
        theme: 'bootstrap4'
    })
</script>

</body>

</html>