<div class="card" style="border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; margin-bottom: 25px;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; padding: 18px 24px; background: #ffffff; border-bottom: 1px solid #e2e8f0; border-top-left-radius: 10px; border-top-right-radius: 10px; flex-wrap: wrap; gap: 10px;">
        <div>
            <h3 style="margin: 0 0 4px 0; color: #0f172a; font-size: 19px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                <i class="ri-global-line" style="color: #2563eb; font-size: 22px;"></i> Main Website Management
            </h3>
            <p style="margin: 0; color: #64748b; font-size: 13px;">Manage media assets and visuals displayed on the public homepage of AlphaMindz.</p>
        </div>
        <div>
            <a href="<?php echo base_url(); ?>" target="_blank" class="btn" style="background-color: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; padding: 7px 14px; border-radius: 6px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s;">
                <i class="ri-external-link-line"></i> View Live Homepage
            </a>
        </div>
    </div>
    
    <div class="card-body" style="padding: 24px;">
        
        <!-- Specification Banner -->
        <div style="background: linear-gradient(135deg, #eff6ff 0%, #f0fdf4 100%); border: 1px solid #bfdbfe; border-left: 5px solid #2563eb; border-radius: 8px; padding: 16px 20px; margin-bottom: 25px;">
            <div style="display: flex; align-items: flex-start; gap: 12px;">
                <i class="ri-information-line" style="color: #2563eb; font-size: 22px; margin-top: 1px;"></i>
                <div>
                    <h4 style="margin: 0 0 6px 0; color: #1e3a8a; font-size: 15px; font-weight: 700;">Hero Section Photo Guidelines & Dimensions</h4>
                    <p style="margin: 0 0 8px 0; color: #334155; font-size: 13px; line-height: 1.5;">
                        This photo represents the primary collaborative visual shown next to <em>"Empower. Inspire. Motivate."</em> in the hero header of the main website. To keep the homepage perfectly aligned, the system preserves the exact dimensions and responsive aspect ratio.
                    </p>
                    <div style="display: flex; flex-wrap: wrap; gap: 15px; font-size: 12px; font-weight: 600; color: #1e40af;">
                        <span style="background: rgba(37,99,235,0.08); padding: 3px 10px; border-radius: 12px; border: 1px solid rgba(37,99,235,0.2);">
                            <i class="ri-aspect-ratio-line"></i> <strong>Aspect Ratio:</strong> 4 : 5 (Portrait)
                        </span>
                        <span style="background: rgba(37,99,235,0.08); padding: 3px 10px; border-radius: 12px; border: 1px solid rgba(37,99,235,0.2);">
                            <i class="ri-ruler-line"></i> <strong>Recommended Dimensions:</strong> 800 × 1000 px or 1000 × 1250 px
                        </span>
                        <span style="background: rgba(37,99,235,0.08); padding: 3px 10px; border-radius: 12px; border: 1px solid rgba(37,99,235,0.2);">
                            <i class="ri-file-image-line"></i> <strong>Formats:</strong> JPG, JPEG, PNG, WebP (Max 12MB)
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <?php
            $default_hero_url = "https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80";
            $is_custom = (!empty($hero_image));
            if ($is_custom) {
                if (strpos($hero_image, 'http') === 0) {
                    $active_hero_url = $hero_image;
                } else {
                    $f_path = FCPATH . $hero_image;
                    $v = file_exists($f_path) ? '?v=' . filemtime($f_path) : '';
                    $active_hero_url = base_url($hero_image) . $v;
                }
            } else {
                $active_hero_url = $default_hero_url;
            }
        ?>

        <!-- Two Column Layout: Upload Form & Live Preview -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 30px; align-items: start;">
            
            <!-- Left: Upload & Change Form -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 22px;">
                <h4 style="margin: 0 0 14px 0; color: #0f172a; font-size: 16px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                    <i class="ri-upload-cloud-2-line" style="color: #2563eb;"></i> Change Homepage Hero Photo
                </h4>
                
                <form action="<?php echo site_url('admin/update_hero_image'); ?>" method="POST" enctype="multipart/form-data" id="heroUploadForm">
                    
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 8px;">
                            Select New Photo File:
                        </label>
                        
                        <div id="dropArea" style="border: 2px dashed #cbd5e1; border-radius: 10px; padding: 24px 16px; text-align: center; background: #f8fafc; cursor: pointer; transition: all 0.2s;" onclick="document.getElementById('heroImageInput').click();">
                            <i class="ri-image-add-line" style="font-size: 38px; color: #94a3b8; display: block; margin-bottom: 8px;"></i>
                            <span style="font-size: 13px; font-weight: 700; color: #2563eb; display: block; margin-bottom: 4px;">Click to Browse Image</span>
                            <span style="font-size: 11px; color: #64748b; display: block;">Supports high-resolution JPG, PNG or WebP</span>
                            <input type="file" name="hero_image" id="heroImageInput" accept="image/*" style="display: none;" onchange="handleFileSelect(this);">
                        </div>
                    </div>

                    <!-- Instant New File Preview Box (hidden until file chosen) -->
                    <div id="newPreviewContainer" style="display: none; margin-bottom: 20px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <span style="font-size: 12px; font-weight: 700; color: #0f172a;"><i class="ri-eye-line"></i> Selected File Preview:</span>
                            <button type="button" onclick="clearSelectedFile();" style="background: none; border: none; color: #ef4444; font-size: 12px; font-weight: 600; cursor: pointer;">Remove</button>
                        </div>
                        <div style="width: 180px; max-width: 100%; aspect-ratio: 4/5; border-radius: 16px; overflow: hidden; margin: 0 auto; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border: 2px solid #2563eb;">
                            <img id="newImagePreview" src="" alt="New Image Preview" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div id="fileInfoText" style="text-align: center; font-size: 11px; color: #64748b; margin-top: 8px;"></div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <button type="submit" id="saveBtn" class="btn" style="background-color: #2563eb; color: #ffffff; padding: 12px 20px; border-radius: 6px; font-size: 14px; font-weight: 700; border: none; cursor: pointer; display: flex; justify-content: center; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(37,99,235,0.25); transition: all 0.2s;">
                            <i class="ri-check-line" style="font-size: 18px;"></i> Save & Publish Hero Photo
                        </button>

                        <?php if ($is_custom): ?>
                        <a href="<?php echo site_url('admin/reset_hero_image'); ?>" onclick="return confirm('Revert to the original default photo? Your custom photo will be replaced.');" class="btn" style="background-color: #ffffff; color: #dc2626; border: 1px solid #fca5a5; padding: 9px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; text-decoration: none; text-align: center; display: flex; justify-content: center; align-items: center; gap: 6px;">
                            <i class="ri-restart-line"></i> Reset to Default Photo
                        </a>
                        <?php endif; ?>
                    </div>
                </form>

            </div>

            <!-- Right: Current Live Homepage Mockup -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 22px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 8px;">
                    <h4 style="margin: 0; color: #0f172a; font-size: 16px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                        <i class="ri-smartphone-line" style="color: #10b981;"></i> Current Live Website Display
                    </h4>
                    <?php if ($is_custom): ?>
                        <span style="background: #dcfce7; color: #15803d; border: 1px solid #86efac; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                            <i class="ri-checkbox-circle-fill"></i> Custom Photo Active
                        </span>
                    <?php else: ?>
                        <span style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                            <i class="ri-image-line"></i> Default Photo Active
                        </span>
                    <?php endif; ?>
                </div>

                <p style="margin: 0 0 16px 0; color: #64748b; font-size: 12px;">
                    Exact preview showing the 4:5 vertical proportions, 24px rounded corners, and overlay badges as seen by visitors:
                </p>

                <!-- Mockup of the Homepage Image Wrapper with Overlay Badges -->
                <div style="max-width: 310px; margin: 0 auto; position: relative;">
                    
                    <div style="position: relative; width: 100%; aspect-ratio: 4/5; border-radius: 24px; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.14); border: 1px solid rgba(0,0,0,0.06); background: #f8fafc;">
                        <img src="<?php echo htmlspecialchars($active_hero_url); ?>" alt="Homepage Hero Visual" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                        
                        <!-- Top-Right Badge Mockup -->
                        <div style="position: absolute; top: 20px; right: 12px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(8px); padding: 8px 12px; border-radius: 12px; display: flex; align-items: center; gap: 10px; box-shadow: 0 8px 20px rgba(0,0,0,0.12); border: 1px solid rgba(255,255,255,0.6);">
                            <div style="background: rgba(48, 98, 135, 0.1); color: #306287; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                                <i class="ri-medal-fill"></i>
                            </div>
                            <div style="line-height: 1.2;">
                                <strong style="display: block; font-size: 11px; color: #0f172a;">Top Global Colleges</strong>
                                <span style="font-size: 10px; color: #64748b;">Placements</span>
                            </div>
                        </div>

                        <!-- Bottom-Left Badge Mockup -->
                        <div style="position: absolute; bottom: 25px; left: 12px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(8px); padding: 8px 12px; border-radius: 12px; display: flex; align-items: center; gap: 10px; box-shadow: 0 8px 20px rgba(0,0,0,0.12); border: 1px solid rgba(255,255,255,0.6);">
                            <div style="background: rgba(131, 187, 77, 0.15); color: #83bb4d; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                                <i class="ri-verified-badge-fill"></i>
                            </div>
                            <div style="line-height: 1.2;">
                                <strong style="display: block; font-size: 11px; color: #0f172a;">Expert Advisors</strong>
                                <span style="font-size: 10px; color: #64748b;">Verified Guidance</span>
                            </div>
                        </div>

                    </div>

                    <div style="text-align: center; margin-top: 12px; font-size: 11px; color: #94a3b8;">
                        <i class="ri-check-double-line" style="color: #10b981;"></i> Dimensions: Fixed 4:5 Aspect Ratio (Same as current live layout)
                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

