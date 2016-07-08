<style>
body{background-color: white}
</style>
<div id="signbox">
	<div id="signup">
		<div class="title"><?php echo $page_title ?></div>
		<form id="form_signup" method="post">
		<input type="email" name="email" class="email" placeholder="email">
		<input type="password" name="password" class="password" placeholder="password">
		<?php if($type=='signup'){?>
		<input type="text" class="nikename" name="nikename" placeholder="nikename">
		<?php } ?>
		</form>
		<input type="button" class="btnsignup" value="<?php echo $page_title ?>">

	</div>
</div>

<script type="text/javascript">
	$(function(){
		$("#signup .btnsignup").on('click',function(){
			$("#form_signup").submit();
		});
	});
</script>