# /bin/bash

rsync -avzr public/* root@web-1.addepi.fr:/home/siteadm_admin/public/
rsync -avzr scripts/* root@web-1.addepi.fr:/home/siteadm_admin/scripts/
rsync -avzr template/* root@web-1.addepi.fr:/home/siteadm_admin/template/

