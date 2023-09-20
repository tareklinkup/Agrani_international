<div id="salesInvoice">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <lc-invoice v-bind:lc_id="lc_id"></lc-invoice>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/components/lcInvoice.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
<script>
new Vue({
    el: '#salesInvoice',
    components: {
        lcInvoice
    },
    data() {
        return {
            lc_id: parseInt('<?php echo $lc_id; ?>')
        }
    }
})
</script>