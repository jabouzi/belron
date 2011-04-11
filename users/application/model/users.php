<?php

class users_model
{
    public function count()
    {
        $table = db('access_users');       
        return $table->count()->execute();
    }
    
    public function get_all_distinct()
    {
        $table = db('access_users');
        $data = $table->select('*')->execute();
        return $data;
    }
    
    public function get_all()
    {
        $table = db('access_users');
        $data = $table->distinct('email');
        return $data;
    }
    
    public function get_all_page($limit, $offset, $order_by, $type)
    {
        $table = db('access_users');
        $orders = $table->select('*')->order_by($order_by, $type)->limit($limit)->offset($offset)->execute();
        return $orders;
    }
    
    public function get_all_type2()
    {
        $table = db('access_users');
         $data = $table->select('*')->where('type','=','2')->execute();
        return $data;
    }
    
    public function get_id($email)
    {
        $table = db('access_users');
        $data = $table->select('id')->where('email','=',$email)->execute();
        return $data;
    }
    
    public function get_name($email)
    {
        $table = db('access_users');
        $data = $table->select('first_name','family_name')->where('email','=',$email)->execute();
        return $data;
    }
    
    public function get_user_infos($id)
    {
        $table = db('access_users');
        $data = $table->select('*')->where('id','=',$id)->execute();
        return $data;
    }
    
    public function check_login($username, $password)
    {
        $table = db('access_users');
        $data['valid'] = $table->count()->where('email','=',$username)->clause('AND')->where('password','=',md5($password))->execute();
        if ($data['valid']) 
            $data['type'] = $table->select('type')->where('email','=',$username)->clause('AND')->where('password','=',md5($password))->execute();        
        return $data;
    }
    
    public function add_user($data)
    {
        $table = db('access_users');
        $res = $table->insert(array(            
            'position' => $data['position'], 
            'family_name' => $data['family_name'], 
            'first_name' => $data['first_name'], 
            'address' => $data['address'], 
            'town' => $data['town'], 
            'province' => $data['province'], 
            'postal_code' => $data['postal_code'], 
            'phone' => $data['phone'], 
            'email' => $data['email'], 
            'password' => $data['password'], 
            'type' => $data['type'],
        ));      

        return $res;
    }
    
    public function delete_user($id)
    {
        $table = db('access_users');
        $table->delete()->where('id','=',$id)->execute();
    }
    
    public function update_user($id, $data)
    {
        $table = db('access_users');
        $res = $table->update(array(            
            'position' => $data['position'], 
            'family_name' => $data['family_name'], 
            'first_name' => $data['first_name'], 
            'address' => $data['address'], 
            'town' => $data['town'], 
            'province' => $data['province'], 
            'postal_code' => $data['postal_code'], 
            'phone' => $data['phone'], 
            'email' => $data['email'], 
            'password' => $data['password'], 
        ))->where('id','=',$id)->execute();
        
        return $res;
    }
}













 
