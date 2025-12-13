<?php
$this->extend('template');
$this->section('content');
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1"><i class="bi bi-search"></i> Course Search Results</h2>
                    <p class="text-muted mb-0">Showing results for: <strong><?= esc($searchTerm ?? '') ?></strong></p>
                </div>
                <a href="<?= site_url('dashboard') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>

            <form method="get" action="<?= site_url('course/search') ?>" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search_term" class="form-control" value="<?= esc($searchTerm ?? '') ?>" placeholder="Search courses by code, name, or description">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
            </form>

            <div class="card shadow-sm">
                <div class="card-header" style="background: #f3f4f6; color: #374151; border-bottom: 1px solid #e5e7eb;">
                    <h5 class="mb-0">Results (<?= isset($courses) ? count($courses) : 0 ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($courses)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="text-muted mt-3 mb-0">No courses found.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Code</th>
                                        <th>Course</th>
                                        <th>Credits</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($courses as $course): ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary"><?= esc($course['course_code'] ?? '') ?></span>
                                            </td>
                                            <td>
                                                <div class="fw-bold"><?= esc($course['course_name'] ?? '') ?></div>
                                                <div class="small text-muted"><?= esc($course['description'] ?? '') ?></div>
                                            </td>
                                            <td>
                                                <span class="text-muted"><?= esc($course['credits'] ?? '') ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= ($course['status'] ?? '') === 'active' ? 'success' : 'secondary' ?>">
                                                    <?= esc($course['status'] ?? 'unknown') ?>
                                                </span>
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
