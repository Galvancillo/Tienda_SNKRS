<?php
$titulo = 'Carrito de compras';
$css_adicional = 'hombre';
$esCarrito = true;
?>
<main class="carrito-container">
    <h1><?php echo $titulo; ?></h1>
    <?php if (!empty($productos)): ?>
        <table class="carrito-tabla">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Talla</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; ?>
                <?php foreach ($productos as $item): ?>
                    <tr>
                        <td>
                            <img src="<?php echo $item['imagen_url']; ?>" alt="<?php echo htmlspecialchars($item['nombre']); ?>" style="width:60px; height:60px; object-fit:cover; border-radius:8px;">
                            <span><?php echo htmlspecialchars($item['nombre']); ?></span>
                        </td>
                        <td><?php echo htmlspecialchars($item['talla']); ?></td>
                        <td><?php echo $item['cantidad']; ?></td>
                        <td>$<?php echo number_format($item['precio'], 2); ?></td>
                        <td>$<?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></td>
                        <?php $total += $item['precio'] * $item['cantidad']; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="carrito-total">
            <strong>Total: $<?php echo number_format($total, 2); ?></strong>
        </div>
    <?php else: ?>
        <p>No hay productos en tu carrito.</p>
    <?php endif; ?>
</main> 