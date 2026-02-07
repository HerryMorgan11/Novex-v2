Alias para sail y en vez de llamar a vendor/bin/sail hacemos sail up:

    alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'
