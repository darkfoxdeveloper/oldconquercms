<?php
function prof($val) {
    $profName = 'Error';
	if ($val>=10 && $val<=15) $profName = 'Trojan';
	if ($val>=20 && $val<=25) $profName = 'War';
	if ($val>=40 && $val<=45) $profName = 'Archer';
	if ($val>=55 && $val<=55) $profName = 'Ninja';
	if ($val>=60 && $val<=66) $profName = 'Monk';
    if ($val>=70 && $val<=75) $profName = 'Pirate';
    if ($val>=80 && $val<=85) $profName = 'BruceLee';
	if ($val>=100 && $val<=101)  $profName = 'Taoist';
	if ($val>=132 && $val<=135) $profName = 'WaterTaoist';
	if ($val>=140 && $val<=145) $profName = 'FireTaoist';
	if ($val>=160 && $val<=165) $profName = 'Windwalker';
	return $profName;
}
function noble($val,$cash,$gender = null) { 
    $kingRankImg = '<img src="'.APP_PATH.'resources/images/king.png">';
    if ($gender == "3" || $gender == "5") {
        $kingRankImg = '<img src="'.APP_PATH.'resources/images/queen.png">';
    }
    if ($val<4) return $kingRankImg;
    if ($val<16) return '<img src="'.APP_PATH.'resources/images/duke.png">';
    if ($val<51)  return '<img src="'.APP_PATH.'resources/images/marquess.png">';

    if ($cash>200000000)  return '<img src="'.APP_PATH.'resources/images/earl.png">';
    if ($cash>100000000)  return '<img src="'.APP_PATH.'resources/images/viscount.png">';
    if ($cash>30000000)  return '<img src="'.APP_PATH.'resources/images/lord.png">';
    return 'Error'; 
}
function pk($val) {  
    if ($val>=1000) return '<font color="black"><b><u>'.$val.'</u></b></font>';
    if ($val>=100) return '<font color="black"><b>'.$val.'</b></font>'; 
    if ($val>=30 && $val<100)  return '<font color="red">'.$val.'</font>';
    return $val;
}
function loginOCCMS($username, $password) {
    global $conn;
    $loginOK = false;
    $sql = "SELECT id FROM openconquercms_users WHERE username='".$username."' AND password='".md5($password)."'";
    if ($q = mysqli_query($conn, $sql)) {
        if (mysqli_num_rows($q) >= 1) $loginOK = true;
    }
    return $loginOK;
}
function getNewsContentTable() {
    global $conn;
    $html = '
    <div class="row"><div class="col-12 mt-2 mb-2 text-right"><a href="openconquercms_panel.php?section=create_new"><button class="btn btn-primary"><i data-toggle="tooltip" title="Create" class="fa fa-plus text-light" aria-hidden="true"></i></button></a></div></div>
    <table id="table-occms-news" class="display datatable" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>User</th>
                <th>Publish Date</th>
                <th>Actions</th>
            </tr>
        </thead>
    <tbody>
    ';
    $sql = "SELECT * FROM openconquercms_news";
    $q = mysqli_query($conn, $sql);
    if ($q) {
            while ($object = mysqli_fetch_object($q)) {
                $html .= '
                <tr>
                    <td>'.$object->id.'</td>
                    <td>'.$object->name.'</td>
                    <td>'.getUserByID($object->user)->username.'</td>
                    <td>'.$object->publish_date.'</td>
                    <td>
                        <a href="openconquercms_panel.php?section=edit_new&id='.$object->id.'"><i data-toggle="tooltip" title="Edit" class="fa fa-pencil" aria-hidden="true"></i></a>
                        <a class="alert-confirm" data-new-id="'.$object->id.'" href="#delete_new"><i data-toggle="tooltip" title="Delete" class="fa fa-remove" aria-hidden="true"></i></a>
                    </td>
                </tr>
                ';
            }
            mysqli_free_result($q);
    }
    $html .= '
        </tbody>
    </table>';
    return $html;
}
function getNews() {
    global $conn, $existConfig;
    $news_objs = array();
    if ($existConfig) {
         $sql = "SELECT * FROM openconquercms_news";
        $q = mysqli_query($conn, $sql);
        if ($q) {
            while($new_obj = mysqli_fetch_object($q)) {
                $news_objs[] = $new_obj;
            }
        }
    }
    return $news_objs;
}
function getNewByID($id = null) {
    global $conn;
    $new_obj = null;
    if ($id == null) {
        $id = $_GET['id'];
    }
    $sql = "SELECT * FROM openconquercms_news WHERE id='".mysqli_real_escape_string($conn, $id)."'";
    $q = mysqli_query($conn, $sql);
    if ($q) {
        $new_obj = mysqli_fetch_object($q);
    }
    return $new_obj;
}
function getUserByID($id) {
    global $conn;
    $user_obj = null;
    $sql = "SELECT * FROM openconquercms_users WHERE id='".mysqli_real_escape_string($conn, $id)."'";
    $q = mysqli_query($conn, $sql);
    if ($q) {
        $user_obj = mysqli_fetch_object($q);
    }
    return $user_obj;
}
function getUsers() {
    global $conn;
    $user_objs = array();
    $sql = "SELECT * FROM openconquercms_users";
    $q = mysqli_query($conn, $sql);
    if ($q) {
        while($user_obj = mysqli_fetch_object($q)) {
            $user_objs[] = $user_obj;
        }
    }
    return $user_objs;
}
function editNew($id, $name, $description) {
    global $conn;
    $created = false;
    $sql = "UPDATE openconquercms_news SET name='".$name."', description='".$description."' WHERE id='".$id."'";
    $q = mysqli_query($conn, $sql);
    if ($q) {
        $created = true;
    }
    return $created;
}
function createNew($name, $description, $user) {
    global $conn;
    $created = false;
    $sql = "INSERT INTO openconquercms_news(name, description, user) VALUES('".$name."','".$description."','".$user."')";
    $q = mysqli_query($conn, $sql);
    if ($q) {
        $created = true;
    }
    return $created;
}
function deleteNew() {
    global $conn;
    $sql = "DELETE FROM openconquercms_news WHERE id=''";
}
function getSettingsContentTable() {
    global $conn;
    $html = '
    <div class="row"><div class="col-12 mt-2 mb-2 text-right"><a href="openconquercms_panel.php?section=create_setting"><button class="btn btn-primary"><i data-toggle="tooltip" title="Create" class="fa fa-plus text-light" aria-hidden="true"></i></button></a></div></div>
    <table id="table-occms-settings" class="display datatable" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Key</th>
                <th>Actions</th>
            </tr>
        </thead>
    <tbody>
    ';
    $config = getSettings();
    if ($config) {
            foreach ($config as $config_obj_key => $config_obj) {
                $html .= '
                <tr>
                    <td>'.$config_obj->id.'</td>
                    <td>'.$config_obj->key.'</td>
                    <td><a href="openconquercms_panel.php?section=edit_setting&id='.$config_obj->id.'"><i data-toggle="tooltip" title="Edit" class="fa fa-pencil" aria-hidden="true"></i></a></td>
                </tr>
                ';
            }
    }
    $html .= '
        </tbody>
    </table>';
    return $html;
}
function getLangContentTable() {
    global $conn;
    $html = '
    <div class="row"><div class="col-12 mt-2 mb-2 text-right"><a href="openconquercms_panel.php?section=create_lang"><button class="btn btn-primary"><i data-toggle="tooltip" title="Create" class="fa fa-plus text-light" aria-hidden="true"></i></button></a></div></div>
    <table id="table-occms-lang" class="display datatable" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Key</th>
                <th>Value</th>
                <th>Lang</th>
                <th>Actions</th>
            </tr>
        </thead>
    <tbody>
    ';
    $sql = "SELECT * FROM openconquercms_lang";
    $q = mysqli_query($conn, $sql);
    if ($q) {
            while ($object = mysqli_fetch_object($q)) {
                $html .= '
                <tr>
                    <td>'.$object->id.'</td>
                    <td>'.$object->key.'</td>
                    <td>'.$object->value.'</td>
                    <td>'.$object->lang.'</td>
                    <td>
                        <a href="openconquercms_panel.php?section=edit_lang&id='.$object->id.'"><i data-toggle="tooltip" title="Edit" class="fa fa-pencil" aria-hidden="true"></i></a>
                        <a class="alert-confirm" data-lang-id="'.$object->id.'" href="#delete_lang"><i data-toggle="tooltip" title="Delete" class="fa fa-remove" aria-hidden="true"></i></a>
                    </td>
                </tr>
                ';
            }
            mysqli_free_result($q);
    }
    $html .= '
        </tbody>
    </table>';
    return $html;
}
function getMenuContentTable() {
    global $conn;
    $html = '
    <div class="row"><div class="col-12 mt-2 mb-2 text-right"><a href="openconquercms_panel.php?section=create_item_menu"><button class="btn btn-primary"><i data-toggle="tooltip" title="Create" class="fa fa-plus text-light" aria-hidden="true"></i></button></a></div></div>
    <table id="table-occms-item-menu" class="display datatable" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Link</th>
                <th>Sort Order</th>
                <th>Actions</th>
            </tr>
        </thead>
    <tbody>
    ';
    $sql = "SELECT * FROM openconquercms_menu";
    $q = mysqli_query($conn, $sql);
    if ($q) {
            while ($object = mysqli_fetch_object($q)) {
                $html .= '
                <tr>
                    <td>'.$object->id.'</td>
                    <td>'.$object->name.'</td>
                    <td>'.$object->link.'</td>
                    <td>'.$object->sort_order.'</td>
                    <td>
                        <a href="openconquercms_panel.php?section=edit_item_menu&id='.$object->id.'"><i data-toggle="tooltip" title="Edit" class="fa fa-pencil" aria-hidden="true"></i></a>
                        <a class="alert-confirm" data-item-menu-id="'.$object->id.'" href="#delete_item_menu"><i data-toggle="tooltip" title="Delete" class="fa fa-remove" aria-hidden="true"></i></a>
                    </td>
                </tr>
                ';
            }
            mysqli_free_result($q);
    }
    $html .= '
        </tbody>
    </table>';
    return $html;
}
function getOCCMSPanel($section = null) {
    $html = '';
    if (!isset($_SESSION["occms_user"])) {
            $html = '
            <p>Enter with your admin account of OpenConquerCMS for enter in this section.</p>
            <div class="mx-auto col-6">
                <form name="login-cms-panel" method="POST" action="">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" name="username" id="username" placeholder="Username">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                    </div>
                    <div class="form-group text-right">
                        <input type="hidden" name="occms_login" value="1">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
            </div>
            ';
    } else {
        if ($section == null) {   
            $html = '
            <div class="row p-2">
                <div class="col-4"><a href="openconquercms_panel.php?section=list_news"><button type="button" class="btn btn-primary w-100"><i class="fa fa-newspaper-o" aria-hidden="true"></i>&nbsp;News Manager</button></a></div>
                <div class="col-4"><a href="openconquercms_panel.php?section=list_settings"><button type="button" class="btn btn-primary w-100"><i class="fa fa-wrench" aria-hidden="true"></i>&nbsp;Settings Manager</button></a></div>
                <div class="col-4"><a href="openconquercms_panel.php?section=list_lang"><button type="button" class="btn btn-primary w-100"><i class="fa fa-wrench" aria-hidden="true"></i>&nbsp;Lang Manager</button></a></div>
                <div class="col-4 mt-2"><a href="openconquercms_panel.php?section=list_item_menu"><button type="button" class="btn btn-primary w-100"><i class="fa fa-wrench" aria-hidden="true"></i>&nbsp;Menu Manager</button></a></div>
            </div>
            ';
        } else {
            switch($section) {
                 case "create_item_menu": {
                    $itemsMenu = getItemMenu();
                    $itemsMenuHTML = '';
                    for($i = 0; $i < count($itemsMenu); $i++) {
                        $itemsMenuHTML .= '<option value="'.$itemsMenu[$i]->id.'">'.$itemsMenu[$i]->name.'</option>';
                    }
                    $html = '
                    <div class="mx-auto col-12">
                        <form name="create-item-menu-cms-panel" method="POST" action="" lang="'.LANG.'">
                            <div class="mx-auto col-6">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="" required>
                                </div>
                            </div>
                            <div class="mx-auto col-6">
                                <div class="form-group">
                                    <label for="link">Link</label>
                                    <input type="text" class="form-control" name="link" id="link" placeholder="Link" value="">
                                </div>
                                <div class="form-group">
                                    <label for="sort-order">Sort Order</label>
                                    <input type="number" class="form-control" name="sort-order" id="sort-order" min="1" max="999" placeholder="Sort Order" value="" required>
                                </div>
                            </div>
                            <div class="mx-auto col-6">
                                <label for="parent-item">Parent Item</label>
                                <select id="parent-item" name="parent-item" class="form-control superselect" data-width="100%" style="width: 100% !important;height: 50px !important;" required>
                                    <option value="0">No Parent</option>
                                    '.$itemsMenuHTML.'
                                </select>
                            </div>
                            <div class="mx-auto col-6 text-right mt-1 mb-1">
                                <label class="custom-control custom-checkbox mb-2 mr-sm-2 mb-sm-0">
                                    <input type="checkbox" class="custom-control-input" name="visible" checked>
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Visible in Menu</span>
                                </label>
                            </div>
                            <div class="mx-auto col-6">
                                 <div class="form-group text-right">
                                    <input type="hidden" name="occms_create_item_menu" value="1">
                                    <a href="openconquercms_panel.php?section=list_item_menu"><button type="button" class="btn btn-info">Back</button></a>
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    ';
                    break;
                }
                case "edit_item_menu": {
                    $visibleChecked = '';
                    $item_menu_obj = getItemMenuByID();
                    $itemsMenu = getItemMenu();
                    $itemsMenuHTML = '';
                    if ($item_menu_obj->visible) {
                        $visibleChecked = ' checked';
                    }
                    for($i = 0; $i < count($itemsMenu); $i++) {
                        $selectedHTML = ' ';
                        if ($itemsMenu[$i]->id == $item_menu_obj->parent_id) {
                            $selectedHTML = ' selected ';
                        }
                        $itemsMenuHTML .= '<option '.$selectedHTML.'value="'.$itemsMenu[$i]->id.'">'.$itemsMenu[$i]->name.'</option>';
                    }
                    $html = '
                    <div class="mx-auto col-12">
                        <form name="edit-item-menu-cms-panel" method="POST" action="" lang="'.LANG.'">
                            <div class="mx-auto col-6">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="'.$item_menu_obj->name.'" required>
                                </div>
                            </div>
                            <div class="mx-auto col-6">
                                <div class="form-group">
                                    <label for="link">Link</label>
                                    <input type="text" class="form-control" name="link" id="link" placeholder="Link" value="'.$item_menu_obj->link.'" required>
                                </div>
                                <div class="form-group">
                                    <label for="sort-order">Sort Order</label>
                                    <input type="number" class="form-control" name="sort-order" id="sort-order" min="1" max="999" placeholder="Sort Order" value="'.$item_menu_obj->sort_order.'" required>
                                </div>
                            </div>
                             <div class="mx-auto col-6">
                                <label for="parent-item">Parent Item</label>
                                <select id="parent-item" name="parent-item" class="form-control superselect" data-width="100%" style="width: 100% !important;height: 50px !important;" required>
                                    <option value="0">No Parent</option>
                                    '.$itemsMenuHTML.'
                                </select>
                            </div>
                            <div class="mx-auto col-6 text-right mt-1 mb-1">
                                <label class="custom-control custom-checkbox mb-2 mr-sm-2 mb-sm-0">
                                    <input type="checkbox" class="custom-control-input" name="visible"'.$visibleChecked.'>
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Visible in Menu</span>
                                </label>
                            </div>
                             <div class="mx-auto col-6">
                                 <div class="form-group text-right">
                                    <input type="hidden" name="occms_edit_item_menu" value="'.$item_menu_obj->id.'">
                                    <a href="openconquercms_panel.php?section=list_item_menu"><button type="button" class="btn btn-info">Back</button></a>
                                    <button type="submit" class="btn btn-primary">Edit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    ';
                    break;
                }
                case "list_item_menu": {
                    $html = '
                    <div class="col-12 bg-white rounded p-1 text-dark">
                        <h4 class="text-dark">Menu Manager</h4>
                        '.getMenuContentTable().'
                    </div>';
                    break;
                }
                case "create_lang": {
                    $file = file_get_contents("resources/json/languages.json");
                    $json = json_decode($file);
                    $lang_codes_html = '';
                    foreach ($json as $key => $obj) {
                        $selected = '';
                        if ($obj->code == LANG) {
                            $selected = 'selected="selected"';
                        }
                        $lang_codes_html .= '<option '.$selected.' value="'.$obj->code.'">'.$obj->name.'</option>';
                    }
                    $html = '
                    <div class="mx-auto col-12">
                        <form name="create-lang-cms-panel" method="POST" action="" lang="'.LANG.'">
                            <div class="mx-auto col-6">
                                <div class="form-group">
                                    <label for="key">Key</label>
                                    <input type="text" class="form-control text-info" name="key" id="key" placeholder="Key" value="">
                                </div>
                            </div>
                            <div class="mx-auto col-6">
                                <div class="form-group">
                                    <label for="value">Value</label>
                                    <input type="text" class="form-control" name="value" id="value" placeholder="Value" value="">
                                </div>
                                <div class="form-group">
                                    <label for="lang">Lang</label>
                                    <select class="form-control superselect" name="lang" id="lang" placeholder="Lang Code" data-width="100%">
                                        '.$lang_codes_html.'
                                    </select>
                                </div>
                                 <div class="form-group text-right">
                                    <input type="hidden" name="occms_create_lang" value="1">
                                    <a href="openconquercms_panel.php?section=list_lang"><button type="button" class="btn btn-info">Back</button></a>
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    ';
                    break;
                }
                case "edit_lang": {
                    $lang_obj = getLangByID();
                    $file = file_get_contents("resources/json/languages.json");
                    $json = json_decode($file);
                    $lang_codes_html = '';
                    foreach ($json as $key => $obj) {
                        $selected = '';
                        if ($lang_obj->lang == $obj->code) {
                            $selected = 'selected="selected"';
                        }
                        $lang_codes_html .= '<option '.$selected.' value="'.$obj->code.'">'.$obj->name.'</option>';
                    }
                    $html = '
                    <div class="mx-auto col-12">
                        <form name="edit-lang-cms-panel" method="POST" action="" lang="'.LANG.'">
                            <div class="mx-auto col-6">
                                <div class="form-group">
                                    <label for="key">Key</label>
                                    <input type="text" class="form-control text-info" name="key" id="key" placeholder="Key" value="'.$lang_obj->key.'" disabled>
                                </div>
                            </div>
                            <div class="mx-auto col-6">
                                <div class="form-group">
                                    <label for="value">Value</label>
                                    <input type="text" class="form-control" name="value" id="value" placeholder="Value" value="'.$lang_obj->value.'">
                                </div>
                                <div class="form-group">
                                    <label for="lang">Lang</label>
                                     <select class="form-control superselect" name="lang" id="lang" placeholder="Lang Code" data-width="100%">
                                        '.$lang_codes_html.'
                                    </select>
                                </div>
                                 <div class="form-group text-right">
                                    <input type="hidden" name="occms_edit_lang" value="'.$lang_obj->id.'">
                                    <a href="openconquercms_panel.php?section=list_lang"><button type="button" class="btn btn-info">Back</button></a>
                                    <button type="submit" class="btn btn-primary">Edit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    ';
                    break;
                }
                case "list_lang": {
                    $html = '
                    <div class="col-12 bg-white rounded p-1 text-dark">
                        <h4 class="text-dark">Lang Manager</h4>
                        '.getLangContentTable().'
                    </div>';
                    break;
                }
                case "list_settings": {
                    $html = '
                    <div class="col-12 bg-white rounded p-1 text-dark">
                        <h4 class="text-dark">Settings Manager</h4>
                        '.getSettingsContentTable().'
                    </div>';
                    break;
                }
                case "create_setting": {
                    $html = '
                    <div class="mx-auto col-12">
                        <form name="create-setting-cms-panel" method="POST" action="" lang="'.LANG.'">
                            <div class="mx-auto col-6">
                                <div class="form-group">
                                    <label for="key">Key</label>
                                    <input type="text" class="form-control text-info" name="key" id="key" placeholder="Key" value="">
                                </div>
                            </div>
                            <div class="mx-auto col-6">
                                <div class="form-group">
                                    <label for="value">Value</label>
                                    <input type="text" class="form-control" name="value" id="value" placeholder="Value" value="">
                                </div>
                                 <div class="form-group text-right">
                                    <input type="hidden" name="occms_create_setting" value="1">
                                    <a href="openconquercms_panel.php?section=list_settings"><button type="button" class="btn btn-info">Back</button></a>
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    ';
                    break;
                }
                case "edit_setting": {
                    $setting_obj = getSettingByID();
                    $valueInput = '<input type="text" class="form-control" name="value" id="value" placeholder="Value" value="'.$setting_obj->value.'">';
                    if ($setting_obj->key == "LANG") {
                        $file = file_get_contents("resources/json/languages.json");
                        $json = json_decode($file);
                        $lang_codes_html = '';
                        foreach ($json as $key => $obj) {
                            $selected = '';
                            if ($obj->code == $setting_obj->value) {
                                $selected = 'selected="selected"';
                            }
                            $lang_codes_html .= '<option '.$selected.' value="'.$obj->code.'">'.$obj->name.'</option>';
                        }
                        $valueInput = '<select data-width="100%" class="form-control superselect" name="value" id="value" placeholder="Value">'.$lang_codes_html.'</select>';
                    }
                    $html = '
                    <div class="mx-auto col-12">
                        <form name="edit-setting-cms-panel" method="POST" action="" lang="'.LANG.'">
                            <div class="mx-auto col-6">
                                <div class="form-group">
                                    <label for="key">Key</label>
                                    <input type="text" class="form-control text-info" name="key" id="key" placeholder="Key" value="'.$setting_obj->key.'" disabled>
                                </div>
                            </div>
                            <div class="mx-auto col-6">
                                <div class="form-group">
                                    <label for="value">Value</label>
                                    '.$valueInput.'
                                </div>
                                 <div class="form-group text-right">
                                    <input type="hidden" name="occms_edit_setting" value="'.$setting_obj->id.'">
                                    <a href="openconquercms_panel.php?section=list_settings"><button type="button" class="btn btn-info">Back</button></a>
                                    <button type="submit" class="btn btn-primary">Edit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    ';
                    break;
                }
                case "list_news": {
                    $html = '
                    <div class="col-12 bg-white rounded p-1 text-dark">
                        <h4 class="text-dark">News Manager</h4>
                        '.getNewsContentTable().'
                    </div>';
                    break;
                }
                case "create_new": {
                    $users = getUsers();
                    $users_options_html = '';
                    foreach ($users as $key => $user_obj) {
                        $users_options_html .= '<option value="'.$user_obj->id.'">'.$user_obj->username.'</option>';
                    }
                    $html = '
                    <div class="mx-auto col-12">
                        <form name="edit-new-cms-panel" method="POST" action="" lang="'.LANG.'">
                            <div class="mx-auto col-6">
                                <div class="form-group">
                                    <label for="username">Name</label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="">
                                </div>
                                <div class="form-group">
                                    <label for="user">User</label>
                                    <select id="user" name="user" class="superselect" data-width="100%">
                                        '.$users_options_html.'
                                    </select>
                                </div>
                            </div>
                            <div class="mx-auto col-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control tinymce-editor" name="description" id="description"></textarea>
                                </div>
                                 <div class="form-group text-right">
                                    <input type="hidden" name="occms_create_new" value="0">
                                    <a href="openconquercms_panel.php?section=list_news"><button type="button" class="btn btn-info">Back</button></a>
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    ';
                    break;
                }
                case "edit_new": {
                    $new_obj = getNewByID();
                    $user_obj = getUserByID($new_obj->user);
                    $users = getUsers();
                    $users_options_html = '';
                    foreach ($users as $key => $user_obj) {
                        $selected = '';
                        if ($user_obj->id == $new_obj->user) {
                            $selected = 'selected="selected"';
                        }
                        $users_options_html .= '<option '.$selected.' value="'.$user_obj->id.'">'.$user_obj->username.'</option>';
                    }
                    $html = '
                    <div class="mx-auto col-12">
                        <form name="edit-new-cms-panel" method="POST" action="" lang="'.LANG.'">
                            <div class="mx-auto col-6">
                                <div class="form-group">
                                    <label for="username">Name</label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="'.$new_obj->name.'">
                                </div>
                                <div class="form-group">
                                    <label for="user">User</label>
                                    <select id="user" name="user" class="superselect" data-width="100%">
                                        '.$users_options_html.'
                                    </select>
                                </div>
                            </div>
                            <div class="mx-auto col-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control tinymce-editor" name="description" id="description">'.$new_obj->description.'</textarea>
                                </div>
                                 <div class="form-group text-right">
                                    <input type="hidden" name="occms_edit_new" value="'.$new_obj->id.'">
                                    <a href="openconquercms_panel.php?section=list_news"><button type="button" class="btn btn-info">Back</button></a>
                                    <button type="submit" class="btn btn-primary">Edit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    ';
                    break;
                }
                default: {
                    $html = '
                    <div class="mx-auto col-12">
                        This section is not available!
                    </div>';
                    break;
                }
            }
        }
    }
    return $html;
}
function generateSuperBox($header, $body, $footer = '', $noprint = false) {
    if (TEMPLATE == "playconquer") {
        $html = '
        <div class="row mb-2">
            <div class="col-12 center-heading">
                '.$header.'
                <span class="center-line d-block"></span>
            </div>
            <div class="col-12">
                '.$body.'
            </div>
            <div class="col-12 text-right">
                '.$footer.'
            </div>
        </div>
        ';
    } else {
        $html = '
        <div class="superbox">
            <div class="superbox-header">
                '.$header.'
            </div>
            <div class="superbox-body">
                '.$body.'
            </div>
            <div class="superbox-footer">
                '.$footer.'
            </div>
        </div>
        ';
    }
    if ($noprint) {
        return $html;
    } else {
        echo $html;
    }
}
function getSettings($key = null) {
    global $conn, $existConfig;
    $settings_objs = array();
    if ($existConfig) {
        $sql = "SELECT * FROM openconquercms_settings";
        $q = mysqli_query($conn, $sql);
        if ($q) {
            while($settings_obj = mysqli_fetch_object($q)) {
                if ($key == null) {
                    $settings_objs[] = $settings_obj;
                } else {
                    if ($settings_obj->key == $key) {
                        $settings_objs = $settings_obj;
                    }
                }
            }
        }
    } else {
        $settings_objs = new stdClass();
        $settings_objs->value = $key;
    }
    return $settings_objs;
}
function getSettingByID($id = null) {
    global $conn;
    $new_obj = null;
    if ($id == null) {
        $id = $_GET['id'];
    }
    $sql = "SELECT * FROM openconquercms_settings WHERE id='".mysqli_real_escape_string($conn, $id)."'";
    $q = mysqli_query($conn, $sql);
    if ($q) {
        $new_obj = mysqli_fetch_object($q);
    }
    return $new_obj;
}
function editSetting($id, $value) {
    global $conn;
    $created = false;
    $sql = "UPDATE openconquercms_settings SET value='".$value."' WHERE id='".$id."'";
    $q = mysqli_query($conn, $sql);
    if ($q) {
        $created = true;
    }
    return $created;
}
function createSetting($key, $value) {
    global $conn;
    $created = false;
    $sql = "INSERT INTO openconquercms_settings(`key`, value) VALUES('".$key."','".$value."')";
    $q = mysqli_query($conn, $sql);
    if ($q) {
        $created = true;
    }
    return $created;
}
function getLang($key = null, $lang = LANG) {
    global $conn, $existConfig;
    $lang_objs = array();
    if ($existConfig) {
        $not_found_key = false;
        $sql = "SELECT * FROM openconquercms_lang";
        if ($key != null) {
            $sql .= " WHERE `key`='".$key."'";
            if ($lang != null) {
                $sql .= " AND lang='".$lang."'";
                $not_found_key = true;
            }
        }
        $q = mysqli_query($conn, $sql);
        if ($q) {
            while($lang_obj = mysqli_fetch_object($q)) {
                if ($key == null) {
                    $lang_objs[] = $lang_obj;
                } else {
                    if ($lang_obj->key == $key) {
                        $lang_objs = $lang_obj;
                        $not_found_key = false;
                    }
                }
            }
        }
        if ($not_found_key) {
            $object = new stdClass();
            $object->value = $key;
            $lang_objs = $object;
        }
    } else {
        $object = new stdClass();
        $object->value = $key;
        $lang_objs = $object;
    }
    return $lang_objs;
}
function getLangByID($id = null) {
    global $conn;
    $lang_obj = null;
    if ($id == null) {
        $id = $_GET['id'];
    }
    $sql = "SELECT * FROM openconquercms_lang WHERE id='".mysqli_real_escape_string($conn, $id)."'";
    $q = mysqli_query($conn, $sql);
    if ($q) {
        $lang_obj = mysqli_fetch_object($q);
    }
    return $lang_obj;
}
function editLang($id, $value, $lang) {
    global $conn;
    $created = false;
    $sql = "UPDATE openconquercms_lang SET value='".$value."', lang='".$lang."' WHERE id='".$id."'";
    $q = mysqli_query($conn, $sql);
    if ($q) {
        $created = true;
    }
    return $created;
}
function createLang($key, $value, $lang) {
    global $conn;
    $created = false;
    $sql = "INSERT INTO openconquercms_lang(`key`, value, lang) VALUES('".$key."','".$value."', '".$lang."')";
    $q = mysqli_query($conn, $sql);
    if ($q) {
        $created = true;
    }
    return $created;
}
function checkPermissionOCCMSPanel() {
    $havePermissions = false;
    if (loginOCCMS($_SESSION["occms_user"]["username"], $_SESSION["occms_user"]["password"])) {
        $havePermissions = true;
    }
    return $havePermissions;
}
function checkExistEntity($uid) {
    global $conn;
    $existEntity = false;
    $q = mysqli_query($conn, "SELECT * FROM entities WHERE UID = '".mysqli_real_escape_string($conn, $uid)."'");
    if(mysqli_num_rows($q)>0) {
        $existEntity = true;
    }
    return $existEntity;
}
function getEntityFromUsername($username) {
    global $conn;
    $q = mysqli_query($conn, "SELECT * FROM entities WHERE Owner = '".$username."'");
    $entity = mysqli_fetch_object($q);
    return $entity;
}
function getBuy() {
    global $conn, $existConfig;
    $buy_objs = array();
    if ($existConfig) {
        $sql = "SELECT * FROM openconquercms_buy";
        $q = mysqli_query($conn, $sql);
        if ($q) {
            while($buy_obj = mysqli_fetch_object($q)) {
                $buy_objs[] = $buy_obj;
            }
        }
    }
    return $buy_objs;
}
function getItemMenu($where = false, $get_childs_item = false) {
    global $conn;
    $item_menu_objs = array();
    $sql = "SELECT * FROM openconquercms_menu";
    if ($where) {
        $sql .= " WHERE ".$where;
    }
    $sql .= " ORDER BY sort_order";
    $q = mysqli_query($conn, $sql);
    if ($q) {
        while($obj = mysqli_fetch_object($q)) {
            if ($get_childs_item) {
                $item_menu_childs_objs = array();
                $sqlChilds = "SELECT * FROM openconquercms_menu WHERE parent_id = '".$obj->id."'";
                $qChilds = mysqli_query($conn, $sqlChilds);
                while($objChild = mysqli_fetch_object($qChilds)) {
                    $item_menu_childs_objs[] = $objChild;
                }
                $obj->childs = $item_menu_childs_objs;
            }
            $item_menu_objs[] = $obj;
        }
    }
    if ($where) {
        $item_menu_objs["current_page"] = basename($_SERVER['PHP_SELF']);
    }
    return $item_menu_objs;
}
function getItemMenuByID($id = null) {
    global $conn;
    $item_menu_obj = null;
    if ($id == null) {
        $id = $_GET['id'];
    }
    $sql = "SELECT * FROM openconquercms_menu WHERE id='".mysqli_real_escape_string($conn, $id)."'";
    $q = mysqli_query($conn, $sql);
    if ($q) {
        $item_menu_obj = mysqli_fetch_object($q);
    }
    return $item_menu_obj;
}
function editItemMenu($id, $name, $link, $sort_order, $parent_id, $visible) {
    global $conn;
    $updated = false;
     if ($visible == "on") {
        $visible = 1;
    } else {
        $visible = 0;
    }
    $sql = "UPDATE openconquercms_menu SET name='".$name."', link='".$link."', sort_order='".$sort_order."', parent_id='".$parent_id."', visible='".$visible."'  WHERE id='".$id."'";
    $q = mysqli_query($conn, $sql);
    if ($q) {
        $updated = true;
    }
    return $updated;
}
function createItemMenu($name, $link, $sort_order, $parent_id, $visible) {
    global $conn;
    $created = false;
    if ($visible == "on") {
        $visible = 1;
    } else {
        $visible = 0;
    }
    $sql = "INSERT INTO openconquercms_menu(name, link, sort_order, parent_id, visible) VALUES('".$name."','".$link."', '".$sort_order."', '".$parent_id."', '".$visible."')";
    $q = mysqli_query($conn, $sql);
    if ($q) {
        $created = true;
    }
    return $created;
}
/* FUNCTIONS FOR TEMPLATES */
function get_index_ranking() {
     global $conn, $existConfig;
     $ranking = array();
     if ($existConfig) {
        $q = mysqli_query($conn, "SELECT * FROM entities ORDER BY Level Desc Limit 5");
        while($player = mysqli_fetch_object($q)){
            $ranking[] = $player;
        }
     }
     return $ranking;
}
function get_full_ranking($type_ranking) {
     global $conn, $existConfig;
     $ranking = array();
     if ($existConfig) {
        $sql = "";
        switch ($type_ranking) {
            case "players":
            {
                $sql = "SELECT * FROM entities WHERE Level>=130 ORDER BY Level DESC LIMIT 20";
                break;
            }
            case "arena":
            {
                $sql = "SELECT * FROM arena WHERE TotalWin>0 ORDER BY TotalWin desc LIMIT 20";
                break;
            }
            case "clans":
            {
                $sql = "SELECT * FROM guilds WHERE Wins>0 ORDER BY Wins desc LIMIT 50";
                break;
            }
            case "nobility":
            {
                $sql = "SELECT * FROM nobility ORDER BY Donation desc LIMIT 50";
                break;
            }
            case "pkpoints":
            {
                $sql = "SELECT * FROM entities WHERE PKPoints>0 ORDER BY PKPoints DESC LIMIT 50";
                break;
            }
        }
        $q = mysqli_query($conn, $sql);
        $i = 0;
        while($player = mysqli_fetch_object($q)){
            if ($type_ranking != "nobility") {
                $player->ClassName = prof($player->Class);
            } else {
                $player->Noblity = noble($i, $player->Donation, $player->Gender);
            }
            $ranking[] = $player;
            $i++;
        }
     }
     return $ranking;
}
function get_online_users() {
    global $conn, $existConfig;
    $online_users = 0;
    if ($existConfig) {
        $accounts = mysqli_query($conn, "SELECT count(*) FROM entities WHERE Online = 1");
        $accountsview = mysqli_fetch_array($accounts);
        $online_users = $accountsview[0];
    }
    return $online_users;
}
function get_server_status() {
    $online = 'offline';
    $fp = @fsockopen(SERVER_ADDRESS, SERVER_PORT, $errno, $errstr, 1);
	if ($fp) {
        $online = 'online';
        fclose($fp);
	}
    return $online;
}
function get_registered_accounts() {
    global $conn, $existConfig;
    $registered_accounts = 0;
    if ($existConfig) {
        $accounts = mysqli_query($conn, "SELECT count(*) FROM accounts");
        $accountsview = mysqli_fetch_array($accounts);
        $registered_accounts = $accountsview[0];
    }
    return $registered_accounts;
}
function remove_session_msg() {
    //Remove msg login success
    if (isset($_SESSION["user"])) {
        $_SESSION["user"]["ShowSuccessLogin"] = false;
    }
}
/* Functions V2 Support Twig */
function getLangV2($key = null, $lang = LANG) {
    global $conn, $existConfig;
    $lang_objs = array();
    if ($existConfig) {
        $not_found_key = false;
        $sql = "SELECT * FROM openconquercms_lang";
        if ($key != null) {
            $sql .= " WHERE `key`='".$key."'";
            if ($lang != null) {
                $sql .= " AND lang='".$lang."'";
                $not_found_key = true;
            }
        } else {
            $sql .= " WHERE lang='".$lang."'";
        }
        $q = mysqli_query($conn, $sql);
        if ($q) {
            while($lang_obj = mysqli_fetch_object($q)) {
                if ($key == null) {
                    $lang_objs[$lang_obj->key] = $lang_obj->value;
                } else {
                    if ($lang_obj->key == $key) {
                        $lang_objs = $lang_obj;
                        $not_found_key = false;
                    }
                }
            }
        }
        if ($not_found_key) {
            $object = new stdClass();
            $object->value = $key;
            $lang_objs = $object;
        }
    } else {
        $object = new stdClass();
        $object->value = $key;
        $lang_objs = $object;
    }
    return $lang_objs;
}
function getSettingsV2($key = null) {
    global $conn, $existConfig;
    $settings_objs = array();
    if ($existConfig) {
        $sql = "SELECT * FROM openconquercms_settings";
        $q = mysqli_query($conn, $sql);
        if ($q) {
            while($settings_obj = mysqli_fetch_object($q)) {
                if ($key == null) {
                    $settings_objs[$settings_obj->key] = $settings_obj->value;
                } else {
                    if ($settings_obj->key == $key) {
                        $settings_objs = $settings_obj;
                    }
                }
            }
        }
    } else {
        $settings_objs = new stdClass();
        $settings_objs->value = $key;
    }
    return $settings_objs;
}
?>