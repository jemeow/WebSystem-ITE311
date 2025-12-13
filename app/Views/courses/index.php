<?php
$this->extend('template');
$this->section('content');
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1"><i class="bi bi-book"></i> Courses</h2>
                    <p class="text-muted mb-0">Browse and search courses</p>
                </div>
                <a href="<?= site_url('dashboard') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-6">
                            <form id="searchForm" class="d-flex" autocomplete="off">
                                <div class="input-group">
                                    <input type="text" id="searchInput" class="form-control" placeholder="Search courses..." name="search_term">
                                    <button class="btn btn-outline-primary" id="searchButton" type="button">
                                        <i class="bi bi-search"></i> Search
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <small class="text-muted">Tip: You can type to filter instantly, or press Search for server search.</small>
                        </div>
                    </div>
                </div>
            </div>

            <div id="coursesContainer" class="row g-4">
                <?php if (!empty($courses)): ?>
                    <?php foreach ($courses as $course): ?>
                        <div class="col-md-4 mb-4 course-wrapper">
                            <div class="card course-card h-100" data-search="<?= strtolower(($course['course_code'] ?? '') . ' ' . ($course['course_name'] ?? '') . ' ' . ($course['description'] ?? '')) ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= esc($course['course_name'] ?? '') ?></h5>
                                    <p class="card-text"><?= esc($course['description'] ?? '') ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-primary"><?= esc($course['course_code'] ?? '') ?></span>
                                        <small class="text-muted"><?= esc($course['credits'] ?? '') ?> credits</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info mb-0">No courses found.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#searchInput').on('keyup', function() {
        const value = $(this).val().toLowerCase();
        $('.course-card').each(function() {
            const haystack = ($(this).data('search') || '').toString();
            $(this).closest('.course-wrapper').toggle(haystack.indexOf(value) > -1);
        });
    });

    $('#searchInput').on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
        }
    });

    $('#searchButton').on('click', function(e) {
        e.preventDefault();
        const searchTerm = $('#searchInput').val();

        $.get('<?= site_url('courses/search') ?>', { search_term: searchTerm }, function(data) {
            $('#coursesContainer').empty();

            if (data.length > 0) {
                $.each(data, function(index, course) {
                    const courseHTML = `
                        <div class="col-md-4 mb-4 course-wrapper">
                            <div class="card course-card h-100" data-search="${(course.course_code || '') + ' ' + (course.course_name || '') + ' ' + (course.description || '')}">
                                <div class="card-body">
                                    <h5 class="card-title">${course.course_name || ''}</h5>
                                    <p class="card-text">${course.description || ''}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-primary">${course.course_code || ''}</span>
                                        <small class="text-muted">${course.credits || ''} credits</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    $('#coursesContainer').append(courseHTML);
                });
            } else {
                $('#coursesContainer').html('<div class="col-12"><div class="alert alert-info mb-0">No courses found matching your search.</div></div>');
            }
        }, 'json');
    });
});
</script>

<?php $this->endSection(); ?>
