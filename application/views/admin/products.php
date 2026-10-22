<div class="card" style="margin-bottom: 25px;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div>
            <h3 style="margin: 0 0 4px 0;">Shop Products & Workbooks</h3>
            <p style="margin: 0; color: #64748b; font-size: 13px;">Manage workbooks, kits, and digital materials sold in the AlphaMindz Store.</p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <a href="<?php echo site_url('shop'); ?>" target="_blank" style="background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; padding: 8px 14px; border-radius: 6px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <i class="ri-external-link-line"></i> View Live Shop
            </a>
            <a href="<?php echo site_url('admin/add_product_category'); ?>" style="background: #ffffff; color: #2563eb; border: 1.5px solid #2563eb; padding: 8px 14px; border-radius: 6px; font-size: 13px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <i class="ri-folder-add-line"></i> Add Category
            </a>
            <a href="<?php echo site_url('admin/add_product'); ?>" class="btn-primary" style="padding: 8px 16px; text-decoration: none; border-radius: 6px; display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 700;">
                <i class="ri-add-line"></i> Add New Product
            </a>
        </div>
    </div>

    <!-- Category Filter Tabs -->
    <div style="padding: 14px 20px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
        <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
            <span style="font-size: 13px; font-weight: 700; color: #475569; margin-right: 4px;">Filter Section:</span>
            <?php $current_filter = isset($current_cat) ? $current_cat : 'all'; ?>
            <a href="<?php echo site_url('admin/products'); ?>" style="padding: 5px 14px; border-radius: 20px; font-size: 13px; text-decoration: none; font-weight: 600; <?php echo ($current_filter === 'all' || empty($current_filter)) ? 'background: #2563eb; color: #fff;' : 'background: #e2e8f0; color: #475569;'; ?>">
                All Products (<?php echo isset($total_count) ? $total_count : count($products); ?>)
            </a>
            <?php if(!empty($categories)): foreach($categories as $cat): ?>
                <a href="<?php echo site_url('admin/products?category='.$cat->slug); ?>" style="padding: 5px 14px; border-radius: 20px; font-size: 13px; text-decoration: none; font-weight: 600; <?php echo ($current_filter === $cat->slug) ? 'background: #2563eb; color: #fff;' : 'background: #e2e8f0; color: #475569;'; ?>">
                    <i class="<?php echo !empty($cat->icon) ? $cat->icon : 'ri-folder-line'; ?>"></i> <?php echo htmlspecialchars($cat->title); ?> (<?php echo isset($cat->product_count) ? $cat->product_count : 0; ?>)
                </a>
            <?php endforeach; endif; ?>
        </div>
    </div>

    <div class="card-body" style="padding: 20px;">
        <div style="overflow-x: auto;">
            <table class="table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; width: 60px;">Preview</th>
                        <th style="padding: 12px; text-align: left;">Title</th>
                        <th style="padding: 12px; text-align: left; width: 140px;">Section / Category</th>
                        <th style="padding: 12px; text-align: left; width: 100px;">Price</th>
                        <th style="padding: 12px; text-align: left; width: 110px;">File</th>
                        <th style="padding: 12px; text-align: left; width: 120px;">Created At</th>
                        <th style="padding: 12px; text-align: right; width: 130px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($products)): foreach($products as $product): ?>
                    <?php 
                        $cat_val = !empty($product->category) ? $product->category : 'ebooks';
                        $cat_title = ucfirst($cat_val);
                        if (!empty($categories)) {
                            foreach ($categories as $c) {
                                if ($c->slug === $cat_val) {
                                    $cat_title = $c->title;
                                    break;
                                }
                            }
                        }
                    ?>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">
                            <?php if($product->image_url): ?>
                                <img src="<?php echo base_url($product->image_url); ?>" alt="<?php echo htmlspecialchars($product->title); ?>" style="height: 50px; width: auto; object-fit: contain; border-radius: 4px;">
                            <?php else: ?>
                                <span style="color: #999;">No image</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 12px;">
                            <strong><?php echo htmlspecialchars($product->title); ?></strong>
                            <div style="font-size: 11px; color: #94a3b8;"><?php echo htmlspecialchars($product->slug); ?></div>
                        </td>
                        <td style="padding: 12px;">
                            <span style="display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 700; background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe;">
                                <?php echo htmlspecialchars($cat_title); ?>
                            </span>
                        </td>
                        <td style="padding: 12px; font-weight: bold; color: #2ecc71;">
                            <?php echo htmlspecialchars($product->price); ?>
                        </td>
                        <td style="padding: 12px;">
                            <?php if(!empty($product->file_path)): ?>
                                <span style="color: #10b981; font-weight: 600; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="ri-file-pdf-fill"></i> Attached
                                </span>
                            <?php else: ?>
                                <span style="color: #94a3b8; font-size: 12px;">None</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 12px; color: #666; font-size: 13px;">
                            <?php echo date('M d, Y', strtotime($product->created_at)); ?>
                        </td>
                        <td style="padding: 12px; text-align: right;">
                            <a href="<?php echo site_url('admin/edit_product/'.$product->id); ?>" class="btn-sm" style="color: #3498db; margin-right: 10px; text-decoration: none;" title="Edit Product"><i class="ri-edit-line"></i></a>
                            <a href="<?php echo site_url('admin/delete_product/'.$product->id); ?>" class="btn-sm" style="color: #e74c3c; text-decoration: none;" onclick="return confirm('Are you sure you want to delete this product?');" title="Delete Product"><i class="ri-delete-bin-line"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="7" style="padding: 30px; text-align: center; color: #64748b;">
                            <i class="ri-inbox-line" style="font-size: 32px; display: block; margin-bottom: 8px; color: #94a3b8;"></i>
                            No products found in this category.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Categories Manager Section -->
        <div style="margin-top: 40px; padding-top: 24px; border-top: 2px dashed #e2e8f0;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
                <div>
                    <h3 style="margin: 0 0 4px 0; color: #0f172a; font-size: 17px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                        <i class="ri-folders-line" style="color: #2563eb;"></i> Shop Categories & Sections
                    </h3>
                    <p style="margin: 0; color: #64748b; font-size: 12px;">Add, rename, or reorder categories shown under the website's Shop menu and catalog filter tabs.</p>
                </div>
                <a href="<?php echo site_url('admin/add_product_category'); ?>" style="background-color: #2563eb; color: #fff; text-decoration: none; padding: 7px 14px; border-radius: 6px; font-weight: 600; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="ri-add-line"></i> Add Category
                </a>
            </div>

            <div style="overflow-x: auto; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0; font-size: 13px; color: #475569;">
                            <th style="padding: 10px 14px; text-align: left; width: 60px;">Icon</th>
                            <th style="padding: 10px 14px; text-align: left;">Category Name</th>
                            <th style="padding: 10px 14px; text-align: left; width: 160px;">URL Slug / Route</th>
                            <th style="padding: 10px 14px; text-align: left; width: 90px;">Products</th>
                            <th style="padding: 10px 14px; text-align: left; width: 80px;">Order</th>
                            <th style="padding: 10px 14px; text-align: left; width: 90px;">Status</th>
                            <th style="padding: 10px 14px; text-align: right; width: 130px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($categories)): foreach($categories as $cat): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9; font-size: 13px;">
                            <td style="padding: 10px 14px; text-align: center; color: #2563eb; font-size: 18px;">
                                <i class="<?php echo !empty($cat->icon) ? $cat->icon : 'ri-store-2-line'; ?>"></i>
                            </td>
                            <td style="padding: 10px 14px;">
                                <strong style="color: #0f172a;"><?php echo htmlspecialchars($cat->title); ?></strong>
                                <?php if(!empty($cat->description)): ?>
                                    <span style="display: block; font-size: 11px; color: #64748b;"><?php echo htmlspecialchars($cat->description); ?></span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 10px 14px; color: #64748b; font-family: monospace;">
                                /shop/<?php echo htmlspecialchars($cat->slug); ?>
                            </td>
                            <td style="padding: 10px 14px; font-weight: 600; color: #0f172a;">
                                <?php echo isset($cat->product_count) ? $cat->product_count : 0; ?>
                            </td>
                            <td style="padding: 10px 14px; color: #64748b;">
                                <?php echo (int)$cat->sort_order; ?>
                            </td>
                            <td style="padding: 10px 14px;">
                                <?php if((int)$cat->is_active === 1): ?>
                                    <span style="background: #ecfdf5; color: #16a34a; padding: 2px 8px; border-radius: 10px; font-weight: 600; font-size: 11px;">Active</span>
                                <?php else: ?>
                                    <span style="background: #f1f5f9; color: #94a3b8; padding: 2px 8px; border-radius: 10px; font-weight: 600; font-size: 11px;">Hidden</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 10px 14px; text-align: right;">
                                <a href="<?php echo site_url('admin/edit_product_category/'.$cat->id); ?>" style="color: #2563eb; background: #eff6ff; padding: 4px 8px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">
                                    Edit
                                </a>
                                <a href="<?php echo site_url('admin/delete_product_category/'.$cat->id); ?>" onclick="return confirm('Are you sure you want to delete category \'<?php echo htmlspecialchars(addslashes($cat->title)); ?>\'? Products under this category will remain.');" style="color: #dc2626; background: #fef2f2; padding: 4px 8px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600; margin-left: 4px;">
                                    <i class="ri-delete-bin-line"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="7" style="padding: 20px; text-align: center; color: #94a3b8;">
                                No shop categories found.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
