This is a very basic link shortener. It's deployed at the subdomain
link.cckitchen.uk

.htaccess config redirects all traffic to /index.php

If it matches a hardcoded url, it redirects to it. Otherwise it falls back to a
menu page of all links (a la CCKallthedocs)

All links are found at links.json
