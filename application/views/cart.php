<div class="cart-header-banner" style="background: #f8f9fa; padding: 50px 20px; text-align: center; border-bottom: 1px solid #e9ecef;">
    <div class="container" style="max-width: 1200px; margin: 0 auto;">
        <h1 style="font-family: 'Playfair Display', serif; font-size: 2.5rem; color: #2c3e50; margin-bottom: 10px;">Your Shopping Cart</h1>
        <p style="color: #6c757d; font-size: 1rem;">Review your selected e-books, products, and assessment tests before checkout.</p>
    </div>
</div>

<div class="container" style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
    <?php if($this->session->flashdata('error')): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 15px 20px; border-radius: 6px; margin-bottom: 25px; border: 1px solid #f5c6cb;">
            <i class="ri-error-warning-line"></i> <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <?php if($this->session->flashdata('success')): ?>
        <div style="background: #d4edda; color: #155724; padding: 15px 20px; border-radius: 6px; margin-bottom: 25px; border: 1px solid #c3e6cb;">
            <i class="ri-checkbox-circle-line"></i> <?php echo $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>

    <?php if(!empty($cart)): ?>
        <div class="cart-wrapper" style="display: grid; grid-template-columns: 1fr 340px; gap: 30px; align-items: start;">
            
            <!-- Cart Items List -->
            <div class="cart-items-section">
                <div class="cart-table-container" style="background: #fff; border: 1px solid #e9ecef; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                    <div style="padding: 20px; background: #f8f9fa; border-bottom: 1px solid #e9ecef; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-weight: 600; color: #2c3e50; font-size: 1.1rem;">Cart Items (<?php echo count($cart); ?>)</span>
                        <a href="<?php echo site_url('cart/clear'); ?>" onclick="return confirm('Clear all items from your cart?');" style="color: #e74c3c; text-decoration: none; font-size: 0.9rem; font-weight: 500;">
                            <i class="ri-delete-bin-line"></i> Clear Cart
                        </a>
                    </div>

                    <div class="cart-items-list">
                        <?php foreach($cart as $item): ?>
                            <div class="cart-item-row" id="row_<?php echo $item['key']; ?>" style="display: flex; align-items: center; justify-content: space-between; padding: 20px; border-bottom: 1px solid #f1f3f5; gap: 20px; flex-wrap: wrap;">
                                
                                <div style="display: flex; align-items: center; gap: 15px; flex: 1; min-width: 250px;">
                                    <div style="width: 60px; height: 60px; background: #f1f3f5; border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden;">
                                        <?php if(!empty($item['image_url'])): ?>
                                            <img src="<?php echo $item['image_url']; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" style="width: 100%; height: 100%; object-fit: contain;">
                                        <?php elseif($item['type'] === 'assessment'): ?>
                                            <i class="ri-file-list-3-line" style="font-size: 1.8rem; color: #3498db;"></i>
                                        <?php else: ?>
                                            <i class="ri-book-2-line" style="font-size: 1.8rem; color: #2ecc71;"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <div style="margin-bottom: 4px;">
                                            <?php if($item['type'] === 'assessment'): ?>
                                                <span style="background: #ebf5fb; color: #2980b9; font-size: 0.75rem; font-weight: 600; padding: 2px 8px; border-radius: 4px; display: inline-block; margin-bottom: 4px;">Assessment Test</span>
                                            <?php else: ?>
                                                <span style="background: #eafaf1; color: #27ae60; font-size: 0.75rem; font-weight: 600; padding: 2px 8px; border-radius: 4px; display: inline-block; margin-bottom: 4px;">Product</span>
                                            <?php endif; ?>
                                        </div>
                                        <h4 style="margin: 0; font-size: 1rem; color: #2c3e50; font-weight: 600;">
                                            <?php echo htmlspecialchars($item['title']); ?>
                                        </h4>
                                        <div style="font-size: 0.9rem; color: #7f8c8d; margin-top: 2px;">
                                            Price: ₹<?php echo number_format($item['price'], 2); ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quantity Controls -->
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <?php if($item['type'] === 'assessment'): ?>
                                        <span style="font-size: 0.85rem; color: #7f8c8d; background: #f8f9fa; padding: 6px 12px; border-radius: 4px; border: 1px solid #dee2e6;">Single Access</span>
                                    <?php else: ?>
                                        <div style="display: flex; align-items: center; border: 1px solid #ced4da; border-radius: 4px; overflow: hidden;">
                                            <button type="button" onclick="updateQty('<?php echo $item['key']; ?>', <?php echo $item['qty'] - 1; ?>)" style="background: #f8f9fa; border: none; padding: 6px 12px; cursor: pointer; color: #495057;">-</button>
                                            <input type="text" value="<?php echo $item['qty']; ?>" readonly style="width: 40px; text-align: center; border: none; font-size: 0.9rem; font-weight: 600;">
                                            <button type="button" onclick="updateQty('<?php echo $item['key']; ?>', <?php echo $item['qty'] + 1; ?>)" style="background: #f8f9fa; border: none; padding: 6px 12px; cursor: pointer; color: #495057;">+</button>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Item Total & Remove -->
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div style="font-weight: 700; color: #2c3e50; font-size: 1.1rem; min-width: 90px; text-align: right;">
                                        ₹<span id="subtotal_<?php echo $item['key']; ?>"><?php echo number_format($item['price'] * $item['qty'], 2); ?></span>
                                    </div>
                                    <a href="<?php echo site_url('cart/remove/' . $item['key']); ?>" style="color: #adb5bd; text-decoration: none; font-size: 1.2rem; transition: color 0.2s;" onmouseover="this.style.color='#e74c3c'" onmouseout="this.style.color='#adb5bd'">
                                        <i class="ri-close-circle-line"></i>
                                    </a>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center;">
                    <a href="<?php echo site_url('shop'); ?>" style="color: #3498db; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="ri-arrow-left-line"></i> Continue Shopping
                    </a>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="cart-summary-section" style="background: #fff; border: 1px solid #e9ecef; border-radius: 8px; padding: 25px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                <h3 style="font-family: 'Playfair Display', serif; margin: 0 0 20px 0; color: #2c3e50; font-size: 1.4rem;">Order Summary</h3>
                
                <div style="display: flex; justify-content: space-between; margin-bottom: 12px; color: #6c757d; font-size: 0.95rem;">
                    <span>Subtotal</span>
                    <span style="font-weight: 600; color: #2c3e50;">₹<span id="cartSubtotal"><?php echo number_format($subtotal, 2); ?></span></span>
                </div>
                
                <div style="display: flex; justify-content: space-between; margin-bottom: 20px; color: #6c757d; font-size: 0.95rem;">
                    <span>Instant Digital Access</span>
                    <span style="color: #27ae60; font-weight: 600;">FREE</span>
                </div>

                <hr style="border: none; border-top: 1px solid #e9ecef; margin: 15px 0;">

                <div style="display: flex; justify-content: space-between; margin-bottom: 25px;">
                    <span style="font-weight: 700; color: #2c3e50; font-size: 1.2rem;">Total</span>
                    <span style="font-weight: 700; color: #e74c3c; font-size: 1.3rem;">₹<span id="cartTotal"><?php echo number_format($total, 2); ?></span></span>
                </div>

                <a href="<?php echo site_url('cart/checkout'); ?>" class="btn-primary" style="display: block; text-align: center; width: 100%; padding: 14px; border-radius: 6px; font-size: 1.05rem; font-weight: 600; box-sizing: border-box; text-decoration: none;">
                    Proceed to Checkout <i class="ri-arrow-right-line"></i>
                </a>
            </div>

        </div>

    <?php else: ?>
        <div style="text-align: center; padding: 80px 20px; background: #fff; border-radius: 12px; border: 1px solid #e9ecef; max-width: 600px; margin: 0 auto; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
            <div style="font-size: 4rem; color: #ced4da; margin-bottom: 15px;">
                <i class="ri-shopping-cart-2-line"></i>
            </div>
            <h2 style="font-family: 'Playfair Display', serif; color: #2c3e50; margin-bottom: 10px;">Your Cart is Empty</h2>
            <p style="color: #6c757d; margin-bottom: 30px;">Looks like you haven't added any products or assessment tests to your cart yet.</p>
            <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
                <a href="<?php echo site_url('shop'); ?>" class="btn-primary" style="padding: 10px 24px;">Browse Shop</a>
                <a href="<?php echo site_url('assessments'); ?>" class="btn-primary" style="background: #34495e; padding: 10px 24px;">Explore Assessments</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function updateQty(key, newQty) {
    var formData = new FormData();
    formData.append('key', key);
    formData.append('qty', newQty);

    fetch('<?php echo site_url("cart/update"); ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            if (data.item_qty <= 0) {
                var row = document.getElementById('row_' + key);
                if (row) row.remove();
            } else {
                var subElem = document.getElementById('subtotal_' + key);
                if (subElem) subElem.innerText = data.item_subtotal;
            }
            document.getElementById('cartSubtotal').innerText = data.subtotal;
            document.getElementById('cartTotal').innerText = data.subtotal;
            var badge = document.getElementById('headerCartCount');
            if (badge) badge.innerText = data.cart_count;

            if (data.cart_count <= 0) {
                window.location.reload();
            }
        }
    })
    .catch(err => console.error(err));
}
</script>

<style>
@media (max-width: 850px) {
    .cart-wrapper {
        grid-template-columns: 1fr !important;
    }
}
</style>
