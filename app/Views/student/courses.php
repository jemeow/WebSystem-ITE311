<?php
$this->extend('template');
$this->section('content');
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1"><i class="bi bi-book"></i> My Courses</h2>
                    <p class="text-muted mb-0">View your enrolled and pending courses</p>
                </div>
                <a href="<?= site_url('dashboard') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>

            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill"></i> <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill"></i> <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-check-circle"></i> Enrolled Courses (<?= isset($enrolledCourses) ? count($enrolledCourses) : 0 ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($enrolledCourses)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="text-muted mt-3 mb-0">You have no enrolled courses yet.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Course</th>
                                        <th>Instructor</th>
                                        <th>Schedule</th>
                                        <th>Enrolled</th>
                                        <th style="width: 150px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($enrolledCourses as $course): ?>
                                        <tr>
                                            <td>
                                                <div>
                                                    <span class="badge bg-primary"><?= esc($course['course_code']) ?></span>
                                                    <div class="fw-bold mt-1"><?= esc($course['course_name']) ?></div>
                                                    <div class="small text-muted"><?= esc($course['description'] ?? '') ?></div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted">
                                                    <i class="bi bi-person"></i> <?= esc($course['teacher_name'] ?? 'No teacher assigned') ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if (!empty($course['schedule_days']) && !empty($course['schedule_start_time']) && !empty($course['schedule_end_time'])): ?>
                                                    <div class="small">
                                                        <i class="bi bi-calendar-week"></i> <?= esc($course['schedule_days']) ?>
                                                    </div>
                                                    <div class="small">
                                                        <i class="bi bi-clock"></i> <?= date('g:i A', strtotime($course['schedule_start_time'])) ?> - <?= date('g:i A', strtotime($course['schedule_end_time'])) ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted small">No schedule</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="text-muted small">
                                                    <?= !empty($course['enrollment_date']) ? date('M d, Y', strtotime($course['enrollment_date'])) : '' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?= site_url('materials/course/' . $course['course_id']) ?>" class="btn btn-sm btn-success">
                                                    <i class="bi bi-files"></i> Materials
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Pending Requests (<?= isset($pendingEnrollments) ? count($pendingEnrollments) : 0 ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($pendingEnrollments)): ?>
                        <div class="text-center py-4">
                            <i class="bi bi-check2-circle" style="font-size: 2.5rem; opacity: 0.3;"></i>
                            <p class="text-muted mt-3 mb-0">No pending enrollment requests.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Course</th>
                                        <th>Instructor</th>
                                        <th>Requested</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pendingEnrollments as $course): ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary"><?= esc($course['course_code']) ?></span>
                                                <div class="fw-bold mt-1"><?= esc($course['course_name']) ?></div>
                                                <div class="small text-muted"><?= esc($course['description'] ?? '') ?></div>
                                            </td>
                                            <td>
                                                <span class="text-muted">
                                                    <i class="bi bi-person"></i> <?= esc($course['teacher_name'] ?? 'No teacher assigned') ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-muted small">
                                                    <?= !empty($course['enrollment_date']) ? date('M d, Y', strtotime($course['enrollment_date'])) : '' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">Pending</span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>
