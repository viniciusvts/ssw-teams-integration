<?php
$SSWTI = new ssw_tint_wp();
include SSW_TEAMSI_PATH."/views/template/header.php";
?>
<h2>Home</h2>
<p>Status da integração:</p>
<?php
if($SSWTI->hasClientId()){ echo '<p>Client ID ok</p>'; }
else{
    echo '<p>Insira o Client ID. <a href="';
    menu_page_url(SSW_TEAMSI_PLUGIN_SLUG.'-config');
    echo '">Aqui</a></p>';
}

if($SSWTI->hasClientSecret()){ echo '<p>Client Secret ok</p>'; }
else{
    echo '<p>Insira o Client Secret. <a href="';
    menu_page_url(SSW_TEAMSI_PLUGIN_SLUG.'-config');
    echo '">Aqui</a></p>';
}

if($SSWTI->hasCode()){ echo '<p>Autorização ok</p>'; }
else{
    if(!$SSWTI->hasClientId() || !$SSWTI->hasClientSecret()){ echo '<p>Código ausente, configure o Client ID/Secret primeiro</p>'; }
    else{ 
        echo '<p>Integração não completada. Antes de iniciar, defina na aplicação na Azure a url de callback para: <strong>';
        echo SSW_TEAMSI_URLCALLBACK.'</strong></p>';
        //url de integração rd
        $url = 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize?client_id=';
        $url .= $SSWTI->getClientId().'&redirect_uri='.SSW_TEAMSI_URLCALLBACK;
        $url .= '&response_type=code&scope=offline_access Directory.ReadWrite.All';
        //
        echo '<a href="';
        echo $url;
        echo '">Iniciar integração</a>';
    }
}
include SSW_TEAMSI_PATH."/views/template/footer.php";
?>
