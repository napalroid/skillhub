const fs = require('fs'); 
let c = require('fs').readFileSync('resources/views/admin/dashboard_new.blade.php', 'utf8'); 
c = c.replace(/font-family: \" DM "\Sans/, 'font-family: \DM" "Sans\'); 
require('fs').writeFileSync('resources/views/admin/dashboard_new.blade.php', c); 
