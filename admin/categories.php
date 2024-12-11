<?php
// admin/categories.php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

// Kategorileri getir
$query = "SELECT * FROM categories ORDER BY created_at DESC";
$categories = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Yönetimi - Admin Panel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Admin Panel</a>
            <div class="navbar-nav">
                <a class="nav-link active" href="categories.php">Kategoriler</a>
                <a class="nav-link" href="logout.php">Çıkış Yap</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Kategori Yönetimi</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                Yeni Kategori Ekle
            </button>
        </div>

        <div id="alert-container"></div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Resim</th>
                        <th>Kategori Adı</th>
                        <th>Oyun Sayısı</th>
                        <th>Oluşturulma Tarihi</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($category = $categories->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $category['id']; ?></td>
                        <td>
                            <img src="../uploads/categories/<?php echo $category['image']; ?>" 
                                alt="<?php echo $category['name']; ?>" 
                                style="width: 50px; height: 50px; object-fit: cover;">
                        </td>
                        <td><?php echo htmlspecialchars($category['name']); ?></td>
                        <td><?php echo $category['game_count']; ?></td>
                        <td><?php echo date('d.m.Y H:i', strtotime($category['created_at'])); ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-category" 
                                data-id="<?php echo $category['id']; ?>"
                                data-name="<?php echo htmlspecialchars($category['name']); ?>">
                                Düzenle
                            </button>
                            <button class="btn btn-sm btn-danger delete-category" 
                                data-id="<?php echo $category['id']; ?>">
                                Sil
                            </button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Yeni Kategori Ekleme Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Yeni Kategori Ekle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addCategoryForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Kategori Adı</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori Resmi</label>
                            <input type="file" class="form-control" name="image" required accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-primary">Ekle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Kategori Düzenleme Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kategori Düzenle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editCategoryForm" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="editCategoryId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Kategori Adı</label>
                            <input type="text" class="form-control" name="name" id="editCategoryName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori Resmi</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                            <small class="text-muted">Sadece yeni resim eklemek istiyorsanız seçin</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-primary">Güncelle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        function showAlert(message, type = 'success') {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            $('#alert-container').html(alertHtml);
        }

        // Yeni kategori ekleme
        $('#addCategoryForm').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            $.ajax({
                url: 'api/add_category.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        showAlert('Kategori başarıyla eklendi');
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showAlert(response.message, 'danger');
                    }
                }
            });
        });

        // Kategori düzenleme modalını aç
        $('.edit-category').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            
            $('#editCategoryId').val(id);
            $('#editCategoryName').val(name);
            $('#editCategoryModal').modal('show');
        });

        // Kategori düzenleme
        $('#editCategoryForm').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            $.ajax({
                url: 'api/edit_category.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        showAlert('Kategori başarıyla güncellendi');
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showAlert(response.message, 'danger');
                    }
                }
            });
        });

        // Kategori silme
        $('.delete-category').on('click', function() {
            if (confirm('Bu kategoriyi silmek istediğinize emin misiniz?')) {
                const id = $(this).data('id');
                
                $.ajax({
                    url: 'api/delete_category.php',
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        if (response.success) {
                            showAlert('Kategori başarıyla silindi');
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            showAlert(response.message, 'danger');
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>