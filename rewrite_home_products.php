<?php
$file = 'd:\xampp\htdocs\AlphaMindz\application\views\home.php';
$content = file_get_contents($file);

$start_str = '<div class="shop-grid">';
$end_str = '<!-- Testimonials Section -->';

$start = strpos($content, $start_str);
$end = strpos($content, $end_str);

if ($start !== false && $end !== false) {
    $top = substr($content, 0, $start + strlen($start_str));
    // the bottom string starts with <!-- Testimonials Section -->, but we need to include the closing tags before it.
    // So let's find the closing tags of shop-grid and section
    
    $loop = '
                <?php if(!empty($products)): foreach($products as $product): ?>
                <div class="product-card">
                    <div class="product-image">
                        <?php if($product->image_url): ?>
                            <img src="<?php echo base_url($product->image_url); ?>" alt="<?php echo htmlspecialchars($product->title); ?>" style="object-fit: contain;">
                        <?php else: ?>
                            <div style="height: 100%; display: flex; align-items: center; justify-content: center; background: #f8f9fa; color: #ccc; font-size: 3rem;">
                                <i class="ri-book-2-line"></i>
                            </div>
                        <?php endif; ?>
                        <span class="product-badge">Product</span>
                    </div>
                    <div class="product-info">
                        <h4><?php echo htmlspecialchars($product->title); ?></h4>
                        <p class="price" style="font-weight: 600; font-size: 1.1rem; color: #ff4757; margin-top: 8px;">₹<?php echo htmlspecialchars($product->price); ?></p>
                        <button type="button" onclick="addToCart(\'product\', <?php echo $product->id; ?>, this)" class="btn-outline btn-sm" style="width: 100%; margin-top: 15px; text-align: center; display: block;">Add to Cart</button>
                    </div>
                </div>
                <?php endforeach; else: ?>
                    <p style="grid-column: 1 / -1; text-align: center; color: #6c757d; padding: 40px 0;">No products available at the moment.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    ';
    
    file_put_contents($file, $top . $loop . substr($content, $end));
    echo "Done updating home.php";
} else {
    echo "Could not find boundaries";
}
?>