<script>
function handleFileSelect(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        
        // Validate type
        if (!file.type.match('image.*')) {
            alert('Please select an image file (PNG, JPG, JPEG, WEBP).');
            input.value = '';
            return;
        }

        // Validate size (12MB)
        if (file.size > 12 * 1024 * 1024) {
            alert('File size exceeds 12MB. Please select a smaller image.');
            input.value = '';
            return;
        }

        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('newImagePreview').src = e.target.result;
            document.getElementById('newPreviewContainer').style.display = 'block';
            document.getElementById('fileInfoText').innerHTML = '<strong>' + file.name + '</strong> (' + (file.size / (1024*1024)).toFixed(2) + ' MB)';
        };
        reader.readAsDataURL(file);
    }
}

function clearSelectedFile() {
    var input = document.getElementById('heroImageInput');
    input.value = '';
    document.getElementById('newPreviewContainer').style.display = 'none';
    document.getElementById('newImagePreview').src = '';
    document.getElementById('fileInfoText').innerText = '';
}

// Drag & drop support
var dropArea = document.getElementById('dropArea');
['dragenter', 'dragover'].forEach(eventName => {
    dropArea.addEventListener(eventName, function(e) {
        e.preventDefault();
        dropArea.style.borderColor = '#2563eb';
        dropArea.style.background = '#eff6ff';
    }, false);
});
['dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, function(e) {
        e.preventDefault();
        dropArea.style.borderColor = '#cbd5e1';
        dropArea.style.background = '#f8fafc';
    }, false);
});
dropArea.addEventListener('drop', function(e) {
    var dt = e.dataTransfer;
    var files = dt.files;
    if (files.length > 0) {
        var input = document.getElementById('heroImageInput');
        input.files = files;
        handleFileSelect(input);
    }
}, false);
</script>
