<?php
$SSWTI = new ssw_tint_wp();
// verifica se há posts para configurar variáveis
if(isset($_POST['client_id'])){ $SSWTI->setClientId($_POST['client_id']); }
if(isset($_POST['client_secret'])){ $SSWTI->setClientSecret($_POST['client_secret']); }
if(isset($_POST['code'])){
    $SSWTI->setCode('');
}
// inicia a página
include SSW_TEAMSI_PATH."/views/template/header.php";
?>
<h1>Autorização</h1>
<p>Essas informações estão no aplicativo criado no Azure Active Directory admin center</p>
<!-- Client ID -->
<form method="POST" action="<?php $_SERVER['HTTP_REFERER'] ?>">
    <label for="client_id">Cliente ID</label>
    <input type="text" name="client_id" value="<?php echo $SSWTI->getClientId() ?>">
    <input type="submit" value="Atualizar">
</form>
<!-- Client Secret -->
<form method="POST" action="<?php $_SERVER['HTTP_REFERER'] ?>">
    <label for="client_secret">Cliente Secret</label>
    <input type="text" name="client_secret" value="<?php echo $SSWTI->getClientSecret() ?>">
    <input type="submit" value="Atualizar">
</form>
<?php
if($SSWTI->hasCode()){
?>
<!-- Reset Code -->
<form method="POST" action="<?php $_SERVER['HTTP_REFERER'] ?>">
    <label for="code">Apagar Autorização?</label>
    <input type="hidden" name="code" value="true">
    <input type="submit" value="Apagar">
</form>
<?php
}
?>
<?php
include SSW_TEAMSI_PATH."/views/template/footer.php";
?>