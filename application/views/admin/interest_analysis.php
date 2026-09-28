<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Interest Inventory Profile Analysis</h1>
                </div>
                <div class="col-sm-6">
                    <a href="<?php echo site_url('admin/test_history/'.$attempt->user_id); ?>" class="btn btn-secondary float-right">Back to History</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-graduate mr-2"></i>
                        Student: <strong><?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?></strong> 
                        (Attempt ID: <?php echo $attempt->id; ?>)
                    </h3>
                </div>
                <div class="card-body">
                    
                    <h4 class="text-indigo-600 font-weight-bold mb-3 border-bottom pb-2">Section 1</h4>
                    <table class="table table-bordered table-striped mb-5">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 50%;">Question</th>
                                <th>Student's Answer</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($interest_answers as $ans): if($ans->section !== 'Section 1') continue; ?>
                            <tr>
                                <td class="font-weight-bold"><?php echo htmlspecialchars($ans->question_text); ?></td>
                                <td><?php echo nl2br(htmlspecialchars((string)$ans->selected_option)); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <h4 class="text-indigo-600 font-weight-bold mb-3 border-bottom pb-2">Section 2: Preliminary Career Interest & Background Profile</h4>
                    <table class="table table-bordered table-striped">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 50%;">Question</th>
                                <th>Student's Answer</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($interest_answers as $ans): if($ans->section !== 'Section 2') continue; ?>
                            <tr>
                                <td class="font-weight-bold"><?php echo nl2br(htmlspecialchars($ans->question_text)); ?></td>
                                <td><?php echo nl2br(htmlspecialchars((string)$ans->selected_option)); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </section>
</div>
