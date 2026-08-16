
<div class="card shadow-sm" style="border:none;border-radius:14px;overflow:hidden">
    <div class="card-header d-flex justify-content-between align-items-center"
         style="background:#fff;border-bottom:1px solid #e5e7eb;padding:.85rem 1.25rem">
        <h5 class="mb-0 fw-semibold" style="font-size:.95rem">
            <i class="fas fa-box-open me-2 text-warning"></i>Product List
        </h5>
        <button class="btn btn-sm btn-warning text-white fw-semibold px-3" type="button" id="create_new">
            <i class="fas fa-plus me-1"></i>Add New
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-hover table-striped mb-0" id="products_table">
            <thead style="background:#f9fafb;font-size:.78rem;text-transform:uppercase;letter-spacing:.04em;color:#6b7280">
                <tr>
                    <th class="text-center px-2 py-2" style="width:4%">#</th>
                    <th class="px-2 py-2" style="width:7%">Image</th>
                    <th class="px-2 py-2" style="width:9%">Code</th>
                    <th class="px-2 py-2" style="width:12%">Category</th>
                    <th class="px-2 py-2" style="width:28%">Product</th>
                    <th class="px-2 py-2 text-end" style="width:8%">Price</th>
                    <th class="px-2 py-2 text-center" style="width:7%">Alert</th>
                    <th class="px-2 py-2 text-center" style="width:10%">Status</th>
                    <th class="px-2 py-2 text-center" style="width:15%">Action</th>
                </tr>
            </thead>
            <tbody style="font-size:.85rem">
                <?php
                $sql = "SELECT p.*, c.name AS cname
                        FROM `product_list` p
                        INNER JOIN `category_list` c ON p.category_id = c.category_id
                        WHERE p.delete_flag = 0
                        ORDER BY c.name ASC, p.name ASC";
                $qry = $conn->query($sql);
                $i = 1;
                while($row = $qry->fetch_assoc()):
                    $has_img = !empty($row['image_url']);
                ?>
                <tr>
                    <td class="text-center px-2 py-1"><?php echo $i++; ?></td>
                    <td class="px-2 py-1">
                        <?php if($has_img): ?>
                            <img src="<?php echo htmlspecialchars($row['image_url']) ?>"
                                 alt="<?php echo htmlspecialchars($row['name']) ?>"
                                 style="width:46px;height:46px;object-fit:cover;border-radius:8px;
                                        border:1px solid #e5e7eb;cursor:pointer"
                                 class="product-thumb"
                                 data-url="<?php echo htmlspecialchars($row['image_url']) ?>"
                                 data-name="<?php echo htmlspecialchars($row['name']) ?>"
                                 onerror="this.src='https://placehold.co/46x46/f4f6fb/aaa?text=?';this.style.cursor='default'">
                        <?php else: ?>
                            <div style="width:46px;height:46px;background:#f4f6fb;border-radius:8px;
                                        border:1px solid #e5e7eb;display:flex;align-items:center;
                                        justify-content:center;color:#d1d5db;font-size:1.1rem">
                                <i class="fas fa-image"></i>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td class="px-2 py-1">
                        <span class="badge bg-light text-dark border" style="font-size:.72rem;font-weight:500">
                            <?php echo htmlspecialchars($row['product_code']) ?>
                        </span>
                    </td>
                    <td class="px-2 py-1" style="color:#6b7280;font-size:.8rem">
                        <?php echo htmlspecialchars($row['cname']) ?>
                    </td>
                    <td class="px-2 py-1">
                        <div class="fw-semibold truncate-1" style="font-size:.85rem"
                             title="<?php echo htmlspecialchars($row['name']) ?>">
                            <?php echo htmlspecialchars($row['name']) ?>
                        </div>
                        <div class="truncate-2 text-muted" style="font-size:.75rem;line-height:1.3"
                             title="<?php echo htmlspecialchars($row['description']) ?>">
                            <?php echo htmlspecialchars($row['description']) ?>
                        </div>
                    </td>
                    <td class="px-2 py-1 text-end fw-semibold">
                        $<?php echo number_format($row['price'], 2) ?>
                    </td>
                    <td class="px-2 py-1 text-center text-muted" style="font-size:.8rem">
                        <?php echo number_format($row['alert_restock']) ?>
                    </td>
                    <td class="px-2 py-1 text-center">
                        <?php if($row['status'] == 1): ?>
                            <span class="badge rounded-pill bg-success px-2" style="font-size:.72rem">Active</span>
                        <?php else: ?>
                            <span class="badge rounded-pill bg-danger px-2" style="font-size:.72rem">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-2 py-1 text-center">
                        <div class="btn-group">
                            <button type="button"
                                    class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                    data-bs-toggle="dropdown" aria-expanded="false"
                                    style="font-size:.78rem">
                                Action
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="font-size:.82rem">
                                <li>
                                    <a class="dropdown-item view_data"
                                       data-id="<?php echo $row['product_id'] ?>"
                                       href="javascript:void(0)">
                                        <i class="fas fa-eye me-2 text-info"></i>View Details
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item edit_data"
                                       data-id="<?php echo $row['product_id'] ?>"
                                       href="javascript:void(0)">
                                        <i class="fas fa-pen me-2 text-warning"></i>Edit
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <a class="dropdown-item delete_data text-danger"
                                       data-id="<?php echo $row['product_id'] ?>"
                                       data-name="<?php echo htmlspecialchars($row['product_code'].' - '.$row['name']) ?>"
                                       href="javascript:void(0)">
                                        <i class="fas fa-trash me-2"></i>Delete
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

<!-- Image lightbox modal -->
<div class="modal fade" id="img_lightbox" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px">
        <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-semibold" id="lightbox_title"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center pt-2">
                <img id="lightbox_img" src="" alt=""
                     style="max-width:100%;max-height:360px;object-fit:contain;border-radius:10px">
            </div>
        </div>
    </div>
</div>

<script>
$(function(){
    $('#create_new').click(function(){
        uni_modal('Add New Product', 'manage_product.php', 'mid-large');
    });
    $(document).on('click','.edit_data', function(){
        uni_modal('Edit Product', 'manage_product.php?id='+$(this).data('id'), 'mid-large');
    });
    $(document).on('click','.view_data', function(){
        uni_modal('Product Details', 'view_product.php?id='+$(this).data('id'), '');
    });
    $(document).on('click','.delete_data', function(){
        _conf('Are you sure to delete <b>'+$(this).data('name')+'</b>?', 'delete_data', [$(this).data('id')]);
    });

    // Thumbnail lightbox
    $(document).on('click','.product-thumb', function(){
        $('#lightbox_title').text($(this).data('name'));
        $('#lightbox_img').attr('src', $(this).data('url'));
        var lb = new bootstrap.Modal(document.getElementById('img_lightbox'));
        lb.show();
    });

    $('table td, table th').addClass('align-middle');
    $('#products_table').dataTable({
        pageLength: 25,
        columnDefs: [
            { orderable: false, targets: [1, 8] }
        ],
        language: { search: 'Filter:', lengthMenu: 'Show _MENU_ products' }
    });
});

function delete_data($id){
    $('#confirm_modal button').attr('disabled', true);
    $.ajax({
        url: './Actions.php?a=delete_product',
        method: 'POST',
        data: { id: $id },
        dataType: 'JSON',
        error: function(err){ console.log(err); alert('An error occurred.'); $('#confirm_modal button').attr('disabled',false); },
        success: function(resp){
            if(resp.status == 'success') location.reload();
            else { alert('An error occurred.'); $('#confirm_modal button').attr('disabled',false); }
        }
    });
}
</script>
