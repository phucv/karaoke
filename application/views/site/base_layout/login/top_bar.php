<!-- start header -->
<header>
    <div class="header width-80">
        <a class="header-logo" href="<?php echo site_url(); ?>">
            <img class="logo" src="<?php echo $logo_company ?>" alt="logo">
        </a>
        <div class="header-support">
            <span><span class="hotline"><?php echo get_string('v-footer-company_hotline', $hotline); ?></span></span>
        </div>
        <?php if (isset($warning_maintain)) echo $warning_maintain; ?>
    </div>
</header>
<!-- end header -->
