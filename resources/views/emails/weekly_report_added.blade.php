<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>

<h2>Stratus</h2>

<h3>New Weekly Report Added</h3>

<p>Dear <?php echo $user->name; ?>,&nbsp;</p>

<p class="p1">New Weekly Report added in Project: <strong><?php echo $user->project_name; ?></strong> <a href="{{ url('/') }}<?php echo $user->link; ?>"> Click Here to see </a></p>

<p class="p1" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12pt; color: #777; font-weight: normal; line-height: 1.45;">Cheers,&nbsp;</p>
<p class="p1" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14pt; color: #777; font-weight: normal; line-height: 1.45;"><img src="{{ url('/') }}/resources/assets/img/logo.png" alt="StratusCM"></p>

</body>
</html>
