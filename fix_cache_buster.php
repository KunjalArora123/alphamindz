<?php
$dir = new RecursiveDirectoryIterator('d:\xampp\htdocs\AlphaMindz\application\views');
$iter = new RecursiveIteratorIterator($dir);
foreach ($iter as $file) {
    if ($file->isFile() && pathinfo($file, PATHINFO_EXTENSION) == 'php') {
        $content = file_get_contents($file->getPathname());
        
        // Fix Favicon
        $new_content = preg_replace('/assets\/images\/favicon\.png\?v=\'\.time\(\)/i', 'assets/images/favicon.png?v=\'.filemtime(FCPATH.\'assets/images/favicon.png\')', $content);
        
        // Fix Logo
        $new_content = preg_replace('/assets\/images\/logo\.png\?v=\'\.time\(\)/i', 'assets/images/logo.png?v=\'.filemtime(FCPATH.\'assets/images/logo.png\')', $new_content);

        if ($new_content !== $content) {
            file_put_contents($file->getPathname(), $new_content);
            echo "Updated " . $file->getPathname() . "\n";
        }
    }
}
echo "Done replacing time() with filemtime() for better caching.\n";
?>
