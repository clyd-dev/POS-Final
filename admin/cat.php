<form method="POST">
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Category Name</label>
            <select name="category_name" id="category_name" class="form-control" required>
                <option value="">-- Select Category --</option>
                <option value="Electronics">Electronics</option>
                <option value="Apparel/Clothing">Apparel/Clothing</option>
                <option value="Home Goods & Furniture">Home Goods & Furniture</option>
                <option value="Beauty & Personal Care">Beauty & Personal Care</option>
                <option value="Food & Beverages">Food & Beverages</option>
                <option value="Sports & Outdoors">Sports & Outdoors</option>
                <option value="Digital Products">Digital Products</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="3" placeholder="Optional description"></textarea>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" name="add" class="btn btn-primary"><i class="bi bi-save me-2"></i>Save Category</button>
        <a href="supplier.php" class="btn btn-success"><i class="bi bi-truck me-2"></i>Suppliers</a>
        <a href="product.php" class="btn btn-warning"><i class="bi bi-box me-2"></i>Products</a>
    </div>
</form>

<script>
document.getElementById('category_name').addEventListener('change', function() {
    const descriptions = {
        "Electronics": "Products that use electronic circuits to function, ranging from personal gadgets to household appliances.",
        "Apparel/Clothing": "Items worn on the body for protection, fashion, or other functions.",
        "Home Goods & Furniture": "Products used to furnish, decorate, or maintain a home.",
        "Beauty & Personal Care": "Products used for hygiene, grooming, and cosmetics.",
        "Food & Beverages": "Edible items and drinks for consumption.",
        "Sports & Outdoors": "Equipment and gear designed for athletic activities, fitness, and outdoor recreation.",
        "Digital Products": "Intangible goods or services delivered electronically."
    };
    const selected = this.value;
    document.getElementById('description').value = descriptions[selected] || '';
});
</script>
