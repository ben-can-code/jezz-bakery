
<?php
// Pre-fetch activity log counts per user for badge display
$log_counts = [];
$lc_qry = @$conn->query("SELECT user_id, COUNT(*) as cnt FROM `activity_log` GROUP BY user_id");
if($lc_qry && $lc_qry->num_rows > 0){
    while($lc = $lc_qry->fetch_assoc()) $log_counts[$lc['user_id']] = $lc['cnt'];
}
?>

<!-- ── Tabs ─────────────────────────────────────────────────── -->
<ul class="nav nav-tabs mb-3" id="usersTabs">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#tab_users">
            <i class="fas fa-users me-1"></i>Users
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#tab_activity">
            <i class="fas fa-history me-1"></i>Activity Log
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#tab_login">
            <i class="fas fa-sign-in-alt me-1"></i>Login Attempts
        </a>
    </li>
</ul>

<div class="tab-content">

<!-- ══════════════════════════════════════════════════════════ -->
<!--  TAB 1 · Users list                                        -->
<!-- ══════════════════════════════════════════════════════════ -->
<div class="tab-pane fade show active" id="tab_users">
    <div class="card shadow-sm" style="border:none;border-radius:14px;overflow:hidden">
        <div class="card-header d-flex justify-content-between align-items-center"
             style="background:#fff;border-bottom:1px solid #e5e7eb;padding:.85rem 1.25rem">
            <h5 class="mb-0 fw-semibold" style="font-size:.95rem">
                <i class="fas fa-users me-2 text-warning"></i>All Users
            </h5>
            <button class="btn btn-sm btn-warning text-white fw-semibold px-3" type="button" id="create_new">
                <i class="fas fa-plus me-1"></i>Add New User
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
            <table class="table table-hover table-striped mb-0" id="users_table">
                <thead style="background:#f9fafb;font-size:.78rem;text-transform:uppercase;letter-spacing:.04em;color:#6b7280">
                    <tr>
                        <th class="text-center px-2 py-2" style="width:5%">#</th>
                        <th class="px-2 py-2">Name</th>
                        <th class="px-2 py-2">Username</th>
                        <th class="px-2 py-2 text-center">Role</th>
                        <th class="px-2 py-2 text-center">Status</th>
                        <th class="px-2 py-2 text-center">Actions Logged</th>
                        <th class="px-2 py-2 text-center">Action</th>
                    </tr>
                </thead>
                <tbody style="font-size:.85rem">
                <?php
                $sql = "SELECT * FROM `user_list` ORDER BY `fullname` ASC";
                $qry = $conn->query($sql);
                $i = 1;
                while($row = $qry->fetch_assoc()):
                    $is_admin_row = ($row['user_id'] == 1);
                    $cnt = $log_counts[$row['user_id']] ?? 0;
                ?>
                <tr>
                    <td class="text-center px-2 py-1"><?php echo $i++; ?></td>
                    <td class="px-2 py-1">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:32px;height:32px;border-radius:50%;
                                        background:linear-gradient(135deg,#e67e22,#f39c12);
                                        display:flex;align-items:center;justify-content:center;
                                        color:#fff;font-weight:700;font-size:.8rem;flex-shrink:0">
                                <?php echo strtoupper(substr($row['fullname'],0,1)) ?>
                            </div>
                            <div>
                                <div class="fw-semibold"><?php echo htmlspecialchars($row['fullname']) ?></div>
                                <div class="text-muted" style="font-size:.72rem">
                                    Since <?php echo date('M d, Y', strtotime($row['date_created'])) ?>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-2 py-1">
                        <code style="font-size:.8rem;background:#f4f6fb;padding:2px 6px;border-radius:4px">
                            <?php echo htmlspecialchars($row['username']) ?>
                        </code>
                    </td>
                    <td class="px-2 py-1 text-center">
                        <?php if($row['type'] == 1): ?>
                            <span class="badge bg-primary px-2" style="font-size:.72rem">Administrator</span>
                        <?php else: ?>
                            <span class="badge bg-info text-dark px-2" style="font-size:.72rem">Cashier</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-2 py-1 text-center">
                        <?php if(($row['status'] ?? 1) == 1): ?>
                            <span class="badge rounded-pill bg-success px-2" style="font-size:.72rem">Active</span>
                        <?php else: ?>
                            <span class="badge rounded-pill bg-secondary px-2" style="font-size:.72rem">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-2 py-1 text-center">
                        <?php if($cnt > 0): ?>
                        <a href="javascript:void(0)" class="view_user_log badge bg-warning text-dark text-decoration-none px-2"
                           data-id="<?php echo $row['user_id'] ?>"
                           data-name="<?php echo htmlspecialchars($row['fullname']) ?>"
                           style="font-size:.72rem">
                            <i class="fas fa-history me-1"></i><?php echo $cnt ?> actions
                        </a>
                        <?php else: ?>
                            <span class="text-muted" style="font-size:.75rem">No activity</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-2 py-1 text-center">
                        <?php if($is_admin_row): ?>
                            <span class="text-muted" style="font-size:.75rem"><i class="fas fa-lock me-1"></i>Protected</span>
                        <?php else: ?>
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                    data-bs-toggle="dropdown" aria-expanded="false" style="font-size:.78rem">
                                Action
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="font-size:.82rem">
                                <li>
                                    <a class="dropdown-item view_user_log"
                                       data-id="<?php echo $row['user_id'] ?>"
                                       data-name="<?php echo htmlspecialchars($row['fullname']) ?>"
                                       href="javascript:void(0)">
                                        <i class="fas fa-history me-2 text-warning"></i>View Activity
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item edit_data"
                                       data-id="<?php echo $row['user_id'] ?>"
                                       href="javascript:void(0)">
                                        <i class="fas fa-pen me-2 text-info"></i>Edit
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <a class="dropdown-item delete_data text-danger"
                                       data-id="<?php echo $row['user_id'] ?>"
                                       data-name="<?php echo htmlspecialchars($row['fullname']) ?>"
                                       href="javascript:void(0)">
                                        <i class="fas fa-trash me-2"></i>Delete
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════ -->
<!--  TAB 2 · Activity Log (all users)                          -->
<!-- ══════════════════════════════════════════════════════════ -->
<div class="tab-pane fade" id="tab_activity">
    <div class="card shadow-sm" style="border:none;border-radius:14px;overflow:hidden">
        <div class="card-header d-flex justify-content-between align-items-center"
             style="background:#fff;border-bottom:1px solid #e5e7eb;padding:.85rem 1.25rem">
            <h5 class="mb-0 fw-semibold" style="font-size:.95rem">
                <i class="fas fa-history me-2 text-warning"></i>System Activity Log
            </h5>
            <button class="btn btn-sm btn-outline-danger" id="clear_activity_log">
                <i class="fas fa-trash me-1"></i>Clear All Logs
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
            <table class="table table-hover table-striped mb-0" id="activity_table">
                <thead style="background:#f9fafb;font-size:.78rem;text-transform:uppercase;letter-spacing:.04em;color:#6b7280">
                    <tr>
                        <th class="text-center px-2 py-2">#</th>
                        <th class="px-2 py-2">User</th>
                        <th class="px-2 py-2 text-center">Action</th>
                        <th class="px-2 py-2">Description</th>
                        <th class="px-2 py-2 text-center">IP Address</th>
                        <th class="px-2 py-2 text-center">Date &amp; Time</th>
                    </tr>
                </thead>
                <tbody style="font-size:.83rem">
                <?php
                $alogs = @$conn->query(
                    "SELECT a.*, u.type as utype
                     FROM `activity_log` a
                     LEFT JOIN `user_list` u ON a.user_id = u.user_id
                     ORDER BY a.date_created DESC
                     LIMIT 500"
                );
                $ai = 1;
                if($alogs && $alogs->num_rows > 0):
                while($al = $alogs->fetch_assoc()):
                    $action_colors = [
                        'login'   => 'success',
                        'logout'  => 'secondary',
                        'create'  => 'primary',
                        'update'  => 'warning',
                        'delete'  => 'danger',
                        'view'    => 'info',
                    ];
                    $ac = strtolower(explode(' ',$al['action'])[0] ?? '');
                    $badge_color = $action_colors[$ac] ?? 'dark';
                ?>
                <tr>
                    <td class="text-center px-2 py-1"><?php echo $ai++; ?></td>
                    <td class="px-2 py-1">
                        <div class="fw-semibold" style="font-size:.82rem">
                            <?php echo htmlspecialchars($al['fullname'] ?? 'System') ?>
                        </div>
                        <?php if(isset($al['utype'])): ?>
                        <div style="font-size:.7rem;color:#9ca3af">
                            <?php echo $al['utype']==1 ? 'Administrator':'Cashier' ?>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td class="px-2 py-1 text-center">
                        <span class="badge bg-<?php echo $badge_color ?> px-2" style="font-size:.72rem">
                            <?php echo htmlspecialchars($al['action']) ?>
                        </span>
                    </td>
                    <td class="px-2 py-1" style="max-width:260px">
                        <?php echo htmlspecialchars($al['description']) ?>
                    </td>
                    <td class="px-2 py-1 text-center">
                        <code style="font-size:.75rem"><?php echo htmlspecialchars($al['ip_address'] ?? '—') ?></code>
                    </td>
                    <td class="px-2 py-1 text-center" style="white-space:nowrap;font-size:.78rem;color:#6b7280">
                        <?php echo date('M d, Y h:i A', strtotime($al['date_created'])) ?>
                    </td>
                </tr>
                <?php endwhile;
                else: ?>
                <tr><td colspan="6" class="text-center py-4 text-muted">No activity recorded yet.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════ -->
<!--  TAB 3 · Login Attempts                                    -->
<!-- ══════════════════════════════════════════════════════════ -->
<div class="tab-pane fade" id="tab_login">
    <div class="card shadow-sm" style="border:none;border-radius:14px;overflow:hidden">
        <div class="card-header d-flex justify-content-between align-items-center"
             style="background:#fff;border-bottom:1px solid #e5e7eb;padding:.85rem 1.25rem">
            <h5 class="mb-0 fw-semibold" style="font-size:.95rem">
                <i class="fas fa-sign-in-alt me-2 text-warning"></i>Login Attempt Log
            </h5>
            <button class="btn btn-sm btn-outline-danger" id="clear_login_log">
                <i class="fas fa-trash me-1"></i>Clear All
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
            <table class="table table-hover table-striped mb-0" id="login_table">
                <thead style="background:#f9fafb;font-size:.78rem;text-transform:uppercase;letter-spacing:.04em;color:#6b7280">
                    <tr>
                        <th class="text-center px-2 py-2">#</th>
                        <th class="px-2 py-2">Username Entered</th>
                        <th class="px-2 py-2 text-center">Result</th>
                        <th class="px-2 py-2 text-center">IP Address</th>
                        <th class="px-2 py-2">Browser / Device</th>
                        <th class="px-2 py-2 text-center">Date &amp; Time</th>
                    </tr>
                </thead>
                <tbody style="font-size:.83rem">
                <?php
                $llogs = @$conn->query(
                    "SELECT * FROM `login_log` ORDER BY `date_created` DESC LIMIT 500"
                );
                $li = 1;
                if($llogs && $llogs->num_rows > 0):
                while($ll = $llogs->fetch_assoc()):
                ?>
                <tr class="<?php echo $ll['status']=='failed'?'table-danger':'' ?>">
                    <td class="text-center px-2 py-1"><?php echo $li++; ?></td>
                    <td class="px-2 py-1 fw-semibold">
                        <?php echo htmlspecialchars($ll['username']) ?>
                    </td>
                    <td class="px-2 py-1 text-center">
                        <?php if($ll['status']=='success'): ?>
                            <span class="badge bg-success px-2" style="font-size:.72rem">
                                <i class="fas fa-check me-1"></i>Success
                            </span>
                        <?php else: ?>
                            <span class="badge bg-danger px-2" style="font-size:.72rem">
                                <i class="fas fa-times me-1"></i>Failed
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-2 py-1 text-center">
                        <code style="font-size:.75rem"><?php echo htmlspecialchars($ll['ip_address'] ?? '—') ?></code>
                    </td>
                    <td class="px-2 py-1" style="font-size:.75rem;color:#6b7280;max-width:220px">
                        <?php
                        $ua = $ll['user_agent'] ?? '';
                        // Simple UA summariser
                        $browser = 'Unknown';
                        if(str_contains($ua,'Chrome'))       $browser = 'Chrome';
                        elseif(str_contains($ua,'Firefox'))  $browser = 'Firefox';
                        elseif(str_contains($ua,'Safari'))   $browser = 'Safari';
                        elseif(str_contains($ua,'Edge'))     $browser = 'Edge';
                        $device = str_contains($ua,'Mobile') ? 'Mobile' : 'Desktop';
                        echo "<i class='fas fa-".($device=='Mobile'?'mobile-alt':'desktop')." me-1'></i>";
                        echo htmlspecialchars("$browser / $device");
                        ?>
                    </td>
                    <td class="px-2 py-1 text-center" style="white-space:nowrap;font-size:.78rem;color:#6b7280">
                        <?php echo date('M d, Y h:i A', strtotime($ll['date_created'])) ?>
                    </td>
                </tr>
                <?php endwhile;
                else: ?>
                <tr><td colspan="6" class="text-center py-4 text-muted">No login attempts recorded yet.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>

</div><!-- /tab-content -->

<!-- ── Per-user activity modal ─────────────────────────────── -->
<div class="modal fade" id="user_log_modal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border:none;border-radius:14px">
            <div class="modal-header" style="border-bottom:1px solid #e5e7eb">
                <h6 class="modal-title fw-semibold" id="user_log_modal_title">Activity</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" id="user_log_modal_body">
                <div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x text-warning"></i></div>
            </div>
        </div>
    </div>
</div>

<script>
$(function(){
    // ── Users tab ─────────────────────────────────────────────
    $('#create_new').click(function(){
        uni_modal('Add New User', 'manage_user.php');
    });
    $(document).on('click','.edit_data', function(){
        uni_modal('Edit User', 'manage_user.php?id='+$(this).data('id'));
    });
    $(document).on('click','.delete_data', function(){
        _conf('Are you sure to delete <b>'+$(this).data('name')+'</b>?', 'delete_data', [$(this).data('id')]);
    });

    // ── Per-user activity log modal ───────────────────────────
    $(document).on('click','.view_user_log', function(){
        var uid  = $(this).data('id');
        var name = $(this).data('name');
        $('#user_log_modal_title').text('Activity: ' + name);
        $('#user_log_modal_body').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x text-warning"></i></div>');
        new bootstrap.Modal(document.getElementById('user_log_modal')).show();
        $.get('./Actions.php?a=get_user_log&uid='+uid, function(html){
            $('#user_log_modal_body').html(html);
        });
    });

    // ── Clear logs ────────────────────────────────────────────
    $('#clear_activity_log').click(function(){
        if(!confirm('Clear ALL activity logs? This cannot be undone.')) return;
        $.post('./Actions.php?a=clear_activity_log', function(r){
            r = JSON.parse(r);
            if(r.status=='success') location.reload();
            else alert('Failed: '+r.msg);
        });
    });
    $('#clear_login_log').click(function(){
        if(!confirm('Clear ALL login logs? This cannot be undone.')) return;
        $.post('./Actions.php?a=clear_login_log', function(r){
            r = JSON.parse(r);
            if(r.status=='success') location.reload();
            else alert('Failed: '+r.msg);
        });
    });

    // ── DataTables ────────────────────────────────────────────
    $('table td, table th').addClass('align-middle');
    $('#users_table').dataTable({
        pageLength: 25,
        columnDefs: [{ orderable: false, targets: [5,6] }]
    });
    $('#activity_table').dataTable({
        pageLength: 25, order: [[5,'desc']],
        columnDefs: [{ orderable: false, targets: [] }]
    });
    $('#login_table').dataTable({
        pageLength: 25, order: [[5,'desc']],
        columnDefs: [{ orderable: false, targets: [] }]
    });

    // ── Keep tab active after reload (via hash) ───────────────
    var hash = window.location.hash;
    if(hash) $('a[href="'+hash+'"]').tab('show');
    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e){
        window.location.hash = $(e.target).attr('href');
    });
});

function delete_data($id){
    $('#confirm_modal button').attr('disabled', true);
    $.ajax({
        url: './Actions.php?a=delete_user', method: 'POST',
        data: {id: $id}, dataType: 'JSON',
        error: function(){ alert('An error occurred.'); $('#confirm_modal button').attr('disabled',false); },
        success: function(resp){
            if(resp.status=='success') location.reload();
            else { alert('An error occurred.'); $('#confirm_modal button').attr('disabled',false); }
        }
    });
}
</script>
