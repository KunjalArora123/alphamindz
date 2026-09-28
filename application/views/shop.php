<div class="page-header" style="background: #f8f9fa; padding: 60px 0; text-align: center; border-bottom: 1px solid #dee2e6;">
    <div class="container">
        <h1 style="font-family: 'Playfair Display', serif; font-size: 3rem; margin-bottom: 15px; color: #2c3e50;">Shop E-Books & Resources</h1>
        <p style="color: #6c757d; max-width: 600px; margin: 0 auto;">Browse our collection of e-books, training kits, and educational materials.</p>
    </div>
</div>

<div class="container" style="padding: 40px 20px 20px 20px; max-width: 1200px; margin: 0 auto;">
    <form action="<?php echo site_url('shop'); ?>" method="GET" style="display: flex; max-width: 600px; margin: 0 auto; gap: 10px;">
        <input type="text" name="q" placeholder="Search for e-books, training kits..." value="<?php echo isset($search_query) ? htmlspecialchars($search_query) : ''; ?>" style="flex: 1; padding: 12px 16px; border: 1px solid #ced4da; border-radius: 4px; font-size: 1rem; outline: none; transition: border-color 0.2s;">
        <button type="submit" class="btn-primary" style="padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; font-weight: 500;">
            <i class="ri-search-line"></i> Search
        </button>
        <?php if(!empty($search_query)): ?>
            <a href="<?php echo site_url('shop'); ?>" class="btn-outline" style="padding: 12px 20px; border-radius: 4px; display: flex; align-items: center; text-decoration: none;">Clear</a>
        <?php endif; ?>
    </form>
</div>

<div class="container" style="padding: 20px 20px 60px 20px; max-width: 1200px; margin: 0 auto;">
    <div class="shop-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 40px;">
        <?php if(!empty($products)): foreach($products as $product): ?>
            <div class="product-card" style="border: 1px solid #eee; border-radius: 8px; overflow: hidden; transition: transform 0.3s, box-shadow 0.3s; text-align: center; background: #fff; padding: 20px;">
                <div class="product-image" style="height: 250px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px; background: #f9f9f9; border-radius: 4px; padding: 10px;">
                    <?php if($product->image_url): ?>
                        <img src="<?php echo base_url($product->image_url); ?>" alt="<?php echo $product->title; ?>" style="max-height: 100%; max-width: 100%; object-fit: contain; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                    <?php else: ?>
                        <div style="color: #ccc; font-size: 3rem;"><i class="ri-book-2-line"></i></div>
                    <?php endif; ?>
                </div>
                <div class="product-details">
                    <h3 style="font-family: 'Playfair Display', serif; font-size: 1.3rem; margin: 0 0 10px 0; color: #2c3e50;">
                        <?php echo $product->title; ?>
                    </h3>
                    <div class="product-price" style="font-size: 1.2rem; font-weight: bold; color: #27ae60; margin-bottom: 15px;">
                        <?php echo $product->price; ?>
                    </div>
                    <!-- Add to Cart / Buy button -->
                    <button type="button" onclick="addToCart('product', <?php echo $product->id; ?>, this)" class="btn-primary" style="padding: 10px 20px; width: 100%; border: none; border-radius: 4px; cursor: pointer; font-weight: 500; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.3s ease;">
                        <i class="ri-shopping-cart-2-line"></i> Add to Cart
                    </button>
                </div>
            </div>
        <?php endforeach; else: ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 60px; color: #6c757d; background: #f9f9f9; border-radius: 8px;">
                <i class="ri-search-line" style="font-size: 3rem; color: #ccc; margin-bottom: 15px; display: block;"></i>
                <?php if(!empty($search_query)): ?>
                    <p style="font-size: 1.2rem;">No products found matching "<strong><?php echo htmlspecialchars($search_query); ?></strong>".</p>
                    <a href="<?php echo site_url('shop'); ?>" style="color: #3498db; text-decoration: underline; margin-top: 10px; display: inline-block;">Clear search</a>
                <?php else: ?>
                    <p style="font-size: 1.2rem;">Our shop is currently being updated. Check back soon for new materials!</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    }
</style>
