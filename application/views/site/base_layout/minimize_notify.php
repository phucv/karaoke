<div class="notify" data-url="<?php echo site_url('site/notify/minimize_notification'); ?>"
     data-load-setting="<?php echo site_url('site/notify/load_setting'); ?>"
     data-total="<?php echo site_url('site/notify/show_total_notification'); ?>">
    <span class="notification-count ic-notify"
          data-url="<?php echo site_url('site/notify/count_unread_notifications'); ?>">0</span>
    <a href="#" class="sp_notify">
        <!--            <div class="icon notify-icon"></div>-->
        <i class="material-icons icon-notify">notifications_none</i>
    </a>
    <div class="notification-container ls_notify" data-url="<?php echo site_url('site/notify/long_polling'); ?>">
        <div class="notification-title">
            <span class="noti-title"><?php echo get_string("v-side_bar_left-minimize_notify"); ?></span>
            <span class="noti-config">
                <span class="read-all"
                      data-url="<?php echo site_url('site/notify/read_all_notification') ?>"><?php echo get_string("v-side_bar_left-minimize_notify-mark_read"); ?></span>
                <a href="<?php echo site_url('site/manage_notifications'); ?>"><i
                            class="material-icons">settings</i></a>
            </span>
        </div>
        <div class="notifications-body"
             data-url="<?php echo site_url('site/notify/show_minimize_notification'); ?>"
             data-change-url="<?php echo site_url('site/notify/change_status_notification') ?>">
            <div id="nothing" class="notifications">
                <div class="noti-content"><?php echo get_string("v-side_bar_left-minimize_notify-no_notifications"); ?></a></div>
            </div>
        </div>
        <div class="notification-footer"><a
                    href="<?php echo site_url('site/manage_notifications'); ?>"><?php echo get_string("v-side_bar_left-minimize_notify-view_all"); ?></a>
        </div>
    </div>

    <!--    notify for messenger-->
    <span class="notification-count ic-message"></span>
    <a href="#" class="sp_message">
        <i class="material-icons icon-message">message</i>
    </a>
    <div id="i_list_message" class="notification-container ls_message"
         data-url="<?php echo site_url('site/messenger/list_message'); ?>"
         data-url-unread="<?php echo site_url('site/messenger/mark_as_unread'); ?>"
         data-url-read="<?php echo site_url('site/messenger/mark_as_read'); ?>" data-count-unread="<?php echo
    site_url('site/messenger/count_unread'); ?>">
        <div class="notification-title">
            <span class="noti-title"><?php echo get_string("v-side_bar_left-minimize_message"); ?></span>
            <span class="noti-config">
                <span class="read-all-message"
                      data-url="<?php echo site_url('site/messenger/read_all_message') ?>"><?php echo get_string("v-side_bar_left-minimize_notify-mark_read"); ?></span>
            </span>
        </div>
        <div class="notifications-body">
            <div id="nothing" class="notifications">
                <div class="noti-content"><?php echo get_string("v-side_bar_left-minimize_notify-no_notifications"); ?></a></div>
            </div>
        </div>
        <div class="notification-footer"><a
                    href="<?php echo site_url('site/messenger'); ?>"><?php echo get_string("v-side_bar_left-minimize_notify-view_all"); ?></a>
        </div>
    </div>
</div>

