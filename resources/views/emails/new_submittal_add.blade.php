<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>

<h2>Stratus</h2>

<h3>New Submittal Add</h3>

<p>Dear <?php echo $user->name; ?>,&nbsp;</p>

<p class="p1">New submittal added in Project: <strong><?php echo $user->project_name; ?></strong> <a href="{{ url('/') }}<?php echo $user->link; ?>"> Click Here to see </a></p>

<p class="p1">Regards&nbsp;</p>

<p class="p1"><strong>Stratus</strong></p>

</body>
</html>
