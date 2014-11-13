<?php  require_once 'init.php'; ?>
<!doctype html>
<html>
<head>
<script src="//code.jquery.com/jquery-2.1.1.min.js"></script>
<script>
const limit = <?=$limit ?> ;
$(function() {
   $('nav a').click(function(e) {
       e.preventDefault();
       e.defaultPrevented = true;
       var offset = (parseInt(this.innerHTML) - 1) * limit
       offset = '?offset=' + offset;
       $('tbody').load('page.php' + offset,
           history.pushState.bind(history, null, null, offset));
       return false;
   }); 
});
</script>
</head>
<body>
<table>
    <tbody>
    <?php require_once 'page.php'; ?>
    </tbody>
</table>
<?php
$size = $db->query('select count(*) from `posts`')->fetchColumn(0);
?>

<nav>
<?php for($i=0; $i < $size; $i += $limit): ?>
    <a href="?offset=<?=$i ?>"><?=$i/$limit + 1?></a>
<?php endfor ?>
</nav>
</body>
