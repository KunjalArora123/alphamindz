<?php
$cat_titles = [
    'all'       => 'Shop E-Books & Workbooks',
    'ielts'     => 'IELTS Workbooks & Materials',
    'achievers' => 'Achievers Series for Children',
    'ebooks'    => 'Educational E-Books',
    'kits'      => 'Training & Educational Kits'
];

$cat_descs = [
    'all'       => 'Browse our complete collection of IELTS workbooks, Achievers series, e-books, and training kits.',
    'ielts'     => 'Specialized IELTS reading, writing, and test preparation workbooks tailored for score enhancement.',
    'achievers' => 'Grade-specific Achievers workbooks and foundational learning materials for school students.',
    'ebooks'    => 'Digital guidebooks, self-improvement materials, and downloadable learning e-books.',
    'kits'      => 'Interactive kits and comprehensive training bundles curated by AlphaMindz educators.'
];

$curr = isset($current_category) ? $current_category : 'all';
$title_text = isset($cat_titles[$curr]) ? $cat_titles[$curr] : 'Shop E-Books & Workbooks';
$desc_text  = isset($cat_descs[$curr]) ? $cat_descs[$curr] : $cat_descs['all'];
?>

<div class="page-header" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding: 65px 0 55px; text-align: center; border-bottom: 1px solid #334155; color: #fff;">
    <div class="container" style="max-width: 900px; margin: 0 auto; padding: 0 20px;">
        <span style="display: inline-block; padding: 5px 14px; background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); font-size: 12px; font-weight: 700; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 14px;">
            AlphaMindz Store
        </span>
        <h1 style="font-family: 'Playfair Display', serif; font-size: 2.8rem; margin: 0 0 14px 0; color: #fff;">
            <?php echo htmlspecialchars($title_text); ?>
        </h1>
        <p style="color: #cbd5e1; max-width: 650px; margin: 0 auto; font-size: 1.05rem; line-height: 1.6;">
            <?php echo htmlspecialchars($desc_text); ?>
        </p>
    </div>
</div>

<!-- Category Tabs & Search Bar -->
<div class="container" style="padding: 30px 20px 10px 20px; max-width: 1200px; margin: 0 auto;">
    
    <!-- Distinct Category Filter Tabs -->
    <div style="display: flex; justify-content: center; align-items: center; gap: 10px; flex-wrap: wrap; margin-bottom: 25px;">
        <a href="<?php echo site_url('shop'); ?>" style="padding: 9px 18px; border-radius: 30px; font-size: 14px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; <?php echo ($curr === 'all') ? 'background: #2563eb; color: #fff; box-shadow: 0 4px 10px rgba(37,99,235,0.25);' : 'background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1;'; ?>">
            <i class="ri-store-2-line"></i> All Products (<?php echo isset($total_count) ? $total_count : count($products); ?>)
        </a>
        
        <a href="<?php echo site_url('shop/ielts'); ?>" style="padding: 9px 18px; border-radius: 30px; font-size: 14px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; <?php echo ($curr === 'ielts') ? 'background: #2563eb; color: #fff; box-shadow: 0 4px 10px rgba(37,99,235,0.25);' : 'background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1;'; ?>">
            <i class="ri-file-text-line"></i> IELTS (<?php echo isset($ielts_count) ? $ielts_count : 0; ?>)
        </a>

        <a href="<?php echo site_url('shop/achievers'); ?>" style="padding: 9px 18px; border-radius: 30px; font-size: 14px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; <?php echo ($curr === 'achievers') ? 'background: #2563eb; color: #fff; box-shadow: 0 4px 10px rgba(37,99,235,0.25);' : 'background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1;'; ?>">
            <i class="ri-medal-line"></i> Achievers (<?php echo isset($achievers_count) ? $achievers_count : 0; ?>)
        </a>

        <a href="<?php echo site_url('shop/ebooks'); ?>" style="padding: 9px 18px; border-radius: 30px; font-size: 14px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; <?php echo ($curr === 'ebooks') ? 'background: #2563eb; color: #fff; box-shadow: 0 4px 10px rgba(37,99,235,0.25);' : 'background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1;'; ?>">
            <i class="ri-book-2-line"></i> E-Books (<?php echo isset($ebooks_count) ? $ebooks_count : 0; ?>)
        </a>

        <a href="<?php echo site_url('shop/kits'); ?>" style="padding: 9px 18px; border-radius: 30px; font-size: 14px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; <?php echo ($curr === 'kits') ? 'background: #2563eb; color: #fff; box-shadow: 0 4px 10px rgba(37,99,235,0.25);' : 'background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1;'; ?>">
            <i class="ri-briefcase-4-line"></i> Training & Educational Kits (<?php echo isset($kits_count) ? $kits_count : 0; ?>)
        </a>
    </div>

    <!-- Search input -->
    <form action="<?php echo ($curr !== 'all') ? site_url('shop/'.$curr) : site_url('shop'); ?>" method="GET" style="display: flex; max-width: 580px; margin: 0 auto; gap: 10px;">
        <input type="text" name="q" placeholder="Search workbooks by title..." value="<?php echo isset($search_query) ? htmlspecialchars($search_query) : ''; ?>" style="flex: 1; padding: 12px 18px; border: 1px solid #ced4da; border-radius: 40px; font-size: 0.95rem; outline: none; background: #fff; box-shadow: 0 2px 6px rgba(0,0,0,0.03);">
        <button type="submit" class="btn-primary" style="padding: 12px 24px; border: none; border-radius: 40px; cursor: pointer; font-weight: 600; background: #2563eb; color: #fff; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 10px rgba(37,99,235,0.2);">
            <i class="ri-search-line"></i> Search
        </button>
        <?php if(!empty($search_query)): ?>
            <a href="<?php echo ($curr !== 'all') ? site_url('shop/'.$curr) : site_url('shop'); ?>" class="btn-outline" style="padding: 12px 20px; border-radius: 40px; display: flex; align-items: center; text-decoration: none; font-size: 14px;">Clear</a>
        <?php endif; ?>
    </form>
