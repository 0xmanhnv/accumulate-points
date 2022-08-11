<div class="wrap">
    <div id="icon-users" class="icon32"></div>
    <!-- <h2>List codes</h2>
    <div class="manage add">
        <a class="add button" href="?page=wp-points&action=export_reward_exchange">Export</a>
    </div> -->
    <form action="/wp-admin/admin.php?page=wp-points&point_type=reward-exchange" method="GET">
        <?php $codesListTable->search_box('Tìm', esc_attr($_REQUEST['reward-exchange']) ); ?>
        <input type="hidden" name="point_type" value="<?= esc_attr($_REQUEST['point_type']) ?>"/>
        <input type="hidden" name="page" value="<?= esc_attr($_REQUEST['page']) ?>"/>
    </form>
    <?php $codesListTable->display(); ?>
</div>