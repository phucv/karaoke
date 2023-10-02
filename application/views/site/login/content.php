<!-- start container -->
<div class="container container-login">
    <div class="sub-container col-description">
        <div class="description-title">
            <h3><?php echo get_string("v-login-name-system"); ?></h3>
        </div>
        <div>
                <span class="description-sub-title">
                    <?php echo get_string("v-login-slogan"); ?>
                </span>
            <ul>
                <li>
                    <svg width="50" height="50" class="desc-item-img">
                        <circle cx="25" cy="25" r="20" fill="#ccc"></circle>
                        <i class="material-icons">book</i>
                    </svg>
                    <span class="desc-item-wrapper">
                            <span class="li-title size-18"><?php echo get_string("v-login-abundant-material"); ?></span>
                            <span class="li-subtitle size-14"><?php echo get_string("v-login-abundant-material-detail"); ?></span>
                        </span>
                </li>
                <li>
                    <svg width="50" height="50" class="desc-item-img">
                        <circle cx="25" cy="25" r="20" fill="#ccc"></circle>
                        <i class="material-icons">videocam</i>
                    </svg>
                    <span class="desc-item-wrapper">
                            <span class="li-title size-18"><?php echo get_string("v-login-live-class"); ?></span>
                            <span class="li-subtitle size-14"><?php echo get_string("v-login-live-class-detail"); ?></span>
                        </span>
                </li>
                <li>
                    <svg width="50" height="50" class="desc-item-img">
                        <circle cx="25" cy="25" r="20" fill="#ccc"></circle>
                        <i class="material-icons">playlist_add_check</i>
                    </svg>
                    <span class="desc-item-wrapper">
                            <span class="li-title size-18"><?php echo get_string("v-login-exam"); ?></span>
                            <span class="li-subtitle size-14"><?php echo get_string("v-login-exam-detail"); ?></span>
                        </span>
                </li>
            </ul>
        </div>
    </div>
    <?php if (isset($data_content)) echo $data_content; ?>
</div>
<!-- end container -->