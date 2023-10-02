<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 30/05/2019
 * Time: 10:54 SA
 */
$t = (empty($maintain_current) ? time() : strtotime($maintain_current->time_end)) - time();
$hours = floor(($t % (60 * 60 * 24)) / (60 * 60));
$minutes = floor(($t % (60 * 60)) / 60);
$seconds = floor($t % 60);
?>
<div class="maintain-layout"
     data-time-end="<?php echo empty($maintain_current) ? time() : strtotime($maintain_current->time_end); ?>">
    <img src="<?php echo base_url("assets/images/site/maintain.jpg"); ?>">
    <div class="note">
        <?php echo mb_strtoupper(get_string("v-system_maintain-noti1")) ?>
    </div>
    <span><?php echo get_string("v-system_maintain-noti2"); ?></span>
    <div class="notify-content text-center">
        <?php echo get_string('v-system_maintain-maintain_done'); ?>
    </div>
    <span class="text-count-down"><span
                id="hour-countdown-maintain"><?php echo $hours < 10 ? '0' . $hours : $hours; ?></span>:<span
                id="minute-countdown-maintain"><?php echo $minutes < 10 ? '0' . $minutes : $minutes; ?></span>:<span
                id="second-countdown-maintain"><?php echo $seconds < 10 ? '0' . $seconds : $seconds; ?></span></span>
</div>
<style>
    .maintain-layout {
        text-align: center;
    }

    .maintain-layout .note {
        font-weight: 600;
        font-size: 20px;
        margin-top: 10px;
        margin-bottom: 5px;
    }

    .notify-content {
        margin-top: 5px;
        margin-bottom: 2px;
    }

    .text-count-down {
        font-family: Arial, Helvetica, sans-serif;
        font-weight: 600;
        font-size: 18px;
    }
</style>
<script type="text/javascript">
    countdownMaintain();
    intervalCountDown = setInterval(countdownMaintain, 1000);

    function countdownMaintain() {
        var maintain_layout = document.getElementsByClassName('maintain-layout');
        var time_end = maintain_layout[0].getAttribute('data-time-end');
        var hour_countdown = document.getElementById('hour-countdown-maintain');
        var minute_countdown = document.getElementById('minute-countdown-maintain');
        var second_countdown = document.getElementById('second-countdown-maintain');
        let now = new Date();
        let t = (parseInt(time_end) * 1000) - now.getTime();
        if (t <= 0) {
            location.reload();
            clearInterval(intervalCountDown);
            return;
        }
        let hours = Math.floor((t % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        let minutes = Math.floor((t % (1000 * 60 * 60)) / (1000 * 60));
        let seconds = Math.floor((t % (1000 * 60)) / 1000);
        hour_countdown.innerText = (hours < 10 ? '0' + hours : hours);
        minute_countdown.innerText = (minutes < 10 ? '0' + minutes : minutes);
        second_countdown.innerText = (seconds < 10 ? '0' + seconds : seconds);
    }
</script>
