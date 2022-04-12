<h2><?php _e( 'Add code', 'Add code' ); ?></h2>		
<div class="wppoints_add_code_form">

<p style="color: red;"><?php if(isset($success) && $success === true) { echo 'Add code success'; } ?></p>
<p style="color: red;"><?php if(isset($errors) && isset($errors['code_exists'])) { echo $errors['code_exists']; } ?></p>
<form action="<?php echo esc_url( admin_url( 'admin.php?page=wp-points-add-code' ) ); ?>" method="post" id="wppoints_add_code_form" >			
    <input type="hidden" name="action" value="submit-add-code">
    <table class="form-table" role="presentation">
        <tbody>
            <tr>
                <th scope="row">
                    <label for="<?php echo $plugin_name; ?>-add-code"> <?php _e('Code', 'Code'); ?> </label>
                </th>
                <td>
                    <input required id="<?php echo $plugin_name; ?>-add-code" type="text" name="<?php echo "code"; ?>" value="" placeholder="<?php _e('Enter code', 'Enter code');?>" />
                    <p style="color: red;"><?php if(isset($errors) && isset($errors['code'])) { echo $errors['code']; } ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="<?php echo $plugin_name; ?>-add-code-point"> <?php _e('Point', 'Point'); ?> </label>
                </th>
                <td>
                    <input required id="<?php echo $plugin_name; ?>-add-code-point" type="text" name="point" value="" placeholder="<?php _e('Enter point', 'Enter point');?>"/>
                    <p style="color: red;"><?php if(isset($errors) && isset($errors['point'])) { echo $errors['point']; } ?></p>
                </td>
            </tr>
        </tbody>        
    </table>
    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Submit"></p>
</form>
<br/><br/>
<div id="wppoints_form_feedback"></div>
<br/><br/>			
</div>

<hr>

<h2><?php _e( 'Import codes with csv', 'Import codes with csv' ); ?></h2>		