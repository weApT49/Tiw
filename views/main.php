<?
if(!empty($_GET['page'])){
    $main_content = "main/".$_GET['page'].".php";
    }else{
        $main_content = "main/firstmain.php";
         }
?>
<div class="container">
<? include($main_content); ?>
</div>
