<?php
$dir = new RecursiveDirectoryIterator('d:\xampp\htdocs\AlphaMindz\application\views');
$iter = new RecursiveIteratorIterator($dir);
foreach ($iter as $file) {
    if ($file->isFile() && pathinfo($file, PATHINFO_EXTENSION) == 'php') {
        $content = file_get_contents($file->getPathname());
        $new_content = preg_replace('/<link (.*?)href="<\?php echo base_url\(\'assets\/images\/logo\.png\'\); \?>">/', '<link $1href="<?php echo base_url(\'assets/images/favicon.png?v=\'.time()); ?>">', $content);
        if ($new_content !== $content) {
            file_put_contents($file->getPathname(), $new_content);
            echo "Updated " . $file->getPathname() . "\n";
        }
    }
}
echo "Done replacing favicon links.\n";
?>
