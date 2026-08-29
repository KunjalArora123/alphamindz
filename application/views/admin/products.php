<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3>All Products (E-Books)</h3>
        <a href="<?php echo site_url('admin/add_product'); ?>" class="btn-primary" style="padding: 8px 16px; text-decoration: none; border-radius: 4px;">Add New Product</a>
    </div>
    <div class="card-body">
        <table class="table" style="width: 100%; border-collapse: collapse; margin-top: 15px;">
            <thead>
                <tr style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                    <th style="padding: 12px; text-align: left;">Preview</th>
                    <th style="padding: 12px; text-align: left;">Title</th>
                    <th style="padding: 12px; text-align: left;">Price</th>
                    <th style="padding: 12px; text-align: left;">Created At</th>
                    <th style="padding: 12px; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($products)): foreach($products as $product): ?>
                <tr style="border-bottom: 1px solid #dee2e6;">
                    <td style="padding: 12px;">
                        <?php if($product->image_url): ?>
                            <img src="<?php echo base_url($product->image_url); ?>" alt="<?php echo $product->title; ?>" style="height: 50px; width: auto; object-fit: contain; border-radius: 4px;">
                        <?php else: ?>
                            <span style="color: #999;">No image</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 12px;"><?php echo $product->title; ?></td>
                    <td style="padding: 12px;"><?php echo $product->price; ?></td>
                    <td style="padding: 12px;"><?php echo date('M d, Y', strtotime($product->created_at)); ?></td>
                    <td style="padding: 12px; text-align: right;">
                        <a href="<?php echo site_url('admin/edit_product/'.$product->id); ?>" style="color: #007bff; text-decoration: none; margin-right: 10px;">Edit</a>
                        <a href="<?php echo site_url('admin/delete_product/'.$product->id); ?>" style="color: #dc3545; text-decoration: none;" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="5" style="padding: 12px; text-align: center;">No products found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
