<div class="content">
    <div class="container-fluid">
        <h4 class="page-title">Site Branding</h4>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Update Logo and Favicon</div>
                    </div>
                    <div class="card-body">
                        <?php if($this->session->flashdata('success')): ?>
                            <div class="alert alert-success">
                                <?php echo $this->session->flashdata('success'); ?>
                            </div>
                        <?php endif; ?>
                        <?php if($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger">
                                <?php echo $this->session->flashdata('error'); ?>
                            </div>
                        <?php endif; ?>

                        <?php echo form_open_multipart('superadmin/branding'); ?>
                        
                        <div class="form-group mb-4">
                            <label for="logo"><strong>Main Site Logo</strong></label><br>
                            <img src="<?php echo base_url('assets/images/logo.png?v='.filemtime(FCPATH.'assets/images/logo.png')); ?>" alt="Current Logo" class="mb-3" style="max-height: 100px; background: #f8f9fa; padding: 10px; border: 1px solid #dee2e6; border-radius: 4px;">
                            <p class="text-muted small">This logo is displayed everywhere on the site (Admin Panel, Student Panel, Certificates, Homepage, etc). Recommended format: PNG with transparent background.</p>
                            <input type="file" name="logo" id="logo" class="form-control-file" accept="image/*">
                        </div>

                        <hr>

                        <div class="form-group mb-4">
                            <label for="favicon"><strong>Site Favicon</strong></label><br>
                            <img src="<?php echo base_url('assets/images/favicon.png?v='.filemtime(FCPATH.'assets/images/favicon.png')); ?>" alt="Current Favicon" class="mb-3" style="max-height: 64px; background: #f8f9fa; padding: 10px; border: 1px solid #dee2e6; border-radius: 4px;">
                            <p class="text-muted small">This is the small icon that appears in the browser tab. Recommended format: PNG or ICO, square aspect ratio.</p>
                            <input type="file" name="favicon" id="favicon" class="form-control-file" accept="image/*">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>

                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
