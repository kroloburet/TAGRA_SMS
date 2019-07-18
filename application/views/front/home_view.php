<!--####### Mine #######-->
<div class="mine_wrapper">
<div class="container" style="max-width:<?=htmlspecialchars($conf['body_width'])?>px">

<!--####### Headline #######-->
<div id="headline">
<h1><?=$data['title']?></h1>
<?php if($conf['addthis_share']&&$data['addthis_share']=='on'){?>
<div class="addthis_layout noprint"><?=$conf['addthis_share']?></div>
<?php }?>
</div>

<?php if($data['layout_l']||$data['layout_r']||$data['layout_t']||$data['layout_b']){//если заполнен один из сегментов макета?>
<!--####### Material content #######-->
<div id="layouts">
<?php if($data['layout_t']){//если заполнен верхний?>
<div id="layout_t"><?=$data['layout_t']?></div>
<?php }?>
<?php if($data['layout_l']||$data['layout_r']){//если заполнен правый или левый?>
<div id="layout_l" style="width:<?=htmlspecialchars($data['layout_l_width'])?>%;"><?=$data['layout_l']?></div>
<div id="layout_r"><?=$data['layout_r']?></div>
<?php }?>
<?php if($data['layout_b']){//если заполнен нижний?>
<div id="layout_b"><?=$data['layout_b']?></div>
<?php }?>
</div>
<?php }?>

<?php if($data['links']){//есть связанные ссылки
$l_opt=json_decode($data['links'],true);
echo '<!--####### Связанные ссылки #######-->'.PHP_EOL;
echo '<div id="links_block" class="noprint">'.PHP_EOL;
echo $l_opt['title']?'<h2>'.$l_opt['title'].'</h2>'.PHP_EOL:FALSE;
foreach($l_opt as $k=>$v){
 echo $k!=='title'?'<a href="'.$v['url'].'" class="links_item fa-chain">&nbsp;'.$v['title'].'</a>'.PHP_EOL:FALSE;
}
echo '</div>'.PHP_EOL;
}?>

</div>
</div>