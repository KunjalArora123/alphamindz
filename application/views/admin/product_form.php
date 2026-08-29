<div class="card">
    <div class="card-header">
        <h3><?php echo isset($product) ? 'Edit Product' : 'Add New Product'; ?></h3>
    </div>
    <div class="card-body">
        <form action="<?php echo isset($product) ? site_url('admin/update_product/'.$product->id) : site_url('admin/save_product'); ?>" method="post" enctype="multipart/form-data" style="max-width: 600px;">
            
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="title" style="display: block; margin-bottom: 5px; font-weight: 500;">Product Title</label>
                <input type="text" name="title" id="title" class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;" value="<?php echo isset($product) ? $product->title : ''; ?>" required>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="price" style="display: block; margin-bottom: 5px; font-weight: 500;">Price</label>
                <input type="text" name="price" id="price" class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;" value="<?php echo isset($product) ? $product->price : ''; ?>" placeholder="E.g. $19.99 or Rs 500" required>
            </div>

            <div class="form-group" style="margin-bottom: 25px;">
                <label for="image" style="display: block; margin-bottom: 5px; font-weight: 500;">Preview Image (Cover)</label>
                <?php if(isset($product) && $product->image_url): ?>
                    <div style="margin-bottom: 10px;">
                        <img src="<?php echo base_url($product->image_url); ?>" alt="Current Image" style="height: 100px; border-radius: 4px; border: 1px solid #ddd;">
                        <p style="font-size: 12px; color: #666;">Current Image</p>
                    </div>
                <?php endif; ?>
                <input type="file" name="image" id="image" class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px; background: #fff;" accept="image/*" <?php echo isset($product) ? '' : 'required'; ?>>
                <small style="color: #6c757d; display: block; margin-top: 5px;">Upload a cover image for the e-book/product.</small>
            </div>

            <div class="form-group">
                <button type="submit" class="btn-primary" style="padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">Save Product</button>
                <a href="<?php echo site_url('admin/products'); ?>" style="margin-left: 10px; color: #6c757d; text-decoration: none;">Cancel</a>
            </div>
        </form>
    </div>
</div>
