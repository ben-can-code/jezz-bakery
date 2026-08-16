<?php
require_once("DBConnection.php");
$is_edit = false;
if(isset($_GET['id']) && (int)$_GET['id'] > 0){
    $is_edit = true;
    $qry = $conn->query("SELECT * FROM `product_list` where product_id = '".(int)$_GET['id']."'");
    foreach($qry->fetch_array() as $k => $v){ $$k = $v; }
}

// Build category prefix map: category_id => prefix
// Derived from first 3 letters of category name (uppercased)
$prefix_map = [];
$cat_all = $conn->query("SELECT category_id, name FROM category_list WHERE status=1 AND delete_flag=0");
while($cr = $cat_all->fetch_assoc()){
    // Special known abbreviations, fallback to first 3 chars
    $known = [
        'Breads'      => 'BRD',
        'Pastries'    => 'PST',
        'Cakes'       => 'CAK',
        'Cookies'     => 'COK',
        'Beverages'   => 'BEV',
        'Sandwiches'  => 'SND',
        'Muffins'     => 'MUF',
        'Donuts'      => 'DNT',
        'Pies & Tarts'=> 'PIE',
        'Specialty'   => 'SPC',
    ];
    $prefix_map[$cr['category_id']] = $known[$cr['name']] ?? strtoupper(substr(preg_replace('/[^A-Za-z]/','',$cr['name']),0,3));
}
// JSON-encode for JS use
$prefix_json = json_encode($prefix_map);
?>
<div class="container-fluid">
    <form action="" id="product-form">
        <input type="hidden" name="id" value="<?php echo isset($product_id) ? $product_id : '' ?>">
        <div class="col-12">
            <div class="row">
                <!-- Left column -->
                <div class="col-md-6">
                    <div class="form-group mb-2">
                        <label class="control-label fw-semibold" style="font-size:.82rem">
                            Category
                        </label>
                        <select name="category_id" id="category_id" class="form-select form-select-sm select2" required>
                            <option <?php echo (!isset($category_id)) ? 'selected' : '' ?> disabled value="">Please Select</option>
                            <?php
                            $cat_qry = $conn->query("SELECT * FROM category_list where `status`=1 and `delete_flag`=0 order by `name` asc");
                            while($row = $cat_qry->fetch_assoc()):
                            ?>
                            <option value="<?php echo $row['category_id'] ?>"
                                <?php echo (isset($category_id) && $category_id==$row['category_id']) ? 'selected':'' ?>>
                                <?php echo htmlspecialchars($row['name']) ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label class="control-label fw-semibold" style="font-size:.82rem">
                            Product Code
                            <?php if(!$is_edit): ?>
                            <span class="text-muted fw-normal" style="font-size:.72rem">
                                — auto-generated when category is selected
                            </span>
                            <?php endif; ?>
                        </label>
                        <div class="input-group input-group-sm">
                            <input type="text" name="product_code" id="product_code" required
                                   class="form-control <?php echo !$is_edit ? 'bg-light' : '' ?>"
                                   placeholder="Select a category first"
                                   <?php echo !$is_edit ? 'readonly' : '' ?>
                                   value="<?php echo isset($product_code) ? htmlspecialchars($product_code) : '' ?>">
                            <?php if(!$is_edit): ?>
                            <button class="btn btn-outline-secondary" type="button" id="regen_code" title="Re-generate code">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                        <?php if(!$is_edit): ?>
                        <div class="text-muted mt-1" style="font-size:.72rem">
                            <i class="fas fa-info-circle"></i>
                            You can unlock and edit the code manually if needed.
                            <a href="javascript:void(0)" id="unlock_code" class="ms-1">Unlock</a>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group mb-2">
                        <label class="control-label fw-semibold" style="font-size:.82rem">Product Name</label>
                        <input type="text" name="name" id="name" required
                               class="form-control form-control-sm"
                               value="<?php echo isset($name) ? htmlspecialchars($name) : '' ?>">
                    </div>
                    <div class="form-group mb-2">
                        <label class="control-label fw-semibold" style="font-size:.82rem">Price</label>
                        <input type="number" step="any" name="price" id="price" required
                               class="form-control form-control-sm text-end"
                               value="<?php echo isset($price) ? $price : '' ?>">
                    </div>
                    <div class="form-group mb-2">
                        <label class="control-label fw-semibold" style="font-size:.82rem">Restock Alert Quantity</label>
                        <input type="number" step="any" name="alert_restock" id="alert_restock" required
                               class="form-control form-control-sm text-end"
                               value="<?php echo isset($alert_restock) ? $alert_restock : '' ?>">
                    </div>
                    <div class="form-group mb-2">
                        <label class="control-label fw-semibold" style="font-size:.82rem">Status</label>
                        <select name="status" id="status" class="form-select form-select-sm" required>
                            <option value="1" <?php echo isset($status) && $status==1 ? 'selected':'' ?>>Active</option>
                            <option value="0" <?php echo isset($status) && $status==0 ? 'selected':'' ?>>Inactive</option>
                        </select>
                    </div>
                </div>
                <!-- Right column -->
                <div class="col-md-6">
                    <div class="form-group mb-2">
                        <label class="control-label fw-semibold" style="font-size:.82rem">Description</label>
                        <textarea name="description" id="description" rows="3"
                                  class="form-control" required><?php echo isset($description) ? htmlspecialchars($description) : '' ?></textarea>
                    </div>
                    <!-- Image URL + preview -->
                    <div class="form-group mb-2">
                        <label class="control-label fw-semibold" style="font-size:.82rem">
                            Product Image URL
                            <span class="text-muted fw-normal">(paste any image link)</span>
                        </label>
                        <div class="input-group input-group-sm">
                            <input type="url" name="image_url" id="image_url"
                                   class="form-control"
                                   placeholder="https://example.com/image.jpg"
                                   value="<?php echo isset($image_url) ? htmlspecialchars($image_url) : '' ?>">
                            <button class="btn btn-outline-secondary" type="button" id="preview_btn" title="Preview">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-outline-danger" type="button" id="clear_img_btn" title="Clear">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <!-- Live preview box -->
                        <div id="img_preview_wrap" class="mt-2 text-center"
                             style="<?php echo (isset($image_url) && $image_url) ? '' : 'display:none' ?>">
                            <img id="img_preview"
                                 src="<?php echo isset($image_url) ? htmlspecialchars($image_url) : '' ?>"
                                 alt="Product preview"
                                 style="max-height:160px;max-width:100%;border-radius:10px;object-fit:cover;
                                        border:2px solid #e5e7eb;box-shadow:0 2px 8px rgba(0,0,0,.1)">
                            <div id="img_error" class="text-danger small mt-1" style="display:none">
                                <i class="fas fa-exclamation-triangle"></i> Could not load image. Check the URL.
                            </div>
                        </div>
                        <div class="text-muted mt-1" style="font-size:.72rem">
                            <i class="fas fa-info-circle"></i>
                            You can copy an image address from Google Images or any website.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
