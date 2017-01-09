<div class="wrap">
    
    <h2><?php echo __('WooCommerce Bulk Product Delete',WC_BPD_LANGUAGE_PATH); ?></h2>
    <div class="updated" id="message_update" style="display:none;">
        <span class="spinner" style="display: block; visibility: visible; float: left; margin-top: 9px; margin-left: 0px;"> </span>
        <p class="data"><?php echo __('Total : 0 Success : 0 | Error : 0',WC_BPD_LANGUAGE_PATH); ?> </p>
    </div>
    
    <div class="error_div" style="display:none;">
        <h3><?php echo __('Error ID\'s / SKU',WC_BPD_LANGUAGE_PATH); ?> </h3>
        <ul>
        </ul>
    </div>
    
    <form id="wc_bpd_form" method="post">
        <table  class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="wc-bpd-values"><?php echo __('Product Values',WC_BPD_LANGUAGE_PATH); ?></label></th>
                    <td><textarea rows="15" cols="60" class="regular-text large" id="wc-bpd-values" name="wc-bpd-values">
Enter Values By , Separated SKU1,SKU2,SKU3 Or ID1,ID2,ID3

Enter Values By Line By Line Separated
ID1
ID2
ID3
or
SKU1
SKU2
SKU3</textarea></td>
                </tr>
                <tr>
                    <th scope="row"><label for="wc-bpd-value-type"><?php echo __('Value Type',WC_BPD_LANGUAGE_PATH); ?></label></th>
                    <td>
                        <select id="wc-bpd-value-type" name="wc-bpd-value-type">
                            <option value="id"><?php echo __('Product ID',WC_BPD_LANGUAGE_PATH); ?></option>
                            <option value="sku"><?php echo __('Product SKU',WC_BPD_LANGUAGE_PATH); ?></option>
                        </select></td>
                </tr>
                <tr>
                    <th scope="row"><label for="wc-bpd-delete-type"><?php echo __('Delete Type',WC_BPD_LANGUAGE_PATH); ?></label></th>
                    <td>
                        <select id="wc-bpd-delete-type" name="wc-bpd-delete-type">
                            <option value="trash"><?php echo __('Move to Trash',WC_BPD_LANGUAGE_PATH); ?></option>
                            <option value="delete"><?php echo __('Delete',WC_BPD_LANGUAGE_PATH); ?></option>
							<option value="untrash"><?php echo __('Restore from Trash',WC_BPD_LANGUAGE_PATH); ?></option>
                        </select></td>
                </tr>
                <tr>
                    <th scope="row"><input type="hidden" name="action" value="wc_bpd_delete" /></th>
                    <td><input type="button" name="wc_bpd_delete_btn" value="<?php _e('Execute',WC_BPD_LANGUAGE_PATH); ?> " class="wc_bpd_delete_btn button button-primary" /></td>
                </tr>
            </tbody>
        </table> 
    </form>
</div>
