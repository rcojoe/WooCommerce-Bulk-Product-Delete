<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/woocommerce-role-based-price/
 *
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    @TODO
 * @subpackage @TODO
 * @author     Varun Sridharan <varunsridharan23@gmail.com>
 */
if ( ! defined( 'WPINC' ) ) { die; }

class WooCommerce_Bulk_Product_Delete_Admin_Ajax {
   
   public static function check_delete(){ 
       if(isset($_POST['wc-bpd-values']) && ! empty($_POST['wc-bpd-values'])){
            $ids =  self::get_value_array();
            $total = count($ids);
            update_option('wc_bpd_total_values',$total);
            if($_POST['wc-bpd-value-type'] == 'id'){ $data = self::delete_product_id($ids); }
            if($_POST['wc-bpd-value-type'] == 'sku'){ $data = self::delete_product_sku($ids);}
        } 
    }


    public static function get_value_array(){
        $comma_seperated = explode(',',$_POST['wc-bpd-values'],2);
        if(count($comma_seperated) == 2){
            return explode(',',$_POST['wc-bpd-values']);
        } else {
            return explode(PHP_EOL,$_POST['wc-bpd-values']);
        }
    }
     
    public static function delete_product_id($ids){
        $ids =  $ids; 
        $error_keys = array();
        $error = 0;
        $success = 0;
        $delete_function = 'wp_trash_post';
        
        if($_POST['wc-bpd-delete-type'] == 'delete'){
            $delete_function = 'wp_delete_post';            
        }
		if($_POST['wc-bpd-delete-type'] == 'untrash'){
            $delete_function = 'wp_untrash_post';            
        }
        
        foreach($ids as $id){
			$id = str_replace("\r","",$id);
			if ($id == "") break;
            $delete = $delete_function($id);
			error_log( $delete_function." ID: " . $id);
            if($delete){
                $success++;
            } else {
                $error++;
                $error_keys[] = $id;
            }
            update_option('wc_bpd_values',array('success' => $success,'error' => $error,'error_ids' => $error_keys));
        }

        return array('success' => $success,'error' => $error,'error_ids' => $error_keys);       
    }
    
    
    public  static function delete_product_sku($ids){
        $skus =  $ids; 
        $error_keys = array();
        $error = 0;
        $success = 0;
        $delete_function = 'wp_trash_post';
        
        if($_POST['wc-bpd-delete-type'] == 'delete'){
            $delete_function = 'wp_delete_post';
        }
		if($_POST['wc-bpd-delete-type'] == 'untrash'){
            $delete_function = 'wp_untrash_post';  
        }
        foreach($skus as $sku){
			$sku = str_replace("\r","",$sku);
			if ($sku == "") break;
			error_log( $delete_function." SKU: " . $sku);
            $product_id = wc_get_product_id_by_sku($sku);
            if($product_id > 0){
                $delete = $delete_function($product_id);
                $success++;
            } else {
                $error++;
                $error_keys[] = $sku;
            } 
            
            update_option('wc_bpd_values',array('success' => $success,'error' => $error,'error_ids' => $error_keys));
        }
        
        return array('success' => $success,'error' => $error,'error_ids' => $error_keys);
    }
    
}
?>
