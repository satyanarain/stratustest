<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<table border="0"  cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td align="left"><a href="{{ url('/') }}"><img src="{{ url('/') }}/resources/assets/img/logo.png" alt="StratusCM"></a></td>
		<td align="right" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12pt; color: #777; font-weight: normal; line-height: 1.45;color:#6f6f6f;">Add New Account<br><?php echo $user->date; ?></td>
	</tr>
</table>
<div style="display: block; width:100%;height:2px;background-color:#047a91;"></div>

<p style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12pt; color: #777; font-weight: normal; line-height: 1.45;color:#5aaaba;">Dear <strong style="text-transform: capitalize;"><?php echo $user->username; ?></strong>,&nbsp;
</p>

<p class="p1" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12pt; color: #777; font-weight: normal; line-height: 1.45;">Your account has successfully been created as a <?php echo $user->role; ?></p>
<p class="p1" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12pt; color: #777; font-weight: normal; line-height: 1.45;">Your username is: <strong><?php echo $user->username; ?></strong></p>
<p class="p1" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12pt; color: #777; font-weight: normal; line-height: 1.45;">Your password is: <strong><?php echo $user->user_email_password; ?></strong></p>
<p class="p1" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12pt; color: #777; font-weight: normal; line-height: 1.45;">Please visit the link below to view your project.</p>
<p class="p1" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12pt; color: #777; font-weight: normal; line-height: 1.45;"><a href="{{ url('/') }}/users/update_password/<?php echo $user->email_verification;?>">Click Here</a></p>
<p class="p1" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12pt; color: #777; font-weight: normal; line-height: 1.45;">Cheers,&nbsp;</p>
<p class="p1" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14pt; color: #777; font-weight: normal; line-height: 1.45;"><img src="{{ url('/') }}/resources/assets/img/logo.png" alt="StratusCM"></p>

</body>
</html>