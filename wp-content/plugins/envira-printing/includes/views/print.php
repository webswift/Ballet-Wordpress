<?php

$path = $_SERVER['DOCUMENT_ROOT'];
include_once $path . '/wp-load.php';

$image_url = esc_url ( $_REQUEST['envira_printing_image'] );

?>

<!DOCTYPE html>
<html lang="en-us">
<head>
</head>
<body>
	<img src="<?php echo $image_url; ?>" />
	<script type="text/javascript">
		window.print();
	</script>
</body>
</html>