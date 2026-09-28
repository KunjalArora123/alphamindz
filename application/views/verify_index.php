<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Credential Verification Portal | AlphaMindz</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/images/logo.png'); ?>">
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url('assets/images/logo.png'); ?>">
    <link rel="apple-touch-icon" href="<?php echo base_url('assets/images/logo.png'); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        :root {
            --sp-blue: rgb(48, 98, 135);
            --sp-blue-dark: rgb(34, 72, 100);
            --sp-green: rgb(131, 187, 77);
            --sp-green-dark: #5c8734;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .verify-header {
            background: linear-gradient(135deg, var(--sp-blue) 0%, var(--sp-blue-dark) 100%);
            color: white;
            padding: 40px 0 60px 0;
            text-align: center;
        }

        .verify-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
            margin-top: -30px;
            padding: 30px;
        }

        .brand-logo {
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #ffffff;
            text-decoration: none;
        }

        .brand-logo span {
            color: var(--sp-green);
        }

        .status-badge-success {
            background-color: rgba(131, 187, 77, 0.15);
            color: var(--sp-green-dark);
            border: 1px solid var(--sp-green);
            padding: 8px 16px;
            border-radius: 30px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .status-badge-error {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #f87171;
            padding: 8px 16px;
            border-radius: 30px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .meta-label {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .meta-value {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
        }

        .search-btn {
            background-color: var(--sp-green);
            color: white;
            font-weight: 700;
            border: none;
        }

        .search-btn:hover {
            background-color: var(--sp-green-dark);
            color: white;
        }
    </style>
</head>
<body>

    <header class="verify-header">
        <div class="container">
            <a href="<?php echo site_url(); ?>" class="brand-logo mb-3 d-inline-flex align-items-center gap-2" style="text-decoration: none;">
                <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="AlphaMindz Logo" style="height: 48px; width: auto; background: #ffffff; padding: 4px 10px; border-radius: 8px;">
                <span>AlphaMindz</span>
            </a>
            <h1 class="fw-extrabold fs-3 fs-md-2 mb-2">Public Credential Verification Portal</h1>
            <p class="text-white-50 max-w-600 mx-auto mb-0">Verify the authenticity of certificates and course credentials issued by AlphaMindz Academy.</p>
        </div>
    </header>

    <main class="container mb-5 flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                
                <!-- Search Box Card -->
                <div class="verify-card mb-4">
                    <form action="<?php echo site_url('verify'); ?>" method="GET" class="row g-2 align-items-center">
                        <div class="col-12 col-sm">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0"><i class="ri-shield-keyhole-line text-muted"></i></span>
                                <input type="text" name="id" class="form-control bg-light border-start-0 fs-6" placeholder="Enter Credential ID (e.g. AMZ-2026-CERT-8842)" value="<?php echo htmlspecialchars($search_id); ?>" required>
                            </div>
                        </div>
                        <div class="col-12 col-sm-auto">
                            <button type="submit" class="btn search-btn btn-lg w-100 px-4 fs-6">
                                <i class="ri-search-line me-1"></i> Verify Credential
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Verification Result Display -->
                <?php if($searched): ?>
                    <?php if($certificate && $user && $course): ?>
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                            <div class="p-4 bg-white border-bottom border-light">
                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                                    <div class="status-badge-success">
                                        <i class="ri-checkbox-circle-fill fs-5"></i> VALIDATED & AUTHENTIC CREDENTIAL
                                    </div>
                                    <span class="text-muted small fw-semibold">Tamper-Proof Verification</span>
                                </div>
                            </div>
                            
                            <div class="p-4 p-md-5 bg-white">
                                <div class="row g-4">
                                    <div class="col-12 col-md-6">
                                        <div class="meta-label mb-1">Learner Name</div>
                                        <div class="meta-value fs-4 text-sp-blue"><?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?></div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="meta-label mb-1">Credential ID</div>
                                        <div class="meta-value font-monospace text-dark"><?php echo htmlspecialchars($certificate->credential_id); ?></div>
                                        <div class="meta-label mb-1 mt-2">Corporate Identity No. (CIN)</div>
                                        <div class="meta-value font-monospace text-muted" style="font-size: 0.85rem;">U80903GA2008PTC005856</div>
                                    </div>

                                    <div class="col-12">
                                        <hr class="my-2 border-light">
                                    </div>

                                    <div class="col-12 col-md-8">
                                        <div class="meta-label mb-1">Course Title</div>
                                        <div class="meta-value fs-5"><?php echo htmlspecialchars($course->title); ?></div>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <div class="meta-label mb-1">Completed Tier Level</div>
                                        <div class="meta-value">
                                            <span class="badge bg-success-subtle text-success border border-success px-3 py-2 fs-6 rounded-pill">
                                                <i class="ri-award-fill me-1"></i> <?php echo htmlspecialchars(ucfirst($certificate->tier_key)); ?> Tier
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="meta-label mb-1">Issue Date</div>
                                        <div class="meta-value"><?php echo date('F d, Y', strtotime($certificate->issue_date)); ?></div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="meta-label mb-1">Issuing Authority</div>
                                        <div class="meta-value text-muted">AlphaMindz Educational Portal</div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-3 bg-light text-center border-top">
                                <span class="small text-muted"><i class="ri-lock-2-line me-1"></i> Cryptographically linked record from AlphaMindz Master Student Registry.</span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                            <div class="p-4 p-md-5 bg-white text-center">
                                <div class="status-badge-error mb-3">
                                    <i class="ri-close-circle-fill fs-5"></i> INVALID OR UNVERIFIED CREDENTIAL
                                </div>
                                <h4 class="fw-bold text-dark mb-2">No Matching Certificate Found</h4>
                                <p class="text-muted max-w-500 mx-auto mb-4">
                                    The Credential ID <code class="px-2 py-1 bg-light rounded text-danger"><?php echo htmlspecialchars($search_id); ?></code> was not found in our active registry or has been revoked.
                                </p>
                                <a href="<?php echo site_url('verify'); ?>" class="btn btn-outline-secondary btn-sm">Try Another Search</a>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

            </div>
        </div>
    </main>

    <footer class="mt-auto py-4 bg-white border-top text-center text-muted small">
        <div class="container">
            &copy; <?php echo date('Y'); ?> AlphaMindz. All rights reserved. | Public Credential Verification Engine
        </div>
    </footer>

</body>
</html>
