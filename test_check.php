<?php if(function_exists("opcache_reset")){ opcache_reset(); echo "opcache reset OK\n"; } else { echo "opcache niet beschikbaar\n"; } echo "Done\n";
