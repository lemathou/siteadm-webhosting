# USER
GRANT SELECT ON `siteadm`.`postfix_alias` TO 'siteadm_postfix'@'localhost';
GRANT SELECT ON `siteadm`.`postfix_redirect` TO 'siteadm_postfix'@'localhost';
GRANT SELECT ON `siteadm`.`postfix_domain` TO 'siteadm_postfix'@'localhost';
GRANT SELECT ON `siteadm`.`postfix_mbox` TO 'siteadm_postfix'@'localhost';

# VIEW postfix_alias
select `t5`.`id` AS `account_id`,`t5`.`name` AS `account_name`,concat(`t1`.`name`,'@',`t3`.`name`) AS `destination`,concat(`t2`.`name`,'@',`t4`.`name`) AS `origine`,(`t5`.`actif` and `t2`.`actif` and `t4`.`email_actif`) AS `actif`
from `email` `t1`
join `email_alias` `t2` on((`t1`.`id` = `t2`.`email_id`))
join `domain` `t3` on((`t1`.`domain_id` = `t3`.`id`))
join `domain` `t4` on((`t2`.`domain_id` = `t4`.`id`))
join `account` `t5` on((`t5`.`id` = `t3`.`account_id`))


# VIEW postfix_redirect
SELECT t5.id as account_id, t5.name as account_name, t2.redirect_email as destination, CONCAT(t2.name,'@',t3.name) as origine, (t2.actif AND t3.email_actif AND t5.actif) as actif
FROM email_alias AS t2
INNER JOIN domain as t3 ON t3.id=t2.domain_id
INNER JOIN account as t5 ON t5.id=t3.account_id
WHERE t2.redirect_email !=  '' AND t2.redirect_email IS NOT NULL

# VIEW postfix_domain
SELECT t2.id account_id, t2.name account_name, t1.name, (t2.actif AND t1.email_actif) actif
FROM `domain` t1
INNER JOIN account t2 ON t2.id = t1.account_id

# VIEW postfix_mbox
SELECT account.id account_id, account.name account_name, (2000+account.id) uid, (2000+account.id) gid, CONCAT(email.name,'@',domain.name) email, CONCAT(account.folder,'/mail/',email.name,"@",domain.name,'/') maildir, (email.actif='1' AND domain.email_actif AND account.actif) actif
FROM email
INNER JOIN domain ON domain.id=email.domain_id
INNER JOIN account ON account.id=domain.account_id
