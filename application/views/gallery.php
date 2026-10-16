<div class="page-header" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; padding: 75px 0; text-align: center; border-bottom: 1px solid #334155;">
    <div class="container" style="max-width: 900px; margin: 0 auto; padding: 0 20px;">
        <span style="display: inline-block; padding: 6px 16px; background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); font-size: 13px; font-weight: 700; border-radius: 20px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 14px;">Media & Highlights</span>
        <h1 style="font-family: 'Playfair Display', serif; font-size: 3rem; margin-bottom: 15px; color: #fff;">Our Gallery</h1>
        <p style="color: #cbd5e1; font-size: 1.15rem; line-height: 1.6; max-width: 700px; margin: 0 auto;">Explore life, workshops, student success moments, and special events at AlphaMindz.</p>
    </div>
</div>

<div class="container" style="padding: 50px 20px 100px; max-width: 1240px; margin: 0 auto;">

    <!-- Filter Buttons -->
    <div style="display: flex; justify-content: center; align-items: center; gap: 10px; margin-bottom: 40px; flex-wrap: wrap;">
        <a href="<?php echo site_url('gallery'); ?>" style="padding: 8px 20px; border-radius: 30px; font-size: 14px; font-weight: 600; text-decoration: none; transition: all 0.2s; <?php echo ($active_type === 'all' && $active_category === 'all') ? 'background: #2563eb; color: #fff; box-shadow: 0 4px 10px rgba(37,99,235,0.3);' : 'background: #f1f5f9; color: #475569;'; ?>">
            All Media
        </a>
        <a href="<?php echo site_url('gallery?type=photo'); ?>" style="padding: 8px 20px; border-radius: 30px; font-size: 14px; font-weight: 600; text-decoration: none; transition: all 0.2s; <?php echo ($active_type === 'photo') ? 'background: #2563eb; color: #fff; box-shadow: 0 4px 10px rgba(37,99,235,0.3);' : 'background: #f1f5f9; color: #475569;'; ?>">
            <i class="ri-image-line"></i> Photos
        </a>
        <a href="<?php echo site_url('gallery?type=video'); ?>" style="padding: 8px 20px; border-radius: 30px; font-size: 14px; font-weight: 600; text-decoration: none; transition: all 0.2s; <?php echo ($active_type === 'video') ? 'background: #2563eb; color: #fff; box-shadow: 0 4px 10px rgba(37,99,235,0.3);' : 'background: #f1f5f9; color: #475569;'; ?>">
            <i class="ri-video-line"></i> Videos
        </a>

        <?php if(!empty($categories)): ?>
            <span style="color: #cbd5e1; margin: 0 6px;">|</span>
            <?php foreach($categories as $cat): ?>
                <a href="<?php echo site_url('gallery?category='.rawurlencode($cat->category)); ?>" style="padding: 8px 18px; border-radius: 30px; font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.2s; <?php echo ($active_category === $cat->category) ? 'background: #0f172a; color: #fff;' : 'background: #fff; color: #64748b; border: 1px solid #e2e8f0;'; ?>">
                    <?php echo htmlspecialchars($cat->category); ?> (<?php echo $cat->count; ?>)
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Gallery Grid -->
    <?php if(!empty($items)): ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 26px;">
            <?php foreach($items as $item): ?>
                <div class="gallery-card" style="background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.04); display: flex; flex-direction: column; transition: transform 0.3s, box-shadow 0.3s;">
                    
                    <div style="position: relative; aspect-ratio: 16/10; overflow: hidden; background: #000;">
                        <?php if($item->type === 'photo'): ?>
                            <?php 
                                $img_src = !empty($item->file_path) ? base_url($item->file_path) : 'https://via.placeholder.com/600x400?text=AlphaMindz+Photo';
                            ?>
                            <img src="<?php echo $img_src; ?>" alt="<?php echo htmlspecialchars($item->title); ?>" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer; transition: transform 0.4s ease;" onclick="openMediaModal('<?php echo $img_src; ?>', 'photo', '<?php echo htmlspecialchars(addslashes($item->title)); ?>')">
                            <span style="position: absolute; top: 12px; left: 12px; background: rgba(15,23,42,0.8); backdrop-filter: blur(4px); color: #fff; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px;">
                                <i class="ri-image-line"></i> Photo
                            </span>
                        <?php else: ?>
                            <?php if(!empty($item->file_path)): ?>
                                <video controls style="width: 100%; height: 100%; object-fit: cover; background: #000;">
                                    <source src="<?php echo base_url($item->file_path); ?>">
                                </video>
                            <?php elseif(!empty($item->video_url)): ?>
                                <?php
                                    $embed_url = $item->video_url;
                                    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $item->video_url, $matches)) {
                                        $embed_url = 'https://www.youtube.com/embed/' . $matches[1];
                                    }
                                ?>
                                <iframe src="<?php echo $embed_url; ?>" style="width: 100%; height: 100%; border: none;" allowfullscreen></iframe>
                            <?php endif; ?>
                            <span style="position: absolute; top: 12px; left: 12px; background: rgba(220,38,38,0.85); backdrop-filter: blur(4px); color: #fff; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px;">
                                <i class="ri-video-line"></i> Video
                            </span>
                        <?php endif; ?>
                    </div>

                    <div style="padding: 20px; flex: 1; display: flex; flex-direction: column;">
                        <span style="font-size: 11px; font-weight: 700; color: #2563eb; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
                            <?php echo htmlspecialchars($item->category ?: 'General'); ?>
                        </span>
                        <h3 style="font-size: 1.15rem; font-weight: 700; color: #1e293b; margin: 0 0 8px 0; line-height: 1.4;">
                            <?php echo htmlspecialchars($item->title); ?>
                        </h3>
                        <?php if(!empty($item->description)): ?>
                            <p style="color: #64748b; font-size: 0.92rem; line-height: 1.5; margin: 0; flex: 1;">
                                <?php echo htmlspecialchars($item->description); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div style="text-align: center; padding: 80px 20px; background: #fff; border-radius: 16px; border: 1px dashed #cbd5e1; max-width: 600px; margin: 0 auto;">
            <i class="ri-gallery-line" style="font-size: 3.5rem; color: #94a3b8; display: block; margin-bottom: 14px;"></i>
            <h3 style="margin-bottom: 8px; color: #334155;">No Gallery Items Yet</h3>
            <p style="color: #64748b;">Photos and videos will be posted soon. Please check back shortly!</p>
        </div>
    <?php endif; ?>

</div>

<!-- Photo Lightbox Modal -->
<div id="mediaModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 99999; background: rgba(0,0,0,0.9); align-items: center; justify-content: center; padding: 20px;" onclick="closeMediaModal()">
    <button style="position: absolute; top: 20px; right: 25px; background: none; border: none; color: #fff; font-size: 32px; cursor: pointer;" onclick="closeMediaModal()">&times;</button>
    <div style="max-width: 90%; max-height: 90%; text-align: center;" onclick="event.stopPropagation()">
        <img id="modalImg" src="" alt="Enlarged" style="max-width: 100%; max-height: 80vh; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
        <h4 id="modalCaption" style="color: #fff; font-size: 1.1rem; margin-top: 14px; font-weight: 500;"></h4>
    </div>
</div>

<script>
function openMediaModal(src, type, caption) {
    if (type === 'photo') {
        var modal = document.getElementById('mediaModal');
        var img = document.getElementById('modalImg');
        var cap = document.getElementById('modalCaption');
        img.src = src;
        cap.innerText = caption;
        modal.style.display = 'flex';
    }
}

function closeMediaModal() {
    var modal = document.getElementById('mediaModal');
    modal.style.display = 'none';
}
</script>
