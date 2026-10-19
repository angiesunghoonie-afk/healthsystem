<?php
$scriptPath = $_SERVER['SCRIPT_NAME'];
if (strpos($scriptPath, '/modules/') !== false) {
    $basePath = '../';
} else {
    $basePath = '';
}
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo $basePath; ?>design/style.css?v=<?php echo time(); ?>">
<script src="<?php echo $basePath; ?>design/km.js"></script>
<link rel="icon" href="<?php echo $basePath; ?>logo/icon.png">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Baranggay Health Center Records System</title>