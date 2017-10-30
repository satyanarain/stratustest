<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<table border="0"  cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td align="left"><a href="{{ url('/') }}"><img src="{{ url('/') }}/resources/assets/img/logo.png" alt="StratusCM"></a></td>
		<td align="right" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12pt; color: #777; font-weight: normal; line-height: 1.45;color:#6f6f6f;">Reset Password Request<br><?php echo $user->date; ?></td>
	</tr>
</table>
<div style="display: block; width:100%;height:2px;background-color:#047a91;"></div>
<h3 style="display: none;"><?php echo $user->title; ?></h3>

<p style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12pt; color: #777; font-weight: normal; line-height: 1.45;color:#5aaaba;">Dear <strong style="text-transform: capitalize;"><?php echo $user->name; ?></strong>,&nbsp;
</p>

<p class="p1" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12pt; color: #777; font-weight: normal; line-height: 1.45;">Your Verification Code is: <strong><?php echo $user->user_password; ?></strong></p>
<p class="p1" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12pt; color: #777; font-weight: normal; line-height: 1.45;">Cheers,&nbsp;</p>
<p class="p1" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14pt; color: #777; font-weight: normal; line-height: 1.45;"><img src="{{ url('/') }}/resources/assets/img/logo.png" alt="StratusCM"></p>

</body>
</html>