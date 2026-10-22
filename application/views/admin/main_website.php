<div class="card" style="border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; margin-bottom: 25px;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; padding: 18px 24px; background: #ffffff; border-bottom: 1px solid #e2e8f0; border-top-left-radius: 10px; border-top-right-radius: 10px; flex-wrap: wrap; gap: 10px;">
        <div>
            <h3 style="margin: 0 0 4px 0; color: #0f172a; font-size: 19px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                <i class="ri-global-line" style="color: #2563eb; font-size: 22px;"></i> Main Website Management
            </h3>
            <p style="margin: 0; color: #64748b; font-size: 13px;">Manage media assets, hero visual, and the primary promotional showcase video displayed on the public homepage.</p>
        </div>
        <div>
            <a href="<?php echo base_url(); ?>" target="_blank" class="btn" style="background-color: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; padding: 7px 14px; border-radius: 6px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s;">
                <i class="ri-external-link-line"></i> View Live Homepage
            </a>
        </div>
    </div>
    
    <div class="card-body" style="padding: 24px;">
        
        <!-- ============================================================== -->
        <!-- SECTION 1: PRIMARY PROMOTIONAL VIDEO -->
        <!-- ============================================================== -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; margin-bottom: 35px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
            
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 18px; flex-wrap: wrap; gap: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 14px;">
                <div>
                    <h4 style="margin: 0 0 4px 0; color: #0f172a; font-size: 17px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                        <i class="ri-video-line" style="color: #ef4444; font-size: 22px;"></i> Primary Promotional Showcase Video
                    </h4>
                    <p style="margin: 0; color: #64748b; font-size: 13px;">
                        This video sits prominently directly beneath the homepage hero section and is launched when visitors click <strong>"Watch Video"</strong>.
                    </p>
                </div>
                <div>
                    <?php if(!empty($promo_video)): ?>
                        <span style="background: #dcfce7; color: #15803d; border: 1px solid #86efac; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                            <i class="ri-checkbox-circle-fill"></i> Custom Video Active
                        </span>
                    <?php else: ?>
                        <span style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                            <i class="ri-movie-line"></i> Default Video Active (assets/video/index.mp4)
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <?php
                $default_video_path = 'assets/video/index.mp4';
                $cur_video = !empty($promo_video) ? $promo_video : $default_video_path;
                $cur_type  = !empty($promo_video_type) ? $promo_video_type : 'file';

                $cur_yt_embed = '';
                if ($cur_type === 'youtube' || $cur_type === 'url') {
                    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $cur_video, $ytm)) {
                        $cur_yt_embed = 'https://www.youtube.com/embed/' . $ytm[1];
                    } else {
                        $cur_yt_embed = $cur_video;
                    }
                }
            ?>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 28px; align-items: start;">
                
                <!-- Left: Form to Change Video -->
                <div>
                    <form action="<?php echo site_url('admin/update_promo_video'); ?>" method="POST" enctype="multipart/form-data" id="promoVideoForm">
                        
                        <!-- Tabs / Source Selector -->
                        <div style="margin-bottom: 18px;">
                            <label style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 8px;">
                                Video Source:
                            </label>
                            <div style="display: flex; gap: 8px; background: #f1f5f9; padding: 4px; border-radius: 8px;">
                                <button type="button" id="tabUploadBtn" onclick="switchVideoTab('upload')" style="flex: 1; padding: 8px 14px; border: none; border-radius: 6px; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.2s; background: #fff; color: #2563eb; box-shadow: 0 2px 4px rgba(0,0,0,0.06);">
                                    <i class="ri-upload-cloud-line"></i> Upload Video File
                                </button>
                                <button type="button" id="tabUrlBtn" onclick="switchVideoTab('url')" style="flex: 1; padding: 8px 14px; border: none; border-radius: 6px; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.2s; background: transparent; color: #64748b;">
                                    <i class="ri-youtube-line"></i> YouTube / Video URL
                                </button>
                            </div>
                            <input type="hidden" name="source_type" id="videoSourceType" value="upload">
                        </div>

                        <!-- Panel 1: File Upload -->
                        <div id="videoUploadPanel" style="margin-bottom: 20px;">
                            <div id="videoDropArea" style="border: 2px dashed #cbd5e1; border-radius: 10px; padding: 22px 16px; text-align: center; background: #f8fafc; cursor: pointer; transition: all 0.2s;" onclick="document.getElementById('promoVideoInput').click();">
                                <i class="ri-video-upload-line" style="font-size: 36px; color: #ef4444; display: block; margin-bottom: 8px;"></i>
                                <span style="font-size: 13px; font-weight: 700; color: #ef4444; display: block; margin-bottom: 4px;">Click to Browse Video File</span>
                                <span style="font-size: 11px; color: #64748b; display: block;">Supports MP4, WEBM, MOV (Max 60MB)</span>
                                <input type="file" name="promo_video" id="promoVideoInput" accept="video/mp4,video/webm,video/quicktime" style="display: none;" onchange="handleVideoFileSelect(this);">
                            </div>
                            
                            <!-- File Info pill (hidden until file chosen) -->
                            <div id="newVideoInfo" style="display: none; margin-top: 10px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; padding: 10px 14px; font-size: 12px; color: #991b1b; display: flex; justify-content: space-between; align-items: center;">
                                <span id="videoFileNameText"><i class="ri-movie-line"></i> Video selected</span>
                                <button type="button" onclick="clearSelectedVideo();" style="background: none; border: none; color: #ef4444; font-weight: 700; cursor: pointer; font-size: 12px;">Remove</button>
                            </div>
                        </div>

                        <!-- Panel 2: YouTube / External URL -->
                        <div id="videoUrlPanel" style="margin-bottom: 20px; display: none;">
                            <label for="promoVideoUrl" style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 6px;">
                                YouTube Link or Video URL:
                            </label>
                            <input type="url" id="promoVideoUrl" name="video_url" value="<?php echo ($cur_type === 'youtube' || $cur_type === 'url') ? htmlspecialchars($cur_video) : ''; ?>" placeholder="https://www.youtube.com/watch?v=... or https://youtu.be/..." style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
                            <small style="color: #64748b; font-size: 12px; display: block; margin-top: 4px;">
                                Paste any standard YouTube link, short link, or external video URL.
                            </small>
                        </div>

                        <!-- Submit Buttons -->
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <button type="submit" class="btn" style="background-color: #ef4444; color: #ffffff; padding: 12px 20px; border-radius: 6px; font-size: 14px; font-weight: 700; border: none; cursor: pointer; display: flex; justify-content: center; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(239,68,68,0.25); transition: all 0.2s;">
                                <i class="ri-check-line" style="font-size: 18px;"></i> Save & Publish Promotional Video
                            </button>

                            <?php if (!empty($promo_video)): ?>
                            <a href="<?php echo site_url('admin/reset_promo_video'); ?>" onclick="return confirm('Revert to the original default promotional video?');" class="btn" style="background-color: #ffffff; color: #64748b; border: 1px solid #cbd5e1; padding: 9px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; text-decoration: none; text-align: center; display: flex; justify-content: center; align-items: center; gap: 6px;">
                                <i class="ri-restart-line"></i> Reset to Default Video (assets/video/index.mp4)
                            </a>
                            <?php endif; ?>
                        </div>

                    </form>
                </div>

                <!-- Right: Live Video Player Preview -->
                <div style="background: #0f172a; border-radius: 12px; padding: 14px; color: #fff;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; font-size: 12px; color: #94a3b8;">
                        <span style="font-weight: 600; color: #e2e8f0;"><i class="ri-play-circle-line"></i> Active Video Player Preview</span>
                        <span>21 : 9 Panoramic</span>
                    </div>

                    <div style="width: 100%; aspect-ratio: 21 / 9; border-radius: 8px; overflow: hidden; background: #000; position: relative;">
                        <?php if (!empty($cur_yt_embed)): ?>
                            <iframe src="<?php echo htmlspecialchars($cur_yt_embed); ?>" style="width: 100%; height: 100%; border: none;" allowfullscreen></iframe>
                        <?php else: ?>
                            <?php 
                                $preview_src = (strpos($cur_video, 'http') === 0) ? $cur_video : base_url($cur_video);
                                if (file_exists(FCPATH . $cur_video)) {
                                    $preview_src .= '?v=' . filemtime(FCPATH . $cur_video);
                                }
                            ?>
                            <video controls muted playsinline style="width: 100%; height: 100%; object-fit: cover;">
                                <source src="<?php echo htmlspecialchars($preview_src); ?>" type="video/mp4">
                                Your browser does not support video.
                            </video>
                        <?php endif; ?>
                    </div>

                    <div style="font-size: 11px; color: #94a3b8; margin-top: 10px; line-height: 1.4;">
                        <strong>Current File / Link:</strong> <span style="font-family: monospace; color: #38bdf8;"><?php echo htmlspecialchars($cur_video); ?></span>
                    </div>
                </div>

            </div>

        </div>

        <!-- ============================================================== -->
        <!-- SECTION 2: HERO COLLABORATIVE PHOTO -->
        <!-- ============================================================== -->
        <div style="border-top: 2px dashed #e2e8f0; padding-top: 30px;">
            <div style="margin-bottom: 18px;">
                <h4 style="margin: 0 0 4px 0; color: #0f172a; font-size: 17px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                    <i class="ri-image-line" style="color: #2563eb; font-size: 22px;"></i> Homepage Hero Section Photo
                </h4>
                <p style="margin: 0; color: #64748b; font-size: 13px;">Manage the primary portrait visual next to the headline in the top hero header.</p>
            </div>

            <!-- Specification Banner -->
            <div style="background: linear-gradient(135deg, #eff6ff 0%, #f0fdf4 100%); border: 1px solid #bfdbfe; border-left: 5px solid #2563eb; border-radius: 8px; padding: 14px 18px; margin-bottom: 22px;">
                <div style="display: flex; align-items: flex-start; gap: 12px;">
                    <i class="ri-information-line" style="color: #2563eb; font-size: 20px; margin-top: 1px;"></i>
                    <div>
                        <div style="display: flex; flex-wrap: wrap; gap: 12px; font-size: 12px; font-weight: 600; color: #1e40af;">
                            <span style="background: rgba(37,99,235,0.08); padding: 3px 10px; border-radius: 12px; border: 1px solid rgba(37,99,235,0.2);">
                                <i class="ri-aspect-ratio-line"></i> <strong>Aspect Ratio:</strong> 4 : 5 (Portrait)
                            </span>
                            <span style="background: rgba(37,99,235,0.08); padding: 3px 10px; border-radius: 12px; border: 1px solid rgba(37,99,235,0.2);">
                                <i class="ri-ruler-line"></i> <strong>Recommended:</strong> 800 × 1000 px or 1000 × 1250 px
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
                $is_custom_hero = (!empty($hero_image));
                if ($is_custom_hero) {
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

                            <?php if ($is_custom_hero): ?>
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
                            <i class="ri-smartphone-line" style="color: #10b981;"></i> Current Live Hero Display
                        </h4>
                        <?php if ($is_custom_hero): ?>
                            <span style="background: #dcfce7; color: #15803d; border: 1px solid #86efac; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="ri-checkbox-circle-fill"></i> Custom Photo Active
                            </span>
                        <?php else: ?>
                            <span style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="ri-image-line"></i> Default Photo Active
                            </span>
                        <?php endif; ?>
                    </div>

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
                            <i class="ri-check-double-line" style="color: #10b981;"></i> Dimensions: Fixed 4:5 Aspect Ratio
                        </div>

                    </div>

                </div>

            </div>
        </div>

    </div>
</div>

<script>
// Tab switcher for video
function switchVideoTab(type) {
    var uploadPanel = document.getElementById('videoUploadPanel');
    var urlPanel = document.getElementById('videoUrlPanel');
    var uploadBtn = document.getElementById('tabUploadBtn');
    var urlBtn = document.getElementById('tabUrlBtn');
    var sourceType = document.getElementById('videoSourceType');

    sourceType.value = type;

    if (type === 'upload') {
        uploadPanel.style.display = 'block';
        urlPanel.style.display = 'none';

        uploadBtn.style.background = '#fff';
        uploadBtn.style.color = '#2563eb';
        uploadBtn.style.boxShadow = '0 2px 4px rgba(0,0,0,0.06)';

        urlBtn.style.background = 'transparent';
        urlBtn.style.color = '#64748b';
        urlBtn.style.boxShadow = 'none';
    } else {
        uploadPanel.style.display = 'none';
        urlPanel.style.display = 'block';

        urlBtn.style.background = '#fff';
        urlBtn.style.color = '#ef4444';
        urlBtn.style.boxShadow = '0 2px 4px rgba(0,0,0,0.06)';

        uploadBtn.style.background = 'transparent';
        uploadBtn.style.color = '#64748b';
        uploadBtn.style.boxShadow = 'none';
    }
}

// Video file select
function handleVideoFileSelect(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        
        // 60MB check
        if (file.size > 60 * 1024 * 1024) {
            alert('Video file size exceeds 60MB. Please compress or choose a smaller file, or paste a YouTube URL.');
            input.value = '';
            return;
        }

        var infoBox = document.getElementById('newVideoInfo');
        var textEl = document.getElementById('videoFileNameText');
        textEl.innerHTML = '<i class="ri-movie-line"></i> <strong>' + file.name + '</strong> (' + (file.size / (1024*1024)).toFixed(2) + ' MB)';
        infoBox.style.display = 'flex';
    }
}

function clearSelectedVideo() {
    var input = document.getElementById('promoVideoInput');
    input.value = '';
    document.getElementById('newVideoInfo').style.display = 'none';
}

// Hero Image Handlers
function handleFileSelect(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        
        if (!file.type.match('image.*')) {
            alert('Please select an image file (PNG, JPG, JPEG, WEBP).');
            input.value = '';
            return;
        }

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

// Drag & drop support for hero
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
