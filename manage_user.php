<?php
require_once("DBConnection.php");
$is_edit = false;
if(isset($_GET['id']) && (int)$_GET['id'] > 0){
    $is_edit = true;
    $qry = $conn->query("SELECT * FROM `user_list` WHERE user_id = '".(int)$_GET['id']."'");
    foreach($qry->fetch_assoc() as $k => $v){ $$k = $v; }
}
?>
<div class="container-fluid">
    <form action="" id="user-form">
        <input type="hidden" name="id" value="<?php echo $is_edit ? $user_id : '' ?>">

        <!-- Full Name -->
        <div class="form-group mb-2">
            <label class="fw-semibold" style="font-size:.82rem">Full Name</label>
            <input type="text" name="fullname" id="fullname" required
                   class="form-control form-control-sm"
                   placeholder="e.g. Jane Smith"
                   value="<?php echo $is_edit ? htmlspecialchars($fullname) : '' ?>">
        </div>

        <!-- Username (auto-generated, but editable) -->
        <div class="form-group mb-2">
            <label class="fw-semibold" style="font-size:.82rem">
                Username
                <?php if(!$is_edit): ?>
                    <span class="text-muted fw-normal" style="font-size:.72rem">
                        — auto-generated from Full Name (you can edit it)
                    </span>
                <?php endif; ?>
            </label>
            <div class="input-group input-group-sm">
                <input type="text" name="username" id="username" required
                       class="form-control"
                       value="<?php echo $is_edit ? htmlspecialchars($username) : '' ?>">
                <?php if(!$is_edit): ?>
                <button class="btn btn-outline-secondary" type="button" id="regen_username" title="Re-generate">
                    <i class="fas fa-sync-alt"></i>
                </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Role -->
        <div class="form-group mb-2">
            <label class="fw-semibold" style="font-size:.82rem">Role</label>
            <select name="type" id="type" class="form-select form-select-sm" required>
                <option value="1" <?php echo ($is_edit && $type==1) ? 'selected':'' ?>>
                    <i class="fas fa-user-shield"></i> Administrator
                </option>
                <option value="0" <?php echo ($is_edit && $type==0) ? 'selected':'' ?>>
                    <i class="fas fa-cash-register"></i> Cashier
                </option>
            </select>
        </div>

        <!-- Password — shown always for new user, optional for edit -->
        <div class="form-group mb-2" id="pw_block">
            <label class="fw-semibold" style="font-size:.82rem">
                Password
                <?php if($is_edit): ?>
                    <span class="text-muted fw-normal" style="font-size:.72rem">— leave blank to keep current</span>
                <?php endif; ?>
            </label>
            <div class="input-group input-group-sm">
                <input type="password" name="password" id="password"
                       <?php echo !$is_edit ? 'required' : '' ?>
                       class="form-control"
                       placeholder="<?php echo $is_edit ? 'Leave blank to keep current' : 'Set a password' ?>">
                <button class="btn btn-outline-secondary" type="button" id="toggle_pw" title="Show/Hide">
                    <i class="fas fa-eye" id="pw_eye"></i>
                </button>
            </div>
            <?php if(!$is_edit): ?>
            <div class="text-muted mt-1" style="font-size:.72rem">
                <i class="fas fa-info-circle"></i>
                The user will log in with this password. You can change it later.
            </div>
            <?php endif; ?>
        </div>

        <!-- Status (edit only) -->
        <?php if($is_edit): ?>
        <div class="form-group mb-2">
            <label class="fw-semibold" style="font-size:.82rem">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="1" <?php echo (isset($status) && $status==1) ? 'selected':'' ?>>Active</option>
                <option value="0" <?php echo (isset($status) && $status==0) ? 'selected':'' ?>>Inactive</option>
            </select>
        </div>
        <?php endif; ?>

    </form>
</div>

<script>
$(function(){

    // ── Auto-generate username from Full Name ─────────────────
    function generateUsername(fullname){
        var parts = fullname.trim().toLowerCase().split(/\s+/);
        if(parts.length === 0 || parts[0] === '') return '';
        if(parts.length === 1) return parts[0];
        // First letter of first name + last name  (e.g. "Jane Smith" → "jsmith")
        return parts[0].charAt(0) + parts[parts.length - 1];
    }

    <?php if(!$is_edit): ?>
    $('#fullname').on('input', function(){
        var gen = generateUsername($(this).val());
        $('#username').val(gen);
    });
    $('#regen_username').on('click', function(){
        var gen = generateUsername($('#fullname').val());
        $('#username').val(gen).focus();
    });
    <?php endif; ?>

    // ── Password toggle ───────────────────────────────────────
    $('#toggle_pw').on('click', function(){
        var pw  = document.getElementById('password');
        var eye = document.getElementById('pw_eye');
        if(pw.type === 'password'){
            pw.type = 'text';
            eye.className = 'fas fa-eye-slash';
        } else {
            pw.type = 'password';
            eye.className = 'fas fa-eye';
        }
    });

    // ── Form submit ───────────────────────────────────────────
    $('#user-form').submit(function(e){
        e.preventDefault();
        $('.pop_msg').remove();
        var _this = $(this);
        var _el   = $('<div>').addClass('pop_msg');
        $('#uni_modal button').attr('disabled', true);
        $('#uni_modal #submit').html('<i class="fas fa-spinner fa-spin me-1"></i>Saving...');

        $.ajax({
            url: './Actions.php?a=save_user',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'JSON',
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
                    <?php if(!$is_edit): ?>
                    _this.get(0).reset();
                    <?php endif; ?>
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
