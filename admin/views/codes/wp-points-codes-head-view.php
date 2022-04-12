<!-- Our admin page content should all be inside .wrap -->
<div class="wrap">
    <!-- Print the page title -->
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <!-- Here are our tabs -->
    <nav class="nav-tab-wrapper">
      <a href="?page=wp-points" class="nav-tab <?php if($tab===null):?>nav-tab-active<?php endif; ?>">Codes</a>
      <a href="?page=wp-points&point_type=users" class="nav-tab <?php if($tab==='users'):?>nav-tab-active<?php endif; ?>">Users</a>
      <a href="?page=wp-points&point_type=used-codes" class="nav-tab <?php if($tab==='used-codes'):?>nav-tab-active<?php endif; ?>">Used code</a>
    </nav>

    <div class="tab-content">