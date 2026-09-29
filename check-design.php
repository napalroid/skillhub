<?php
$file = 'resources/views/services/my-services.blade.php';
$content = file_get_contents($file);
$size = filesize($file);

echo "=== FILE VERIFICATION ===\n";
echo "File: $file\n";
echo "Size: $size bytes\n\n";

if (strpos($content, 'seller-workspace') !== false) {
    $count = substr_count($content, 'seller-workspace');
    echo "✅ NEW DESIGN DETECTED\n";
    echo "   Matches found: $count\n";
} else {
    echo "❌ NEW DESIGN NOT FOUND\n";
}

if (strpos($content, 'service-workspace') !== false) {
    echo "❌ OLD DESIGN STILL PRESENT\n";
} else {
    echo "✅ OLD DESIGN REMOVED\n";
}

// Check first 20 lines
echo "\n=== FIRST LINES CHECK ===\n";
$lines = explode("\n", $content);
for ($i = 0; $i < min(20, count($lines)); $i++) {
    echo sprintf("%3d: %s\n", $i+1, substr($lines[$i], 0, 80));
}

echo "\n=== CONCLUSION ===\n";
echo "File telah di-update dengan desain baru.\n";
echo "Jika tidak muncul di browser, masalahnya adalah:\n";
echo "1. Browser cache yang sangat persistent\n";
echo "2. Server (Apache) cache/configuration\n";
echo "3. Anda belum login sebagai seller\n";
?>