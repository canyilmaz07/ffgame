<?php
// admin/products.php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

// Ürünleri getir
$query = "SELECT p.*, c.name as category_name 
          FROM products p 
          LEFT JOIN categories c ON p.category_id = c.id 
          ORDER BY p.created_at DESC";
$products = $conn->query($query);

// Kategorileri getir (select için)
$categories_query = "SELECT * FROM categories ORDER BY name ASC";
$categories = $conn->query($categories_query);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Yönetimi - Admin Panel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Admin Panel</a>
            <div class="navbar-nav">
                <a class="nav-link" href="categories.php">Kategoriler</a>
                <a class="nav-link active" href="products.php">Ürünler</a>
                <a class="nav-link" href="logout.php">Çıkış Yap</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Ürün Yönetimi</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                Yeni Ürün Ekle
            </button>
        </div>

        <div id="alert-container"></div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Resim</th>
                        <th>Ürün Adı</th>
                        <th>Kategori</th>
                        <th>Fiyat</th>
                        <th>Öne Çıkan</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($product = $products->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $product['id']; ?></td>
                        <td>
                            <img src="../uploads/products/<?php echo $product['image']; ?>" 
                                alt="<?php echo $product['name']; ?>" 
                                style="width: 50px; height: 50px; object-fit: cover;">
                        </td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                        <td>₺<?php echo number_format($product['price'], 2); ?></td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input toggle-featured" type="checkbox" 
                                    data-id="<?php echo $product['id']; ?>"
                                    <?php echo $product['is_featured'] ? 'checked' : ''; ?>>
                            </div>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-product" 
                                data-id="<?php echo $product['id']; ?>"
                                data-name="<?php echo htmlspecialchars($product['name']); ?>"
                                data-description="<?php echo htmlspecialchars($product['description']); ?>"
                                data-price="<?php echo $product['price']; ?>"
                                data-category="<?php echo $product['category_id']; ?>"
                                data-featured="<?php echo $product['is_featured']; ?>">
                                Düzenle
                            </button>
                            <button class="btn btn-sm btn-danger delete-product" 
                                data-id="<?php echo $product['id']; ?>">
                                Sil
                            </button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Yeni Ürün Ekleme Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Yeni Ürün Ekle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addProductForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Ürün Adı</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Açıklama</label>
                            <textarea class="form-control" name="description" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fiyat</label>
                            <input type="number" step="0.01" class="form-control" name="price" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select class="form-control" name="category_id" required>
                                <?php 
                                $categories->data_seek(0);
                                while ($category = $categories->fetch_assoc()): 
                                ?>
                                <option value="<?php echo $category['id']; ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ürün Resmi</label>
                            <input type="file" class="form-control" name="image" required accept="image/*">
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_featured" value="1">
                                <label class="form-check-label">Öne Çıkan Ürün</label>
                            </div>
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

    <!-- Ürün Düzenleme Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ürün Düzenle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editProductForm" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="editProductId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Ürün Adı</label>
                            <input type="text" class="form-control" name="name" id="editProductName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Açıklama</label>
                            <textarea class="form-control" name="description" id="editProductDescription" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fiyat</label>
                            <input type="number" step="0.01" class="form-control" name="price" id="editProductPrice" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select class="form-control" name="category_id" id="editProductCategory" required>
                                <?php 
                                $categories->data_seek(0);
                                while ($category = $categories->fetch_assoc()): 
                                ?>
                                <option value="<?php echo $category['id']; ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ürün Resmi</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                            <small class="text-muted">Sadece yeni resim eklemek istiyorsanız seçin</small>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="editProductFeatured" value="1">
                                <label class="form-check-label">Öne Çıkan Ürün</label>
                            </div>
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

        // Yeni ürün ekleme
        $('#addProductForm').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            $.ajax({
                url: 'api/add_product.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        showAlert('Ürün başarıyla eklendi');
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showAlert(response.message, 'danger');
                    }
                }
            });
        });

        // Ürün düzenleme modalını aç
        $('.edit-product').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const description = $(this).data('description');
            const price = $(this).data('price');
            const category = $(this).data('category');
            const featured = $(this).data('featured');
            
            $('#editProductId').val(id);
            $('#editProductName').val(name);
            $('#editProductDescription').val(description);
            $('#editProductPrice').val(price);
            $('#editProductCategory').val(category);
            $('#editProductFeatured').prop('checked', featured == 1);
            
            $('#editProductModal').modal('show');
        });

        // Ürün düzenleme
        $('#editProductForm').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            $.ajax({
                url: 'api/edit_product.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        showAlert('Ürün başarıyla güncellendi');
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showAlert(response.message, 'danger');
                    }
                }
            });
        });

        // Ürün silme
        $('.delete-product').on('click', function() {
            if (confirm('Bu ürünü silmek istediğinize emin misiniz?')) {
                const id = $(this).data('id');
                
                $.ajax({
                    url: 'api/delete_product.php',
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        if (response.success) {
                            showAlert('Ürün başarıyla silindi');
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

        // Öne çıkan durumunu güncelle
        $('.toggle-featured').on('change', function() {
            const id = $(this).data('id');
            const featured = $(this).prop('checked') ? 1 : 0;
            
            $.ajax({
                url: 'api/toggle_featured.php',
                type: 'POST',
                data: { 
                    id: id,
                    featured: featured
                },
                success: function(response) {
                    if (response.success) {
                        showAlert('Ürün durumu güncellendi');
                    } else {
                        showAlert(response.message, 'danger');
                    }
                }
            });
        });
    </script>
</body>
</html>