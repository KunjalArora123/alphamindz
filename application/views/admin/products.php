<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div>
            <h3 style="margin: 0 0 4px 0;">Shop Products & Workbooks</h3>
            <div style="display: flex; gap: 8px; margin-top: 8px; flex-wrap: wrap;">
                <?php $current_filter = isset($current_cat) ? $current_cat : 'all'; ?>
                <a href="<?php echo site_url('admin/products'); ?>" style="padding: 4px 12px; border-radius: 20px; font-size: 13px; text-decoration: none; font-weight: 600; <?php echo ($current_filter === 'all') ? 'background: #2563eb; color: #fff;' : 'background: #e2e8f0; color: #475569;'; ?>">All (<?php echo isset($total_count) ? $total_count : count($products); ?>)</a>
                <a href="<?php echo site_url('admin/products?category=ielts'); ?>" style="padding: 4px 12px; border-radius: 20px; font-size: 13px; text-decoration: none; font-weight: 600; <?php echo ($current_filter === 'ielts') ? 'background: #2563eb; color: #fff;' : 'background: #e2e8f0; color: #475569;'; ?>">IELTS (<?php echo isset($ielts_count) ? $ielts_count : 0; ?>)</a>
                <a href="<?php echo site_url('admin/products?category=achievers'); ?>" style="padding: 4px 12px; border-radius: 20px; font-size: 13px; text-decoration: none; font-weight: 600; <?php echo ($current_filter === 'achievers') ? 'background: #2563eb; color: #fff;' : 'background: #e2e8f0; color: #475569;'; ?>">Achievers (<?php echo isset($achievers_count) ? $achievers_count : 0; ?>)</a>
                <a href="<?php echo site_url('admin/products?category=ebooks'); ?>" style="padding: 4px 12px; border-radius: 20px; font-size: 13px; text-decoration: none; font-weight: 600; <?php echo ($current_filter === 'ebooks') ? 'background: #2563eb; color: #fff;' : 'background: #e2e8f0; color: #475569;'; ?>">E-Books</a>
                <a href="<?php echo site_url('admin/products?category=kits'); ?>" style="padding: 4px 12px; border-radius: 20px; font-size: 13px; text-decoration: none; font-weight: 600; <?php echo ($current_filter === 'kits') ? 'background: #2563eb; color: #fff;' : 'background: #e2e8f0; color: #475569;'; ?>">Kits</a>
            </div>
        </div>
        <a href="<?php echo site_url('admin/add_product'); ?>" class="btn-primary" style="padding: 8px 16px; text-decoration: none; border-radius: 4px; display: inline-flex; align-items: center; gap: 6px;">
            <i class="ri-add-line"></i> Add New Product
        </a>
    </div>
    <div class="card-body">
        <table class="table" style="width: 100%; border-collapse: collapse; margin-top: 15px;">
            <thead>
                <tr style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                    <th style="padding: 12px; text-align: left; width: 60px;">Preview</th>
                    <th style="padding: 12px; text-align: left;">Title</th>
                    <th style="padding: 12px; text-align: left; width: 130px;">Section / Category</th>
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
                    $cat_badges = [
                        'ielts'     => ['label' => 'IELTS', 'bg' => '#eff6ff', 'color' => '#1e40af', 'border' => '#bfdbfe'],
                        'achievers' => ['label' => 'Achievers', 'bg' => '#ecfdf5', 'color' => '#065f46', 'border' => '#a7f3d0'],
                        'ebooks'    => ['label' => 'E-Books', 'bg' => '#fefce8', 'color' => '#854d0e', 'border' => '#fef08a'],
                        'kits'      => ['label' => 'Kits', 'bg' => '#f3e8ff', 'color' => '#6b21a8', 'border' => '#e9d5ff']
                    ];
                    $badge = isset($cat_badges[$cat_val]) ? $cat_badges[$cat_val] : $cat_badges['ebooks'];
                ?>
                <tr style="border-bottom: 1px solid #dee2e6;">
                    <td style="padding: 12px;">
                        <?php if($product->image_url): ?>
                            <img src="<?php echo base_url($product->image_url); ?>" alt="<?php echo htmlspecialchars($product->title); ?>" style="height: 50px; width: auto; object-fit: contain; border-radius: 4px;">
                        <?php else: ?>
                            <span style="color: #999;">No image</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 12px; font-weight: 600; color: #1e293b;"><?php echo htmlspecialchars($product->title); ?></td>
                    <td style="padding: 12px;">
                        <span style="background: <?php echo $badge['bg']; ?>; color: <?php echo $badge['color']; ?>; border: 1px solid <?php echo $badge['border']; ?>; padding: 3px 10px; border-radius: 12px; font-size: 12px; font-weight: 700;">
                            <?php echo $badge['label']; ?>
                        </span>
                    </td>
                    <td style="padding: 12px; font-weight: 600;"><?php echo htmlspecialchars($product->price); ?></td>
                    <td style="padding: 12px;">
                        <?php if(!empty($product->file_path)): ?>
                            <span style="background: #2ecc71; color: #fff; padding: 2px 8px; border-radius: 12px; font-size: 11px;">PDF Uploaded</span>
                        <?php else: ?>
                            <span style="color: #999; font-size: 11px;">No PDF</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 12px; color: #64748b; font-size: 13px;"><?php echo date('M d, Y', strtotime($product->created_at)); ?></td>
                    <td style="padding: 12px; text-align: right;">
                        <a href="<?php echo site_url('admin/edit_product/'.$product->id); ?>" style="color: #007bff; text-decoration: none; margin-right: 10px; font-weight: 600;">Edit</a>
                        <a href="<?php echo site_url('admin/delete_product/'.$product->id); ?>" style="color: #dc3545; text-decoration: none; font-weight: 600;" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="7" style="padding: 30px; text-align: center; color: #94a3b8;">No products found in this category.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
