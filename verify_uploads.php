<?php
/**
 * Script untuk memverifikasi bahwa uploads folder dapat diakses melalui web server
 */

echo "<h2>Verifikasi Uploads Access</h2>";

// Cek apakah public/uploads ada
$publicUploadsPath = __DIR__ . '/public/uploads';
if (is_dir($publicUploadsPath)) {
    echo "<p style='color: green;'>✓ public/uploads folder exists</p>";
    
    // Cek folder logos
    $logosPath = $publicUploadsPath . '/logos';
    if (is_dir($logosPath)) {
        echo "<p style='color: green;'>✓ public/uploads/logos folder exists</p>";
        
        // List files in logos folder
        $files = scandir($logosPath);
        $logoFiles = array_filter($files, function($file) use ($logosPath) {
            return !in_array($file, ['.', '..']) && is_file($logosPath . '/' . $file);
        });
        
        if (count($logoFiles) > 0) {
            echo "<p style='color: green;'>✓ Found " . count($logoFiles) . " logo file(s):</p>";
            echo "<ul>";
            foreach ($logoFiles as $file) {
                $webPath = '/uploads/logos/' . $file;
                echo "<li><a href='$webPath' target='_blank'>$file</a> - <img src='$webPath' style='max-width: 50px; max-height: 50px;' alt='Logo preview'></li>";
            }
            echo "</ul>";
        } else {
            echo "<p style='color: orange;'>⚠ No logo files found in logos folder</p>";
        }
    } else {
        echo "<p style='color: red;'>✗ public/uploads/logos folder not found</p>";
    }
} else {
    echo "<p style='color: red;'>✗ public/uploads folder not found</p>";
}

// Test upload path dari controller
echo "<h3>Upload Path Test</h3>";
$uploadsDir = __DIR__ . '/uploads/logos/';
if (is_dir($uploadsDir)) {
    echo "<p style='color: green;'>✓ Original uploads/logos folder exists</p>";
    
    $files = scandir($uploadsDir);
    $logoFiles = array_filter($files, function($file) use ($uploadsDir) {
        return !in_array($file, ['.', '..']) && is_file($uploadsDir . $file);
    });
    
    if (count($logoFiles) > 0) {
        echo "<p style='color: green;'>✓ Found " . count($logoFiles) . " file(s) in original uploads folder</p>";
    } else {
        echo "<p style='color: orange;'>⚠ No files in original uploads folder</p>";
    }
} else {
    echo "<p style='color: red;'>✗ Original uploads/logos folder not found</p>";
}

echo "<hr>";
echo "<p><strong>Test:</strong> Try uploading a logo through the settings page to verify the upload functionality.</p>";
?>