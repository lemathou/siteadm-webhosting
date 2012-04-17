# View
SELECT account.id AS account_id, (account.actif AND ftp_user.actif) AS actif, ftp_user.id, ftp_user.username, ENCRYPT(ftp_user.password) password, (2000+account.id) AS uid, (2000+account.id) AS gid, CONCAT('/home/siteadm/',account.folder,'/public',ftp_user.folder) AS folder, '/bin/bash'
FROM account
INNER JOIN ftp_user ON account.id=ftp_user.account_id
