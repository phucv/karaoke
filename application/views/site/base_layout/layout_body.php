
<?php //echo $side_bar_left;
$user_data = $this->session->userdata("user_data");
$role = empty($user_data->role_alias) ? "" : $user_data->role_alias;
?>
<div class="content-layout" <?php if ($role == "student") echo 'style="margin-left: 0px"'; ?>>
    <div id="loading-overlay">
        <div id="loading-overlay-animation"></div>
    </div>
	<div class="wrapper-content-layout">
    	<?php echo $content; ?>
    </div>
</div>
<?php echo $footer; ?>
