<p style="color: red;"><?php if(isset($success) && $success === true) { echo 'Edit gift success'; } ?></p>
<p style="color: red;"><?php if(isset($errors) && isset($errors['gift_point_exists'])) { echo $errors['gift_point_exists']; } ?></p>
<form action="<?php echo esc_url( admin_url( 'admin.php?page=wp-points-add-gift&action=edit-gift-post' ) ); ?>" method="post" id="wppoints_add_gift_form" >			
    <input type="hidden" name="action" value="edit-gift-post">
    <table class="form-table" role="presentation">
    <input id="<?php echo $plugin_name; ?>-edit-gift" type="hidden" name="id" value="<?php echo $gift->id ?>" />
        <tbody>
            <tr>
                <th scope="row">
                    <label for="<?php echo $plugin_name; ?>-edit-gift"> <?php _e('Gift', 'Gift'); ?> </label>
                </th>
                <td>
                    <input required id="<?php echo $plugin_name; ?>-edit-gift" type="text" name="<?php echo "gift"; ?>" value="<?php echo $gift->gift ?>" placeholder="<?php _e('Enter gift', 'Enter gift');?>" />
                    <p style="color: red;"><?php if(isset($errors) && isset($errors['gift'])) { echo $errors['gift']; } ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="<?php echo $plugin_name; ?>-edit-gift-point"> <?php _e('Point', 'Point'); ?> </label>
                </th>
                <td>
                    <input required id="<?php echo $plugin_name; ?>-edit-gift-point" type="text" name="point" value="<?php echo $gift->point ?>" placeholder="<?php _e('Enter point', 'Enter point');?>"/>
                    <p style="color: red;"><?php if(isset($errors) && isset($errors['point'])) { echo $errors['point']; } ?></p>
                </td>
            </tr>
        </tbody>        
    </table>
    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Update"></p>
</form>
<br/><br/>
<div id="wppoints_form_feedback"></div>
<br/><br/>			
</div>	