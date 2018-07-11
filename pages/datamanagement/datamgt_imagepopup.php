<?php 
	$file = $_GET['fname'];	
?>
<script type="text/javascript">
	function closewindow()
	{
		self.close ();
	}
</script>
<html>
<body>
<table align="center" width="100%">
<tr>
	<td align="center"><img src="../../productimage/<?php echo $file; ?>" align="middle" /></td>
</tr>
<!--<tr>
	<td align="center"> <input type="button" class="btn" value="Close" onclick="return closewindow();"</td>
</tr>
--></table>
</body>
</html>

