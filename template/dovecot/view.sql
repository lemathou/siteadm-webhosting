SELECT
	account.id as account_id, account.name as account_name,
	(account.actif AND email.actif) as actif,
	email.name as email_name, domain.name as domain_name, CONCAT(email.name,'@',domain.name) as email,
	MD5(email.password) as password,
	(account.id+2000) as uid, (account.id+2000) as gid,
	CONCAT('/home/siteadm/',account.folder,'/mail/',email.name,'@',domain.name,'/') as home,
	CONCAT('maildir:/home/siteadm/',account.folder,'/mail/',email.name,'@',domain.name,'/') as mail
FROM
	email
INNER JOIN
	domain
	ON domain.id=email.domain_id
INNER JOIN
	account
	ON account.id=domain.account_id