$(function(){

    // ── Category prefix map (from PHP) ────────────────────────
    var prefixMap = <?php echo $prefix_json ?>;

    // ── Auto-generate product code ────────────────────────────
    <?php if(!$is_edit): ?>
    function generateCode(catId){
        if(!catId) return;
        var prefix = prefixMap[catId] || 'PRD';
        // Ask server for next available number for this category
        $.get('./Actions.php?a=next_product_code&cat_id=' + catId, function(resp){
            try {
                var r = JSON.parse(resp);
                if(r.code){
                    $('#product_code').val(r.code);
                }
            } catch(e){}
        });
    }

    // Trigger on category change (select2 fires 'change')
    $('#category_id').on('change', function(){
        generateCode($(this).val());
    });

    // Re-generate button
    $('#regen_code').on('click', function(){
        generateCode($('#category_id').val());
    });

    // Unlock link — lets admin manually type the code
    var unlocked = false;
    $('#unlock_code').on('click', function(){
        if(!unlocked){
            $('#product_code').prop('readonly', false).removeClass('bg-light').focus();
            $('#regen_code').hide();
            $(this).text('Lock').addClass('text-danger');
            unlocked = true;
        } else {
            $('#product_code').prop('readonly', true).addClass('bg-light');
            $('#regen_code').show();
            $(this).text('Unlock').removeClass('text-danger');
            unlocked = false;
            generateCode($('#category_id').val()); // re-fetch
        }
    });

    // If a category is already pre-selected on load, generate code
    var initCat = $('#category_id').val();
    if(initCat) generateCode(initCat);
    <?php endif; ?>

    // ── Live image preview ────────────────────────────────────
    function showPreview(url){
        if(!url){ $('#img_preview_wrap').hide(); return; }
        $('#img_preview').attr('src', url);
        $('#img_error').hide();
        $('#img_preview_wrap').show();
    }

    $('#preview_btn').on('click', function(){
        showPreview($('#image_url').val().trim());
    });

    $('#image_url').on('paste', function(){
        setTimeout(function(){ showPreview($('#image_url').val().trim()); }, 100);
    }).on('blur', function(){
        showPreview($(this).val().trim());
    });

    $('#img_preview').on('error', function(){
        $('#img_error').show();
        $(this).attr('src','');
    }).on('load', function(){
        if($(this).attr('src')) $('#img_error').hide();
    });

    $('#clear_img_btn').on('click', function(){
        $('#image_url').val('');
        $('#img_preview_wrap').hide();
        $('#img_error').hide();
    });

    // Trigger preview on load if editing
    var existing = $('#image_url').val().trim();
    if(existing) showPreview(existing);

    // ── Form submit ───────────────────────────────────────────
    $('#product-form').submit(function(e){
        e.preventDefault();
        $('.pop_msg').remove();
        var _this = $(this);
        var _el   = $('<div>').addClass('pop_msg');
        $('#uni_modal button').attr('disabled', true);
        $('#uni_modal #submit').html('<i class="fas fa-spinner fa-spin me-1"></i>Saving...');
        $.ajax({
            url: './Actions.php?a=save_product',
            data: new FormData($(this)[0]),
            cache: false, contentType: false, processData: false,
            method: 'POST', dataType: 'json',
            error: function(err){
                console.log(err);
                _el.addClass('alert alert-danger').text('An error occurred.');
                _this.prepend(_el); _el.show('slow');
                $('#uni_modal button').attr('disabled', false);
                $('#uni_modal #submit').html('Save');
            },
            success: function(resp){
                if(resp.status == 'success'){
                    _el.addClass('alert alert-success');
                    $('#uni_modal').on('hide.bs.modal', function(){ location.reload(); });
                    if("<?php echo $is_edit ? '1':'0' ?>" !== '1'){
                        _this.get(0).reset();
                        $('.select2').val('').trigger('change');
                        $('#product_code').val('').prop('readonly', true).addClass('bg-light');
                        $('#img_preview_wrap').hide();
                        unlocked = false;
                        <?php if(!$is_edit): ?>
                        $('#regen_code').show();
                        $('#unlock_code').text('Unlock').removeClass('text-danger');
                        <?php endif; ?>
                    }
                } else {
                    _el.addClass('alert alert-danger');
                }
                _el.text(resp.msg).hide();
                _this.prepend(_el); _el.show('slow');
                $('#uni_modal button').attr('disabled', false);
                $('#uni_modal #submit').html('Save');
            }
        });
    });
});
</script>
