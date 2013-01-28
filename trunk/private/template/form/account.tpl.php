<form method="post" class="edit">
	<?php if ($account->id) { ?>
	<input type="hidden" name="id" value="<?php echo $account->id; ?>" />
	<?php } ?>

	<div style="width: 600px;" class="cadre">
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td class="label">Type</td>
				<td class="field"><select name="type"><?php
				foreach ($account_type_list as $i=>$j)
				{
					if ($i == $account->type)
						echo "<option value=\"$i\" selected>$j</option>";
					else
						echo "<option value=\"$i\">$j</option>";
				}
				?></select>
				</td>
			</tr>
			<tr>
				<td class="label">Managing account</td>
				<td class="field"><select name="manager_id"><option value="0">--
							Choisir au besoin --</option>
						<?
						foreach($manager_list as $i=>$j)
						{
							if ($i == $account->manager_id)
								echo "<option value=\"$i\" selected>$j</option>\n";
							else
								echo "<option value=\"$i\">$j</option>\n";
						}
						?></select>
				</td>
			</tr>
			<tr>
				<td class="label">Actif</td>
				<td class="field"><input name="actif" type="radio" value="1"
				<?php if ($account->actif) echo " checked"; ?> /> ACTIF <input
					name="actif" type="radio" value="0"
					<?php if (!$account->actif) echo " checked"; ?> /> INACTIF</td>
			</tr>
			<tr>
				<td class="label">Username</td>
				<td class="field"><?php if ($account->id) { 
					echo $account->name;
				} else { ?><input name="username"
					value="<?php echo $account->email; ?>" size="32" />
				<?php }?>
				</td>
			</tr>
			<tr>
				<td class="label">Email</td>
				<td class="field"><input name="email"
					value="<?php echo $account->email; ?>" size="32" />
				</td>
			</tr>
			<tr>
				<td class="label">Civilité</td>
				<td class="field"><select name="civilite"><?php
				foreach($civilite_list as $i=>$j)
				{
					if ($i==$account->civilite)
						echo "<option value=\"$i\" selected>$j</option>\n";
					else
						echo "<option value=\"$i\">$j</option>\n";
				}
				?></select>
				</td>
			</tr>
			<tr>
				<td class="label">Prénom</td>
				<td class="field"><input name="prenom"
					value="<?php echo $account->prenom; ?>" />
				</td>
			</tr>
			<tr>
				<td class="label">Nom</td>
				<td class="field"><input name="nom"
					value="<?php echo $account->nom; ?>" />
				</td>
			</tr>
			<tr>
				<td class="label">Entreprise</td>
				<td class="field"><input name="societe"
					value="<?php echo $account->societe; ?>" />
				</td>
			</tr>
			<tr>
				<td class="label">Offre</td>
				<td class="field"><select name="offre_id"><option></option>
						<?php
						foreach($offre_list as $i=>$j)
	{
		if ($i==$account->offre_id)
			echo "<option value=\"$i\" selected>$j[name]</option>\n";
		else
			echo "<option value=\"$i\">$j[name]</option>\n";
	}
	?></select>
				</td>
			</tr>
		</table>
	</div>

	<p>
		<input type="submit" name="<?php echo $form_submit_name; ?>"
			value="<?php echo $form_submit_text; ?>" />
	</p>

</form>
