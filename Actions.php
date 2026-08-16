<?php
session_start();
require_once('DBConnection.php');

Class Actions extends DBConnection {

    function __construct(){ parent::__construct(); }
    function __destruct()  { parent::__destruct(); }

    // ── Helper: write to activity_log ────────────────────────
    private function log_activity($action, $description){
        // Wrapped in try-catch — logging must never break core operations
        try {
            $user_id  = isset($_SESSION['user_id'])  ? (int)$_SESSION['user_id']  : null;
            $fullname = isset($_SESSION['fullname']) ? $this->db->real_escape_string($_SESSION['fullname']) : 'System';
            $action   = $this->db->real_escape_string($action);
            $desc     = $this->db->real_escape_string($description);
            $ip       = $this->db->real_escape_string($_SERVER['REMOTE_ADDR'] ?? '');
            $uid_sql  = $user_id ? "'$user_id'" : "NULL";
            @$this->db->query(
                "INSERT INTO `activity_log` (`user_id`,`fullname`,`action`,`description`,`ip_address`)
                 VALUES ($uid_sql,'$fullname','$action','$desc','$ip')"
            );
        } catch(Exception $e){ /* silently ignore if table doesn't exist */ }
    }

    // ── Helper: write to login_log ───────────────────────────
    private function log_login($username, $status){
        try {
            $u  = $this->db->real_escape_string($username);
            $s  = ($status === 'success') ? 'success' : 'failed';
            $ip = $this->db->real_escape_string($_SERVER['REMOTE_ADDR'] ?? '');
            $ua = $this->db->real_escape_string($_SERVER['HTTP_USER_AGENT'] ?? '');
            @$this->db->query(
                "INSERT INTO `login_log` (`username`,`status`,`ip_address`,`user_agent`)
                 VALUES ('$u','$s','$ip','$ua')"
            );
        } catch(Exception $e){ /* silently ignore */ }
    }

    // ── Helper: generate unique username from fullname ───────
    private function generate_username($fullname){
        $parts = preg_split('/\s+/', strtolower(trim($fullname)));
        if(count($parts) === 0) return 'user'.time();
        $base = (count($parts) >= 2)
            ? substr($parts[0],0,1) . end($parts)   // jsmith
            : $parts[0];
        // Ensure uniqueness
        $candidate = $base;
        $i = 1;
        while(true){
            $c = $this->db->real_escape_string($candidate);
            $chk = $this->db->query("SELECT COUNT(*) as n FROM user_list WHERE username='$c'")->fetch_array()['n'];
            if($chk == 0) break;
            $candidate = $base . $i++;
        }
        return $candidate;
    }

    // ────────────────────────────────────────────────────────
    function login(){
        $username = $this->db->real_escape_string($_POST['username'] ?? '');
        $password = md5($_POST['password'] ?? '');

        $qry = $this->db->query(
            "SELECT * FROM user_list WHERE username='$username' AND password='$password' LIMIT 1"
        );
        $row = $qry ? $qry->fetch_array() : null;

        if(!$row){
            $this->log_login($username, 'failed');
            $resp['status'] = 'failed';
            $resp['msg']    = 'Invalid username or password.';
        } else {
            $this->log_login($username, 'success');
            $resp['status'] = 'success';
            $resp['msg']    = 'Login successful.';
            foreach($row as $k => $v){
                if(!is_numeric($k)) $_SESSION[$k] = $v;
            }
            // log the successful login
            $fname = $this->db->real_escape_string($row['fullname']);
            $ip    = $this->db->real_escape_string($_SERVER['REMOTE_ADDR'] ?? '');
            $ua    = $this->db->real_escape_string($_SERVER['HTTP_USER_AGENT'] ?? '');
            $uid   = (int)$row['user_id'];
            @$this->db->query(
                "INSERT INTO `activity_log` (`user_id`,`fullname`,`action`,`description`,`ip_address`)
                 VALUES ($uid,'$fname','Login','Logged in successfully from IP: $ip','$ip')"
            );
        }
        return json_encode($resp);
    }

    // ────────────────────────────────────────────────────────
    function logout(){
        $this->log_activity('Logout', 'User logged out.');
        session_destroy();
        header("location:./login.php");
        exit;
    }

    // ────────────────────────────────────────────────────────
    function save_user(){
        $id       = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $fullname = trim($_POST['fullname'] ?? '');
        $type     = (int)($_POST['type'] ?? 0);
        $status   = (int)($_POST['status'] ?? 1);
        $password = trim($_POST['password'] ?? '');

        if(empty($fullname)){
            return json_encode(['status'=>'failed','msg'=>'Full name is required.']);
        }

        // Auto-generate username if creating new user and none supplied
        $username = trim($_POST['username'] ?? '');
        if($id === 0 && empty($username)){
            $username = $this->generate_username($fullname);
        }
        $username = $this->db->real_escape_string($username);
        $fullname = $this->db->real_escape_string($fullname);

        // Check username uniqueness
        $dup = $this->db->query(
            "SELECT COUNT(*) as n FROM user_list WHERE username='$username'"
            .($id > 0 ? " AND user_id != $id" : "")
        )->fetch_array()['n'];

        if($dup > 0){
            return json_encode(['status'=>'failed','msg'=>'Username already exists. Try a different one.']);
        }

        if($id === 0){
            // New user — password required
            if(empty($password)){
                return json_encode(['status'=>'failed','msg'=>'Please set a password for the new user.']);
            }
            $pw_hash = md5($password);
            $sql = "INSERT INTO `user_list` (`fullname`,`username`,`password`,`type`,`status`)
                    VALUES ('$fullname','$username','$pw_hash',$type,$status)";
            $action_label = 'Create User';
            $log_desc     = "Created new user: $username ($fullname)";
        } else {
            // Edit — only update password if provided
            $pw_clause = '';
            if(!empty($password)){
                $pw_hash   = md5($password);
                $pw_clause = ", `password`='$pw_hash'";
            }
            $sql = "UPDATE `user_list`
                    SET `fullname`='$fullname', `username`='$username',
                        `type`=$type, `status`=$status $pw_clause
                    WHERE user_id=$id";
            $action_label = 'Update User';
            $log_desc     = "Updated user: $username ($fullname)";
        }

        $save = $this->db->query($sql);
        if($save){
            $this->log_activity($action_label, $log_desc);
            $resp = [
                'status'   => 'success',
                'msg'      => ($id === 0) ? "User '$username' created successfully." : 'User updated successfully.',
                'username' => $username,
            ];
        } else {
            $resp = ['status'=>'failed','msg'=>'Database error: '.$this->db->error];
        }
        return json_encode($resp);
    }

    // ────────────────────────────────────────────────────────
    function delete_user(){
        $id = (int)($_POST['id'] ?? 0);
        $info = $this->db->query("SELECT fullname,username FROM user_list WHERE user_id=$id")->fetch_assoc();
        $delete = $this->db->query("DELETE FROM `user_list` WHERE user_id=$id");
        if($delete){
            $this->log_activity('Delete User', "Deleted user: ".($info['username']??'')." (".($info['fullname']??'').")");
            $_SESSION['flashdata'] = ['type'=>'success','msg'=>'User successfully deleted.'];
            $resp = ['status'=>'success'];
        } else {
            $resp = ['status'=>'failed','error'=>$this->db->error];
        }
        return json_encode($resp);
    }

    // ────────────────────────────────────────────────────────
    function update_credentials(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k => $v){
            if(!in_array($k,['id','old_password']) && !empty($v)){
                if(!empty($data)) $data .= ",";
                if($k == 'password') $v = md5($v);
                $data .= " `{$k}` = '".addslashes($v)."' ";
            }
        }
        if(!empty($password) && md5($old_password) != $_SESSION['password']){
            return json_encode(['status'=>'failed','msg'=>'Old password is incorrect.']);
        }
        $sql  = "UPDATE `user_list` SET $data WHERE user_id='".(int)$_SESSION['user_id']."'";
        $save = $this->db->query($sql);
        if($save){
            $this->log_activity('Update Credentials', 'User updated their own credentials.');
            $_SESSION['flashdata'] = ['type'=>'success','msg'=>'Credentials updated successfully.'];
            foreach($_POST as $k => $v){
                if(!in_array($k,['id','old_password']) && !empty($v)){
                    if($k == 'password') $v = md5($v);
                    $_SESSION[$k] = $v;
                }
            }
            $resp = ['status'=>'success'];
        } else {
            $resp = ['status'=>'failed','msg'=>'Update failed: '.$this->db->error];
        }
        return json_encode($resp);
    }

    // ────────────────────────────────────────────────────────
    function save_category(){
        extract($_POST);
        $data = ""; $cols = []; $vals = [];
        foreach($_POST as $k => $v){
            if($k === 'id') continue;
            $v = addslashes(trim($v));
            if(empty($id)){ $cols[] = "`{$k}`"; $vals[] = "'{$v}'"; }
            else { if(!empty($data)) $data .= ", "; $data .= "`{$k}`='{$v}'"; }
        }
        $cols_j = implode(',',$cols); $vals_j = implode(',',$vals);
        $sql = empty($id)
            ? "INSERT INTO `category_list` ($cols_j) VALUES ($vals_j)"
            : "UPDATE `category_list` SET $data WHERE category_id='".addslashes($id)."'";
        $dup = $this->db->query(
            "SELECT COUNT(*) as n FROM category_list WHERE name='".addslashes($name)."'"
            .($id > 0 ? " AND category_id != '".addslashes($id)."'" : "")
        )->fetch_array()['n'];
        if($dup > 0) return json_encode(['status'=>'failed','msg'=>'Category already exists.']);
        $save = $this->db->query($sql);
        if($save){
            $label = empty($id) ? 'Create Category' : 'Update Category';
            $this->log_activity($label, ($label).": ".addslashes($name));
            $resp = ['status'=>'success','msg'=>empty($id)?'Category saved.':'Category updated.'];
        } else {
            $resp = ['status'=>'failed','msg'=>'Error: '.$this->db->error];
        }
        return json_encode($resp);
    }

    // ────────────────────────────────────────────────────────
    function delete_category(){
        $id = addslashes($_POST['id'] ?? '');
        $update = $this->db->query("UPDATE `category_list` SET `delete_flag`=1 WHERE category_id='$id'");
        if($update){
            $this->log_activity('Delete Category', "Soft-deleted category ID: $id");
            $_SESSION['flashdata'] = ['type'=>'success','msg'=>'Category deleted.'];
            $resp = ['status'=>'success'];
        } else {
            $resp = ['status'=>'failed','error'=>$this->db->error];
        }
        return json_encode($resp);
    }

    // ────────────────────────────────────────────────────────
    function save_product(){
        $id           = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $product_code = $this->db->real_escape_string(trim($_POST['product_code'] ?? ''));
        $category_id  = (int)($_POST['category_id'] ?? 0);
        $name         = $this->db->real_escape_string(trim($_POST['name'] ?? ''));
        $description  = $this->db->real_escape_string(trim($_POST['description'] ?? ''));
        $price        = (float)($_POST['price'] ?? 0);
        $alert_restock= (float)($_POST['alert_restock'] ?? 0);
        $status       = (int)($_POST['status'] ?? 1);
        $image_url    = $this->db->real_escape_string(trim($_POST['image_url'] ?? ''));

        if(empty($product_code) || empty($name) || $category_id === 0){
            return json_encode(['status'=>'failed','msg'=>'Product code, category, and name are required.']);
        }

        // Duplicate checks
        $dup1 = $this->db->query(
            "SELECT COUNT(*) as n FROM product_list WHERE product_code='$product_code' AND delete_flag=0"
            .($id > 0 ? " AND product_id != $id" : "")
        )->fetch_array()['n'];
        $dup2 = $this->db->query(
            "SELECT COUNT(*) as n FROM product_list WHERE name='$name' AND delete_flag=0"
            .($id > 0 ? " AND product_id != $id" : "")
        )->fetch_array()['n'];

        if($dup1 > 0) return json_encode(['status'=>'failed','msg'=>'Product code already exists.']);
        if($dup2 > 0) return json_encode(['status'=>'failed','msg'=>'Product name already exists.']);

        if($id === 0){
            $sql = "INSERT INTO `product_list`
                        (`product_code`,`category_id`,`name`,`description`,`price`,`alert_restock`,`status`,`image_url`)
                    VALUES
                        ('$product_code',$category_id,'$name','$description',$price,$alert_restock,$status,'$image_url')";
        } else {
            $sql = "UPDATE `product_list` SET
                        `product_code`  = '$product_code',
                        `category_id`   = $category_id,
                        `name`          = '$name',
                        `description`   = '$description',
                        `price`         = $price,
                        `alert_restock` = $alert_restock,
                        `status`        = $status,
                        `image_url`     = '$image_url'
                    WHERE `product_id`  = $id";
        }

        $save = $this->db->query($sql);
        if($save){
            $label = ($id === 0) ? 'Create Product' : 'Update Product';
            @$this->log_activity($label, "$label: $product_code – $name");
            $resp = ['status'=>'success', 'msg'=> ($id===0) ? "Product '$name' saved successfully." : "Product '$name' updated successfully."];
        } else {
            $resp = ['status'=>'failed', 'msg'=>'Database error: '.$this->db->error];
        }
        return json_encode($resp);
    }

    // ────────────────────────────────────────────────────────
    function delete_product(){
        $id   = addslashes($_POST['id'] ?? '');
        $info = $this->db->query("SELECT name,product_code FROM product_list WHERE product_id='$id'")->fetch_assoc();
        $upd  = $this->db->query("UPDATE `product_list` SET delete_flag=1 WHERE product_id='$id'");
        if($upd){
            $this->log_activity('Delete Product', "Deleted product: ".($info['product_code']??'')." - ".($info['name']??''));
            $_SESSION['flashdata'] = ['type'=>'success','msg'=>'Product deleted.'];
            $resp = ['status'=>'success'];
        } else {
            $resp = ['status'=>'failed','error'=>$this->db->error];
        }
        return json_encode($resp);
    }

    // ────────────────────────────────────────────────────────
    function save_stock(){
        extract($_POST);
        $data = ""; $cols = []; $vals = [];
        foreach($_POST as $k => $v){
            if($k === 'id') continue;
            $v = addslashes(trim($v));
            if(empty($id)){ $cols[] = "`{$k}`"; $vals[] = "'{$v}'"; }
            else { if(!empty($data)) $data .= ", "; $data .= "`{$k}`='{$v}'"; }
        }
        $cols_j = implode(',',$cols); $vals_j = implode(',',$vals);
        $sql = empty($id)
            ? "INSERT INTO `stock_list` ($cols_j) VALUES ($vals_j)"
            : "UPDATE `stock_list` SET $data WHERE stock_id='".addslashes($id)."'";
        $save = $this->db->query($sql);
        if($save){
            $this->log_activity(empty($id)?'Add Stock':'Update Stock',
                "Stock ".( empty($id)?'added':'updated')." for product_id: ".addslashes($product_id ?? ''));
            $resp = ['status'=>'success','msg'=>empty($id)?'Stock saved.':'Stock updated.'];
        } else {
            $resp = ['status'=>'failed','msg'=>'Error: '.$this->db->error,'error'=>$this->db->error];
        }
        return json_encode($resp);
    }

    // ────────────────────────────────────────────────────────
    function delete_stock(){
        $id  = addslashes($_POST['id'] ?? '');
        $del = $this->db->query("DELETE FROM `stock_list` WHERE stock_id='$id'");
        if($del){
            $this->log_activity('Delete Stock', "Deleted stock ID: $id");
            $_SESSION['flashdata'] = ['type'=>'success','msg'=>'Stock deleted.'];
            $resp = ['status'=>'success'];
        } else {
            $resp = ['status'=>'failed','error'=>$this->db->error];
        }
        return json_encode($resp);
    }

    // ────────────────────────────────────────────────────────
    function save_transaction(){
        extract($_POST);
        $data = ""; $cols = []; $vals = [];
        $receipt_no = time();
        $i = 0;
        while(true){
            $i++;
            $chk = $this->db->query("SELECT COUNT(*) as n FROM transaction_list WHERE receipt_no='$receipt_no'")->fetch_array()['n'];
            if($chk > 0) $receipt_no = time().$i; else break;
        }
        $_POST['receipt_no'] = $receipt_no;
        $_POST['user_id']    = $_SESSION['user_id'];
        foreach($_POST as $k => $v){
            if($k === 'id' || is_array($_POST[$k])) continue;
            $v = addslashes(trim($v));
            if(empty($id)){ $cols[] = "`{$k}`"; $vals[] = "'{$v}'"; }
            else { if(!empty($data)) $data .= ", "; $data .= "`{$k}`='{$v}'"; }
        }
        $cols_j = implode(',',$cols); $vals_j = implode(',',$vals);
        $sql = empty($id)
            ? "INSERT INTO `transaction_list` ($cols_j) VALUES ($vals_j)"
            : "UPDATE `transaction_list` SET $data WHERE transaction_id='".addslashes($id)."'";
        $save = $this->db->query($sql);
        if($save){
            $last_id = $this->db->insert_id;
            $tid = empty($id) ? $last_id : $id;
            $items_data = "";
            foreach($product_id as $k => $v){
                if(!empty($items_data)) $items_data .= ",";
                $items_data .= "('$tid','".addslashes($v)."','".addslashes($quantity[$k])."','".addslashes($price[$k])."')";
            }
            if(!empty($items_data)){
                $this->db->query("DELETE FROM transaction_items WHERE transaction_id='$tid'");
                $this->db->query("INSERT INTO transaction_items (transaction_id,product_id,quantity,price) VALUES $items_data");
            }
            $this->log_activity(empty($id)?'New Sale':'Update Sale',
                "Transaction #$receipt_no — Total: ".addslashes($total ?? 0));
            $_SESSION['flashdata'] = ['type'=>'success','msg'=>empty($id)?'Transaction saved.':'Transaction updated.'];
            $resp = ['status'=>'success','transaction_id'=>$tid];
        } else {
            $resp = ['status'=>'failed','msg'=>'Error: '.$this->db->error];
        }
        return json_encode($resp);
    }

    // ────────────────────────────────────────────────────────
    function delete_transaction(){
        $id  = addslashes($_POST['id'] ?? '');
        $del = $this->db->query("DELETE FROM `transaction_list` WHERE transaction_id='$id'");
        if($del){
            $this->log_activity('Delete Transaction', "Deleted transaction ID: $id");
            $_SESSION['flashdata'] = ['type'=>'success','msg'=>'Transaction deleted.'];
            $resp = ['status'=>'success'];
        } else {
            $resp = ['status'=>'failed','error'=>$this->db->error];
        }
        return json_encode($resp);
    }

    // ────────────────────────────────────────────────────────
    // AJAX: get per-user activity log (HTML snippet)
    function get_user_log(){
        $uid = (int)($_GET['uid'] ?? 0);
        $qry = $this->db->query(
            "SELECT * FROM activity_log WHERE user_id=$uid ORDER BY date_created DESC LIMIT 200"
        );
        ob_start();
        if(!$qry || $qry->num_rows === 0){
            echo '<div class="text-center py-4 text-muted p-3">No activity recorded for this user.</div>';
        } else {
            echo '<div class="table-responsive"><table class="table table-sm table-striped mb-0" style="font-size:.82rem">';
            echo '<thead style="background:#f9fafb"><tr>
                    <th class="px-2 py-1 text-center">#</th>
                    <th class="px-2 py-1 text-center">Action</th>
                    <th class="px-2 py-1">Description</th>
                    <th class="px-2 py-1 text-center">IP</th>
                    <th class="px-2 py-1 text-center">Date &amp; Time</th>
                  </tr></thead><tbody>';
            $ai = 1;
            $action_colors = ['login'=>'success','logout'=>'secondary','create'=>'primary',
                              'update'=>'warning','delete'=>'danger','add'=>'primary','new'=>'primary'];
            while($r = $qry->fetch_assoc()){
                $ac = strtolower(explode(' ',$r['action'])[0] ?? '');
                $bc = $action_colors[$ac] ?? 'dark';
                echo '<tr>';
                echo '<td class="text-center px-2 py-1">'.($ai++).'</td>';
                echo '<td class="text-center px-2 py-1"><span class="badge bg-'.$bc.' px-2" style="font-size:.7rem">'.htmlspecialchars($r['action']).'</span></td>';
                echo '<td class="px-2 py-1">'.htmlspecialchars($r['description']).'</td>';
                echo '<td class="text-center px-2 py-1"><code style="font-size:.72rem">'.htmlspecialchars($r['ip_address']??'—').'</code></td>';
                echo '<td class="text-center px-2 py-1" style="white-space:nowrap;color:#6b7280">'.date('M d, Y h:i A', strtotime($r['date_created'])).'</td>';
                echo '</tr>';
            }
            echo '</tbody></table></div>';
        }
        return ob_get_clean();
    }

    // ────────────────────────────────────────────────────────
    // AJAX: return next available product code for a category
    function next_product_code(){
        $cat_id = (int)($_GET['cat_id'] ?? 0);
        if(!$cat_id) return json_encode(['code'=>null]);

        // Get category name to derive prefix
        $cat = $this->db->query("SELECT name FROM category_list WHERE category_id=$cat_id LIMIT 1")->fetch_assoc();
        if(!$cat) return json_encode(['code'=>null]);

        $known = [
            'Breads'       => 'BRD',
            'Pastries'     => 'PST',
            'Cakes'        => 'CAK',
            'Cookies'      => 'COK',
            'Beverages'    => 'BEV',
            'Sandwiches'   => 'SND',
            'Muffins'      => 'MUF',
            'Donuts'       => 'DNT',
            'Pies & Tarts' => 'PIE',
            'Specialty'    => 'SPC',
        ];
        $prefix = $known[$cat['name']]
            ?? strtoupper(substr(preg_replace('/[^A-Za-z]/','',$cat['name']),0,3));

        // Find highest existing number for this prefix
        $esc    = $this->db->real_escape_string($prefix);
        $result = $this->db->query(
            "SELECT product_code FROM product_list
             WHERE product_code LIKE '{$esc}-%' AND delete_flag=0
             ORDER BY product_code DESC"
        );
        $max = 0;
        while($r = $result->fetch_assoc()){
            // Extract the numeric part after the dash
            if(preg_match('/^'.preg_quote($prefix,'/').'\\-(\\d+)$/i', $r['product_code'], $m)){
                $max = max($max, (int)$m[1]);
            }
        }
        $next_num  = $max + 1;
        $next_code = $prefix . '-' . str_pad($next_num, 3, '0', STR_PAD_LEFT);

        // Ensure it doesn't already exist (collision guard)
        $safe = $this->db->real_escape_string($next_code);
        $exists = $this->db->query(
            "SELECT COUNT(*) as n FROM product_list WHERE product_code='$safe' AND delete_flag=0"
        )->fetch_array()['n'];
        while($exists > 0){
            $next_num++;
            $next_code = $prefix . '-' . str_pad($next_num, 3, '0', STR_PAD_LEFT);
            $safe = $this->db->real_escape_string($next_code);
            $exists = $this->db->query(
                "SELECT COUNT(*) as n FROM product_list WHERE product_code='$safe' AND delete_flag=0"
            )->fetch_array()['n'];
        }
        return json_encode(['code' => $next_code, 'prefix' => $prefix]);
    }

    // ────────────────────────────────────────────────────────
    function clear_activity_log(){
        $del = $this->db->query("DELETE FROM `activity_log`");
        return json_encode($del ? ['status'=>'success'] : ['status'=>'failed','msg'=>$this->db->error]);
    }

    function clear_login_log(){
        $del = $this->db->query("DELETE FROM `login_log`");
        return json_encode($del ? ['status'=>'success'] : ['status'=>'failed','msg'=>$this->db->error]);
    }
}

// ── Route ────────────────────────────────────────────────────
$a      = $_GET['a'] ?? '';
$action = new Actions();
switch($a){
    case 'login':               echo $action->login();               break;
    case 'logout':              $action->logout();                   break;
    case 'save_user':           echo $action->save_user();           break;
    case 'delete_user':         echo $action->delete_user();         break;
    case 'update_credentials':  echo $action->update_credentials();  break;
    case 'save_category':       echo $action->save_category();       break;
    case 'delete_category':     echo $action->delete_category();     break;
    case 'save_product':        echo $action->save_product();        break;
    case 'delete_product':      echo $action->delete_product();      break;
    case 'save_stock':          echo $action->save_stock();          break;
    case 'delete_stock':        echo $action->delete_stock();        break;
    case 'save_transaction':    echo $action->save_transaction();    break;
    case 'delete_transaction':  echo $action->delete_transaction();  break;
    case 'get_user_log':        echo $action->get_user_log();        break;
    case 'clear_activity_log':  echo $action->clear_activity_log();  break;
    case 'clear_login_log':     echo $action->clear_login_log();     break;
    case 'next_product_code':   echo $action->next_product_code();   break;
    default: break;
}
