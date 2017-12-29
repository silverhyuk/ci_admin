<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 퍼미션 라이브러리.
 *
 * 반드시 Migrate 를 커멘드로 실행하세요.
 *
 * 예시) 컨트롤러에서 활용 예시..
 *
 * $this->load->library("permission");
 *
 * 유저에게 어드민 페이지 접근허가하기
 * $this->permission->user('username')->can("access admin page");
 *
 * 접근 불허하기
 * $this->permission->user('username')->cannot("access admin page");
 *
 * 패스워드 체크
 * $this->user("username")->check("password");
 *
 * 허가여부 체크
 * $this->permission->user('username')->isPermitted("access admin page");
 * 유저에게 단일 퍼미션이 우선적으로 있는지 확인하고 이후 유저에게 부여된 역할통해 역할이 가진 퍼미션을 검사합니다.
 *
 *  간단하게 확인하기
 *  if ( permission("username", "permission_name") ){
 *      echo "퍼미션 통과"
 * }else{
 *      echo "퍼미션 불가"
 * }
 */
class Permission
{
    protected $ci;
    protected $user;
    protected $perm;
    protected $role;
    protected $table = [];
    public function __construct()
    {
        $this->ci =& get_instance();
        // 테이블 명을 바꾸고 싶다면 아래 값을 변경하세요.
        $this->table['user'] = "au_user";
        $this->table['role'] = "au_role";
        $this->table['perm'] = "au_permission";
        $this->table['user_perm'] = "au_users_permissions";
        $this->table['user_role'] = "au_users_roles";
        $this->table['role_perm'] = "au_roles_permissions";
        $this->ci->load->library("session");
    }
    /**
     * 사용자를 설정
     * @param  [type] $username [description]
     * @return [type]           [description]
     */
    public function user($username)
    {
        $this->user = $this->ci->db->where("username", $username)->get($this->table['user'])->row();
        if($this->user){
            $this->session->userdata['user'] = $this->user;
            return $this;
        }else{
            show_error("잘못된 아이디 또는 패스워드입니다.");
            return false;
        }
    }
    function check($password){
        if($this->user->password == do_hash($password)){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 사용자 또는 역할에 퍼미션을 할당합니다.
     * @param  [type] $permission [description]
     * @return [type]             [description]
     */
    function can($permission)
    {
        $this->_getPerm($permission);
        if($this->user){
            if(!$this->isPermitted($permission)){
                $this->_setUserPerm();
            }
            return $this;
        }
        if($this->role){
            if(!$this->_isRoleHasPerm()){
                $this->_setRolePerm();
            }
            return $this;
        }
    }
    /**
     * 할당된 퍼미션을 삭제합니다.
     * @param  [type] $permission [description]
     * @return [type]             [description]
     */
    function cannot($permission)
    {
        $this->_getPerm($permission);
        if($this->user){
            if($this->isPermitted($permission)){
                $this->_unsetUserPerm();
            }
            return $this;
        }
        if($this->role){
            $this->_unsetRolePerm();
            return $this;
        }
    }
    /**
     * 퍼미션을 종합적으로 확인합니다.
     * @param  [type]  $permission [description]
     * @return boolean             [description]
     */
    function isPermitted($permission)
    {
        $this->_checkUser();

        if($this->user->id == 1 OR $this->_isSuperRole() ){
            return true;
        }

        $this->_getPerm($permission);
        if($this->_isUserPermitted()){
            // 다이렉트 퍼미션 확인
            return true;
        }else{
            //사용자의 역할을 기준으로 확인 user > user_role > role_id > role_perm
            return $this->_isUserRolePermitted();
        }
    }
    /**
     * 사용자에게 역할을 할당합니다.
     * @param  [type]  $role [description]
     * @return boolean       [description]
     */
    function hasRole($role)
    {
        $this->_checkUser();

        $this->_getRole($role);
        if(!$this->isUserAssigned()){
            $this->_assignedTo();
        }
        return $this;
    }
    /**
     * 할당된 역할을 삭제합니다.
     * @param  [type] $role [description]
     * @return [type]       [description]
     */
    function hasntRole($role)
    {
        $this->_checkUser();

        $this->_getRole($role);

        if( $this->isUserAssigned() )
        {
            $this->_unsetUserAssigned();
        }
    }
    /**
     * 역할을 설정합니다.
     * @param  [type] $role [description]
     * @return [type]       [description]
     */
    function role($role){
        $this->_getRole($role);
        return $this;
    }
    /**
     * 사용자에게 역할이 할당되어 있는지 체크 합니다.
     * @param  [type]  $role [description]
     * @return boolean       [description]
     */
    function isAssignedTo($role){
        $this->_getRole($role);
        return $this->isUserAssigned();
    }
    function isUserAssigned($role="")
    {
        $this->_checkUser();
        if($role){
            $this->_getRole($role);
        }
        $role = $this->ci->db->where(["user_id"=>$this->user->id , "role_id"=>$this->role->id])->get($this->table['user_role'])->row();
        if($role){
            return true;
        }else{
            return false;
        }
    }
    protected function _isRoleHasPerm(){
        if(!$this->role->id || !$this->perm->id){
            show_error("역할 설정 또는 퍼미션 설정이 안되어 있습니다.");
        }
        $role = $this->ci->db->where( [ "role_id"=>$this->role->id, "permission_id"=>$this->perm->id ] )->get($this->table['role_perm'])->row();
        if($role){
            return true;
        }else{
            return false;
        }
    }
    protected function _isUserRolePermitted(){
        $perm = $this->ci->db->from($this->table['user'])
            ->join($this->table['user_role'], $this->table['user'] . ".id = ".$this->table['user_role'].".user_id" , "INNER")
            ->join($this->table['role_perm'], $this->table['user_role'].".role_id = " . $this->table['role_perm'] . ".permission_id","INNER")
            ->where( [ "permission_id"=>$this->perm->id , $this->table['user'].".id"=>$this->user->id ])->get()->row();

        if($perm){
            return true;
        }else{
            return false;
        }
    }
    protected function _isSuperRole(){
        $superRole = $this->ci->db->where("id", 1)->get($this->table['role'])->row();
        return $this->isUserAssigned($superRole->name);
    }
    protected function _unsetUserAssigned()
    {
        $this->ci->db->where(["user_id"=>$this->user->id , "role_id"=>$this->role->id])->delete($this->table['user_role']);
    }
    protected function _assignedTo()
    {
        $this->ci->db->insert($this->table['user_role'],
            ["user_id"=>$this->user->id , "role_id"=>$this->role->id]);
    }
    protected function _getRole($role)
    {
        $this->role = $this->ci->db->where("name", $role)->get($this->table['role'])->row();
        if(!$this->role){
            show_error("해당 역할이 없습니다.");
        }
    }
    protected function _setRole($role)
    {
        $this->ci->db->insert($this->table['user_role'], ['user_id'=>$this->user->id, 'role_id'=>$this->role->id ]);
    }
    /** 유저와 퍼미션 관계설정 */
    protected function _unsetUserPerm()
    {
        $this->ci->db->where(["permission_id"=>$this->perm->id, "user_id"=>$this->user->id])->delete($this->table['user_perm']);
    }
    protected function _setUserPerm()
    {
        $this->ci->db->insert($this->table['user_perm'],
            ["user_id"=>$this->user->id , "permission_id"=>$this->perm->id]);
    }
    protected function _unsetRolePerm()
    {
        $this->ci->db->where(["permission_id"=>$this->perm->id, "role_id"=>$this->role->id])->delete($this->table['role_perm']);
    }
    protected function _setRolePerm()
    {
        $this->ci->db->insert($this->table['role_perm'],
            ["role_id"=>$this->role->id , "permission_id"=>$this->perm->id]);
    }
    protected function _getPerm($permission)
    {
        $this->perm = $this->ci->db->where("name", $permission)->get($this->table['perm'])->row();
        if(!$this->perm){
            show_error("No exist Permission Name");
        }
    }
    protected function _isUserPermitted()
    {
        $perm = $this->ci->db->where([ "user_id"=>$this->user->id, "permission_id"=>$this->perm->id ])->get($this->table['user_perm'])->row();
        if( $perm ){
            return true;
        }else{
            return false;
        }
    }

    protected function _checkUser()
    {
        if(!$this->user){
            show_error("No permission without user setting");
        }
    }
    /**
     * 사용자 등록
     * @param  [type] $username [description]
     * @param  [type] $password [description]
     * @return [type]           [description]
     */
    function registerUser($username, $password){
        $this->ci->db->insert($this->table['user'], ['username'=>$username, "password"=>do_hash($password)]);
        return $this->ci->db->insert_id();
    }
    /**
     * 역할 등록
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    function registerRole($name){
        $this->ci->db->insert($this->table['role'], ['name'=>$username]);
        return $this->ci->db->insert_id();
    }
    /**
     * 퍼미션 등록
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    function registerPermission($name){
        $this->ci->db->insert($this->table['perm'], ['name'=>$username]);
        return $this->ci->db->insert_id();
    }
    function unitTest(){

        $success = 0;
        echo "<pre>";
        if( $this->user("admin")->isPermitted("access SUPER") ){
            echo "permitted SUPER \n";
            $success++;
        }

        if ( !$this->user("tester")->isPermitted("access SUPER") ){
            echo $this->user->username ."퍼밋 실패. 퍼밋 생성 후 재 실행 \n";
            $this->user("tester")->can("access SUPER"); // 퍼미션을 줍니다.
            $success++;
        }

        if($this->isPermitted("access SUPER")){
            echo $this->user->username . "퍼미션 성공. 퍼밋 제거 후 재 실행 \n";
            $this->user("tester")->cannot("access SUPER");
            $success++;
        }

        if(!$this->isPermitted("access SUPER")){
            echo $this->user->username . "퍼미션 실패. 퍼미션 제거 성공. 역할 설정 및 재 실행 \n";
            $this->user("tester")->hasRole("Administer");
            $success++;
        }

        if($this->isPermitted("access SUPER")){
            echo $this->user->username . "퍼미션 성공. 역할 제거 및 재 실행 \n";
            $this->user("tester")->hasntRole("Administer");
            $success++;
        }

        if(!$this->isPermitted("access SUPER")){
            echo $this->user->username . "퍼미션 실패, 역할제거 성공 \n";
            $success++;
        }
        echo "\n \n \n ". $success;
        if($success == 6 ){
            echo "최종 성공";
        }else{
            echo "테스트 실패";
        }
        echo "</pre>";

    }

    function migrate(){
        if( !$this->input->is_cli_request() ){
            show_error("마이그레이션은 콘솔을 통해 접근하세요.");
        }
        $sql = "
CREATE TABLE `au_permission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
 
CREATE TABLE `au_role` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
 
CREATE TABLE `au_roles_permissions` (
  `permission_id` int(10) unsigned DEFAULT NULL ,
  `role_id` int(10) unsigned DEFAULT NULL ,
  KEY `FK_au_role_TO_au_roles_permissions` (`permission_id`),
  KEY `FK_au_permission_TO_au_roles_permissions` (`role_id`),
  CONSTRAINT `FK_au_permission_TO_au_roles_permissions` FOREIGN KEY (`role_id`) REFERENCES `au_permission` (`id`),
  CONSTRAINT `FK_au_role_TO_au_roles_permissions` FOREIGN KEY (`permission_id`) REFERENCES `au_role` (`id`)
) ENGINE=InnoDB;
 
CREATE TABLE `au_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT ,
  `username` varchar(100) CHARACTER SET utf8 NOT NULL ,
  `password` varchar(100) NOT NULL ,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=InnoDB;
 
CREATE TABLE `au_users_permissions` (
  `user_id` int(10) unsigned DEFAULT NULL ,
  `permission_id` int(10) unsigned DEFAULT NULL ,
  KEY `FK_au_user_TO_au_users_permissions` (`user_id`),
  KEY `FK_au_permission_TO_au_users_permissions` (`permission_id`),
  CONSTRAINT `FK_au_permission_TO_au_users_permissions` FOREIGN KEY (`permission_id`) REFERENCES `au_permission` (`id`),
  CONSTRAINT `FK_au_user_TO_au_users_permissions` FOREIGN KEY (`user_id`) REFERENCES `au_user` (`id`)
) ENGINE=InnoDB;
 
CREATE TABLE `au_users_roles` (
  `user_id` int(10) unsigned DEFAULT NULL ,
  `role_id` int(10) unsigned DEFAULT NULL ,
  KEY `FK_au_user_TO_au_users_roles` (`user_id`),
  KEY `FK_au_role_TO_au_users_roles` (`role_id`),
  CONSTRAINT `FK_au_role_TO_au_users_roles` FOREIGN KEY (`role_id`) REFERENCES `au_role` (`id`),
  CONSTRAINT `FK_au_user_TO_au_users_roles` FOREIGN KEY (`user_id`) REFERENCES `au_user` (`id`)
) ENGINE=InnoDB;
        ";
        $this->ci->db->query($sql);
        $this->seed();
    }
    protected function seed(){
        $this->ci->db->insert($this->table['user'], ['username'=>'admin', 'password'=>do_hash("admin")]);
        $this->ci->db->insert($this->table['role'], ['name'=>'Administer']);
        $this->ci->db->insert($this->table['perm'], ['name'=>'access SUPER']);
        $this->ci->db->insert($this->table['user_role'], ['user_id'=>1, 'role_id'=>1]);
        $this->ci->db->insert($this->table['role_perm'], ['role_id'=>1, 'permission_id'=>1]);
        $this->ci->db->insert($this->table['user_perm'], ['user_id'=>1, 'permission_id'=>1]);
        $this->registerUser("tester", "tester");
    }
}
function permission($username, $permission){
    $perm = new Permission;
    return $perm->user($username)->isPermitted($permission);
}

/* End of file Permission.php */
/* Location: ./application/libraries/Permission.php */