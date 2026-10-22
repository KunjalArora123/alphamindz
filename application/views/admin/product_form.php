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
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                    <label for="category" style="font-weight: 500; margin: 0;">Shop Section / Category <span style="color: red;">*</span></label>
                    <a href="<?php echo site_url('admin/add_product_category'); ?>" target="_blank" style="font-size: 12px; color: #2563eb; text-decoration: none; font-weight: 600;">+ New Category</a>
                </div>
                <select name="category" id="category" class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px; background: #fff;" required>
                    <?php if(!empty($categories)): ?>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat->slug); ?>" <?php echo (isset($product) && $product->category === $cat->slug) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat->title); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="ielts" <?php echo (isset($product) && $product->category === 'ielts') ? 'selected' : ''; ?>>IELTS</option>
                        <option value="achievers" <?php echo (isset($product) && $product->category === 'achievers') ? 'selected' : ''; ?>>Achievers</option>
                        <option value="ebooks" <?php echo (isset($product) && $product->category === 'ebooks') ? 'selected' : ''; ?>>E-Books</option>
                        <option value="kits" <?php echo (isset($product) && $product->category === 'kits') ? 'selected' : ''; ?>>Training & Educational Kits</option>
                    <?php endif; ?>
                </select>
                <small style="color: #6c757d; display: block; margin-top: 4px;">Controls which dropdown section and filter tab this product appears under.</small>
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

            <div class="form-group" style="margin-bottom: 25px;">
                <label for="pdf_file" style="display: block; margin-bottom: 5px; font-weight: 500;">Book PDF File</label>
                <?php if(isset($product) && $product->file_path): ?>
                    <div style="margin-bottom: 10px; font-size: 13px; color: #2ecc71;">
                        <i class="ri-checkbox-circle-line"></i> Current PDF: <strong><?php echo basename($product->file_path); ?></strong>
                    </div>
                <?php endif; ?>
                <input type="file" name="pdf_file" id="pdf_file" class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px; background: #fff;" accept="application/pdf">
                <small style="color: #6c757d; display: block; margin-top: 5px;">Upload the PDF file to be stored in books_pdfs. Leave empty to keep existing.</small>
            </div>

            <button type="submit" class="btn-primary" style="padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">
                <?php echo isset($product) ? 'Update Product' : 'Add Product'; ?>
            </button>
            <a href="<?php echo site_url('admin/products'); ?>" class="btn-secondary" style="padding: 10px 20px; border: 1px solid #ccc; border-radius: 4px; text-decoration: none; color: #333; margin-left: 10px;">Cancel</a>

        </form>
    </div>
</div>
