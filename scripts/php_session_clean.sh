#!/bin/bash

# default = 1d = 24h = 1440mn = 86400s
find /home/siteadm/*/cookies/ -type f -cmin +1440 -print0 | xargs -r -0 rm
