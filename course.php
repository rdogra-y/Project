<?php if ($course): ?>
    <h2><?php echo htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
    <p><strong>Description:</strong> <?php echo htmlspecialchars($course['description'], ENT_QUOTES, 'UTF-8'); ?></p>
    <p><strong>Instructor ID:</strong> <?php echo htmlspecialchars($course['instructor_id'], ENT_QUOTES, 'UTF-8'); ?></p>
    <?php if (!empty($course['image'])): ?>
        <img src="<?php echo htmlspecialchars($course['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Course Image" style="max-width: 500px; height: auto;">
    <?php endif; ?>
<?php endif; ?>
