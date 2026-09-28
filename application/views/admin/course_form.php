<div class="form-card">
        <form action="<?php echo isset($course) ? site_url('admin/update_course/'.$course->id) : site_url('admin/save_course'); ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Course Title <span style="color: red;">*</span></label>
            <input type="text" id="title" name="title" value="<?php echo isset($course) ? htmlspecialchars($course->title) : ''; ?>" required>
        </div>
                    
                    <div style="display: flex; gap: 16px;">
                        <div class="form-group" style="flex: 1;">
                            <label for="duration">Duration (e.g. 10 hours)</label>
                            <input type="text" id="duration" name="duration" value="<?php echo isset($course) ? htmlspecialchars($course->duration) : ''; ?>">
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label for="status">Status</label>
                            <select id="status" name="status">
                                <option value="publish" <?php echo (isset($course) && $course->status === 'publish') ? 'selected' : ''; ?>>Publish</option>
                                <option value="draft" <?php echo (isset($course) && $course->status === 'draft') ? 'selected' : ''; ?>>Draft</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="thumbnail"><i class="ri-image-line"></i> Course Thumbnail Image</label>
                        <?php if (isset($course) && !empty($course->thumbnail)): ?>
                            <div style="margin-bottom: 10px;">
                                <img src="<?php echo base_url($course->thumbnail); ?>" alt="Thumbnail Preview" style="max-width: 180px; max-height: 120px; object-fit: cover; border-radius: 8px; border: 1px solid #ccc; display: block; margin-bottom: 6px;">
                                <span style="font-size: 12px; color: #666;">Current Thumbnail: <code><?php echo htmlspecialchars($course->thumbnail); ?></code></span>
                            </div>
                        <?php endif; ?>
                        <input type="file" id="thumbnail" name="thumbnail" accept="image/*" style="padding: 8px; border: 1px solid #ccc; border-radius: 4px; width: 100%;">
                        <small style="color: #666; font-size: 12px; display: block; margin-top: 4px;">Supported formats: JPG, JPEG, PNG, WEBP, GIF. Recommended ratio: 16:9.</small>
                    </div>

                    <div class="form-group">
                        <label for="introduction"><i class="ri-book-read-line"></i> Course Introduction</label>
                        <textarea id="introduction" name="introduction" placeholder="Write a summary / introduction for students..."><?php echo isset($course->introduction) ? htmlspecialchars($course->introduction) : ''; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="description"><i class="ri-file-text-line"></i> Full Course Description / Modules Outline</label>
                        <textarea id="description" name="description"><?php echo isset($course) ? htmlspecialchars($course->description) : ''; ?></textarea>
                    </div>

                    <!-- Differential Pricing Tiers Section -->
                    <div style="margin-top: 30px; margin-bottom: 24px; border-top: 2px solid #eee; padding-top: 20px;">
                        <h3 style="margin-top: 0; color: #2c3e50;"><i class="ri-price-tag-3-line"></i> Additive Differential Pricing Tiers (3 or 4 Tiers)</h3>
                        <p style="font-size: 13px; color: #666;">Set up pricing and policies for Basic, Standard, Advanced, and an optional 4th Premium tier.</p>
                        
                        <?php 
                        $default_tiers = array(
                            'basic' => array(
                                'name' => 'Basic Tier',
                                'color' => '#3498db',
                                'badge' => 'BASIC',
                                'price' => isset($tiers['basic']->price) ? $tiers['basic']->price : (isset($course->price) ? $course->price : '499'),
                                'desc' => isset($tiers['basic']->description) ? $tiers['basic']->description : 'Tier 1 (Basic) PDF Modules',
                                'delivery' => isset($tiers['basic']->delivery_policy) ? $tiers['basic']->delivery_policy : 'Strictly View-Only: Rendered inside embedded canvas. Direct download, printing, right-click, and text scraping disabled.',
                                'ebook' => isset($tiers['basic']->ebook_policy) ? $tiers['basic']->ebook_policy : 'Downloadable PDFs: All purchased standalone E-Books are directly downloadable to user devices.',
                                'enabled' => true
                            ),
                            'standard' => array(
                                'name' => 'Standard Tier',
                                'color' => '#f39c12',
                                'badge' => 'STANDARD',
                                'price' => isset($tiers['standard']->price) ? $tiers['standard']->price : '999',
                                'desc' => isset($tiers['standard']->description) ? $tiers['standard']->description : 'Tier 1 (Basic) + Tier 2 (Standard) Modules',
                                'delivery' => isset($tiers['standard']->delivery_policy) ? $tiers['standard']->delivery_policy : 'Strictly View-Only: Session-authenticated streaming via signed URLs.',
                                'ebook' => isset($tiers['standard']->ebook_policy) ? $tiers['standard']->ebook_policy : 'Downloadable PDFs: All purchased standalone E-Books are directly downloadable to user devices.',
                                'enabled' => true
                            ),
                            'advanced' => array(
                                'name' => 'Advanced Tier',
                                'color' => '#27ae60',
                                'badge' => 'ADVANCED',
                                'price' => isset($tiers['advanced']->price) ? $tiers['advanced']->price : '1499',
                                'desc' => isset($tiers['advanced']->description) ? $tiers['advanced']->description : 'Tier 1 (Basic) + Tier 2 (Standard) + Tier 3 (Advanced)',
                                'delivery' => isset($tiers['advanced']->delivery_policy) ? $tiers['advanced']->delivery_policy : 'Strictly View-Only: Cryptographically signed temporary streaming tokens.',
                                'ebook' => isset($tiers['advanced']->ebook_policy) ? $tiers['advanced']->ebook_policy : 'Downloadable PDFs: All purchased standalone E-Books are directly downloadable to user devices.',
                                'enabled' => true
                            ),
                            'premium' => array(
                                'name' => 'Premium / Pro Tier (4th Tier)',
                                'color' => '#8e44ad',
                                'badge' => 'PREMIUM',
                                'price' => isset($tiers['premium']->price) ? $tiers['premium']->price : '',
                                'desc' => isset($tiers['premium']->description) ? $tiers['premium']->description : 'All Tiers (1+2+3) + 1-on-1 Mentorship & Live Support',
                                'delivery' => isset($tiers['premium']->delivery_policy) ? $tiers['premium']->delivery_policy : 'Full VIP Access with priority resolution',
                                'ebook' => isset($tiers['premium']->ebook_policy) ? $tiers['premium']->ebook_policy : 'Downloadable PDFs & Hardcopy Materials included',
                                'enabled' => isset($tiers['premium'])
                            )
                        );
                        ?>

                        <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
                            <?php foreach ($default_tiers as $key => $tier_info): ?>
                                <div style="background: #fafafa; border: 1px solid #e0e0e0; border-left: 5px solid <?php echo $tier_info['color']; ?>; border-radius: 6px; padding: 16px;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                        <h4 style="margin: 0; color: #333;">
                                            <span style="background: <?php echo $tier_info['color']; ?>; color: #fff; padding: 2px 8px; border-radius: 4px; font-size: 11px; margin-right: 8px; font-weight: bold;"><?php echo $tier_info['badge']; ?></span>
                                            <?php echo $tier_info['name']; ?>
                                        </h4>
                                        <?php if ($key === 'premium'): ?>
                                            <label style="font-weight: normal; font-size: 13px; cursor: pointer;">
                                                <input type="checkbox" name="tiers[premium][enabled]" value="1" <?php echo $tier_info['enabled'] ? 'checked' : ''; ?>> Enable 4th Pricing Tier
                                            </label>
                                        <?php else: ?>
                                            <input type="hidden" name="tiers[<?php echo $key; ?>][enabled]" value="1">
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div style="display: flex; gap: 16px; margin-bottom: 12px;">
                                        <div style="flex: 1;">
                                            <label style="font-size: 12px; margin-bottom: 4px;">Price (₹)</label>
                                            <input type="number" step="0.01" name="tiers[<?php echo $key; ?>][price]" value="<?php echo htmlspecialchars($tier_info['price']); ?>" placeholder="e.g. 999.00" style="padding: 8px;">
                                        </div>
                                        <div style="flex: 2;">
                                            <label style="font-size: 12px; margin-bottom: 4px;">Module Composition / Summary</label>
                                            <input type="text" name="tiers[<?php echo $key; ?>][description]" value="<?php echo htmlspecialchars($tier_info['desc']); ?>" placeholder="Module contents included in tier" style="padding: 8px;">
                                        </div>
                                    </div>
                                    <div style="display: flex; gap: 16px;">
                                        <div style="flex: 1;">
                                            <label style="font-size: 12px; margin-bottom: 4px;">Delivery Security Policy</label>
                                            <input type="text" name="tiers[<?php echo $key; ?>][delivery_policy]" value="<?php echo htmlspecialchars($tier_info['delivery']); ?>" style="padding: 6px; font-size: 12px;">
                                        </div>
                                        <div style="flex: 1;">
                                            <label style="font-size: 12px; margin-bottom: 4px;">E-Book Policy</label>
                                            <input type="text" name="tiers[<?php echo $key; ?>][ebook_policy]" value="<?php echo htmlspecialchars($tier_info['ebook']); ?>" style="padding: 6px; font-size: 12px;">
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div style="margin-top: 24px;">
                        <button type="submit" class="btn-submit">
                            <i class="ri-save-line"></i> <?php echo isset($course) ? 'Update Course' : 'Save Course'; ?>
                        </button>
                        <a href="<?php echo site_url('admin/courses'); ?>" class="btn-cancel">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- CKEditor Rich Text Editor -->
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script>
      CKEDITOR.replace('introduction', {
          height: 180,
          removeButtons: 'PasteFromWord',
          versionCheck: false
      });
      CKEDITOR.replace('description', {
          height: 300,
          removeButtons: 'PasteFromWord',
          versionCheck: false
      });
    </script>
