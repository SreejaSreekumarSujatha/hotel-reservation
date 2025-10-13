<!doctype html>
<html>
<head>
    <title>User Management</title>
</head>
<body>
<h2>Manage Users</h2>
<a href="create.php">+ Add User</a> | <a href="../../index.php">Dashboard</a>

<table border="1" cellpadding="5">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Role</th>
    <th>Created</th>
    <th>Actions</th>
</tr>

<?php foreach($users as $u): ?>
<tr>
    <td><?= e($u['id']) ?></td>
    <td><?= e($u['name']) ?></td>
    <td><?= e($u['email']) ?></td>
    <td><?= e($u['role']) ?></td>
    <td><?= e($u['created_at']) ?></td>
    <td>
        <a href="edit.php?id=<?= e($u['id']) ?>">Edit</a> |
        <a href="delete.php?id=<?= e($u['id']) ?>" onclick="return confirm('Delete this user?')">Delete</a>
    </td>
</tr>
<?php endforeach; ?>

</table>
</body>
</html>
