<?php
// começa manipulação do form
if(isset($_POST[SSW_TEAMSI_GROUP])) update_option(SSW_TEAMSI_GROUP, $_POST[SSW_TEAMSI_GROUP]);
// termino manipulação do form
$SSWTI = new ssw_tint_wp();
$groups = $SSWTI->getGroups();
$groupSelected = get_option(SSW_TEAMSI_GROUP);
// inicia a página
include SSW_TEAMSI_PATH."/views/template/header.php";

echo('<h2>Configuração</h2>');
// se retornou um erro
if (isset($groups->errors)) {
	echo('<h3>Houve um erro ao verificar os grupos</h3>');
	echo('<p>Tipo do erro: '.$groups->errors->error_type.'</p>');
	echo('<p>Mensagem do erro: '.$groups->errors->error_message.'</p>');
	echo('<p>Entre em contato com o desenvolvedor do sistema.</p>');
} else if (isset($groups->value)) { // se existem valores
?>
<form action="<?php echo($_SERVER['REQUEST_URI']); ?>" method="POST">
	<label for="<?php echo(SSW_TEAMSI_GROUP); ?>">Selecione o grupo em que serão adicionados os novos leads</label>
    <select name="<?php echo(SSW_TEAMSI_GROUP); ?>" id="<?php echo(SSW_TEAMSI_GROUP); ?>" required>
        <option value="">Selecione um grupo</option>
		<?php
		foreach ($groups->value as $key => $value) {
		?>
			<option value="<?php echo $value->id; ?>" <?php if($value->id == $groupSelected){ echo('selected'); } ?>>
				<?php echo $value->displayName ?>
			</option>
		<?php
		}
		?>
    </select>
	<div>
    	<button type="submit">OK</button>
	</div>
</form>
<?php
} else { // se retornou falso
	echo('<h3>Houve um erro ao verificar os custom posts, já configurou o acesso?</h3>');
}

include SSW_TEAMSI_PATH."/views/template/footer.php";
?>