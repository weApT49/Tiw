<div class="container">
    <h2 style="font-family:'Cinzel', serif; text-align:center; margin-bottom:40px; color:var(--bg-dark);">Your Selection</h2>
    
    <?php if (empty($_SESSION['cart'])): ?>
        <div style="text-align:center; padding:80px 20px; background:#FFF; border:1px solid #E0E0E0;">
            <h3 style="color:var(--text-light);">Your shopping bag is empty</h3>
            <a href="index.php?page=product" class="btn-primary" style="margin-top:20px;">Browse Collection</a>
        </div>
    <?php else: ?>
        <form action="index.php?page=checkout" method="post" id="cartForm">
            <div style="overflow-x:auto;">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th width="5%"></th>
                            <th width="15%">Timepiece</th>
                            <th width="35%">Model Details</th>
                            <th width="15%">Price</th>
                            <th width="15%">Quantity</th>
                            <th width="15%">Total</th>
                            <th width="5%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $ids = implode(",", array_keys($_SESSION['cart']));
                            $stmt = $pdo->query("SELECT * FROM product WHERE id_menu IN ($ids)");
                            while ($row = $stmt->fetch()) {
                                $id = $row['id_menu'];
                                $qty = $_SESSION['cart'][$id];
                                $price = isset($row['price']) ? $row['price'] : 0;
                                $sum = $price * $qty;
                        ?>
                        <tr>
                            <td style="text-align:center;">
                                <input type="checkbox" name="selected_items[]" value="<?=$id?>" onclick="calculateTotal()">
                            </td>
                            <td>
                                <img src="image/<?=$row['img']?>" style="width:80px; border:1px solid #eee;" onerror="this.src='image/icon.png'">
                            </td>
                            <td>
                                <div style="font-weight:700; color:var(--bg-dark); font-family:'Cinzel',serif;"><?=$row['name_menu']?></div>
                                <small style="color:#888;"><?=$row['how']?></small>
                            </td>
                            <td><?=number_format($price)?></td>
                            <td>
                                <input type="number" name="qty[<?=$id?>]" value="<?=$qty?>" min="1" class="form-control" 
                                       style="width:70px; text-align:center; padding:5px; margin:0;" onchange="updateSubtotal(this, <?=$price?>)">
                            </td>
                            <td style="font-weight:700; color:var(--accent-gold);">
                                <span class="item-total"><?=number_format($sum)?></span>
                            </td>
                            <td style="text-align:center;">
                                <a href="cart_action.php?action=delete&id=<?=$id?>" style="color:#C62828;" onclick="return confirm('Remove this item?')">✕</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div style="background:#FFF; padding:30px; margin-top:30px; border:1px solid #E0E0E0; text-align:right;">
                <div style="font-size:1.1rem; color:#666; margin-bottom:10px;">
                    Subtotal: <span id="grandTotal" style="color:var(--bg-dark); font-weight:700;">0</span> THB
                </div>
                <button type="submit" class="btn-primary" id="checkoutBtn" style="opacity:0.5; pointer-events:none;">
                    Proceed to Checkout
                </button>
            </div>
        </form>
    <?php endif; ?>
</div>

<script>
function updateSubtotal(input, price) {
    let qty = parseInt(input.value);
    if(qty < 1) { input.value = 1; qty = 1; }
    let subtotal = qty * price;
    let row = input.closest('tr');
    row.querySelector('.item-total').innerText = subtotal.toLocaleString();
    calculateTotal();
}

function calculateTotal() {
    let total = 0;
    let checkboxes = document.querySelectorAll('input[name="selected_items[]"]');
    let count = 0;
    checkboxes.forEach(cb => {
        if(cb.checked) {
            let row = cb.closest('tr');
            let priceText = row.querySelector('.item-total').innerText.replace(/,/g, '');
            total += parseFloat(priceText);
            count++;
        }
    });
    document.getElementById('grandTotal').innerText = total.toLocaleString();
    let btn = document.getElementById('checkoutBtn');
    if(count > 0) { btn.style.opacity = '1'; btn.style.pointerEvents = 'auto'; } 
    else { btn.style.opacity = '0.5'; btn.style.pointerEvents = 'none'; }
}
</script>