</div>

<!-- Product Listing Grid -->
<div class="container" style="padding: 20px 20px 70px 20px; max-width: 1200px; margin: 0 auto;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 10px; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px;">
        <span style="font-size: 14px; color: #64748b; font-weight: 600;">
            Showing <?php echo count($products); ?> item<?php echo count($products) != 1 ? 's' : ''; ?>
            <?php if($curr !== 'all'): ?>
                under <strong><?php echo ucfirst($curr); ?></strong>
            <?php endif; ?>
        </span>
        <span style="font-size: 13px; color: #94a3b8;">Instant Digital & Material Access</span>
    </div>

    <div class="shop-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 30px;">
        <?php if(!empty($products)): foreach($products as $product): ?>
            <?php 
                $is_ielts = (isset($product->category) && $product->category === 'ielts') || stripos($product->title, 'IELTS') !== false;
                $is_achiever = (isset($product->category) && $product->category === 'achievers') || stripos($product->title, 'Achievers') !== false;
                $badge_label = $is_ielts ? 'IELTS' : ($is_achiever ? 'Achievers' : 'E-Book');
                $badge_color = $is_ielts ? '#2563eb' : ($is_achiever ? '#10b981' : '#f59e0b');
            ?>
            <div class="product-card" style="border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; transition: transform 0.25s, box-shadow 0.25s; text-align: center; background: #fff; padding: 20px; display: flex; flex-direction: column; justify-content: space-between; position: relative;">
                
                <span style="position: absolute; top: 12px; left: 12px; background: <?php echo $badge_color; ?>; color: #fff; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 12px; letter-spacing: 0.5px;">
                    <?php echo $badge_label; ?>
                </span>

                <div>
                    <div class="product-image" style="height: 220px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px; background: #f8fafc; border-radius: 8px; padding: 12px;">
                        <?php if(!empty($product->image_url)): ?>
                            <img src="<?php echo base_url($product->image_url); ?>" alt="<?php echo htmlspecialchars($product->title); ?>" style="max-height: 100%; max-width: 100%; object-fit: contain; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.08));">
                        <?php else: ?>
                            <div style="color: #cbd5e1; font-size: 3.5rem;"><i class="ri-book-2-line"></i></div>
                        <?php endif; ?>
                    </div>
                    
                    <h3 style="font-family: var(--font-heading); font-size: 1.1rem; line-height: 1.4; margin: 0 0 10px 0; color: #1e293b; min-height: 48px;">
                        <?php echo htmlspecialchars($product->title); ?>
                    </h3>
                </div>

                <div class="product-details" style="margin-top: 10px;">
                    <div class="product-price" style="font-size: 1.3rem; font-weight: 700; color: #0f172a; margin-bottom: 14px;">
                        <?php echo htmlspecialchars($product->price); ?>
                    </div>
                    
                    <button type="button" onclick="addToCart('product', <?php echo $product->id; ?>, this)" class="btn-primary" style="padding: 10px 18px; width: 100%; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 14px; display: flex; align-items: center; justify-content: center; gap: 8px; background: #2563eb; color: #fff; box-shadow: 0 4px 10px rgba(37,99,235,0.2); transition: all 0.25s ease;">
                        <i class="ri-shopping-cart-2-line"></i> Add to Cart
                    </button>
                </div>
            </div>
        <?php endforeach; else: ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: #64748b; background: #fff; border: 1px dashed #cbd5e1; border-radius: 12px;">
                <i class="ri-search-line" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 12px; display: block;"></i>
                <?php if(!empty($search_query)): ?>
                    <h3 style="color: #1e293b; margin: 0 0 8px 0;">No matching products found</h3>
                    <p style="margin: 0 0 14px 0;">No items found matching "<strong><?php echo htmlspecialchars($search_query); ?></strong>".</p>
                    <a href="<?php echo site_url('shop'); ?>" style="color: #2563eb; font-weight: 600; text-decoration: underline;">View all products</a>
                <?php else: ?>
                    <h3 style="color: #1e293b; margin: 0 0 8px 0;">No products in this section yet</h3>
                    <p style="margin: 0;">New materials will be added shortly.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 25px rgba(0,0,0,0.08) !important;
        border-color: #cbd5e1 !important;
    }
</style>
