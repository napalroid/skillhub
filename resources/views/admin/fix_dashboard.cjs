const fs = require('fs'); 
let content = fs.readFileSync('resources/views/admin/dashboard_new.blade.php', 'utf8'); 
content = content.replace(/body \{ font-family: .DM Sans., sans-serif; background: #f8f8f8; \}/, 'body { font-family: " DM "Sans, sans-serif; background: #FAFAFA; }'); 
content = content.replace(/\.badge \{ font-size: 10px; letter-spacing: 0.05em; \}/, '.badge { font-size: 12px; font-weight: 500; letter-spacing: 0.08em; text-transform: uppercase; }'); 
fs.writeFileSync('resources/views/admin/dashboard_new.blade.php', content); 
console.log('Done'); 
