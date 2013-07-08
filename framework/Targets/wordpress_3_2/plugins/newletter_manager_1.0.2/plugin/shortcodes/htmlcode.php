
<script>
function verify_fields()
{
var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
var address = document.subscription.xyz_em_email.value;
if(reg.test(address) == false) {
alert("Please check whether the email is correct.");
return false;
}else{
document.subscription.submit();
}
}
</script>
<style>
#tdTop{
	border-top:none;
}

</style>

<form method="POST" name="subscription" action="<?php echo plugins_url('newsletter-manager/subscription.php');?>">
<table border="0" style=" width: 600; border: 1px solid #FFFFFF; color: black;">
<tr>
<td id="tdTop"  colspan="2">
<span style="font-size:14px;"><b><?php echo esc_html(get_option('xyz_em_widgetName'))?></b></span>
</td>
</tr>
<tr >
<td id="tdTop" width="200">Name</td>
<td id="tdTop" >
<input  name="xyz_em_name"
type="text" />
</td>
</tr>
<tr >
<td id="tdTop" width="200">Email Address</td>
<td id="tdTop">
<input  name="xyz_em_email"
type="text" /><span style="color:#FF0000">*</span>
</td>
</tr>

<tr>
<td id="tdTop">&nbsp;</td>
<td id="tdTop">
<div style="height:20px;"><input name="htmlSubmit"  id="submit" class="button-primary" type="submit" value="Subscribe" onclick="javascript: if(!verify_fields()) return false; "  /></div>
</td>
</tr>
<tr>
<td id="tdTop" colspan="3" >&nbsp;</td>
</tr>
</table>
</form>
