<p style="color: red;"><?php if(isset($success) && $success === true) { echo 'Add gift success'; } ?></p>
<p style="color: red;"><?php if(isset($errors) && isset($errors['gift_point_exists'])) { echo $errors['gift_point_exists']; } ?></p>
<form action="<?php echo esc_url( admin_url( 'admin.php?page=wp-points-add-gift' ) ); ?>" method="post" id="wppoints_add_gift_form" >			
    <input type="hidden" name="action" value="submit-add-gift">
    <table class="form-table" role="presentation">
        <tbody>
            <tr>
                <th scope="row">
                    <label for="<?php echo $plugin_name; ?>-add-gift"> <?php _e('Gift', 'Gift'); ?> </label>
                </th>
                <td>
                    <input required id="<?php echo $plugin_name; ?>-add-gift" type="text" name="<?php echo "gift"; ?>" value="" placeholder="<?php _e('Enter gift', 'Enter gift');?>" />
                    <p style="color: red;"><?php if(isset($errors) && isset($errors['gift'])) { echo $errors['gift']; } ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="<?php echo $plugin_name; ?>-add-gift-point"> <?php _e('Point', 'Point'); ?> </label>
                </th>
                <td>
                    <input required id="<?php echo $plugin_name; ?>-add-gift-point" type="text" name="point" value="" placeholder="<?php _e('Enter point', 'Enter point');?>"/>
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

<h2><?php _e( 'Import gifts with csv', 'Import gifts with csv' ); ?></h2>
<p style="color: red;"><?php if(isset($success_file) && $success_file === true) { echo 'Add gift success'; } ?></p>
<table width="600">
    <form action="<?php echo $_SERVER["PHP_SELF"]. "?page=wp-points-add-gift"; ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="action" value="submit-add-gift-csv">
        <tr>
            <td width="20%">Select file CSV</td>
            <td width="80%"><input type="file" name="file" id="file" /></td>
        </tr>

        <tr>
            <td>Submit</td>
            <td><p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Submit"></p></td>
        </tr>

    </form>
</table>	