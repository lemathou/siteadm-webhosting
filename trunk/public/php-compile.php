<pre><?php
require_once "../config/config.inc.php";
require_once SITEADM_PRIVATE_DIR."/common.inc.php";
set_time_limit(0);
@ini_set('implicit_flush', 1);
passthru("nohup sudo -- /home/workspace/SiteAdm/sources/compile.sh 'php' '5.3.6' 'full'");
echo "FINISHED";
?></pre>

<p>Logs séparés pour la sortie standard et la sortie d'erreur.</p>
<p>Actualiser l'affichage par un tail (par exemple) toutes les secondes.</p>
