<?php if ($ftp->id) { ?>
<form method="post" class="edit">
<input type="hidden" name="id" value="<?php echo $ftp->id; ?>" />
<table>
<tr>
	<th class="label">Nouveau mot de passe :</th>
	<td><input type="password" name="password" size="16" /></td>
</tr>
<tr>
	<th class="label">Vérification du mot de passe :</th>
	<td><input type="password" name="password_verif" size="16" /></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><input class="submit" type="submit" name="_password_update" value="Changer de mot de passe" /></td>
</table>
</form>
<?php } ?>

<form method="post" class="edit">
<input type="hidden" name="account_id" value="<?php echo $ftp->account_id; ?>" />
<table>
<tr>
	<th class="label">Compte :</th>
	<td><?php echo $account; ?></td>
</tr>
<tr>
	<th class="label">Nom d'utilisateur :</th>
	<td><?php echo $account->name; ?>_<input name="username" value="<?php echo $ftp->username; ?>" /></td>
</tr>
<?php if (!$ftp->id) { ?>
<tr>
	<th class="label">Mot de passe :</th>
	<td><input type="password" name="password" /></td>
</tr>
<tr>
	<th class="label">Vérification du mot de passe :</th>
	<td><input type="password" name="password_verif" /></td>
</tr>
<?php } ?>
<tr>
	<th class="label">Dossier :</th>
	<td><select name="type"><?php
	foreach($ftp::$_f["type"]["list"] as $i)
		if ($ftp->type == $i)
			echo "<option value=\"$i\" selected>$i</option>";
		else
			echo "<option value=\"$i\">$i</option>";
	?></select> / <input name="folder" value="<?php echo $ftp->folder; ?>" /></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><input class="submit" type="submit" name="<?php if (!$ftp->id) echo "_insert"; else echo "_update"; ?>" value="<?php if (!$ftp->id) echo "Ajouter"; else echo "Mettre à jour"; ?>" /></td>
</table>
</form>
