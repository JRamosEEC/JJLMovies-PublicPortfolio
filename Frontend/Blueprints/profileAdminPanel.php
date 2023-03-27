<div id="itemContainer" class="col-xl-12 no-marginR no-marginL">
    <div class="row">
        <div class="col-3 d-flex align-items-center">
            Admin Panel:
        </div>
        <div class="col-9">
            <div class="row">
                <div class="col-3">
                    <div class="row d-flex justify-content-center">
                        Current User's Role
                    </div>

                    <div id="adminPanelRole" class="row d-flex justify-content-center">
                        (<?php echo $userRole ?>)
                    </div>
                </div>
                
                <?php if($sessionRole == "owner") :?>
                    <div class="col-3 d-flex align-items-center">
                        <select name='userRoleSelect' class="form-control" id="userRoleSelect">
                            <option value="user">user</option>
                            <option value="admin">admin</option>
                        </select>
                    </div>

                    <div class="col-3 d-flex align-items-center">
                        <a id="changeRoleBtn" class="btn btn-primary">Change Role</a>
                    </div>
                <?php else : ?>
                    <div class="col-3 d-flex align-items-center"></div>
                    <div class="col-3 d-flex align-items-center"></div>
                <?php endif; ?>

                <?php if($sessionRole == "owner" || ($sessionRole == "admin" && $userRole == "user")) : ?>
                    <div class="col-auto d-flex align-items-center">
                        <div id="deleteAccount" class="col-12 d-flex justify-content-center">
                            <a id="deleteUserBtn" class="btn btn-primary">Delete User</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>      
        </div>
    </div>
</div>

<script src="/Frontend/JavaScript/AdminPanel/adminFunctions.js"></script>